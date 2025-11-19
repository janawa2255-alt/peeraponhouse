<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Lease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Mail\EmailInvoice;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class InvoiceController extends Controller
{
   public function index(Request $request)
{

    $status = $request->query('status', 'all');

    $query = Invoice::with([
            'expense.lease.tenants',
            'expense.lease.rooms',
        ]);


    switch ($status) {
        case 'unpaid':  
            $query->where('status', 0);
            break;
        case 'paid':     
            $query->where('status', 1);
            break;
        case 'overdue':  
            $query->where('status', 2);
            break;
        case 'canceled': 
            $query->where('status', 3);
            break;
        case 'all':
        default:
        
            break;
    }

    $invoices = $query
        ->orderByDesc('invoice_data')
        ->paginate(10)
        ->appends(['status' => $status]);


    return view('invoices.index', compact('invoices', 'status'));
}
    public function create()
    {
        
        $leases = Lease::with(['tenants', 'rooms'])
            ->where('status', 1)
            ->get();

        return view('invoices.create', compact('leases'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'lease_id'     => 'required|exists:leases,lease_id',
            'month'        => 'required|string|max:2',
            'year'         => 'required|string|max:4',
            'prev_water'   => 'required|integer|min:0',
            'curr_water'   => 'required|integer|min:0|gte:prev_water',
            'water_rate'   => 'required|integer|min:0',
            'elec_total'   => 'required|integer|min:0',
            // room_rent ดึงจาก Lease ไม่ต้องเชื่อฟอร์ม
            'invoice_date' => 'nullable|date',
            'due_date'     => 'nullable|date',
            'discount'     => 'nullable|integer|min:0',
            'pic_water'    => 'nullable|image|max:2048',
            'pic_elec'     => 'nullable|image|max:2048',
        ], [
            'lease_id.required' => 'กรุณาเลือกห้อง',
            'lease_id.exists'   => 'ไม่พบข้อมูลสัญญาเช่า',
            'curr_water.gte'    => 'เลขมิเตอร์เดือนนี้ต้องมากกว่าหรือเท่ากับเดือนก่อน',
        ]);

        // กันบิลซ้ำ: ห้ามมีใบแจ้งหนี้ของสัญญานี้ + เดือน + ปี ซ้ำ
        $exists = Invoice::whereHas('expense', function ($q) use ($request) {
                $q->where('lease_id', $request->lease_id)
                  ->where('month', $request->month)
                  ->where('year',  $request->year);
            })
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'lease_id' => 'มีใบแจ้งหนี้ของห้องนี้สำหรับเดือน/ปีนี้อยู่แล้ว',
                ]);
        }

        DB::beginTransaction();

        try {
            // ดึงสัญญาเช่า เพื่อเอาค่าเช่าห้องจากฐานข้อมูล
            $lease    = Lease::with('tenants')->findOrFail($request->lease_id);
            $roomRent = (int) $lease->rent_amount; // ดึงจาก leases เท่านั้น

            // คำนวณค่าน้ำ / ยอดรวม
            $prev  = (int) $request->prev_water;
            $curr  = (int) $request->curr_water;
            $units = max($curr - $prev, 0);

            $waterRate  = (int) $request->water_rate;
            $waterTotal = $units * $waterRate;

            $elecTotal  = (int) $request->elec_total;
            $subtotal   = $waterTotal + $elecTotal + $roomRent;

            $discount = (int) ($request->discount ?? 0);
            if ($discount < 0) {
                $discount = 0;
            }

            // รวมสุทธิหลังหักส่วนลด (ไว้ใช้แสดง/เก็บใน expenses.total_amount)
            $grandTotal = max($subtotal - $discount, 0);

            // อัปโหลดรูป (ถ้ามี)
            $picWaterPath = null;
            $picElecPath  = null;

            if ($request->hasFile('pic_water')) {
                $picWaterPath = $request->file('pic_water')
                    ->store('water_bills', 'public');
            }

            if ($request->hasFile('pic_elec')) {
                $picElecPath = $request->file('pic_elec')
                    ->store('elec_bills', 'public');
            }

            // วันที่ออกบิล / วันครบกำหนด
            $invoiceDate = $request->filled('invoice_date')
                ? Carbon::parse($request->invoice_date)
                : now();

            // กำหนดชำระอัตโนมัติ: วันที่ 5 ของ "เดือนถัดไป" ของรอบบิล (month/year)
            if ($request->filled('due_date')) {
                $dueDate = Carbon::parse($request->due_date);
            } else {
                $billYear  = (int) $request->year;
                $billMonth = (int) $request->month;

                // สร้างเป็นวันที่ 5 ของเดือนรอบบิล แล้วเลื่อนไปเดือนถัดไป
                $base    = Carbon::create($billYear, $billMonth, 5, 0, 0, 0);
                $dueDate = $base->addMonthNoOverflow(); // = วันที่ 5 เดือนถัดไป
            }

            // บันทึกตาราง Expenses
            $expense = Expense::create([
                'lease_id'     => $request->lease_id,
                'month'        => $request->month,
                'year'         => $request->year,
                'prev_water'   => $prev,
                'curr_water'   => $curr,
                'water_units'  => $units,
                'water_rate'   => $waterRate,
                'water_total'  => $waterTotal,
                'elec_total'   => $elecTotal,
                'room_rent'    => $roomRent,
                'discount'     => $discount,
                'total_amount' => $grandTotal,
                'pic_water'    => $picWaterPath,
                'pic_elec'     => $picElecPath,
                'created_at'   => now(),
            ]);

            // สร้างรหัสใบแจ้งหนี้
            $invoiceCode = $this->generateInvoiceCode($request->year);

            // บันทึกตาราง Invoices
            $invoice = Invoice::create([
                'ex_id'        => $expense->ex_id,
                'invoice_code' => $invoiceCode,
                'invoice_data' => $invoiceDate,
                'due_date'     => $dueDate,
                'status'       => 0,              // 0 = รอชำระ
                'created_by'   => Auth::user()->name ?? 'system',
            ]);

            DB::commit();

            return redirect()
                ->route('invoices.show', $invoice->invoice_id)
                ->with('success', 'บันทึกใบแจ้งหนี้เรียบร้อยแล้ว');

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()
                ->withInput()
                ->with('error', 'เกิดข้อผิดพลาดในการบันทึกใบแจ้งหนี้');
        }
    }

    public function notify(Invoice $invoice)
    {
        $invoice->load('expense.lease.tenants');

        $tenant = optional($invoice->expense->lease)->tenants;
        $email  = $tenant->email ?? null; // 👈 เปลี่ยนชื่อฟิลด์ให้ตรงกับตาราง tenants ถ้าไม่ใช่ email

        if (!$email) {
            return back()->with('error', 'ผู้เช่าไม่มีอีเมล ไม่สามารถส่งแจ้งเตือนได้');
        }

        try {
            Mail::to($email)->send(new EmailInvoice($invoice));
            return back()->with('success', 'ส่งอีเมลแจ้งเตือนผู้เช่าเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'ส่งอีเมลไม่สำเร็จ');
        }
    }

    public function show(Invoice $invoice)
{
    $invoice->load([
        'expense.lease.tenants',
        'expense.lease.rooms',
    ]);

    $expense = $invoice->expense;
    $lease   = $expense->lease ?? null;
    $tenant  = $lease->tenants ?? null;
    $room    = $lease->rooms ?? null;

    [$statusLabel, $statusClass] = $this->getInvoiceStatusMeta($invoice->status);

    $water    = $expense->water_total ?? 0;
    $elec     = $expense->elec_total ?? 0;
    $rent     = $expense->room_rent ?? 0;
    $subtotal = $water + $elec + $rent;

    $discount   = $expense->discount ?? 0;
    $grandTotal = $expense->total_amount ?? max($subtotal - $discount, 0);

    return view('invoices.show', compact(
        'invoice',
        'expense',
        'lease',
        'tenant',
        'room',
        'statusLabel',
        'statusClass',
        'subtotal',
        'discount',
        'grandTotal'
    ));
}

/**
 * คืน label + class ของสถานะใบแจ้งหนี้
 */
protected function getInvoiceStatusMeta(int $status): array
{
    switch ($status) {
        case 1:
            return ['ชำระแล้ว', 'bg-green-500/90 text-white'];
        case 2:
            return ['เกินกำหนด', 'bg-red-500/90 text-white'];
        case 3:
            return ['ยกเลิก', 'bg-gray-500/90 text-white'];
        default:
            return ['รอชำระ', 'bg-yellow-400/90 text-black'];
    }
}
 protected function generateInvoiceCode(string $year): string
    {
        $latest = Invoice::whereYear('invoice_data', $year)
            ->orderByDesc('invoice_id')
            ->first();

        $running = 1;

        if ($latest) {
            // ดึงเลขลำดับท้ายสุดจาก code เดิม (เช่น INV-2025-001)
            $parts   = explode('-', $latest->invoice_code);
            $running = isset($parts[2]) ? ((int) $parts[2] + 1) : 1;
        }

        return sprintf('INV-%s-%03d', $year, $running);
    }

    /**
     * ดึงข้อมูลจากสัญญาเช่า สำหรับหน้าออกใบแจ้งหนี้ (AJAX)
     */
}
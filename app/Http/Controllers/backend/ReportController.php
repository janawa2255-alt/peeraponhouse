<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // รายงานรายได้
    public function income(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month');
        $roomNo = $request->get('room_no');

        // ดึงข้อมูลรายได้จาก payments ที่อนุมัติแล้ว (status = 1)
        $query = Payment::where('status', 1)
            ->with(['invoice.expense.lease.tenants', 'invoice.expense.lease.rooms']);

        // กรองตามปี
        $query->whereYear('paid_date', $year);

        // ถ้าเลือกเดือน ก็กรองเดือนด้วย
        if ($month) {
            $query->whereMonth('paid_date', $month);
        }

        // กรองตามห้อง
        if ($roomNo) {
            $query->whereHas('invoice.expense.lease.rooms', function($q) use ($roomNo) {
                $q->where('room_no', $roomNo);
            });
        }

        $payments = $query->orderBy('paid_date', 'desc')->get();

        // สรุปยอดรวม
        $totalIncome = $payments->sum('total_amount');

        // รายได้แยกตามเดือน (สำหรับกราฟ)
        $monthlyIncome = Payment::where('status', 1)
            ->whereYear('paid_date', $year)
            ->select(
                DB::raw('MONTH(paid_date) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        // ดึงรายการห้องทั้งหมด
        $rooms = \App\Models\Room::orderBy('room_no')->get();

        return view('reports.income', compact('payments', 'totalIncome', 'year', 'month', 'monthlyIncome', 'rooms', 'roomNo'));
    }

    // รายงานยอดค้างชำระ
    public function outstanding(Request $request)
    {
        // Update overdue invoices before displaying
        $this->updateOverdueInvoices();
        
        $roomNo = $request->get('room_no');
        
        // ดึงใบแจ้งหนี้ที่ยังไม่ชำระ (status = 0) หรือเกินกำหนด (status = 2)
        $query = Invoice::with(['expense.lease.tenants', 'expense.lease.rooms'])
            ->whereIn('status', [0, 2]);
        
        // กรองตามห้อง
        if ($roomNo) {
            $query->whereHas('expense.lease.rooms', function($q) use ($roomNo) {
                $q->where('room_no', $roomNo);
            });
        }
        
        $invoices = $query->orderBy('due_date', 'asc')
            ->get();

        // สรุปยอดรวมค้างชำระ
        $totalOutstanding = $invoices->sum(function($invoice) {
            return $invoice->expense->total_amount ?? 0;
        });

        // นับจำนวนใบแจ้งหนี้
        $countUnpaid = $invoices->where('status', 0)->count();
        $countOverdue = $invoices->where('status', 2)->count();

        // ดึงรายการห้องทั้งหมด
        $rooms = \App\Models\Room::orderBy('room_no')->get();

        return view('reports.outstanding', compact('invoices', 'totalOutstanding', 'countUnpaid', 'countOverdue', 'rooms', 'roomNo'));
    }

    /**
     * Automatically update overdue invoices
     * Sets status to 2 (overdue) for unpaid invoices past their due date
     */
    protected function updateOverdueInvoices()
    {
        Invoice::where('status', 0) // Only unpaid invoices
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->format('Y-m-d'))
            ->update(['status' => 2]);
    }
}

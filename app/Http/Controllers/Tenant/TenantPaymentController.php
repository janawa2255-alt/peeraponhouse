<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Expense;
use Illuminate\Http\Request;

class TenantPaymentController extends Controller
{
    /**
     * แสดงประวัติการชำระเงินทั้งหมด
     */
    public function index(Request $request)
    {
        $authTenant = $request->session()->get('auth_tenant');

        if (!$authTenant) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $tenant = Tenant::findOrFail($authTenant['id']);

        // ดึงสัญญาเช่าปัจจุบัน
        $currentLease = Lease::where('tenant_id', $tenant->tenant_id)
            ->where('status', 1) // active lease (1 = กำลังเช่าอยู่)
            ->orderByDesc('start_date')
            ->first();

        if (!$currentLease) {
            return view('tenant.payments.no-lease', compact('tenant'));
        }

        // ดึง expenses ของสัญญาเช่านี้
        $expenseIds = Expense::where('lease_id', $currentLease->lease_id)
            ->pluck('ex_id');

        // ดึง invoices
        $invoiceIds = Invoice::whereIn('ex_id', $expenseIds)
            ->pluck('invoice_id');

        // ดึงประวัติการชำระเงิน
        $payments = Payment::with(['invoice.expense.lease.rooms', 'bank'])
            ->whereIn('invoice_id', $invoiceIds)
            ->orderByDesc('payment_id')
            ->paginate(15);

        return view('tenant.payments.index', compact('tenant', 'payments', 'currentLease'));
    }

    /**
     * แสดงรายละเอียดการชำระเงิน
     */
    public function show(Request $request, $id)
    {
        $authTenant = $request->session()->get('auth_tenant');

        if (!$authTenant) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $tenant = Tenant::findOrFail($authTenant['id']);

        $payment = Payment::with([
            'invoice.expense.lease.rooms',
            'invoice.expense.lease.tenants',
            'bank'
        ])->findOrFail($id);

        // ตรวจสอบสิทธิ์
        if ($payment->invoice->expense->lease->tenant_id != $tenant->tenant_id) {
            abort(403, 'คุณไม่มีสิทธิ์ดูข้อมูลนี้');
        }

        return view('tenant.payments.show', compact('tenant', 'payment'));
    }

    /**
     * แสดงฟอร์มชำระเงิน
     */
    public function create(Request $request, $invoiceId)
    {
        $authTenant = $request->session()->get('auth_tenant');

        if (!$authTenant) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $tenant = Tenant::findOrFail($authTenant['id']);

        $invoice = Invoice::with(['expense.lease.rooms', 'expense.lease.tenants'])
            ->findOrFail($invoiceId);

        // ตรวจสอบสิทธิ์
        if ($invoice->expense->lease->tenant_id != $tenant->tenant_id) {
            abort(403, 'คุณไม่มีสิทธิ์ชำระใบแจ้งหนี้นี้');
        }

        // ดึงข้อมูลธนาคาร
        $banks = \App\Models\Bank::all();

        return view('tenant.payments.create', compact('tenant', 'invoice', 'banks'));
    }

    /**
     * บันทึกการชำระเงิน
     */
    public function store(Request $request)
    {
        $authTenant = $request->session()->get('auth_tenant');

        if (!$authTenant) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $tenant = Tenant::findOrFail($authTenant['id']);

        $request->validate([
            'invoice_id' => 'required|exists:invoices,invoice_id',
            'bank_id' => 'required|exists:banks,bank_id',
            'amount' => 'required|numeric|min:0',
            'paid_date' => 'required|date',
            'slip_image' => 'required|image|max:2048',
            'note' => 'nullable|string',
        ]);

        // ตรวจสอบสิทธิ์
        $invoice = Invoice::with('expense.lease')->findOrFail($request->invoice_id);
        if ($invoice->expense->lease->tenant_id != $tenant->tenant_id) {
            abort(403, 'คุณไม่มีสิทธิ์ชำระใบแจ้งหนี้นี้');
        }

        // อัปโหลดสลิป
        $slipPath = null;
        if ($request->hasFile('slip_image')) {
            $slipPath = $request->file('slip_image')->store('payment_slips', 'public');
        }

        // บันทึกการชำระเงิน
        $payment = Payment::create([
            'invoice_id' => $request->invoice_id,
            'bank_id' => $request->bank_id,
            'total_amount' => $request->amount,
            'paid_date' => $request->paid_date,
            'pic_slip' => $slipPath,
            'note' => $request->note,
            'status' => 0, // รอตรวจสอบ
        ]);

        return redirect()->route('payments')
            ->with('success', 'บันทึกการชำระเงินเรียบร้อยแล้ว รอเจ้าหน้าที่ตรวจสอบ');
    }
}

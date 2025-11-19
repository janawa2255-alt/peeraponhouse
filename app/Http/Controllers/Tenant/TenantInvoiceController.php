<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Lease;
use App\Models\Invoice;
use App\Models\Expense;
use Illuminate\Http\Request;

class TenantInvoiceController extends Controller
{
    /**
     * แสดงรายการใบแจ้งหนี้ทั้งหมดของผู้เช่า
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
            return view('tenant.invoices.no-lease', compact('tenant'));
        }

        // ดึง expenses ทั้งหมดของสัญญาเช่านี้
        $expenseIds = Expense::where('lease_id', $currentLease->lease_id)
            ->pluck('ex_id');

        // ดึงใบแจ้งหนี้ทั้งหมด
        $invoices = Invoice::with(['expense.lease.rooms'])
            ->whereIn('ex_id', $expenseIds)
            ->orderByDesc('invoice_data')
            ->paginate(10);

        return view('tenant.invoices.index', compact('tenant', 'invoices', 'currentLease'));
    }

    /**
     * แสดงรายละเอียดใบแจ้งหนี้
     */
    public function show(Request $request, $id)
    {
        $authTenant = $request->session()->get('auth_tenant');

        if (!$authTenant) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $tenant = Tenant::findOrFail($authTenant['id']);

        $invoice = Invoice::with(['expense.lease.rooms', 'expense.lease.tenants'])
            ->findOrFail($id);

        // ตรวจสอบว่าใบแจ้งหนี้นี้เป็นของผู้เช่าคนนี้หรือไม่
        if ($invoice->expense->lease->tenant_id != $tenant->tenant_id) {
            abort(403, 'คุณไม่มีสิทธิ์ดูใบแจ้งหนี้นี้');
        }

        // ดึงประวัติการชำระเงิน
        $payments = $invoice->payments ?? collect();

        return view('tenant.invoices.show', compact('tenant', 'invoice', 'payments'));
    }
}

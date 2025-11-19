<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Lease;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantDashboardController extends Controller
{
    /**
     * แสดงหน้า Dashboard สำหรับผู้เช่า
     */
    public function index(Request $request)
    {
        $authTenant = $request->session()->get('auth_tenant');

        if (!$authTenant) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $tenant = Tenant::findOrFail($authTenant['id']);

        // ดึงสัญญาเช่าปัจจุบัน
        $currentLease = Lease::with(['rooms', 'tenants'])
            ->where('tenant_id', $tenant->tenant_id)
            ->where('status', 1) // active lease
            ->orderByDesc('start_date')
            ->first();

        // ถ้ามีสัญญา ให้ดึงข้อมูลเพิ่มเติม
        $stats = [];
        $recentInvoices = collect();
        $unpaidInvoices = collect();

        if ($currentLease) {
            // ดึง Expense ทั้งหมดของสัญญาเช่านี้
            $expenseIds = Expense::where('lease_id', $currentLease->lease_id)
                ->pluck('ex_id');

            // ดึงใบแจ้งหนี้ล่าสุด 5 รายการ
            $recentInvoices = Invoice::with(['expense.lease.rooms'])
                ->whereIn('ex_id', $expenseIds)
                ->orderByDesc('invoice_data')
                ->limit(5)
                ->get();

            // นับใบแจ้งหนี้ที่ยังไม่ชำระ
            $unpaidInvoices = Invoice::with(['expense.lease.rooms'])
                ->whereIn('ex_id', $expenseIds)
                ->where('status', 0) // unpaid
                ->get();

            // สถิติ
            $stats = [
                'total_invoices' => $recentInvoices->count(),
                'unpaid_count' => $unpaidInvoices->count(),
                'unpaid_total' => $unpaidInvoices->sum(function($invoice) {
                    return $invoice->expense->total_amount ?? 0;
                }),
                'room_rent' => $currentLease->rent_amount ?? 0,
            ];
        }

        // ดึงประกาศที่เปิดใช้งาน
        $announcements = Announcement::where('status', 1)
            ->orderByDesc('created_at')
            ->get();

        return view('tenant.dashboard', compact('tenant', 'currentLease', 'stats', 'recentInvoices', 'unpaidInvoices', 'announcements'));
    }
}

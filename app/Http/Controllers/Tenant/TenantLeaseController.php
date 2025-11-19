<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantLeaseController extends Controller
{
    /**
     * แสดงสัญญาเช่าปัจจุบันของผู้เช่าที่ล็อกอินอยู่
     */
    public function showCurrent(Request $request)
    {
        $authTenant = $request->session()->get('auth_tenant');

        if (!$authTenant) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $tenant = Tenant::findOrFail($authTenant['id']);

        // ดึงสัญญาเช่าปัจจุบัน
        $lease = Lease::with(['rooms', 'tenants', 'expenses'])
            ->where('tenant_id', $tenant->tenant_id)
            ->where('status', 1) // active lease (1 = กำลังเช่าอยู่)
            ->orderByDesc('start_date')
            ->first();

        if (!$lease) {
            return view('tenant.lease.no-lease', compact('tenant'));
        }

        // ดึง expense ล่าสุด
        $latestExpense = $lease->expenses()
            ->orderByDesc('created_at')
            ->first();

        return view('tenant.lease.show', compact('tenant', 'lease', 'latestExpense'));
    }
}

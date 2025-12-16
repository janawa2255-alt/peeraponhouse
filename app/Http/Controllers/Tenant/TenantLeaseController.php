<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Expense;
use App\Models\CancelLease;
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

        // ดึงคำขอยกเลิกล่าสุด (ถ้ามี)
        $cancelRequest = CancelLease::where('lease_id', $lease->lease_id)
            ->orderByDesc('cancel_id')
            ->first();

        return view('tenant.lease.show', compact('tenant', 'lease', 'latestExpense', 'cancelRequest'));
    }

    /**
     * บันทึกคำขอยกเลิกสัญญาเช่า
     */
    public function requestCancel(Request $request, $leaseId)
    {
        $authTenant = $request->session()->get('auth_tenant');

        if (!$authTenant) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $tenant = Tenant::findOrFail($authTenant['id']);

        // ตรวจสอบว่าสัญญาเช่านี้เป็นของผู้เช่าคนนี้หรือไม่
        $lease = Lease::where('lease_id', $leaseId)
            ->where('tenant_id', $tenant->tenant_id)
            ->where('status', 1) // ต้องเป็นสัญญาที่ active
            ->firstOrFail();

        // ตรวจสอบว่ามีคำขอยกเลิกที่รออยู่หรือไม่
        $existingRequest = CancelLease::where('lease_id', $leaseId)
            ->where('status', 0) // 0 = รอพิจารณา
            ->first();

        if ($existingRequest) {
            return redirect()
                ->route('lease.show')
                ->with('error', 'คุณมีคำขอยกเลิกสัญญาที่รอการพิจารณาอยู่แล้ว');
        }

        // Validate
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        // บันทึกคำขอยกเลิก
        CancelLease::create([
            'lease_id' => $leaseId,
            'request_date' => now(),
            'reason' => $request->reason,
            'status' => 0, // 0 = รอพิจารณา
            'created_at' => now(),
            'created_by' => $tenant->tenant_id,
        ]);

        return redirect()
            ->route('lease.show')
            ->with('success', 'ส่งคำขอยกเลิกสัญญาเช่าเรียบร้อยแล้ว รอการพิจารณาจากเจ้าของหอพัก');
    }
}

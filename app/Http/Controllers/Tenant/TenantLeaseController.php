<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use Illuminate\Support\Facades\Auth;

class TenantLeaseController extends Controller
{
    /**
     * แสดงสัญญาเช่าปัจจุบันของผู้เช่าที่ล็อกอินอยู่
     */
// ตัวอย่างใน TenantLeaseController

public function showCurrent(Request $request)
{
    $authTenant = $request->session()->get('auth_tenant');

    if (! $authTenant) {
        return redirect()->route('login');
    }

    $tenant = Tenant::findOrFail($authTenant['id']);

    $lease = Lease::with('rooms')
        ->where('tenant_id', $tenant->tenant_id)
        ->orderByDesc('start_date')
        ->firstOrFail();

    return view('tenants_leases.show', compact('tenant', 'lease'));
}


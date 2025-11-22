<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTenantAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // ตรวจสอบว่ามี session auth_tenant หรือไม่
        if (!$request->session()->has('auth_tenant')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }

        // ตรวจสอบสถานะล่าสุดจากฐานข้อมูล
        $sessionTenant = $request->session()->get('auth_tenant');
        $tenant = \App\Models\Tenant::find($sessionTenant['id']);

        if (!$tenant || $tenant->status != 0) {
            // ถ้าไม่พบผู้ใช้ หรือสถานะไม่ใช่ 0 (เช่าอยู่) ให้ logout
            $request->session()->forget('auth_tenant');
            return redirect()->route('login')->with('error', 'บัญชีของคุณถูกปิดใช้งาน กรุณาติดต่อเจ้าของหอพัก');
        }

        return $next($request);
    }
}

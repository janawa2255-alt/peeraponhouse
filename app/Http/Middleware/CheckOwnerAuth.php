<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckOwnerAuth
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
        // ตรวจสอบว่ามี session auth_owner หรือไม่
        if (!$request->session()->has('auth_owner')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }

        // ตรวจสอบสถานะล่าสุดจากฐานข้อมูล
        $sessionOwner = $request->session()->get('auth_owner');
        $employee = \App\Models\Employee::find($sessionOwner['id']);

        if (!$employee || $employee->status != 0) {
            // ถ้าไม่พบผู้ใช้ หรือสถานะไม่ใช่ 0 (ใช้งานอยู่) ให้ logout
            $request->session()->forget('auth_owner');
            return redirect()->route('login')->with('error', 'บัญชีของคุณถูกปิดใช้งาน');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomLoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        // เช็คว่ามาจาก backend route หรือไม่
        $isBackend = $request->is('backend/*');
        
        // ถ้าเป็น backend และ admin login อยู่แล้ว ให้ redirect ไปหน้า dashboard
        if ($isBackend) {
            if (session()->has('auth_owner')) {
                return redirect()->route('backend.dashboard');
            }
            return view('auth.admin-login');
        }
        
        // ถ้าเป็น tenant และ tenant login อยู่แล้ว ให้ redirect ไปหน้าแรก
        if (session()->has('auth_tenant')) {
            return redirect()->route('home');
        }
        
        // ถ้าไม่ใช่ ให้ไปหน้า tenant-login
        return view('auth.tenant-login');
    }

    // ---------------- ผู้เช่า ----------------
    public function loginTenant(Request $request)
    {
        $request->validate([
            'username' => ['required','string'],
            'password' => ['required','string'],
        ]);

        // ตรวจสอบว่ามีผู้ใช้นี้หรือไม่
        $tenantCheck = Tenant::where('username', $request->username)->first();
        
        if ($tenantCheck && $tenantCheck->status == 1) {
            return back()
                ->withErrors(['tenant_login' => 'บัญชีของคุณถูกปิดใช้งาน กรุณาติดต่อเจ้าของหอพัก'])
                ->withInput();
        }

        $tenant = Tenant::where('username', $request->username)
            ->where('status', 0)          // ใช้งานอยู่เท่านั้น
            ->first();

        if (! $tenant || ! $this->checkPassword($request->password, $tenant->password)) {
            return back()
                ->withErrors(['tenant_login' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'])
                ->withInput();
        }

        // เก็บข้อมูลผู้เช่าไว้ใน session
        $request->session()->put('auth_tenant', [
            'id'          => $tenant->tenant_id,
            'name'        => $tenant->name,
            'email'       => $tenant->email,
            'username'    => $tenant->username,
            'avatar_path' => $tenant->avatar_path,
        ]);

        // กันไม่ให้ชนกับ session ของเจ้าของ
        $request->session()->forget('auth_owner');

        // บังคับให้ save session ก่อน redirect
        $request->session()->save();

        // ไปหน้ามุมมองผู้เช่า (หน้าแรก/Dashboard)
        return redirect()->route('home');
    }

    // ---------------- เจ้าของ / แอดมิน ----------------
    public function loginOwner(Request $request)
    {
        $request->validate([
            'username' => ['required','string'],
            'password' => ['required','string'],
        ]);

        $employee = Employee::where('username', $request->username)
            ->where('status', 0)          // 0 = ใช้งานอยู่
            ->first();

        if (! $employee || ! $this->checkPassword($request->password, $employee->password)) {
            return back()
                ->withErrors(['owner_login' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'])
                ->withInput();
        }

        // เก็บข้อมูลเจ้าของไว้ใน session
        $request->session()->put('auth_owner', [
            'id'          => $employee->emp_id,
            'name'        => $employee->name,
            'email'       => $employee->email,
            'username'    => $employee->username,
            'avatar_path' => $employee->avatar_path,
        ]);

        // กันไม่ให้ชนกับ session ผู้เช่า
        $request->session()->forget('auth_tenant');

        // บังคับให้ save session ก่อน redirect
        $request->session()->save();

        // ไปหน้า backend เจ้าของ
        return redirect()->route('backend.dashboard');
    }

    /**
     * ฟังก์ชันช่วยเช็ครหัสผ่าน
     * ถ้าตารางเก็บแบบเข้ารหัส bcrypt -> ใช้ Hash::check
     * ถ้าเก็บ plain text (ยังไม่แนะนำ) -> เทียบตรงๆ
     */
    protected function checkPassword(string $plain, string $stored): bool
    {
        // ✅ ถ้าเก็บแบบ bcrypt (แนะนำ)
        if (strlen($stored) > 40) {        // เดาเบื้องต้นว่าเป็น hash
            return Hash::check($plain, $stored);
        }

        // ⚠ ถ้าเก็บเป็น plain text
        return $plain === $stored;
    }
}

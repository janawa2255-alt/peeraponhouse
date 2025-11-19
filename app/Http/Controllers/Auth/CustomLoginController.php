<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ---------------- ผู้เช่า ----------------
    public function loginTenant(Request $request)
    {
        $request->validate([
            'username' => ['required','string'],
            'password' => ['required','string'],
        ]);

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
            'id'   => $tenant->tenant_id,
            'name' => $tenant->name,
        ]);

        // กันไม่ให้ชนกับ session ของเจ้าของ
        $request->session()->forget('auth_owner');

        // ไปหน้ามุมมองผู้เช่า (เช่น หน้าสัญญาเช่า)
        return redirect()->route('tenant.lease.show');
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
            'id'   => $employee->emp_id,
            'name' => $employee->name,
        ]);

        // กันไม่ให้ชนกับ session ผู้เช่า
        $request->session()->forget('auth_tenant');

        // ไปหน้า backend เจ้าของ (เปลี่ยน route ตามจริงของจ๋า)
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

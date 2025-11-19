<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TenantProfileController extends Controller
{
    /**
     * แสดงหน้าโปรไฟล์ส่วนตัว
     */
    public function show(Request $request)
    {
        $authTenant = $request->session()->get('auth_tenant');

        if (!$authTenant) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $tenant = Tenant::findOrFail($authTenant['id']);

        return view('tenant.profile.show', compact('tenant'));
    }

    /**
     * อัปเดตข้อมูลโปรไฟล์
     */
    public function update(Request $request)
    {
        $authTenant = $request->session()->get('auth_tenant');

        if (!$authTenant) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $tenant = Tenant::findOrFail($authTenant['id']);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:tenants,email,' . $tenant->tenant_id . ',tenant_id',
            'id_card' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'username' => 'required|string|unique:tenants,username,' . $tenant->tenant_id . ',tenant_id',
            'avatar' => 'nullable|image|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        // อัปเดตข้อมูลพื้นฐาน
        $tenant->name = $request->name;
        $tenant->phone = $request->phone;
        $tenant->email = $request->email;
        $tenant->id_card = $request->id_card;
        $tenant->address = $request->address;
        $tenant->username = $request->username;

        // อัปโหลด avatar (ถ้ามี)
        if ($request->hasFile('avatar')) {
            // ลบรูปเก่า
            if ($tenant->avatar_path && file_exists(public_path($tenant->avatar_path))) {
                unlink(public_path($tenant->avatar_path));
            }
            
            // สร้างชื่อไฟล์ใหม่
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // ย้ายไฟล์ไปที่ public/images/tenants/
            $file->move(public_path('images/tenants'), $filename);
            
            // บันทึก path
            $tenant->avatar_path = 'images/tenants/' . $filename;
        }

        // เปลี่ยนรหัสผ่าน (ถ้ามี)
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $tenant->password)) {
                return back()->withErrors(['current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง']);
            }

            $tenant->password = Hash::make($request->new_password);
        }

        $tenant->save();

        // อัปเดต session
        $request->session()->put('auth_tenant', [
            'id' => $tenant->tenant_id,
            'name' => $tenant->name,
            'email' => $tenant->email,
            'username' => $tenant->username,
            'avatar_path' => $tenant->avatar_path,
        ]);

        return back()->with('success', 'อัปเดตข้อมูลโปรไฟล์เรียบร้อยแล้ว');
    }
}

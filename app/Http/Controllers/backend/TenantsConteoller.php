<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

class TenantsConteoller extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::query();

        // ค้นหาชื่อผู้เช่า
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // กรองสถานะ
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tenants = $query->orderBy('tenant_id', 'desc')->paginate(10);
        return view('tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('tenants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'required|string|digits:10|regex:/^[0-9]{10}$/',
            'email'         => 'required|email|max:255|unique:tenants,email',
            'id_card'       => 'required|digits:13',
            'address'       => 'required|max:255',
            'username'      => 'required|max:255|unique:tenants,username',
            'password'      => 'required|min:6|max:255',
            'avatar_path'   => 'nullable|image|mimes:jpg,png,jpeg|max:2048',        
            'status'        => 'nullable|integer',
        ], [
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'phone.digits' => 'เบอร์โทรศัพท์ต้องเป็นตัวเลข 10 หลักเท่านั้น',
            'phone.regex' => 'เบอร์โทรศัพท์ไม่ถูกต้อง',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
            'id_card.digits' => 'เลขบัตรประชาชนต้องเป็นตัวเลข 13 หลัก',
            'username.unique' => 'ชื่อผู้ใช้นี้ถูกใช้งานแล้ว',
        ]);

        $profile = null;
        if ($request->hasFile('avatar_path')) {
            $upload   = $request->file('avatar_path');
            $filename = time() . '_' . $upload->getClientOriginalName();
            $path     = public_path('images/tenants');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $upload->move($path, $filename);
            $profile = 'images/tenants/' . $filename;
        }

        Tenant::create([
            'name'          => $request->name,
            'phone'         => $request->phone,
            'email'         => $request->email,
            'id_card'       => $request->id_card,
            'address'       => $request->address,
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
            'avatar_path'   => $profile,
            'status'        => $request->status ?? 0,
        ]);

        return redirect()->route('backend.tenants.index')->with('success', 'เพิ่มบัญชีสำเร็จ');
    }

    public function edit($id)
    {
        $tenant = Tenant::findOrFail($id);
        return view('tenants.edit', compact('tenant'));
    }

    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'required|string|digits:10|regex:/^[0-9]{10}$/',
            'email'         => 'required|email|max:255|unique:tenants,email,' . $tenant->tenant_id . ',tenant_id',
            'id_card'       => 'required|digits:13',
            'address'       => 'required|max:255',
            'username'      => 'required|max:255|unique:tenants,username,' . $tenant->tenant_id . ',tenant_id',
            'password'      => 'nullable|min:6|max:255',
            'avatar_path'   => 'nullable|image|mimes:jpg,png,jpeg|max:2048',        
            'status'        => 'nullable|integer',
        ], [
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'phone.digits' => 'เบอร์โทรศัพท์ต้องเป็นตัวเลข 10 หลักเท่านั้น',
            'phone.regex' => 'เบอร์โทรศัพท์ไม่ถูกต้อง',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว (ใช้โดยผู้เช่าคนอื่น)',
            'id_card.digits' => 'เลขบัตรประชาชนต้องเป็นตัวเลข 13 หลัก',
            'username.unique' => 'ชื่อผู้ใช้นี้ถูกใช้งานแล้ว',
        ]);

        $profile = $tenant->avatar_path;
        if ($request->hasFile('avatar_path')) {
            $upload   = $request->file('avatar_path');
            $filename = time() . '_' . $upload->getClientOriginalName();
            $path     = public_path('images/tenants');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $upload->move($path, $filename);
            $profile = 'images/tenants/' . $filename;
        }

        $updateData = [
            'name'          => $request->name,
            'phone'         => $request->phone,
            'email'         => $request->email,
            'id_card'       => $request->id_card,
            'address'       => $request->address,
            'username'      => $request->username,
            'avatar_path'   => $profile,
            'status'        => $request->status ?? 0,
        ];
        if (!empty($request->password)) {
            $updateData['password'] = Hash::make($request->password);
        }
        $tenant->update($updateData);

        return redirect()->route('backend.tenants.index')->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }

    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);
        
        // ตรวจสอบว่ามีสัญญาเช่าที่ยังใช้งานอยู่หรือไม่ (status = 1)
        $hasActiveLease = $tenant->leases()->where('status', 1)->exists();
        
        if ($hasActiveLease) {
            return redirect()
                ->route('backend.tenants.index')
                ->with('error', 'ไม่สามารถลบผู้เช่าได้ เนื่องจากยังมีสัญญาเช่าที่ใช้งานอยู่');
        }
        
        // ลบรูปโปรไฟล์ถ้ามี
        if ($tenant->avatar_path && file_exists(public_path($tenant->avatar_path))) {
            unlink(public_path($tenant->avatar_path));
        }
        
        $tenant->delete();
        return redirect()->route('backend.tenants.index')->with('success', 'ลบรายการเรียบร้อยแล้ว');
    }
   public function show($id)
        {
            $tenant = Tenant::findOrFail($id);
            return view('tenants.show', compact('tenant'));  
        }
}
<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller

{
    public function index()
    {
        $employees = Employee::orderBy('emp_id', 'desc')->paginate(10);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
       $request->validate([
            'name'      => 'required|max:255',
            'phone'     => 'nullable|min:10|max:10',
            'email'     => 'required|email|max:255|unique:employees,email',
            'username'  => 'required|max:255|unique:employees,username',
            'password'  => 'required|min:6|max:255',
            'status'    => 'required|integer',
        ]);
        Employee::create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'status'   => $request->status,
        ]);
        

        return redirect()->route('backend.employees.index')->with('success', 'เพิ่มพนักงานสำเร็จ');
    }

        public function edit($id)
        {
            $emp = Employee::findOrFail($id);
            return view('employees.edit', compact('emp'));
        }


    public function update(Request $request, $id)
    {
        $emp = Employee::findOrFail($id);

        $request->validate([
            'name'      => 'required|max:255',
            'phone'     => 'nullable|min:10|max:10',
            'email'     => 'required|email|max:255|unique:employees,email,' . $emp->emp_id . ',emp_id',
            'username'  => 'required|max:255|unique:employees,username,' . $emp->emp_id . ',emp_id',
            'password'  => 'nullable|min:6|max:255',
            'status'    => 'required|integer',
        ]);

        $emp->name = $request->name;
        $emp->phone = $request->phone;
        $emp->email = $request->email;
        $emp->username = $request->username;
        $emp->status = $request->status;

        if (!empty($request->password)) {
            $emp->password = Hash::make($request->password);
        }

        $emp->save();

        return redirect()->route('backend.employees.index')->with('success', 'แก้ไขข้อมูลพนักงานสำเร็จ');
    }

    public function destroy($id)
    {
        $emp = Employee::findOrFail($id);
        $emp->delete();

        return redirect()->route('backend.employees.index')->with('success', 'ลบพนักงานเรียบร้อยแล้ว');
    }
}

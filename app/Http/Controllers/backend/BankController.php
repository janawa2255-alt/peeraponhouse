<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::orderBy('bank_id', 'desc')->get();
        return view('banks.bankindex', compact('banks'));
    }

    public function create()
    {
        return view('banks.bankcreate');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_code'     => 'required|integer',
            'bank_name'     => 'nullable|max:255',
            'account_name'  => 'nullable|max:255',
            'number'        => 'nullable|max:20',
            'status'        => 'required|integer|in:1,2',
            'qrcode_pic'    => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $qrPath = null;
        if ($request->hasFile('qrcode_pic')) {
            $qrPath = $request->file('qrcode_pic')->store('qrcodes', 'public');
        }

        Bank::create([
            'bank_code'    => $request->bank_code,
            'bank_name'    => $request->bank_name,
            'account_name' => $request->account_name,
            'number'       => $request->number,
            'qrcode_pic'   => $qrPath,
            'status'       => $request->status,
        ]);

        return redirect()->route('banks.index')->with('success', 'เพิ่มบัญชีสำเร็จ');
    }

    public function edit($id)
    {
        $bank = Bank::findOrFail($id);
        return view('banks.bankedit', compact('bank'));
    }

    public function update(Request $request, $id)
    {
        $bank = Bank::findOrFail($id);

        $request->validate([
            'bank_code'     => 'required|integer',
            'bank_name'     => 'nullable|max:255',
            'account_name'  => 'nullable|max:255',
            'number'        => 'nullable|max:20',
            'status'        => 'required|integer|in:1,2',
            'qrcode_pic'    => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('qrcode_pic')) {

            if ($bank->qrcode_pic && file_exists(storage_path('app/public/'.$bank->qrcode_pic))) {
                unlink(storage_path('app/public/'.$bank->qrcode_pic));
            }

            $bank->qrcode_pic = $request->file('qrcode_pic')->store('qrcodes', 'public');
        }
        $bank->update([
            'bank_code'    => $request->bank_code,
            'bank_name'    => $request->bank_name,
            'account_name' => $request->account_name,
            'number'       => $request->number,
            'status'       => $request->status,
        ]);

        return redirect()->route('banks.index')->with('success', 'แก้ไขข้อมูลสำเร็จ');
    } 

    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);

        if ($bank->qrcode_pic && file_exists(storage_path('app/public/'.$bank->qrcode_pic))) {
            unlink(storage_path('app/public/'.$bank->qrcode_pic));
        }

        $bank->delete();

        return redirect()->route('banks.index')->with('success', 'ลบรายการเรียบร้อยแล้ว');
    }
}

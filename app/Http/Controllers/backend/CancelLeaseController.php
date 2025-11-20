<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\CancelLease;
use App\Models\Lease;
use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CancelLeaseController extends Controller
{
   
   public function index()
{
    $cancelRequests = CancelLease::with(['lease.tenants', 'lease.rooms'])
        ->where('status', 0 ) // รออนุมัติ
        ->orderByDesc('cancel_id')
        ->get();

    return view('cancel_leases.index', compact('cancelRequests'));
}

public function show($id)
{
    $cancel = CancelLease::with(['lease.tenants', 'lease.rooms'])->findOrFail($id);

    return view('cancel_leases.show', compact('cancel'));
}

public function approve(Request $request, $cancelId)
{
    $cancel = CancelLease::with('lease')->findOrFail($cancelId);

    if ($cancel->status != 0) {
        return back()->with('error', 'รายการนี้ถูกดำเนินการไปแล้ว');
    }

    $request->validate([
        'note_owner' => 'nullable|string|max:255',
    ]);

    $lease = $cancel->lease;

    DB::transaction(function () use ($cancel, $lease, $request) {

        $cancel->update([
            'status'     => 1,  // อนุมัติแล้ว
            'note_owner' => $request->note_owner,
        ]);

        // เรียก logic ยกเลิกสัญญาจริง (เหมือน LeaseController@cancel)
        $lease->update(['status' => 3]);

        $hasOther = Lease::where('room_id', $lease->room_id)
            ->where('status', 1)
            ->where('lease_id', '!=', $lease->lease_id)
            ->exists();

        if (! $hasOther) {
            Room::where('room_id', $lease->room_id)->update(['status' => 0]);
        }

        // sync สถานะผู้เช่า
        // (จะก็อปฟังก์ชัน syncTenantStatus มาไว้ใน Controller นี้ หรือเรียกจาก service ก็ได้)
    });

    return redirect()->route('backend.cancel_lease.index')
        ->with('success', 'อนุมัติการยกเลิกสัญญาเรียบร้อยแล้ว');
}

public function reject(Request $request, $cancelId)
{
    $cancel = CancelLease::findOrFail($cancelId);

    if ($cancel->status != 0) {
        return back()->with('error', 'รายการนี้ถูกดำเนินการไปแล้ว');
    }

    $request->validate([
        'note_owner' => 'nullable|string|max:255',
    ]);

    $cancel->update([
        'status'     => 2,  // ปฏิเสธ
        'note_owner' => $request->note_owner,
    ]);

    return redirect()->route('backend.cancel_lease.index')
        ->with('success', 'ปฏิเสธการยกเลิกสัญญาเรียบร้อยแล้ว');
}
}
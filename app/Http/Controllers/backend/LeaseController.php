<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Models\CancelLease;
use Illuminate\Support\Facades\DB;

class LeaseController extends Controller
{
    private function syncTenantStatus($tenantId)
    {
        if (! $tenantId) {
            return;
        }

        $hasActiveLease = Lease::where('tenant_id', $tenantId)
            ->where('status', 1)  
            ->exists();

        Tenant::where('tenant_id', $tenantId)
            ->update([
                'status' => $hasActiveLease ? 0 : 1,   // 0=เช่าอยู่, 1=ยกเลิก
            ]);
    }
        public function index(Request $request)
    {
        // เปลี่ยน default จาก active → all
        $status = $request->input('status', 'all');  
        $search = $request->input('q');                 
        $query = Lease::with(['tenants', 'rooms']);

        switch ($status) {
            case 'active':
                $query->where('status', 1);
                break;
            case 'ended':
                $query->where('status', 2);
                break;
            case 'canceled':
                $query->where('status', 3);
                break;
            case 'all':
            default:
                // ไม่กรองอะไร
                break;
        }

        if (!empty($search)) {
            $query->whereHas('tenants', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        $leases = $query->orderByDesc('lease_id')->paginate(10);

        return view('leases.index', [
            'leases' => $leases,
            'status' => $status,
            'search' => $search,
        ]);
    }


    public function create()
    {
    
        $activeTenantIds = Lease::where('status', 1)
            ->pluck('tenant_id')
            ->toArray();
        $tenants = Tenant::when(!empty($activeTenantIds), function ($query) use ($activeTenantIds) {
                $query->whereNotIn('tenant_id', $activeTenantIds);
            })
            ->orderBy('name')
            ->get();

        $rooms = Room::where('status', 0)
            ->orderBy('room_no')
            ->get();

        return view('leases.create', compact('tenants', 'rooms'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'tenant_id'   => 'required|exists:tenants,tenant_id',
            'room_id'     => 'required|exists:rooms,room_id',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after:start_date',
            'rent_amount' => 'required|integer|min:0',
            'deposit'     => 'nullable|integer|min:0',
            'note'        => 'nullable|string|max:255',
            'pic_tenant'  => 'required|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $hasActiveLeaseForRoom = Lease::where('room_id', $request->room_id)
            ->where('status', 1) 
            ->exists();

        if ($hasActiveLeaseForRoom) {
            return back()
                ->withErrors(['room_id' => 'ห้องนี้มีสัญญาเช่าที่กำลังเช่าอยู่แล้ว'])
                ->withInput();
        }

        $hasActiveLeaseForTenant = Lease::where('tenant_id', $request->tenant_id)
            ->where('status', 1)
            ->exists();

        if ($hasActiveLeaseForTenant) {
            return back()
                ->withErrors(['tenant_id' => 'ผู้เช่ารายนี้ยังมีสัญญาเช่าที่กำลังเช่าอยู่ ไม่สามารถสร้างสัญญาใหม่ได้'])
                ->withInput();
        }

        DB::transaction(function () use ($request) {

            $idCardPath = null;
            if ($request->hasFile('pic_tenant')) {
                $idCardPath = $request->file('pic_tenant')
                    ->store('lease_idcards', 'public');
            }

            Lease::create([
                'tenant_id'   => $request->tenant_id,
                'room_id'     => $request->room_id,
                'start_date'  => $request->start_date,
                'end_date'    => $request->end_date,
                'rent_amount' => $request->rent_amount,
                'deposit'     => $request->deposit,
                'note'        => $request->note,
                'pic_tenant'  => $idCardPath,
                'status'      => 1, 
            ]);


            Room::where('room_id', $request->room_id)
                ->update(['status' => 1]);
        });

        $this->syncTenantStatus($request->tenant_id);

        return redirect()->route('backend.leases.index')
            ->with('success', 'เพิ่มสัญญาเช่าห้องเรียบร้อยแล้ว');
    }

    public function show($id)
    {
        $lease = Lease::with(['tenants', 'rooms', 'cancelLeases'])
            ->findOrFail($id);

        return view('leases.show', compact('lease'));
    }


    public function cancelForm($id)
    {
        $lease = Lease::with(['tenants', 'rooms'])->findOrFail($id);

        if ($lease->status != 1) {
            return redirect()->route('backend.leases.index')
                ->with('success', 'สัญญานี้ถูกยกเลิกหรือสิ้นสุดไปแล้ว');
        }

        return view('leases.cancel', compact('lease'));
    }

    public function cancel(Request $request, $id)
    {
        $lease = Lease::findOrFail($id);


        if ((int) $lease->status !== 1) {
            return redirect()->route('backend.leases.index')
                ->with('success', 'สัญญานี้ถูกยกเลิกหรือสิ้นสุดไปแล้ว');
        }

        DB::transaction(function () use ($lease, $request) {

            CancelLease::create([
                'lease_id'     => $lease->lease_id,
                'request_date' => now()->toDateString(),
                'reason'       => $request->input('reason', 'ยกเลิกสัญญาโดยผู้ดูแลระบบ'),
                'status'       => 1,   
                'note_owner'   => $request->input('note_owner'),
                'created_at'   => now()->toDateString(),
                'created_by'   => 'admin:' . (auth()->id() ?? 'system'),
            ]);


            $lease->update(['status' => 3]);

            $hasOther = Lease::where('room_id', $lease->room_id)
                ->where('status', 1)
                ->where('lease_id', '!=', $lease->lease_id)
                ->exists();

            if (! $hasOther) {
                Room::where('room_id', $lease->room_id)
                    ->update(['status' => 0]); 
            }
        });


        $this->syncTenantStatus($lease->tenant_id);

        return redirect()->route('backend.leases.index')
            ->with('success', 'ยกเลิกสัญญาเช่าเรียบร้อยแล้ว');
    }
    }
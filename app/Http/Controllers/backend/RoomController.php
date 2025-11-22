<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('room_no', 'asc')->paginate(10);
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_no'   => 'required|max:50|unique:rooms,room_no',
            'base_rent' => 'required|integer|min:0',
            // status ไม่ต้องเลือกจากฟอร์ม ให้เริ่มที่ 0
            'note'      => 'nullable|string',
        ], [
            'room_no.required'   => 'กรุณากรอกเลขห้อง',
            'room_no.unique'     => 'เลขห้องนี้มีในระบบแล้ว',
            'base_rent.required' => 'กรุณากรอกค่าเช่าพื้นฐาน',
            'base_rent.integer'  => 'ค่าเช่าต้องเป็นตัวเลข',
        ]);

        Room::create([
            'room_no'   => $request->room_no,
            'base_rent' => $request->base_rent,
            'status'    => 0,                 // 0 = ว่าง (ค่าเริ่มต้น)
            'note'      => $request->note,
        ]);

        return redirect()->route('rooms.index')
                         ->with('success', 'เพิ่มข้อมูลห้องเช่าเรียบร้อยแล้ว');
    }

    public function edit($id)
    {
        $room = Room::findOrFail($id);
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $request->validate([
            'room_no'   => 'required|max:50|unique:rooms,room_no,' . $room->room_id . ',room_id',
            'base_rent' => 'required|integer|min:0',
            'note'      => 'nullable|string',
        ]);

        $room->update([
            'room_no'   => $request->room_no,
            'base_rent' => $request->base_rent,
            'note'      => $request->note,
        ]);

        return redirect()->route('rooms.index')
                         ->with('success', 'แก้ไขข้อมูลห้องเช่าเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('rooms.index')
                         ->with('success', 'ลบข้อมูลห้องเช่าเรียบร้อยแล้ว');
    }
}

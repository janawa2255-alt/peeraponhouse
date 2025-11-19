<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * แสดงรายการประกาศทั้งหมด
     */
    public function index()
    {
        $announcements = Announcement::orderByDesc('created_at')->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    /**
     * แสดงฟอร์มสร้างประกาศใหม่
     */
    public function create()
    {
        return view('announcements.create');
    }

    /**
     * บันทึกประกาศใหม่
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:0,1',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('announcements', 'public');
        }

        $authOwner = $request->session()->get('auth_owner');

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'image_path' => $imagePath,
            'status' => $request->status,
            'created_by' => $authOwner['id'] ?? null,
        ]);

        return redirect()->route('backend.announcements.index')
            ->with('success', 'สร้างประกาศเรียบร้อยแล้ว');
    }

    /**
     * แสดงฟอร์มแก้ไขประกาศ
     */
    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('announcements.edit', compact('announcement'));
    }

    /**
     * อัปเดตประกาศ
     */
    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:0,1',
        ]);

        $imagePath = $announcement->image_path;
        if ($request->hasFile('image')) {
            // ลบรูปเก่า
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('announcements', 'public');
        }

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'image_path' => $imagePath,
            'status' => $request->status,
        ]);

        return redirect()->route('backend.announcements.index')
            ->with('success', 'อัปเดตประกาศเรียบร้อยแล้ว');
    }

    /**
     * ลบประกาศ
     */
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);

        // ลบรูปภาพ
        if ($announcement->image_path) {
            Storage::disk('public')->delete($announcement->image_path);
        }

        $announcement->delete();

        return redirect()->route('backend.announcements.index')
            ->with('success', 'ลบประกาศเรียบร้อยแล้ว');
    }
}

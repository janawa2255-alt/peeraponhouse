@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-neutral-200">จัดการประกาศ</h1>
        <a href="{{ route('backend.announcements.create') }}" 
           class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>สร้างประกาศใหม่
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-neutral-800 rounded-lg border border-neutral-700 overflow-hidden">
        <table class="w-full">
            <thead class="bg-neutral-900 border-b border-neutral-700">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-neutral-300">หัวข้อ</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-neutral-300">เนื้อหา</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-neutral-300">รูปภาพ</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-neutral-300">สถานะ</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-neutral-300">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-700">
                @forelse($announcements as $announcement)
                <tr class="hover:bg-neutral-700/50">
                    <td class="px-4 py-3 text-neutral-200">{{ $announcement->title }}</td>
                    <td class="px-4 py-3 text-neutral-300 text-sm">
                        {{ Str::limit($announcement->content, 80) }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($announcement->image_path)
                            <img src="{{ asset('storage/' . $announcement->image_path) }}" 
                                 alt="Preview" 
                                 class="h-16 w-16 object-cover rounded mx-auto cursor-pointer hover:scale-110 transition-transform"
                                 onclick="window.open(this.src, '_blank')">
                        @else
                            <span class="text-gray-500 text-xs">ไม่มีรูป</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($announcement->status == 1)
                            <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded">เปิดใช้งาน</span>
                        @else
                            <span class="px-2 py-1 bg-red-500/20 text-red-400 text-xs rounded">ปิดใช้งาน</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('backend.announcements.edit', $announcement->announcement_id) }}" 
                               class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">
                                <i class="fas fa-edit"></i> แก้ไข
                            </a>
                            <form action="{{ route('backend.announcements.destroy', $announcement->announcement_id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('ยืนยันการลบประกาศนี้?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded">
                                    <i class="fas fa-trash"></i> ลบ
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-neutral-400">
                        ยังไม่มีประกาศ
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $announcements->links() }}
    </div>
</div>
@endsection

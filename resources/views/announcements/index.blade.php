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

    {{-- Desktop Table View --}}
    <div class="hidden md:block bg-neutral-800 rounded-lg border border-neutral-700 overflow-hidden">
        <div class="overflow-x-auto">
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
    </div>

    {{-- Mobile Card View --}}
    <div class="grid grid-cols-1 gap-4 md:hidden">
        @forelse($announcements as $announcement)
        <div class="bg-neutral-900/90 border border-neutral-800 rounded-xl p-4 shadow-lg relative overflow-hidden">
            {{-- Status Strip --}}
            <div class="absolute top-0 left-0 w-1 h-full {{ $announcement->status == 1 ? 'bg-green-500' : 'bg-red-500' }}"></div>
            
            <div class="pl-2">
                {{-- Header with Title and Status --}}
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-white font-semibold text-base flex-1">{{ $announcement->title }}</h3>
                    @if($announcement->status == 1)
                        <span class="px-2 py-1 rounded text-[10px] font-bold bg-green-500/20 text-green-400 border border-green-500/30 ml-2">
                            ACTIVE
                        </span>
                    @else
                        <span class="px-2 py-1 rounded text-[10px] font-bold bg-red-500/20 text-red-400 border border-red-500/30 ml-2">
                            INACTIVE
                        </span>
                    @endif
                </div>

                {{-- Content --}}
                <div class="bg-neutral-800/50 p-3 rounded border border-neutral-800 mb-3">
                    <p class="text-gray-300 text-sm">
                        {{ Str::limit($announcement->content, 100) }}
                    </p>
                </div>

                {{-- Image Preview --}}
                @if($announcement->image_path)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $announcement->image_path) }}" 
                         alt="Preview" 
                         class="w-full h-32 object-cover rounded border border-neutral-700 cursor-pointer hover:scale-105 transition-transform"
                         onclick="window.open(this.src, '_blank')">
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('backend.announcements.edit', $announcement->announcement_id) }}" 
                       class="flex-1 py-2 rounded-lg bg-blue-600 text-white text-xs font-medium text-center hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit"></i> แก้ไข
                    </a>
                    <form action="{{ route('backend.announcements.destroy', $announcement->announcement_id) }}" 
                          method="POST" 
                          class="flex-1"
                          onsubmit="return confirm('ยืนยันการลบประกาศนี้?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 rounded-lg bg-red-600 text-white text-xs font-medium text-center hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash"></i> ลบ
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-neutral-900/90 border border-neutral-800 rounded-xl p-8 text-center">
            <i class="fas fa-bullhorn text-gray-600 text-4xl mb-3"></i>
            <p class="text-neutral-400">ยังไม่มีประกาศ</p>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $announcements->links() }}
    </div>
</div>
@endsection

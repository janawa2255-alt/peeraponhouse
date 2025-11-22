@extends('layouts.app')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">
                จัดการข้อมูลห้องเช่า
            </h1>
            <p class="text-sm text-gray-400">
                ดูรายการห้องทั้งหมด เพิ่ม แก้ไข หรือลบข้อมูลห้องเช่าในระบบ
            </p>
        </div>

        <a href="{{ route('backend.rooms.create') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg
                  bg-gradient-to-r from-orange-500 to-orange-600 text-white
                  hover:from-orange-400 hover:to-orange-500 shadow-md shadow-orange-900/40
                  focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-neutral-900">
            + เพิ่มห้องเช่าใหม่
        </a>
    </div>


    @if (session('success'))
        <div class="p-3 rounded-lg border border-green-500/40 bg-green-500/10 text-sm text-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-200">
            <thead class="bg-neutral-900/90 text-xs uppercase text-gray-400 border-b border-orange-500/30">
                <tr>
                    
                    <th class="px-4 py-3">เลขห้อง</th>
                    <th class="px-4 py-3">ค่าเช่าพื้นฐาน</th>
                    <th class="px-4 py-3">สถานะ</th>
                    <th class="px-4 py-3">หมายเหตุ</th>
                    <th class="px-4 py-3 text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rooms as $room)
                        <td class="px-4 py-3 font-medium text-white">
                            {{ $room->room_no }}
                        </td>
                        <td class="px-4 py-3">
                            {{ number_format($room->base_rent, 2) }} บาท
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusText = \App\Models\Room::$statusText[$room->status] ?? 'ไม่ทราบสถานะ';
                                $statusColor = match($room->status) {
                                    1 => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40',
                                    2 => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/40',
                                    3 => 'bg-red-500/20 text-red-300 border-red-500/40',
                                    default => 'bg-gray-500/20 text-gray-300 border-gray-500/40',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[0.7rem] border {{ $statusColor }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-300">
                            {{ $room->note }}
                        </td>
                        <td class="px-4 py-3 text-center space-x-2">
                            <a href="{{ route('backend.rooms.edit', $room->room_id) }}"
                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg
                                      bg-amber-500/20 text-amber-200 border border-amber-500/40
                                      hover:bg-amber-500/30">
                                แก้ไข
                            </a>

                            <form action="{{ route('backend.rooms.destroy', $room->room_id) }}"
                                  method="POST"
                                  class="inline-block"
                                  onsubmit="return confirm('ยืนยันการลบห้อง {{ $room->room_no }} ใช่หรือไม่?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg
                                               bg-red-500/20 text-red-200 border border-red-500/40
                                               hover:bg-red-500/30">
                                    ลบ
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-400">
                            ยังไม่มีข้อมูลห้องเช่าในระบบ
                        </td>
                    </tr>
                @endforelse
            </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-neutral-800">
            {{ $rooms->links() }}
        </div>
    </div>
</div>
@endsection

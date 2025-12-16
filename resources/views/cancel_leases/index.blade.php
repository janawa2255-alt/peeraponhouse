@extends('layouts.app')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">
                จัดการคำขอยกเลิกสัญญาเช่า
            </h1>
            <p class="text-sm text-gray-400">
                ดูรายการแจ้งยกเลิกสัญญาที่ผู้เช่าส่งมา และอนุมัติ/ตรวจสอบแต่ละรายการ
            </p>
        </div>
    </div>

    {{-- ฟอร์มกรองสถานะ --}}
    <form method="GET" action="{{ route('backend.cancel_lease.index') }}" class="flex items-end gap-3">
        <div class="w-full md:w-1/3">
            <label class="block text-sm font-medium text-gray-300 mb-1">
                กรองตามสถานะ
            </label>
            @php $currentStatus = request('status', 'all'); @endphp
            <select name="status" 
                    onchange="this.form.submit()"
                    class="w-full px-3 py-2 rounded-lg bg-neutral-900 border border-gray-600 text-gray-100
                           focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>ทั้งหมด</option>
                <option value="0" {{ $currentStatus === '0' ? 'selected' : '' }}>รออนุมัติ</option>
                <option value="1" {{ $currentStatus === '1' ? 'selected' : '' }}>อนุมัติแล้ว</option>
                <option value="2" {{ $currentStatus === '2' ? 'selected' : '' }}>ปฏิเสธคำขอ</option>
            </select>
        </div>
    </form>

    @if (session('success'))
        <div class="p-3 rounded-lg border border-green-500/40 bg-green-500/10 text-sm text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="p-3 rounded-lg border border-red-500/40 bg-red-500/10 text-sm text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-200">
            <thead class="bg-neutral-900/90 text-xs uppercase text-gray-400 border-b border-orange-500/30">
                <tr>
                    <th class="px-4 py-3 text-center">ลำดับ</th>
                    <th class="px-4 py-3">ผู้เช่า</th>
                    <th class="px-4 py-3">ห้อง</th>
                    <th class="px-4 py-3">วันที่แจ้ง</th>
                    <th class="px-4 py-3">เหตุผล</th>
                    <th class="px-4 py-3">สถานะคำขอ</th>
                    <th class="px-4 py-3 text-center">จัดการ</th>
                </tr>
            </thead>

            <tbody>
            @forelse ($cancelRequests as $item)
                @php
                    switch ((int) $item->status) {
                        case 0:
                            $statusLabel = 'รออนุมัติ';
                            $statusClass = 'bg-yellow-500/20 text-yellow-200 border-yellow-500/40';
                            break;
                        case 1:
                            $statusLabel = 'อนุมัติแล้ว';
                            $statusClass = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40';
                            break;
                        case 2:
                            $statusLabel = 'ปฏิเสธคำขอ';
                            $statusClass = 'bg-red-500/20 text-red-300 border-red-500/40';
                            break;
                        default:
                            $statusLabel = 'ไม่ระบุ';
                            $statusClass = 'bg-gray-500/20 text-gray-300 border-gray-500/40';
                    }
                @endphp

                <tr class="border-t border-neutral-800 hover:bg-neutral-800/70">
                    <td class="px-4 py-3 text-center text-gray-400">{{ $loop->iteration }}</td>

                    <td class="px-4 py-3">
                        {{ optional(optional($item->lease)->tenants)->name ?? '-' }}
                    </td>

                    <td class="px-4 py-3 text-gray-300">
                        {{ optional(optional($item->lease)->rooms)->room_no ?? '-' }}
                    </td>

                    <td class="px-4 py-3 text-gray-300">
                        {{ \Carbon\Carbon::parse($item->request_date)->format('d/m/Y') }}
                    </td>

                    <td class="px-4 py-3 text-gray-300">
                        {{ \Illuminate\Support\Str::limit($item->reason, 40) }}
                    </td>

                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[0.7rem] border {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('backend.cancel_lease.show', $item->cancel_id) }}"
                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg
                                  bg-blue-500/20 text-blue-200 border border-blue-500/40
                                  hover:bg-blue-500/30">
                            ดู / อนุมัติ
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-400">
                        ยังไม่มีคำขอยกเลิกสัญญาเช่า
                    </td>
                </tr>
            @endforelse
            </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

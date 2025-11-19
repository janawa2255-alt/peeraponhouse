@extends('layouts.app')

@section('content')
<div class="space-y-4">

    {{-- หัวข้อหน้า --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">
                จัดการสัญญาเช่า
            </h1>
            <p class="text-sm text-gray-400">
                ดูรายการสัญญาเช่าปัจจุบันและสัญญาเก่าทั้งหมด เพิ่มสัญญาใหม่ หรือยกเลิกสัญญาได้จากหน้านี้
            </p>
        </div>

        <a href="{{ route('backend.leases.create') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg
                  bg-gradient-to-r from-orange-500 to-orange-600 text-white
                  hover:from-orange-400 hover:to-orange-500 shadow-md shadow-orange-900/40
                  focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-neutral-900">
            + เพิ่มสัญญาเช่าใหม่
        </a>
    </div>

    {{-- ฟอร์มกรองสถานะ + ช่องค้นหาชื่อ (รีเฟรชเฉพาะสถานะ / ค้นหาทันที) --}}
    <form id="leaseFilterForm" method="GET" action="{{ route('backend.leases.index') }}"
          class="flex flex-col md:flex-row md:items-end gap-3 rounded-xl p-4">

        {{-- เลือกสถานะ --}}
        <div class="w-full md:w-1/3">
            <label class="block text-sm font-medium text-gray-300 mb-1">
                กรองตามสถานะสัญญา
            </label>
           @php $currentStatus = $status ?? 'all'; @endphp
            <select name="status" id="statusFilter"
                    class="w-full px-3 py-2 rounded-lg bg-neutral-900 border border-gray-600 text-gray-100
                           focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="active"   {{ $currentStatus === 'active' ? 'selected' : '' }}>กำลังเช่าอยู่</option>
                <option value="ended"    {{ $currentStatus === 'ended' ? 'selected' : '' }}>สิ้นสุดสัญญา</option>
                <option value="canceled" {{ $currentStatus === 'canceled' ? 'selected' : '' }}>ยกเลิกสัญญา</option>
                <option value="all"      {{ $currentStatus === 'all' ? 'selected' : '' }}>แสดงทุกสถานะ</option>
            </select>
        </div>

        {{-- ช่องค้นหาชื่อผู้เช่า (กรองทันทีบนหน้า) --}}
        <div class="w-full md:w-1/3">
            <label class="block text-sm font-medium text-gray-300 mb-1">ค้นหาชื่อผู้เช่า</label>
            <input type="text" id="tenantSearch" placeholder="พิมพ์ชื่อผู้เช่า..."
                   class="w-full px-3 py-2 rounded-lg bg-neutral-900 border border-gray-600 text-gray-100
                          focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>

    </form>

    {{-- flash message --}}
    @if (session('success'))
        <div class="p-3 rounded-lg border border-green-500/40 bg-green-500/10 text-sm text-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- ตารางรายการ --}}
    <div class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 overflow-hidden">
        <table class="min-w-full text-sm text-left text-gray-200">
            <thead class="bg-neutral-900/90 text-xs uppercase text-gray-400 border-b border-orange-500/30">
                <tr>
                    <th class="px-4 py-3">ผู้เช่า</th>
                    <th class="px-4 py-3">ห้องเช่า</th>
                    <th class="px-4 py-3">ค่าเช่ารายเดือน</th>
                    <th class="px-4 py-3">วันที่เริ่มสัญญา</th>
                    <th class="px-4 py-3">วันสิ้นสุดสัญญา</th>
                    <th class="px-4 py-3">สถานะ</th>
                    <th class="px-4 py-3 text-center">จัดการ</th>
                </tr>
            </thead>

            <tbody id="leaseTableBody">
            @forelse ($leases as $lease)
                @php
                    switch ((int) $lease->status) {
                        case 1:
                            $statusLabel = 'เช่าอยู่';
                            $statusClass = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40';
                            break;
                        case 2:
                            $statusLabel = 'สิ้นสุดสัญญา';
                            $statusClass = 'bg-gray-500/20 text-gray-300 border-gray-500/40';
                            break;
                        case 3:
                            $statusLabel = 'ยกเลิกสัญญา';
                            $statusClass = 'bg-red-500/20 text-red-300 border-red-500/40';
                            break;
                        default:
                            $statusLabel = 'ไม่ระบุ';
                            $statusClass = 'bg-gray-500/20 text-gray-300 border-gray-500/40';
                    }
                @endphp

                <tr class="border-t border-neutral-800 hover:bg-neutral-800/70">
                    <td class="px-4 py-3 font-medium text-white tenant-name">
                        {{ optional($lease->tenants)->name ?? '-' }}
                    </td>

                    <td class="px-4 py-3 text-gray-300">
                        {{ optional($lease->rooms)->room_no ?? '-' }}
                    </td>

                    <td class="px-4 py-3 text-gray-300">
                        {{ number_format($lease->rent_amount, 0) }} ฿
                    </td>

                    <td class="px-4 py-3 text-gray-300">
                        {{ \Carbon\Carbon::parse($lease->start_date)->format('d/m/Y') }}
                    </td>

                    <td class="px-4 py-3 text-gray-300">
                        {{ \Carbon\Carbon::parse($lease->end_date)->format('d/m/Y') }}
                    </td>

                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[0.7rem] border {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>

                    <td class="px-4 text-center py-3 space-x-2">
                        {{-- ดูรายละเอียด --}}
                        <a href="{{ route('backend.leases.show', $lease->lease_id) }}"
                           class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium
                                      bg-neutral-700 hover:bg-neutral-600 text-gray-100 border border-neutral-600">
                            ดูรายละเอียด
                        </a>

                        {{-- ยกเลิกสัญญาได้เฉพาะสัญญาที่กำลังเช่าอยู่ --}}
                        @if ($lease->status == 1)
                            <a href="{{ route('backend.leases.cancel.form', $lease->lease_id) }}"
                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg
                                               bg-red-500/20 text-red-200 border border-red-500/40
                                               hover:bg-red-500/30"  >    
                                ยกเลิกสัญญาเช่า
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-400">
                        ยังไม่มีข้อมูลสัญญาเช่าในระบบ
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

{{-- สคริปต์กรอง --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form   = document.getElementById('leaseFilterForm');
    const status = document.getElementById('statusFilter');
    const search = document.getElementById('tenantSearch');
    const rows   = document.querySelectorAll('#leaseTableBody tr');

    // เปลี่ยนสถานะแล้ว submit form (รีเฟรชหน้า)
    if (status && form) {
        status.addEventListener('change', function () {
            form.submit();
        });
    }

    // ค้นหาชื่อผู้เช่าแบบทันที (ไม่รีเฟรชหน้า)
    if (search) {
        search.addEventListener('input', function () {
            const keyword = search.value.toLowerCase().trim();

            rows.forEach(row => {
                const name = row.querySelector('.tenant-name')?.textContent.toLowerCase() ?? '';
                row.style.display = name.includes(keyword) ? '' : 'none';
            });
        });
    }
});
</script>

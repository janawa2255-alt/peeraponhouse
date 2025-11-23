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

    {{-- Desktop Table View --}}
    <div class="hidden md:block bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 overflow-hidden">
        <div class="overflow-x-auto">
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
                            $statusBg = 'bg-emerald-500';
                            break;
                        case 2:
                            $statusLabel = 'สิ้นสุดสัญญา';
                            $statusClass = 'bg-gray-500/20 text-gray-300 border-gray-500/40';
                            $statusBg = 'bg-gray-500';
                            break;
                        case 3:
                            $statusLabel = 'ยกเลิกสัญญา';
                            $statusClass = 'bg-red-500/20 text-red-300 border-red-500/40';
                            $statusBg = 'bg-red-500';
                            break;
                        default:
                            $statusLabel = 'ไม่ระบุ';
                            $statusClass = 'bg-gray-500/20 text-gray-300 border-gray-500/40';
                            $statusBg = 'bg-gray-500';
                    }
                @endphp

                <tr class="border-t border-neutral-800 hover:bg-neutral-800/70 lease-item">
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

                        {{-- ปุ่มลบ --}}
                        <form action="{{ route('backend.leases.destroy', $lease->lease_id) }}"
                              method="POST"
                              class="inline-block"
                              onsubmit="return confirm('ยืนยันการลบสัญญาเช่านี้? ข้อมูลและรูปภาพจะถูกลบถาวร');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg
                                           bg-red-600/20 text-red-200 border border-red-600/40
                                           hover:bg-red-600/30">
                                ลบ
                            </button>
                        </form>
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

    {{-- Mobile Card View --}}
    <div class="grid grid-cols-1 gap-4 md:hidden" id="leaseCardContainer">
        @forelse ($leases as $lease)
            @php
                switch ((int) $lease->status) {
                    case 1:
                        $statusLabel = 'เช่าอยู่';
                        $statusClass = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40';
                        $statusBg = 'bg-emerald-500';
                        break;
                    case 2:
                        $statusLabel = 'สิ้นสุดสัญญา';
                        $statusClass = 'bg-gray-500/20 text-gray-300 border-gray-500/40';
                        $statusBg = 'bg-gray-500';
                        break;
                    case 3:
                        $statusLabel = 'ยกเลิกสัญญา';
                        $statusClass = 'bg-red-500/20 text-red-300 border-red-500/40';
                        $statusBg = 'bg-red-500';
                        break;
                    default:
                        $statusLabel = 'ไม่ระบุ';
                        $statusClass = 'bg-gray-500/20 text-gray-300 border-gray-500/40';
                        $statusBg = 'bg-gray-500';
                }
            @endphp
            <div class="bg-neutral-900/90 border border-neutral-800 rounded-xl p-4 shadow-lg relative overflow-hidden lease-item">
                {{-- Status Strip --}}
                <div class="absolute top-0 left-0 w-1 h-full {{ $statusBg }}"></div>

                <div class="flex justify-between items-start mb-3 pl-2">
                    <div>
                        <h3 class="text-white font-bold text-base tenant-name">{{ optional($lease->tenants)->name ?? '-' }}</h3>
                        <p class="text-xs text-gray-400">ห้อง: {{ optional($lease->rooms)->room_no ?? '-' }}</p>
                    </div>
                    <span class="px-2 py-1 rounded text-[10px] font-bold border {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2 mb-4 pl-2 text-sm">
                    <div class="bg-neutral-800/50 p-2 rounded border border-neutral-800">
                        <p class="text-[10px] text-gray-500 mb-0.5">วันที่เริ่ม</p>
                        <p class="text-gray-200 font-medium">{{ \Carbon\Carbon::parse($lease->start_date)->format('d/m/Y') }}</p>
                    </div>
                    <div class="bg-neutral-800/50 p-2 rounded border border-neutral-800">
                        <p class="text-[10px] text-gray-500 mb-0.5">วันสิ้นสุด</p>
                        <p class="text-gray-200 font-medium">{{ \Carbon\Carbon::parse($lease->end_date)->format('d/m/Y') }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-4 pl-2 bg-neutral-800/30 p-2 rounded-lg">
                    <span class="text-gray-400 text-sm">ค่าเช่า/เดือน</span>
                    <span class="text-base font-bold text-orange-400">
                        {{ number_format($lease->rent_amount, 0) }} ฿
                    </span>
                </div>

                <div class="flex flex-col gap-2 pl-2">
                    <a href="{{ route('backend.leases.show', $lease->lease_id) }}"
                       class="w-full py-2 rounded-lg bg-neutral-700 text-white text-xs font-medium text-center hover:bg-neutral-600 transition-colors border border-neutral-600">
                        ดูรายละเอียด
                    </a>
                    @if ($lease->status == 1)
                        <a href="{{ route('backend.leases.cancel.form', $lease->lease_id) }}"
                           class="w-full py-2 rounded-lg bg-red-500/10 text-red-500 text-xs font-medium text-center hover:bg-red-500/20 transition-colors border border-red-500/30">
                            ยกเลิกสัญญาเช่า
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500 bg-neutral-900/50 rounded-xl border border-neutral-800">
                ยังไม่มีข้อมูลสัญญาเช่าในระบบ
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $leases->appends(request()->query())->links() }}
    </div>

</div>
@endsection

{{-- สคริปต์กรอง --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form   = document.getElementById('leaseFilterForm');
    const status = document.getElementById('statusFilter');
    const search = document.getElementById('tenantSearch');
    // Select both table rows and mobile cards
    const items  = document.querySelectorAll('.lease-item');

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

            items.forEach(item => {
                const name = item.querySelector('.tenant-name')?.textContent.toLowerCase() ?? '';
                item.style.display = name.includes(keyword) ? '' : 'none';
            });
        });
    }
});
</script>

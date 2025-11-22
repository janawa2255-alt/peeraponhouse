@extends('layouts.app')

@section('content')
<div class="space-y-4">

    {{-- หัวข้อหน้า --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">
                ใบแจ้งหนี้
            </h1>
            <p class="text-sm text-gray-400">
                ดูรายการใบแจ้งหนี้ทั้งหมด และจัดการสถานะได้จากหน้านี้
            </p>
        </div>

        <a href="{{ route('backend.invoices.create') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg
                  bg-gradient-to-r from-orange-500 to-orange-600 text-white
                  hover:from-orange-400 hover:to-orange-500 shadow-md shadow-orange-900/40">
            ออกใบแจ้งหนี้เดือนนี้
        </a>
    </div>

    {{-- ฟอร์มกรองสถานะ + ค้นหาเลขห้อง --}}
    <form id="invoiceFilterForm" method="GET" action="{{ route('backend.invoices.index') }}"
          class="flex flex-col md:flex-row md:items-end gap-3 rounded-xl p-4">

        {{-- เลือกสถานะ --}}
        <div class="w-full md:w-1/3">
            <label class="block text-sm font-medium text-gray-300 mb-1">
                กรองตามสถานะใบแจ้งหนี้
            </label>
            @php $currentStatus = $status ?? 'all'; @endphp
            <select name="status" id="statusFilter"
                    class="w-full px-3 py-2 rounded-lg bg-neutral-900 border border-gray-600 text-gray-100
                           focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="unpaid"   {{ $currentStatus === 'unpaid'   ? 'selected' : '' }}>รอชำระ</option>
                <option value="paid"     {{ $currentStatus === 'paid'     ? 'selected' : '' }}>ชำระแล้ว</option>
                <option value="overdue"  {{ $currentStatus === 'overdue'  ? 'selected' : '' }}>เกินกำหนด</option>
                <option value="canceled" {{ $currentStatus === 'canceled' ? 'selected' : '' }}>ยกเลิก</option>
                <option value="all"      {{ $currentStatus === 'all'      ? 'selected' : '' }}>แสดงทุกสถานะ</option>
            </select>
        </div>

        {{-- ค้นหาเลขห้อง (กรองทันทีบนหน้า) --}}
        <div class="w-full md:w-1/3">
            <label class="block text-sm font-medium text-gray-300 mb-1">
                ค้นหาเลขห้อง
            </label>
            <input type="text" id="roomSearch" placeholder="พิมพ์เลขห้อง..."
                   class="w-full px-3 py-2 rounded-lg bg-neutral-900 border border-gray-600 text-gray-100
                          focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>

    </form>

    {{-- Desktop Table View --}}
    <div class="hidden md:block bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 overflow-hidden">
        {{-- หัวเทา --}}
        <div class="bg-neutral-900/90 px-6 py-3 border-b border-orange-500/30">
            <h2 class="text-white font-semibold text-lg">
                รายการใบแจ้งหนี้
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-300">
                <thead class="bg-neutral-900/90 text-gray-200 text-xs uppercase border-b border-neutral-800">
                    <tr>
                        <th class="px-6 py-3">เลขที่ใบแจ้งหนี้</th>
                        <th class="px-6 py-3">ผู้เช่า</th>
                        <th class="px-6 py-3">ห้อง</th>
                        <th class="px-6 py-3 text-right">ยอดรวม</th>
                        <th class="px-6 py-3">ครบกำหนด</th>
                        <th class="px-6 py-3">สถานะ</th>
                        <th class="px-6 py-3 text-center">จัดการ</th>
                    </tr>
                </thead>

                <tbody id="invoiceTableBody">
                @forelse($invoices as $invoice)
                    @php
                        $expense = $invoice->expense ?? null;
                        $lease   = $expense->lease ?? null;
                        $tenant  = $lease->tenants ?? null;
                        $room    = $lease->rooms ?? null;

                        // แปลงสถานะเป็น label + สี
                        switch ($invoice->status) {
                            case 1:
                                $statusLabel = 'ชำระแล้ว';
                                $statusClass = 'bg-green-500/90 text-white';
                                $statusBg = 'bg-green-500';
                                break;
                            case 2:
                                $statusLabel = 'เกินกำหนด';
                                $statusClass = 'bg-red-500/90 text-white';
                                $statusBg = 'bg-red-500';
                                break;
                            case 3:
                                $statusLabel = 'ยกเลิก';
                                $statusClass = 'bg-gray-500/90 text-white';
                                $statusBg = 'bg-gray-500';
                                break;
                            default:
                                $statusLabel = 'รอชำระ';
                                $statusClass = 'bg-yellow-400/90 text-black';
                                $statusBg = 'bg-yellow-400';
                        }
                    @endphp

                    <tr class="border-t border-neutral-800 hover:bg-neutral-800/60">
                        <td class="px-6 py-3 whitespace-nowrap">
                            {{ $invoice->invoice_code }}
                        </td>

                        <td class="px-6 py-3">
                            {{ optional($lease)->tenant->name ?? optional($tenant)->name ?? '-' }}
                        </td>

                        <td class="px-6 py-3 room-no">
                            {{ $room->room_no ?? '-' }}
                        </td>

                        <td class="px-6 py-3 text-right whitespace-nowrap">
                            @if($expense)
                                {{ number_format($expense->total_amount, 0) }} ฿
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-6 py-3 whitespace-nowrap">
                            {{ optional($invoice->due_date)->format('d/m/Y') }}
                        </td>

                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>

  <td class="px-6 py-3">
    {{-- ใช้ Grid เพื่อล็อคคอลัมน์ให้ตรงกันทุกแถว --}}
    <div class="grid grid-cols-[120px_120px_80px] gap-2 justify-center">

        {{-- ปุ่มดูรายละเอียด --}}
        <a href="{{ route('backend.invoices.show', $invoice->invoice_id) }}"
            class="flex items-center justify-center w-full px-2 py-1.5 rounded-lg text-xs font-medium
            bg-neutral-700 hover:bg-neutral-600 text-gray-100 border border-neutral-600 transition-colors">
            ดูรายละเอียด
        </a>

        {{-- ปุ่มส่งแจ้งเตือน --}}
        <form action="{{ route('backend.invoices.notify', $invoice->invoice_id) }}"
            method="POST"
            class="w-full"
            onsubmit="return confirm('ส่งอีเมลแจ้งผู้เช่าอีกรอบ?')">
            @csrf
            <button type="submit"
                    class="flex items-center justify-center w-full px-2 py-1.5 text-xs font-medium rounded-lg
                    bg-amber-500/20 text-amber-200 border border-amber-500/40
                    hover:bg-amber-500/30 transition-colors">
                ส่งแจ้งเตือน
            </button>
        </form>

        {{-- ปุ่มยกเลิก --}}
        @if($invoice->status == 0)
            <form method="POST"
                action="{{ route('backend.invoices.cancel', $invoice->invoice_id) }}"
                class="w-full"
                onsubmit="return confirm('ต้องการยกเลิกใบแจ้งหนี้นี้หรือไม่?');">
                @csrf
                @method('PATCH')
                <button type="submit"
                        class="flex items-center justify-center w-full px-2 py-1.5 text-xs font-medium rounded-lg
                            bg-red-500/20 text-red-200 border border-red-500/40
                            hover:bg-red-500/30 transition-colors">
                    ยกเลิก
                </button>
            </form>
        @else
            {{-- Spacer สำหรับแถวที่ไม่มีปุ่มยกเลิก --}}
            <div class="w-full"></div>
        @endif

    </div>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-gray-400">
                            ยังไม่มีใบแจ้งหนี้
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($invoices, 'links'))
            <div class="px-6 py-4 border-t border-neutral-800">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

    {{-- Mobile Card View --}}
    <div class="grid grid-cols-1 gap-4 md:hidden">
        @forelse($invoices as $invoice)
            @php
                $expense = $invoice->expense ?? null;
                $lease   = $expense->lease ?? null;
                $tenant  = $lease->tenants ?? null;
                $room    = $lease->rooms ?? null;

                // แปลงสถานะเป็น label + สี
                switch ($invoice->status) {
                    case 1:
                        $statusLabel = 'ชำระแล้ว';
                        $statusClass = 'bg-green-500/20 text-green-400 border border-green-500/30';
                        $statusBg = 'bg-green-500';
                        break;
                    case 2:
                        $statusLabel = 'เกินกำหนด';
                        $statusClass = 'bg-red-500/20 text-red-400 border border-red-500/30';
                        $statusBg = 'bg-red-500';
                        break;
                    case 3:
                        $statusLabel = 'ยกเลิก';
                        $statusClass = 'bg-gray-500/20 text-gray-400 border border-gray-500/30';
                        $statusBg = 'bg-gray-500';
                        break;
                    default:
                        $statusLabel = 'รอชำระ';
                        $statusClass = 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30';
                        $statusBg = 'bg-yellow-500';
                }
            @endphp
            <div class="bg-neutral-900/90 border border-neutral-800 rounded-xl p-4 shadow-lg relative overflow-hidden">
                {{-- Status Strip --}}
                <div class="absolute top-0 left-0 w-1 h-full {{ $statusBg }}"></div>

                <div class="flex justify-between items-start mb-3 pl-2">
                    <div>
                        <h3 class="text-white font-bold text-lg">{{ $invoice->invoice_code }}</h3>
                        <p class="text-xs text-gray-400">ครบกำหนด: {{ optional($invoice->due_date)->format('d/m/Y') }}</p>
                    </div>
                    <span class="px-2 py-1 rounded text-[10px] font-bold {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2 mb-4 pl-2 text-sm">
                    <div class="bg-neutral-800/50 p-2 rounded border border-neutral-800">
                        <p class="text-[10px] text-gray-500 mb-0.5">ผู้เช่า</p>
                        <p class="text-gray-200 font-medium truncate">{{ optional($lease)->tenant->name ?? optional($tenant)->name ?? '-' }}</p>
                    </div>
                    <div class="bg-neutral-800/50 p-2 rounded border border-neutral-800">
                        <p class="text-[10px] text-gray-500 mb-0.5">ห้อง</p>
                        <p class="text-gray-200 font-medium">{{ $room->room_no ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-4 pl-2 bg-neutral-800/30 p-2 rounded-lg">
                    <span class="text-gray-400 text-sm">ยอดรวม</span>
                    <span class="text-xl font-bold text-orange-400">
                        @if($expense)
                            {{ number_format($expense->total_amount, 0) }} ฿
                        @else
                            -
                        @endif
                    </span>
                </div>

                <div class="flex flex-col gap-2 pl-2">
                    <div class="flex gap-2">
                        <a href="{{ route('backend.invoices.show', $invoice->invoice_id) }}" 
                           class="flex-1 py-2 rounded-lg bg-neutral-700 text-white text-xs font-medium text-center hover:bg-neutral-600 transition-colors border border-neutral-600">
                            ดูรายละเอียด
                        </a>
                        <form action="{{ route('backend.invoices.notify', $invoice->invoice_id) }}" method="POST" class="flex-1" onsubmit="return confirm('ส่งอีเมลแจ้งผู้เช่าอีกรอบ?')">
                            @csrf
                            <button type="submit" class="w-full py-2 rounded-lg bg-amber-500/10 text-amber-500 text-xs font-medium text-center hover:bg-amber-500/20 transition-colors border border-amber-500/30">
                                ส่งแจ้งเตือน
                            </button>
                        </form>
                    </div>
                    @if($invoice->status == 0)
                        <form method="POST" action="{{ route('backend.invoices.cancel', $invoice->invoice_id) }}" class="w-full" onsubmit="return confirm('ต้องการยกเลิกใบแจ้งหนี้นี้หรือไม่?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full py-2 rounded-lg bg-red-500/10 text-red-500 text-xs font-medium text-center hover:bg-red-500/20 transition-colors border border-red-500/30">
                                ยกเลิกใบแจ้งหนี้
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500 bg-neutral-900/50 rounded-xl border border-neutral-800">
                ยังไม่มีใบแจ้งหนี้
            </div>
        @endforelse

        @if(method_exists($invoices, 'links'))
            <div class="mt-4">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

{{-- สคริปต์กรอง --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form   = document.getElementById('invoiceFilterForm');
    const status = document.getElementById('statusFilter');
    const search = document.getElementById('roomSearch');
    const rows   = document.querySelectorAll('#invoiceTableBody tr');

    // เปลี่ยนสถานะแล้ว submit form (รีเฟรชหน้า)
    if (status && form) {
        status.addEventListener('change', function () {
            form.submit();
        });
    }

    // ค้นหาเลขห้องแบบทันที (ไม่รีเฟรชหน้า)
    if (search) {
        search.addEventListener('input', function () {
            const keyword = search.value.toLowerCase().trim();

            rows.forEach(row => {
                const roomNo = row.querySelector('.room-no')?.textContent.toLowerCase() ?? '';
                row.style.display = roomNo.includes(keyword) ? '' : 'none';
            });
        });
    }
});
</script>

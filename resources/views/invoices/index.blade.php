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

    {{-- กล่องตาราง --}}
    <div class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 overflow-hidden">
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
                                break;
                            case 2:
                                $statusLabel = 'เกินกำหนด';
                                $statusClass = 'bg-red-500/90 text-white';
                                break;
                            case 3:
                                $statusLabel = 'ยกเลิก';
                                $statusClass = 'bg-gray-500/90 text-white';
                                break;
                            default:
                                $statusLabel = 'รอชำระ';
                                $statusClass = 'bg-yellow-400/90 text-black';
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
    <div class="flex items-center justify-center gap-2">

        {{-- ปุ่มดูรายละเอียด --}}
        <a href="{{ route('backend.invoices.show', $invoice->invoice_id) }}"
            class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium
            bg-neutral-700 hover:bg-neutral-600 text-gray-100 border border-neutral-600">
            ดูรายละเอียด
        </a>

                            {{-- ปุ่มส่งแจ้งเตือน --}}
                            <form action="{{ route('backend.invoices.notify', $invoice->invoice_id) }}"
                                method="POST"
                                onsubmit="return confirm('ส่งอีเมลแจ้งผู้เช่าอีกรอบ?')">
                                @csrf
                                <button type="submit"
                                         class="inline-flex  px-3 py-1.5 text-xs font-medium rounded-lg
                                        bg-amber-500/20 text-amber-200 border border-amber-500/40
                                        hover:bg-amber-500/30">
                                    ส่งแจ้งเตือน
                                </button>
                            </form>

                            {{-- ปุ่มยกเลิก --}}
                            @if($invoice->status == 0)
                                <form method="POST"
                                    action="{{ route('backend.invoices.cancel', $invoice->invoice_id) }}"
                                    onsubmit="return confirm('ต้องการยกเลิกใบแจ้งหนี้นี้หรือไม่?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg
                                               bg-red-500/20 text-red-200 border border-red-500/40
                                               hover:bg-red-500/30">
                                        ยกเลิก
                                    </button>
                                </form>
                            @endif

                        </div>
                    </td>
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

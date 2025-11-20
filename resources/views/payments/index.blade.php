@extends('layouts.app')

@section('content')
<div class="space-y-4">

    {{-- หัวข้อหน้า --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">
                การแจ้งชำระเงิน
            </h1>
            <p class="text-sm text-gray-400">
                ตรวจสอบการแจ้งชำระเงินจากผู้เช่า และอนุมัติ / ปฏิเสธได้จากหน้านี้
            </p>
        </div>
    </div>

    {{-- ฟอร์มกรองสถานะ + ช่องค้นหาเลขห้อง --}}
    <form id="paymentFilterForm" method="GET" action="{{ route('backend.payments.index') }}"
          class="flex flex-col md:flex-row md:items-end gap-3 rounded-xl p-4">

        {{-- เลือกสถานะ --}}
        <div class="w-full md:w-1/3">
            <label class="block text-sm font-medium text-gray-300 mb-1">
                กรองตามสถานะการชำระเงิน
            </label>
            @php $currentStatus = $status ?? 'all'; @endphp
            <select name="status" id="statusFilter"
                    class="w-full px-3 py-2 rounded-lg bg-neutral-900 border border-gray-600 text-gray-100
                           focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="pending"  {{ $currentStatus === 'pending'  ? 'selected' : '' }}>รอตรวจสอบ</option>
                <option value="approved" {{ $currentStatus === 'approved' ? 'selected' : '' }}>อนุมัติแล้ว</option>
                <option value="rejected" {{ $currentStatus === 'rejected' ? 'selected' : '' }}>ถูกปฏิเสธ / ยกเลิก</option>
                <option value="all"      {{ $currentStatus === 'all'      ? 'selected' : '' }}>แสดงทุกสถานะ</option>
            </select>
        </div>

        {{-- ช่องค้นหาเลขห้อง (กรองทันทีบนหน้า) --}}
        <div class="w-full md:w-1/3">
            <label class="block text-sm font-medium text-gray-300 mb-1">
                ค้นหาเลขห้อง
            </label>
            <input type="text" id="roomSearch" placeholder="พิมพ์เลขห้อง..."
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
                    <th class="px-4 py-3">เลขที่ใบแจ้งหนี้</th>
                    <th class="px-4 py-3">ผู้เช่า</th>
                    <th class="px-4 py-3">ห้องเช่า</th>
                    <th class="px-4 py-3">วิธีชำระ</th>
                    <th class="px-4 py-3">วันที่โอน</th>
                    <th class="px-4 py-3">ยอดชำระ</th>
                    <th class="px-4 py-3">สลิป</th>
                    <th class="px-4 py-3">สถานะ</th>
                    <th class="px-4 py-3 text-center">จัดการ</th>
                </tr>
            </thead>

            <tbody id="paymentTableBody">
            @forelse ($payments as $payment)
                @php
                    $invoice = $payment->invoice;
                    $expense = $invoice->expense ?? null;
                    $lease   = $expense->lease ?? null;
                    $tenant  = $lease->tenants ?? null;
                    $room    = $lease->rooms ?? null;

                    switch ((int) $payment->status) {
                        case 0:
                            $statusLabel = 'รอตรวจสอบ';
                            $statusClass = 'bg-yellow-500/20 text-yellow-300 border-yellow-500/40';
                            break;
                        case 1:
                            $statusLabel = 'อนุมัติแล้ว';
                            $statusClass = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40';
                            break;
                        case 2:
                            $statusLabel = 'ถูกปฏิเสธ / ยกเลิก';
                            $statusClass = 'bg-red-500/20 text-red-300 border-red-500/40';
                            break;
                        default:
                            $statusLabel = 'ไม่ทราบสถานะ';
                            $statusClass = 'bg-gray-500/20 text-gray-300 border-gray-500/40';
                    }
                @endphp

                <tr class="border-t border-neutral-800 hover:bg-neutral-800/70">
                    <td class="px-4 py-3 text-gray-200">
                        {{ $invoice->invoice_code ?? '-' }}
                    </td>

                    <td class="px-4 py-3 text-gray-200">
                        {{ optional($lease)->tenant->name ?? optional($tenant)->name ?? '-' }}
                    </td>

                    <td class="px-4 py-3 text-gray-200 room-no">
                        {{ $room->room_no ?? '-' }}
                    </td>

                    <td class="px-4 py-3 text-gray-300">
                        {{ $payment->method_label }}
                    </td>

                    <td class="px-4 py-3 text-gray-300">
                        {{ optional($payment->paid_date)->format('d/m/Y') }}
                    </td>

                    <td class="px-4 py-3 text-gray-300">
                        {{ number_format($payment->total_amount, 0) }} ฿
                    </td>

                    <td class="px-4 py-3">
                        @if ($payment->pic_slip)
                            <a href="{{ route('backend.payments.show', $payment) }}"
                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg
                                      bg-gray-200 text-gray-800 border border-gray-300
                                      hover:bg-gray-300">
                                ดูสลิป
                            </a>
                        @else
                            <span class="text-xs text-gray-500">ไม่มีสลิป</span>
                        @endif
                    </td>

                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[0.7rem] border {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-center space-x-2">
                        {{-- ปุ่มจัดการ แสดงเฉพาะตอนรอตรวจสอบ --}}
                        @if ($payment->status == 0)
                            {{-- อนุมัติ --}}
                            <form action="{{ route('backend.payments.updateStatus', $payment) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('ยืนยันการอนุมัติการชำระเงินนี้หรือไม่?')">
                                @csrf
                                <input type="hidden" name="status" value="1">
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg
                                           bg-green-500/80 text-white hover:bg-green-400">
                                    อนุมัติ
                                </button>
                            </form>

                            {{-- ปฏิเสธ / ยกเลิก --}}
                            <form action="{{ route('backend.payments.updateStatus', $payment) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('ยืนยันการปฏิเสธการชำระเงินนี้หรือไม่?')">
                                @csrf
                                <input type="hidden" name="status" value="2">
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg
                                           bg-red-500/80 text-white hover:bg-red-400">
                                    ปฏิเสธ
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-gray-500">
                                ตรวจสอบแล้ว
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-4 py-6 text-center text-gray-400">
                        ยังไม่มีการแจ้งชำระเงิน
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

    </div>

    <div>
        {{ $payments->links() }}
    </div>

</div>
@endsection

{{-- สคริปต์กรอง --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form   = document.getElementById('paymentFilterForm');
    const status = document.getElementById('statusFilter');
    const search = document.getElementById('roomSearch');
    const rows   = document.querySelectorAll('#paymentTableBody tr');

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

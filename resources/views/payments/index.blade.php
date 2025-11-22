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
    {{-- Desktop Table View --}}
    <div class="hidden md:block bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 overflow-hidden">
        <div class="overflow-x-auto">
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
                            $statusBg = 'bg-yellow-500';
                            break;
                        case 1:
                            $statusLabel = 'อนุมัติแล้ว';
                            $statusClass = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40';
                            $statusBg = 'bg-emerald-500';
                            break;
                        case 2:
                            $statusLabel = 'ถูกปฏิเสธ / ยกเลิก';
                            $statusClass = 'bg-red-500/20 text-red-300 border-red-500/40';
                            $statusBg = 'bg-red-500';
                            break;
                        default:
                            $statusLabel = 'ไม่ทราบสถานะ';
                            $statusClass = 'bg-gray-500/20 text-gray-300 border-gray-500/40';
                            $statusBg = 'bg-gray-500';
                    }
                @endphp

                <tr class="border-t border-neutral-800 hover:bg-neutral-800/70 payment-item">
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
    </div>

    {{-- Mobile Card View --}}
    <div class="grid grid-cols-1 gap-4 md:hidden" id="paymentCardContainer">
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
                        $statusBg = 'bg-yellow-500';
                        break;
                    case 1:
                        $statusLabel = 'อนุมัติแล้ว';
                        $statusClass = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40';
                        $statusBg = 'bg-emerald-500';
                        break;
                    case 2:
                        $statusLabel = 'ถูกปฏิเสธ / ยกเลิก';
                        $statusClass = 'bg-red-500/20 text-red-300 border-red-500/40';
                        $statusBg = 'bg-red-500';
                        break;
                    default:
                        $statusLabel = 'ไม่ทราบสถานะ';
                        $statusClass = 'bg-gray-500/20 text-gray-300 border-gray-500/40';
                        $statusBg = 'bg-gray-500';
                }
            @endphp
            <div class="bg-neutral-900/90 border border-neutral-800 rounded-xl p-4 shadow-lg relative overflow-hidden payment-item">
                {{-- Status Strip --}}
                <div class="absolute top-0 left-0 w-1 h-full {{ $statusBg }}"></div>

                <div class="flex justify-between items-start mb-3 pl-2">
                    <div>
                        <h3 class="text-white font-bold text-base">{{ $invoice->invoice_code ?? '-' }}</h3>
                        <p class="text-xs text-gray-400">วันที่โอน: {{ optional($payment->paid_date)->format('d/m/Y') }}</p>
                    </div>
                    <span class="px-2 py-1 rounded text-[10px] font-bold border {{ $statusClass }}">
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
                        <p class="text-gray-200 font-medium room-no">{{ $room->room_no ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-4 pl-2 bg-neutral-800/30 p-2 rounded-lg">
                    <span class="text-gray-400 text-sm">ยอดชำระ</span>
                    <span class="text-base font-bold text-orange-400">
                        {{ number_format($payment->total_amount, 0) }} ฿
                    </span>
                </div>

                <div class="flex flex-col gap-2 pl-2">
                    @if ($payment->pic_slip)
                        <a href="{{ route('backend.payments.show', $payment) }}"
                           class="w-full py-2 rounded-lg bg-neutral-700 text-white text-xs font-medium text-center hover:bg-neutral-600 transition-colors border border-neutral-600">
                            ดูสลิป
                        </a>
                    @endif

                    @if ($payment->status == 0)
                        <div class="flex gap-2">
                            <form action="{{ route('backend.payments.updateStatus', $payment) }}" method="POST" class="flex-1"
                                  onsubmit="return confirm('ยืนยันการอนุมัติการชำระเงินนี้หรือไม่?')">
                                @csrf
                                <input type="hidden" name="status" value="1">
                                <button type="submit" class="w-full py-2 rounded-lg bg-green-500/20 text-green-400 text-xs font-medium text-center hover:bg-green-500/30 transition-colors border border-green-500/40">
                                    อนุมัติ
                                </button>
                            </form>
                            <form action="{{ route('backend.payments.updateStatus', $payment) }}" method="POST" class="flex-1"
                                  onsubmit="return confirm('ยืนยันการปฏิเสธการชำระเงินนี้หรือไม่?')">
                                @csrf
                                <input type="hidden" name="status" value="2">
                                <button type="submit" class="w-full py-2 rounded-lg bg-red-500/20 text-red-400 text-xs font-medium text-center hover:bg-red-500/30 transition-colors border border-red-500/40">
                                    ปฏิเสธ
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500 bg-neutral-900/50 rounded-xl border border-neutral-800">
                ยังไม่มีการแจ้งชำระเงิน
            </div>
        @endforelse
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
    // Select both table rows and mobile cards
    const items  = document.querySelectorAll('.payment-item');

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

            items.forEach(item => {
                const roomNo = item.querySelector('.room-no')?.textContent.toLowerCase() ?? '';
                item.style.display = roomNo.includes(keyword) ? '' : 'none';
            });
        });
    }
});
</script>

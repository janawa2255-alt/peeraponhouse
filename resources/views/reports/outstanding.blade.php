@extends('layouts.app')

@section('content')
<style>
    @media print {
        nav, aside, .no-print, header, form {
            display: none !important;
        }
        body, .text-white, .text-gray-200, .text-gray-300, .text-gray-400 {
            color: black !important;
            background: white !important;
        }
        main {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .bg-neutral-900, .bg-neutral-800, .bg-neutral-700 {
            background-color: white !important;
            border: 1px solid #ddd !important;
        }
        /* Adjust grid for print */
        .grid {
            display: flex !important;
            gap: 10px !important;
        }
        .grid-cols-1, .md\:grid-cols-3 {
            flex-direction: row !important;
        }
        /* Make cards look like simple boxes */
        .rounded-xl, .rounded-2xl {
            border-radius: 0 !important;
            border: 1px solid #ccc !important;
            box-shadow: none !important;
        }
        /* Remove gradients */
        .bg-gradient-to-br {
            background: white !important;
            color: black !important;
            border: 1px solid #000 !important;
        }
        .text-red-100, .text-yellow-100, .text-orange-100 {
            color: #333 !important;
        }
        /* Table adjustments */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        th, td {
            border: 1px solid #ddd !important;
            color: black !important;
        }
    }
</style>

<div class="space-y-4">
    {{-- Header --}}
    <div class="flex items-center justify-between no-print">
        <div>
            <h1 class="text-2xl font-semibold text-white">
                รายงานยอดค้างชำระ
            </h1>
            <p class="text-sm text-gray-400">
                รายการใบแจ้งหนี้ที่ยังไม่ได้ชำระและเกินกำหนด
            </p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-500 transition-colors shadow-lg shadow-blue-900/20">
            <i class="fas fa-print mr-2"></i> พิมพ์รายงาน
        </button>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm">ยอดค้างชำระทั้งหมด</p>
                    <h3 class="text-3xl font-bold text-white mt-1">{{ number_format($totalOutstanding, 0) }}</h3>
                    <p class="text-red-100 text-xs mt-1">บาท</p>
                </div>
                <div class="text-red-100 text-4xl">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-600 to-yellow-700 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">รอชำระ</p>
                    <h3 class="text-3xl font-bold text-white mt-1">{{ $countUnpaid }}</h3>
                    <p class="text-yellow-100 text-xs mt-1">ใบแจ้งหนี้</p>
                </div>
                <div class="text-yellow-100 text-4xl">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-600 to-orange-700 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">เกินกำหนด</p>
                    <h3 class="text-3xl font-bold text-white mt-1">{{ $countOverdue }}</h3>
                    <p class="text-orange-100 text-xs mt-1">ใบแจ้งหนี้</p>
                </div>
                <div class="text-orange-100 text-4xl">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Outstanding List --}}
    <div class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-200">
            <thead class="bg-neutral-900/90 text-xs uppercase text-gray-400 border-b border-orange-500/30">
                <tr>
                    <th class="px-4 py-3">เลขที่ใบแจ้งหนี้</th>
                    <th class="px-4 py-3">ผู้เช่า</th>
                    <th class="px-4 py-3">ห้อง</th>
                    <th class="px-4 py-3">วันครบกำหนด</th>
                    <th class="px-4 py-3 text-right">ยอดเงิน</th>
                    <th class="px-4 py-3">สถานะ</th>
                    <th class="px-4 py-3 text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    @php
                        $expense = $invoice->expense ?? null;
                        $lease = $expense->lease ?? null;
                        $tenant = $lease->tenants ?? null;
                        $room = $lease->rooms ?? null;

                        $statusLabel = $invoice->status == 0 ? 'รอชำระ' : 'เกินกำหนด';
                        $statusClass = $invoice->status == 0 
                            ? 'bg-yellow-400/90 text-black' 
                            : 'bg-red-500/90 text-white';
                    @endphp
                    <tr class="border-t border-neutral-800 hover:bg-neutral-800/60">
                        <td class="px-4 py-3">
                            {{ $invoice->invoice_code }}
                        </td>
                        <td class="px-4 py-3">
                            {{ optional($lease)->tenant->name ?? $tenant->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $room->room_no ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ optional($invoice->due_date)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 text-right font-medium text-red-400">
                            {{ number_format($expense->total_amount ?? 0, 0) }} ฿
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('backend.invoices.show', $invoice->invoice_id) }}"
                               class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium
                                      bg-neutral-700 hover:bg-neutral-600 text-gray-100 border border-neutral-600">
                                ดูรายละเอียด
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-400">
                            🎉 ไม่มียอดค้างชำระ
                        </td>
                    </tr>
                @endforelse
            </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

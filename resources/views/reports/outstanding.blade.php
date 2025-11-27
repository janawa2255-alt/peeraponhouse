@extends('layouts.app')

@section('content')
<style>
    @media print {
        @page {
            size: A4;
            margin: 1cm;
        }
        nav, aside, .no-print, header, form {
            display: none !important;
        }
        body, .text-white, .text-gray-200, .text-gray-300, .text-gray-400 {
            color: black !important;
            background: white !important;
            font-family: 'Sarabun', sans-serif;
        }
        /* Fix sidebar margin issue */
        main, .sidebar-expanded-margin {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .bg-neutral-900, .bg-neutral-800, .bg-neutral-700 {
            background-color: white !important;
            border: 1px solid #ddd !important;
        }
        /* Adjust grid for print */
        .grid {
            display: flex !important;
            gap: 10px !important;
            margin-bottom: 20px !important;
        }
        .grid-cols-1, .md\:grid-cols-3 {
            flex-direction: row !important;
            width: 100% !important;
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
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ddd !important;
            color: black !important;
            padding: 8px !important;
        }
        /* Print Header */
        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .print-header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .print-header p {
            font-size: 12px;
            color: #666;
        }
    }
    .print-header {
        display: none;
    }
</style>

<div class="print-header">
    <h1>Peerpol House</h1>
    <p>รายงานยอดค้างชำระ / Outstanding Report</p>
</div>

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
        <div class="flex gap-2">
            <button onclick="window.print()" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-500 transition-colors shadow-lg shadow-blue-900/20">
                <i class="fas fa-print mr-2"></i> พิมพ์รายงาน
            </button>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="bg-neutral-900/80 border border-orange-500/20 rounded-xl p-4 no-print">
        <form method="GET" action="{{ route('backend.reports.outstanding') }}" class="flex flex-col md:flex-row gap-3">
            <div class="w-full md:w-1/3">
                <label class="block text-sm font-medium text-gray-300 mb-1">กรองตามห้อง</label>
                <select name="room_no" class="w-full px-3 py-2 rounded-lg bg-neutral-900 border border-gray-600 text-gray-100">
                    <option value="">ทุกห้อง</option>
                    @foreach($rooms ?? [] as $room)
                        <option value="{{ $room->room_no }}" {{ ($roomNo ?? '') == $room->room_no ? 'selected' : '' }}>ห้อง {{ $room->room_no }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 rounded-lg bg-orange-500 text-white hover:bg-orange-600">
                    <i class="fas fa-search mr-1"></i> ค้นหา
                </button>
            </div>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-3 md:grid-cols-3 gap-3 md:gap-4">
        <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-xl p-4 md:p-6 shadow-lg">
            <div class="flex flex-col items-center text-center">
                <i class="fas fa-exclamation-triangle text-red-100 text-2xl md:text-4xl mb-2"></i>
                <p class="text-red-100 text-xs md:text-sm">ยอดค้างชำระทั้งหมด</p>
                <h3 class="text-xl md:text-3xl font-bold text-white mt-1">{{ number_format($totalOutstanding, 0) }}</h3>
                <p class="text-red-100 text-xs mt-1">บาท</p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-600 to-yellow-700 rounded-xl p-4 md:p-6 shadow-lg">
            <div class="flex flex-col items-center text-center">
                <i class="fas fa-clock text-yellow-100 text-2xl md:text-4xl mb-2"></i>
                <p class="text-yellow-100 text-xs md:text-sm">รอชำระ</p>
                <h3 class="text-xl md:text-3xl font-bold text-white mt-1">{{ $countUnpaid }}</h3>
                <p class="text-yellow-100 text-xs mt-1">ใบแจ้งหนี้</p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-600 to-orange-700 rounded-xl p-4 md:p-6 shadow-lg">
            <div class="flex flex-col items-center text-center">
                <i class="fas fa-exclamation-circle text-orange-100 text-2xl md:text-4xl mb-2"></i>
                <p class="text-orange-100 text-xs md:text-sm">เกินกำหนด</p>
                <h3 class="text-xl md:text-3xl font-bold text-white mt-1">{{ $countOverdue }}</h3>
                <p class="text-orange-100 text-xs mt-1">ใบแจ้งหนี้</p>
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
                            {{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : '\u0e44\u0e21\u0e48\u0e21\u0e35\u0e01\u0e33\u0e2b\u0e19\u0e14' }}
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

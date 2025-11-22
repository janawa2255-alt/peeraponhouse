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
        .text-green-100, .text-blue-100, .text-orange-100 {
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
                รายงานรายได้
            </h1>
            <p class="text-sm text-gray-400">
                สรุปรายได้จากการชำระเงินของผู้เช่า
            </p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-500 transition-colors shadow-lg shadow-blue-900/20">
            <i class="fas fa-print mr-2"></i> พิมพ์รายงาน
        </button>
    </div>

    {{-- Filter Form --}}
    <div class="bg-neutral-900/80 border border-orange-500/20 rounded-xl p-4 no-print">
        <form method="GET" action="{{ route('backend.reports.income') }}" class="flex flex-col md:flex-row gap-3">
            <div class="w-full md:w-1/4">
                <label class="block text-sm font-medium text-gray-300 mb-1">ปี</label>
                <select name="year" class="w-full px-3 py-2 rounded-lg bg-neutral-900 border border-gray-600 text-gray-100">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y + 543 }}</option>
                    @endfor
                </select>
            </div>
            <div class="w-full md:w-1/4">
                <label class="block text-sm font-medium text-gray-300 mb-1">เดือน</label>
                <select name="month" class="w-full px-3 py-2 rounded-lg bg-neutral-900 border border-gray-600 text-gray-100">
                    <option value="">ทั้งหมด</option>
                    @php
                        $months = [
                            '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม',
                            '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน',
                            '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน',
                            '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม',
                        ];
                    @endphp
                    @foreach($months as $key => $label)
                        <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>{{ $label }}</option>
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">รายได้รวม</p>
                    <h3 class="text-3xl font-bold text-white mt-1">{{ number_format($totalIncome, 0) }}</h3>
                    <p class="text-green-100 text-xs mt-1">บาท</p>
                </div>
                <div class="text-green-100 text-4xl">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">จำนวนรายการ</p>
                    <h3 class="text-3xl font-bold text-white mt-1">{{ $payments->count() }}</h3>
                    <p class="text-blue-100 text-xs mt-1">รายการ</p>
                </div>
                <div class="text-blue-100 text-4xl">
                    <i class="fas fa-receipt"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-600 to-orange-700 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">เฉลี่ย/รายการ</p>
                    <h3 class="text-3xl font-bold text-white mt-1">
                        {{ $payments->count() > 0 ? number_format($totalIncome / $payments->count(), 0) : 0 }}
                    </h3>
                    <p class="text-orange-100 text-xs mt-1">บาท</p>
                </div>
                <div class="text-orange-100 text-4xl">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment List --}}
    <div class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-200">
            <thead class="bg-neutral-900/90 text-xs uppercase text-gray-400 border-b border-orange-500/30">
                <tr>
                    <th class="px-4 py-3">วันที่ชำระ</th>
                    <th class="px-4 py-3">เลขที่ใบแจ้งหนี้</th>
                    <th class="px-4 py-3">ผู้เช่า</th>
                    <th class="px-4 py-3">ห้อง</th>
                    <th class="px-4 py-3 text-right">ยอดเงิน</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr class="border-t border-neutral-800 hover:bg-neutral-800/60">
                        <td class="px-4 py-3">
                            {{ optional($payment->paid_date)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $payment->invoice->invoice_code ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ optional($payment->invoice->expense->lease)->tenant->name ?? optional($payment->invoice->expense->lease)->tenants->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $payment->invoice->expense->lease->rooms->room_no ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-right font-medium text-green-400">
                            {{ number_format($payment->total_amount, 0) }} ฿
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-400">
                            ไม่มีข้อมูลรายได้ในช่วงเวลาที่เลือก
                        </td>
                    </tr>
                @endforelse
            </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.tenant')

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
            display: block !important;
        }
        .grid-cols-1, .md\:grid-cols-3, .md\:grid-cols-2 {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: wrap !important;
            gap: 20px !important;
        }
        /* Specific adjustments for this page */
        .invoice-container {
            border: none !important;
            box-shadow: none !important;
        }
        .text-orange-400, .text-green-400, .text-yellow-600, .text-green-600, .text-red-600 {
            color: black !important;
        }
    }
</style>

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between no-print">
        <h1 class="text-2xl font-bold text-white mb-2">
            รายละเอียดใบแจ้งหนี้ประจำการชำระเงิน
        </h1>
    </div>

    {{-- Invoice Detail Card --}}
    <div class="bg-neutral-900/80 border border-neutral-700 rounded-xl overflow-hidden invoice-container">
        <div class="p-6 space-y-5">
            {{-- Header Info --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-400">เลขที่ใบแจ้งหนี้:</span>
                    <span class="text-white ml-2">{{ $invoice->invoice_code }}</span>
                </div>
                <div>
                    <span class="text-gray-400">ผู้เช่า:</span>
                    <span class="text-white ml-2">{{ $invoice->expense->lease->tenants->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">สถานะ:</span>
                    @php
                        $statusConfig = [
                            0 => ['label' => 'รอชำระเงิน', 'class' => 'bg-yellow-600'],
                            1 => ['label' => 'ชำระแล้ว', 'class' => 'bg-green-600'],
                            2 => ['label' => 'เกินกำหนด', 'class' => 'bg-red-600'],
                        ];
                        $config = $statusConfig[$invoice->status] ?? ['label' => '-', 'class' => 'bg-gray-600'];
                    @endphp
                    <span class="inline-block px-2 py-0.5 rounded text-xs text-white ml-2 {{ $config['class'] }}">
                        {{ $config['label'] }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400">ประเภทการชำระ:</span>
                    <span class="text-white ml-2">โอนผ่านบัญชี/สลิปเงิน</span>
                </div>
                <div>
                    <span class="text-gray-400">ห้องเช่า:</span>
                    <span class="text-white ml-2">{{ $invoice->expense->lease->rooms->room_no ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">วันที่ออกบิล:</span>
                    <span class="text-white ml-2">{{ \Carbon\Carbon::parse($invoice->invoice_data)->format('d/m/Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-400">วันครบกำหนด:</span>
                    <span class="text-white ml-2">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</span>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-neutral-700"></div>

            {{-- 2 Column Layout --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Left Column - ข้อมูลมิเตอร์น้ำ --}}
                <div>
                    <div class="bg-neutral-800 px-3 py-2 mb-3">
                        <h3 class="text-white font-medium text-sm">ข้อมูลมิเตอร์น้ำ</h3>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">เลขมิเตอร์เดือนที่แล้ว:</span>
                            <span class="text-white">{{ number_format($invoice->expense->prev_water ?? 0, 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">เลขมิเตอร์เดือนนี้:</span>
                            <span class="text-white">{{ number_format($invoice->expense->curr_water ?? 0, 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">ใช้ไป (หน่วย):</span>
                            <span class="text-white">{{ number_format($invoice->expense->water_units ?? 0, 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">อัตราค่าน้ำ (บาท/หน่วย):</span>
                            <span class="text-white">{{ number_format($invoice->expense->water_rate ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-neutral-600">
                            <span class="text-gray-400 font-medium">ค่าน้ำรวม:</span>
                            <span class="text-white font-medium">{{ number_format($invoice->expense->water_total ?? 0, 0) }} บาท</span>
                        </div>
                    </div>
                </div>

                {{-- Right Column - ข้อมูลการชำระเงิน --}}
                <div>
                    <div class="bg-neutral-800 px-3 py-2 mb-3">
                        <h3 class="text-white font-medium text-sm">ข้อมูลการชำระเงิน</h3>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">ค่าเช่า:</span>
                            <span class="text-white">{{ number_format($invoice->expense->room_rent ?? 0, 0) }} บาท</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">ค่าไฟ:</span>
                            <span class="text-white">{{ number_format($invoice->expense->elec_total ?? 0, 0) }} บาท</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">ค่าน้ำ:</span>
                            <span class="text-white">{{ number_format($invoice->expense->water_total ?? 0, 0) }} บาท</span>
                        </div>
                        @if($invoice->expense->discount > 0)
                        <div class="flex justify-between text-green-400">
                            <span>ส่วนลด:</span>
                            <span>-{{ number_format($invoice->expense->discount ?? 0, 0) }} บาท</span>
                        </div>
                        @endif
                        <div class="flex justify-between pt-2 border-t border-neutral-600">
                            <span class="text-white font-medium">ยอดรวม:</span>
                            <span class="text-orange-400 font-bold text-lg">{{ number_format($invoice->expense->total_amount ?? 0, 0) }} บาท</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ปุ่มดูรูปบิล --}}
            <div class="flex flex-wrap gap-3 no-print mt-4">
                @if($invoice->expense && $invoice->expense->pic_water)
                    <a href="{{ asset('storage/'.$invoice->expense->pic_water) }}" target="_blank"
                       class="px-4 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                        <i class="fas fa-image mr-2"></i> ดูภาพบิลค่าน้ำ
                    </a>
                @endif

                @if($invoice->expense && $invoice->expense->pic_elec)
                    <a href="{{ asset('storage/'.$invoice->expense->pic_elec) }}" target="_blank"
                       class="px-4 py-2 text-sm rounded-lg bg-amber-600 text-white hover:bg-amber-700 transition-colors">
                        <i class="fas fa-image mr-2"></i> ดูภาพบิลค่าไฟ
                    </a>
                @endif
            </div>

            {{-- Back Button --}}
            <div class="border-t border-neutral-700 pt-4 no-print">
                <a href="{{ route('invoices') }}" 
                   class="inline-block px-4 py-2 bg-neutral-700 hover:bg-neutral-600 text-white text-sm rounded transition-colors">
                    ย้อนกลับ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

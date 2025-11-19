@extends('layouts.tenant')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-white mb-2">
            รายะเอียดใบแจ้งหนี้ประจำการชำระเงิน
        </h1>
    </div>

    {{-- Invoice Detail Card --}}
    <div class="bg-neutral-900/80 border border-neutral-700 rounded-xl overflow-hidden">
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
                    <span class="text-gray-400">วันที่เริ่ม:</span>
                    <span class="text-white ml-2">{{ \Carbon\Carbon::parse($invoice->invoice_data)->format('d/m/Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-400">วันที่เริ่ม:</span>
                    <span class="text-white ml-2">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</span>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-neutral-700"></div>

            {{-- 2 Column Layout --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Left Column - ข้อมูลการค่าใช้จ่าย --}}
                <div>
                    <div class="bg-neutral-800 px-3 py-2 mb-3">
                        <h3 class="text-white font-medium text-sm">ข้อมูลการค่าใช้จ่าย</h3>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">ค่าเช่าเดือนนี้:</span>
                            <span class="text-white">{{ number_format($invoice->expense->rent_amount ?? 0, 0) }} บาท</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">ค่าไฟเดือนนี้เนือนที่ผ่านมา</span>
                            <span class="text-white">{{ $invoice->expense->curr_elec ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">เลขมิเตอร์เดือนนี้</span>
                            <span class="text-white">{{ $invoice->expense->curr_water ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">เลขมิเตอร์เดือนที่แล้ว</span>
                            <span class="text-white">{{ $invoice->expense->prev_water ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">ใช้ไป (หน่วย)</span>
                            <span class="text-white">{{ ($invoice->expense->curr_water ?? 0) - ($invoice->expense->prev_water ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">ออกเป็นเงิน (บาทต่อหน่วย)</span>
                            <span class="text-white">{{ $invoice->expense->water_rate ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">ค่าห้อง</span>
                            <span class="text-white">{{ number_format($invoice->expense->rent_amount ?? 0, 0) }} บาท</span>
                        </div>
                    </div>
                </div>

                {{-- Right Column - ข้อมูลการค่าใช้จ่าย --}}
                <div>
                    <div class="bg-neutral-800 px-3 py-2 mb-3">
                        <h3 class="text-white font-medium text-sm">ข้อมูลการค่าใช้จ่าย</h3>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">ค่าเช่า:</span>
                            <span class="text-white">{{ number_format($invoice->expense->rent_amount ?? 0, 0) }} บาท</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">ค่าไฟ:</span>
                            <span class="text-white">{{ number_format($invoice->expense->elec_total ?? 0, 0) }} บาท</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">ค่าน้ำ:</span>
                            <span class="text-white">{{ number_format($invoice->expense->water_total ?? 0, 0) }} บาท</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-neutral-600">
                            <span class="text-white font-medium">ยอดรวม:</span>
                            <span class="text-white font-bold">{{ number_format($invoice->expense->total_amount ?? 0, 0) }} บาท</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Back Button --}}
            <div class="border-t border-neutral-700 pt-4">
                <a href="{{ route('invoices') }}" 
                   class="inline-block px-4 py-2 bg-neutral-700 hover:bg-neutral-600 text-white text-sm rounded transition-colors">
                    ย้อนคลับ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

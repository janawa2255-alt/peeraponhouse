@extends('layouts.app')

@section('content')
<div class="space-y-4">
</div>
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-white">
            รายละเอียดใบแจ้งหนี้
        </h1>
        <a href="{{ route('backend.invoices.index') }}"
           class="px-4 py-2 text-sm font-medium rounded-lg border border-neutral-600 text-gray-200 hover:bg-neutral-800">
            ย้อนกลับ
        </a>
    </div>

    <div class="bg-neutral-900 border border-neutral-700 rounded-xl overflow-hidden">
        {{-- header เทา --}}
        <div class="bg-neutral-700 px-6 py-3 flex items-center justify-between">
            <h2 class="text-white font-semibold">
                เลขที่ใบแจ้งหนี้: {{ $invoice->invoice_code }}
            </h2>
            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                {{ $statusLabel }}
            </span>
        </div>

        <div class="px-8 py-6 space-y-8">
            {{-- แถวข้อมูลหลัก --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm text-gray-200">

                <div class="space-y-2">
                    <p>
                        <span class="font-semibold">เลขที่ใบแจ้งหนี้:</span>
                        <span class="ml-2">{{ $invoice->invoice_code }}</span>
                    </p>
                    <p>
                        <span class="font-semibold">วันที่ออกใบแจ้งหนี้:</span>
                        <span class="ml-2">
                            {{ optional($invoice->invoice_data)->format('d/m/Y') }}
                        </span>
                    </p>
                    <p>
                        <span class="font-semibold">ครบกำหนดชำระ:</span>
                        <span class="ml-2">
                            {{ optional($invoice->due_date)->format('d/m/Y') }}
                        </span>
                    </p>
                    @if($expense)
                        <p>
                            <span class="font-semibold">รอบบิล:</span>
                            <span class="ml-2">
                                เดือน {{ $expense->month ?? '-' }} / ปี {{ $expense->year ?? '-' }}
                            </span>
                        </p>
                    @endif
                </div>

                <div class="space-y-2">
                    <p>
                        <span class="font-semibold">ผู้เช่า:</span>
                        <span class="ml-2">{{ $tenant->name ?? '-' }}</span>
                    </p>
                    <p>
                        <span class="font-semibold">ห้องเช่า:</span>
                        <span class="ml-2">{{ $room->room_no ?? '-' }}</span>
                    </p>
                    @if(!empty($tenant?->phone))
                        <p>
                            <span class="font-semibold">เบอร์โทรผู้เช่า:</span>
                            <span class="ml-2">{{ $tenant->phone }}</span>
                        </p>
                    @endif
                </div>

            </div>

            {{-- ปุ่มดูรูปบิล --}}
            <div class="flex flex-wrap gap-3">
                @if($expense && $expense->pic_water)
                    <a href="{{ asset('storage/'.$expense->pic_water) }}" target="_blank"
                       class="px-4 py-2 text-sm rounded-lg bg-neutral-700 text-white hover:bg-neutral-600">
                        ดูภาพบิลค่าน้ำ
                    </a>
                @endif

                @if($expense && $expense->pic_elec)
                    <a href="{{ asset('storage/'.$expense->pic_elec) }}" target="_blank"
                       class="px-4 py-2 text-sm rounded-lg bg-neutral-700 text-white hover:bg-neutral-600">
                        ดูภาพบิลค่าไฟ
                    </a>
                @endif
            </div>

            {{-- ตาราง 2 ฝั่ง --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm text-gray-200">

                {{-- ฝั่งข้อมูลค่าน้ำ/ค่าไฟละเอียด --}}
                <div class="border border-neutral-700 rounded-lg overflow-hidden">
                    <div class="bg-neutral-800 px-4 py-2 text-center font-semibold">
                        ข้อมูลค่าใช้จ่ายตามมิเตอร์
                    </div>
                    <div class="px-4 py-3 space-y-1">
                        <p>ค่าน้ำหน่วยละ: {{ number_format($expense->water_rate ?? 0) }} ฿</p>
                        <p>เลขมิเตอร์เดือนก่อน: {{ $expense->prev_water ?? '-' }}</p>
                        <p>เลขมิเตอร์เดือนนี้: {{ $expense->curr_water ?? '-' }}</p>
                        <p>ใช้ไปแล้ว (หน่วย): {{ $expense->water_units ?? '-' }}</p>
                        <p>ยอดค่าน้ำรวม: {{ number_format($expense->water_total ?? 0) }} ฿</p>
                        <p>ยอดค่าไฟตามบิล: {{ number_format($expense->elec_total ?? 0) }} ฿</p>
                    </div>
                </div>

                {{-- ฝั่งสรุปค่าเช่าห้อง + ยอดรวม + ส่วนลด --}}
                <div class="border border-neutral-700 rounded-lg overflow-hidden">
                    <div class="bg-neutral-800 px-4 py-2 text-center font-semibold">
                        สรุปค่าใช้จ่ายใบแจ้งหนี้นี้
                    </div>
                    <div class="px-4 py-3 space-y-1">
                        <p>ค่าเช่าห้อง: {{ number_format($expense->room_rent ?? 0) }} ฿</p>
                        <p>ค่าน้ำ: {{ number_format($expense->water_total ?? 0) }} ฿</p>
                        <p>ค่าไฟ: {{ number_format($expense->elec_total ?? 0) }} ฿</p>

                        <hr class="my-2 border-neutral-700">

                        <p>ยอดรวมก่อนส่วนลด: {{ number_format($subtotal) }} ฿</p>
                        <p>ส่วนลด (ถ้ามี): {{ number_format($discount) }} ฿</p>

                        <p class="mt-3 text-lg font-semibold text-green-400">
                            ยอดสุทธิที่ต้องชำระ: {{ number_format($grandTotal) }} ฿
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
@php
    $invoice = $payment->invoice;
    $expense = $invoice->expense ?? null;
    $lease   = $expense->lease ?? null;
    $tenant  = $lease->tenants ?? null;
@endphp

<div class="space-y-6">

    <a href="{{ route('backend.payments.index') }}"
       class="inline-flex items-center text-sm text-gray-400 hover:text-gray-200">
        ← กลับไปหน้าการแจ้งชำระเงิน
    </a>

    {{-- ข้อมูลแถวบน --}}
    <div class="bg-neutral-900 border border-neutral-700 rounded-xl overflow-hidden">
        <div class="bg-neutral-800 px-6 py-3">
            <h1 class="text-white text-lg font-semibold">
                รายละเอียดการแจ้งชำระเงิน
            </h1>
        </div>

        <div class="px-6 py-4 space-y-2 text-sm text-gray-200">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <div class="text-gray-400 text-xs">เลขที่ใบแจ้งหนี้</div>
                    <div>{{ $invoice->invoice_code ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-gray-400 text-xs">ผู้เช่า</div>
                    <div>{{ $tenant->full_name ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-gray-400 text-xs">วิธีชำระ</div>
                    <div>{{ $payment->method_label }}</div>
                </div>
                <div>
                    <div class="text-gray-400 text-xs">วันที่โอน</div>
                    <div>{{ optional($payment->paid_date)->format('d/m/Y') }}</div>
                </div>
                <div>
                    <div class="text-gray-400 text-xs">ยอดชำระ</div>
                    <div>{{ number_format($payment->total_amount, 0) }} ฿</div>
                </div>
                <div>
                    <div class="text-gray-400 text-xs">สถานะ</div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $payment->status_badge_class }}">
                        {{ $payment->status_label }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- กล่องเทากลางแบบ mockup --}}
    <div class="flex justify-center">
        <div class="bg-gray-100 text-gray-800 rounded-lg shadow-md px-10 py-8 w-full md:w-3/4">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div>
                    <h2 class="text-center font-semibold mb-3">ข้อมูลการใช้น้ำ</h2>

                    <div class="flex justify-between mb-1">
                        <span>ค่าน้ำหน่วยละ</span>
                        <span>{{ number_format($expense->water_rate ?? 0) }} ฿</span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span>เลขมิเตอร์เดิม</span>
                        <span>{{ $expense->prev_water ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span>เลขมิเตอร์ใหม่</span>
                        <span>{{ $expense->curr_water ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span>ใช้ไป (หน่วย)</span>
                        <span>{{ $expense->water_units ?? '-' }}</span>
                    </div>
                </div>

                <div>
                    <h2 class="text-center font-semibold mb-3">ข้อมูลการค่าใช้จ่าย</h2>

                    <div class="flex justify-between mb-1">
                        <span>ค่าห้อง</span>
                        <span>{{ number_format($expense->room_rent ?? 0) }} ฿</span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span>ค่าน้ำ</span>
                        <span>{{ number_format($expense->water_total ?? 0) }} ฿</span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span>ค่าไฟ</span>
                        <span>{{ number_format($expense->elec_total ?? 0) }} ฿</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between font-semibold">
                        <span>ยอดรวม</span>
                        <span>{{ number_format($expense->total_amount ?? $payment->total_amount, 0) }} ฿</span>
                    </div>
                </div>
            </div>

            {{-- ปุ่มดูสลิป --}}
            <div class="mt-6 flex flex-col items-center gap-4">
                @if ($payment->pic_slip)
                    <div class="text-center">
                        <button onclick="document.getElementById('slip-img').classList.toggle('hidden')"
                                class="px-4 py-1 rounded-md bg-gray-300 text-gray-800 text-xs hover:bg-gray-400">
                            ดูสลิป
                        </button>
                    </div>

                    <div id="slip-img" class="hidden mt-3">
                        <img src="{{ asset('storage/'.$payment->pic_slip) }}"
                             alt="สลิปการโอน"
                             class="max-h-80 rounded-md shadow">
                    </div>
                @else
                    <span class="text-xs text-gray-500">ไม่มีสลิปแนบมา</span>
                @endif
            </div>

            {{-- ปุ่มอนุมัติ / ปฏิเสธ --}}
            <div class="mt-6 flex justify-center gap-4">
                <form action="{{ route('backend.payments.updateStatus', $payment) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="1">
                    <button type="submit"
                        class="px-6 py-2 rounded-md bg-green-500 text-white text-sm hover:bg-green-400">
                        อนุมัติ
                    </button>
                </form>

                <form action="{{ route('backend.payments.updateStatus', $payment) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="2">
                    <button type="submit"
                        class="px-6 py-2 rounded-md bg-red-500 text-white text-sm hover:bg-red-400">
                        ปฏิเสธ
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection

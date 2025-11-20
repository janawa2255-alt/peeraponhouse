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

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="bg-green-600/20 border border-green-600/40 text-green-200 px-4 py-3 rounded-lg flex items-center gap-3">
        <i class="fas fa-check-circle text-xl"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-600/20 border border-red-600/40 text-red-200 px-4 py-3 rounded-lg flex items-center gap-3">
        <i class="fas fa-exclamation-circle text-xl"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

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

    {{-- รายละเอียดค่าใช้จ่าย --}}
    <div class="bg-neutral-900 border border-neutral-700 rounded-xl overflow-hidden">
        <div class="bg-neutral-800 px-6 py-3 border-b border-neutral-700">
            <h2 class="text-white text-lg font-semibold">รายละเอียดค่าใช้จ่าย</h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- ข้อมูลการใช้น้ำ --}}
                <div class="bg-neutral-800/50 rounded-lg p-5 border border-neutral-700">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center">
                            <i class="fas fa-tint text-blue-400 text-lg"></i>
                        </div>
                        <h3 class="text-white font-semibold">ข้อมูลการใช้น้ำ</h3>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">ค่าน้ำหน่วยละ</span>
                            <span class="text-white font-medium">{{ number_format($expense->water_rate ?? 0) }} ฿</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">เลขมิเตอร์เดิม</span>
                            <span class="text-white font-medium">{{ $expense->prev_water ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">เลขมิเตอร์ใหม่</span>
                            <span class="text-white font-medium">{{ $expense->curr_water ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-neutral-600">
                            <span class="text-gray-400">ใช้ไป (หน่วย)</span>
                            <span class="text-blue-400 font-semibold">{{ $expense->water_units ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- ข้อมูลค่าใช้จ่าย --}}
                <div class="bg-neutral-800/50 rounded-lg p-5 border border-neutral-700">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-orange-600/20 flex items-center justify-center">
                            <i class="fas fa-receipt text-orange-400 text-lg"></i>
                        </div>
                        <h3 class="text-white font-semibold">สรุปค่าใช้จ่าย</h3>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">ค่าห้อง</span>
                            <span class="text-white font-medium">{{ number_format($expense->room_rent ?? 0) }} ฿</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">ค่าน้ำ</span>
                            <span class="text-white font-medium">{{ number_format($expense->water_total ?? 0) }} ฿</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">ค่าไฟ</span>
                            <span class="text-white font-medium">{{ number_format($expense->elec_total ?? 0) }} ฿</span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-neutral-600">
                            <span class="text-white font-semibold">ยอดรวมทั้งหมด</span>
                            <span class="text-orange-400 font-bold text-lg">{{ number_format($expense->total_amount ?? $payment->total_amount, 0) }} ฿</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- สลิปการโอน --}}
            <div class="mt-6">
                <div class="bg-neutral-800/50 rounded-lg p-5 border border-neutral-700">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-purple-600/20 flex items-center justify-center">
                            <i class="fas fa-file-image text-purple-400 text-lg"></i>
                        </div>
                        <h3 class="text-white font-semibold">สลิปการโอนเงิน</h3>
                    </div>

                    @if ($payment->pic_slip)
                        <div class="text-center">
                            <button onclick="document.getElementById('slip-img').classList.toggle('hidden')"
                                    class="px-5 py-2.5 rounded-lg bg-neutral-700 hover:bg-neutral-600 text-white text-sm font-medium transition-colors border border-neutral-600">
                                <i class="fas fa-eye mr-2"></i>ดูสลิปการโอน
                            </button>
                        </div>

                        <div id="slip-img" class="hidden mt-4">
                            <div class="flex justify-center">
                                <img src="{{ asset('storage/'.$payment->pic_slip) }}"
                                     alt="สลิปการโอน"
                                     class="max-h-96 rounded-lg shadow-2xl border-2 border-neutral-600 cursor-pointer hover:scale-105 transition-transform"
                                     onclick="window.open(this.src, '_blank')">
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-image text-gray-600 text-3xl mb-2"></i>
                            <p class="text-gray-500 text-sm">ไม่มีสลิปแนบมา</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ปุ่มอนุมัติ / ปฏิเสธ --}}
            @if($payment->status == 0)
                {{-- แสดงปุ่มเฉพาะเมื่อสถานะเป็น 0 (รอตรวจสอบ) --}}
                <div class="mt-6 flex justify-center gap-4">
                    <form action="{{ route('backend.payments.updateStatus', $payment) }}" method="POST" 
                          onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะอนุมัติการชำระเงินนี้?')">
                        @csrf
                        <input type="hidden" name="status" value="1">
                        <button type="submit"
                            class="px-6 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition-colors shadow-lg shadow-green-600/20">
                            <i class="fas fa-check mr-2"></i>อนุมัติ
                        </button>
                    </form>

                    <form action="{{ route('backend.payments.updateStatus', $payment) }}" method="POST"
                          onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะปฏิเสธการชำระเงินนี้?')">
                        @csrf
                        <input type="hidden" name="status" value="2">
                        <button type="submit"
                            class="px-6 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-colors shadow-lg shadow-red-600/20">
                            <i class="fas fa-times mr-2"></i>ปฏิเสธ
                        </button>
                    </form>
                </div>
            @else
                {{-- แสดงข้อความเมื่อดำเนินการแล้ว --}}
                <div class="mt-6">
                    <div class="bg-neutral-800/50 rounded-lg p-5 border border-neutral-700">
                        <div class="flex items-center justify-center gap-3">
                            <div class="w-12 h-12 rounded-full {{ $payment->status == 1 ? 'bg-green-600/20' : 'bg-red-600/20' }} flex items-center justify-center">
                                <i class="fas {{ $payment->status == 1 ? 'fa-check-circle text-green-400' : 'fa-times-circle text-red-400' }} text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-white font-semibold text-lg">
                                    {{ $payment->status == 1 ? 'อนุมัติแล้ว' : 'ปฏิเสธแล้ว' }}
                                </p>
                                @if($payment->note)
                                    <p class="text-gray-400 text-sm mt-1">หมายเหตุ: {{ $payment->note }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection

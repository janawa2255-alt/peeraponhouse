@extends('layouts.app')

@section('content')
<style>
    @media print {
        @page {
            size: A4;
            margin: 1cm;
        }
        /* Hide sidebar, header, and other non-essential elements */
        nav, aside, .no-print, header, footer, form {
            display: none !important;
        }
        /* Reset text colors for printing */
        body, .text-white, .text-gray-200, .text-gray-300, .text-gray-400, .text-green-200, .text-red-200, .text-blue-400, .text-orange-400, .text-purple-400 {
            color: black !important;
            background: white !important;
            font-family: 'Sarabun', sans-serif;
        }
        /* Ensure the main content takes full width */
        main, .sidebar-expanded-margin {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        /* Container adjustments */
        .bg-neutral-900, .bg-neutral-800, .bg-neutral-700, .bg-neutral-800\/50 {
            background-color: white !important;
            border: 1px solid #ccc !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        .border-neutral-700, .border-neutral-600 {
            border-color: #ccc !important;
        }
        
        /* Grid adjustments */
        .grid {
            display: block !important;
        }
        .md\:grid-cols-4 {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
            gap: 10px !important;
        }
        .md\:grid-cols-2 {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            gap: 20px !important;
        }
        
        /* Typography */
        h1, h2, h3 {
            color: black !important;
            font-weight: bold !important;
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
            font-size: 24px;
            font-weight: bold;
        }
        .print-header p {
            font-size: 14px;
        }
        
        /* Specific tweaks */
        .w-10, .h-10 {
            display: none !important; /* Hide icons in circles */
        }
        .flex.items-center.gap-2.mb-4 {
            margin-bottom: 0.5rem !important;
        }
    }
    .print-header {
        display: none;
    }
</style>

@php
    $invoice = $payment->invoice;
    $expense = $invoice->expense ?? null;
    $lease   = $expense->lease ?? null;
    $tenant  = $lease->tenants ?? null;
@endphp

<div class="space-y-6">
    {{-- Print Header (Visible only in print) --}}
    <div class="print-header">
        <h1>พีรพล เฮ้าส์ (Peerapon House)</h1>
        <p>รายละเอียดการชำระเงิน / Payment Details</p>
    </div>

    <div class="flex items-center justify-between no-print">
        <a href="{{ route('backend.payments.index') }}"
           class="inline-flex items-center text-sm text-gray-400 hover:text-gray-200">
            ← กลับไปหน้าการแจ้งชำระเงิน
        </a>
        
        <button onclick="window.print()" 
                class="px-4 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-500 transition-colors shadow-lg shadow-blue-900/20">
            <i class="fas fa-print mr-2"></i> พิมพ์ / บันทึกเป็น PDF
        </button>
    </div>

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
    <div id="payment-details" class="bg-neutral-900 border border-neutral-700 rounded-xl overflow-hidden">
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
                    <div>{{ $tenant->name ?? '-' }}</div>
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
                        <h3 class="text-white font-semibold">{{ $payment->method == 2 ? 'รายละเอียดการชำระเงินสด' : 'สลิปการโอนเงิน' }}</h3>
                    </div>

                    <div class="text-center">
                        <button onclick="openSlipModal()"
                                class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium transition-colors shadow-lg shadow-blue-900/20">
                            <i class="fas fa-eye mr-2"></i>{{ $payment->method == 2 ? 'ดูรายละเอียด' : 'ดูสลิปการโอน' }}
                        </button>
                    </div>
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

{{-- Slip Modal --}}
@if($payment->pic_slip)
<div id="slipModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    {{-- Backdrop --}}
    <div id="slipBackdrop" class="fixed inset-0 bg-black transition-opacity duration-300 ease-out opacity-0"></div>
    
    {{-- Modal Container --}}
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        {{-- Modal Content --}}
        <div id="slipContent" 
             class="relative inline-block align-bottom bg-neutral-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all duration-300 ease-out sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full opacity-0 scale-95 translate-y-4">
            
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-white/20">
                            <i class="fas fa-receipt text-white text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">{{ $payment->method == 2 ? 'รายละเอียดการชำระเงินสด' : 'สลิปการโอนเงิน' }}</h3>
                    </div>
                    <button onclick="closeSlipModal()" 
                            class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-5 bg-neutral-800">
                @if($payment->pic_slip)
                    <p class="text-gray-400 text-sm mb-3 text-center">
                        <i class="fas fa-info-circle mr-1"></i>คลิกที่รูปเพื่อดูแบบชัดเจน
                    </p>
                    <div class="flex justify-center">
                        <img id="slip-image"
                             src="{{ asset($payment->pic_slip) }}" 
                             alt="{{ $payment->method == 2 ? 'รายละเอียดการชำระเงินสด' : 'สลิปการโอนเงิน' }}" 
                             class="max-w-full h-auto rounded-lg shadow-lg cursor-pointer transition-all duration-300 blur-md hover:blur-sm"
                             onclick="toggleSlipBlur(this)"
                             title="คลิกเพื่อดูแบบชัดเจน">
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="w-24 h-24 rounded-full bg-neutral-700/50 flex items-center justify-center mb-4">
                            <i class="fas fa-image text-gray-500 text-4xl"></i>
                        </div>
                        <p class="text-gray-400 text-lg font-medium mb-2">ไม่มีรูปภาพ</p>
                        <p class="text-gray-500 text-sm">{{ $payment->method == 2 ? 'การชำระเงินสดไม่มีสลิปแนบมา' : 'ไม่มีสลิปการโอนแนบมา' }}</p>
                    </div>
                @endif
            </div>

            {{-- Modal Footer --}}
            <div class="bg-neutral-900 px-6 py-4 flex {{ $payment->pic_slip ? 'justify-between' : 'justify-end' }} items-center">
                @if($payment->pic_slip)
                    <a href="{{ asset($payment->pic_slip) }}" 
                       download
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-download mr-2"></i>ดาวน์โหลด
                    </a>
                @endif
                <button type="button" 
                        onclick="closeSlipModal()"
                        class="px-5 py-2.5 bg-neutral-700 hover:bg-neutral-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>ปิด
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Open Slip modal with fade animation
function openSlipModal() {
    const modal = document.getElementById('slipModal');
    const backdrop = document.getElementById('slipBackdrop');
    const content = document.getElementById('slipContent');
    
    // Show modal
    modal.classList.remove('hidden');
    
    // Trigger animation
    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-75');
        
        content.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
        content.classList.add('opacity-100', 'scale-100', 'translate-y-0');
    }, 10);
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

// Close Slip modal with fade animation
function closeSlipModal() {
    const modal = document.getElementById('slipModal');
    const backdrop = document.getElementById('slipBackdrop');
    const content = document.getElementById('slipContent');
    
    // Trigger close animation
    backdrop.classList.remove('opacity-75');
    backdrop.classList.add('opacity-0');
    
    content.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
    content.classList.add('opacity-0', 'scale-95', 'translate-y-4');
    
    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
    
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

// Close modal when clicking backdrop
document.getElementById('slipModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeSlipModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('slipModal').classList.contains('hidden')) {
        closeSlipModal();
    }
});

// Toggle blur effect on slip
function toggleSlipBlur(img) {
    if (img.classList.contains('blur-md')) {
        img.classList.remove('blur-md', 'hover:blur-sm');
        img.classList.add('blur-none');
        img.title = 'คลิกเพื่อเบลออีกครั้ง';
    } else {
        img.classList.remove('blur-none');
        img.classList.add('blur-md', 'hover:blur-sm');
        img.title = 'คลิกเพื่อดูแบบชัดเจน';
    }
}
</script>
@endif
@endsection
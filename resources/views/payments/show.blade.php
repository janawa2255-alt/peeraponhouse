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
        body, .text-white, .text-gray-200, .text-gray-300, .text-gray-400, .text-green-200, .text-red-200, .text-blue-400, .text-orange-400, .text-purple-400, .text-yellow-200 {
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
        .bg-neutral-900, .bg-neutral-800, .bg-neutral-700, .bg-neutral-800\/60, .bg-neutral-900\/80, .bg-gradient-to-br {
            background: white !important;
            background-color: white !important;
            border: 1px solid #ccc !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        .border-neutral-700, .border-neutral-600, .border-orange-500\/20 {
            border-color: #ccc !important;
        }
        
        /* Grid adjustments */
        .grid {
            display: block !important;
        }
        .md\:grid-cols-2 {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            gap: 20px !important;
            flex-wrap: wrap !important;
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
        .text-4xl {
            font-size: 24px !important;
        }
    }
    .print-header {
        display: none;
    }
</style>

<div class="space-y-6">
    {{-- Print Header (Visible only in print) --}}
    <div class="print-header">
        <h1>พีรพล เฮ้าส์ (Peerapon House)</h1>
        <p>รายละเอียดการชำระเงิน / Payment Details</p>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between no-print">
        <div>
            <h1 class="text-2xl font-bold text-white mb-1">
                รายละเอียดการชำระเงิน
            </h1>
            <p class="text-sm text-gray-400">
                ข้อมูลการชำระเงินจากผู้เช่า
            </p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" 
                    class="px-4 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-500 transition-colors shadow-lg shadow-blue-900/20">
                <i class="fas fa-print mr-2"></i> พิมพ์ / บันทึกเป็น PDF
            </button>
            <a href="{{ route('backend.payments.index') }}" 
               class="px-4 py-2 text-sm rounded-lg border border-neutral-600 text-gray-200 hover:bg-neutral-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                ย้อนกลับ
            </a>
        </div>
    </div>

    {{-- Payment Info Card --}}
    <div id="payment-receipt" class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl overflow-hidden shadow-lg p-1">
        <div class="bg-neutral-800/60 px-6 py-4 border-b border-neutral-700">
            <h2 class="text-white font-semibold">ข้อมูลการชำระเงิน</h2>
        </div>

        <div class="p-6 space-y-6 bg-neutral-900">
            {{-- Status Badge --}}
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-400">สถานะ</span>
                @php
                    $statusClasses = [
                        0 => 'bg-yellow-500/20 text-yellow-200 border-yellow-500/40',
                        1 => 'bg-green-500/20 text-green-200 border-green-500/40',
                        2 => 'bg-red-500/20 text-red-200 border-red-500/40',
                    ];
                    $statusLabels = [
                        0 => 'รอตรวจสอบ',
                        1 => 'อนุมัติแล้ว',
                        2 => 'ปฏิเสธ',
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $statusClasses[$payment->status] ?? '' }}">
                    {{ $statusLabels[$payment->status] ?? 'ไม่ทราบ' }}
                </span>
            </div>

            {{-- Amount --}}
            <div class="bg-gradient-to-br from-orange-500/10 to-orange-600/5 rounded-xl p-6 border border-orange-500/20 text-center">
                <p class="text-gray-400 text-sm mb-2">ยอดที่ชำระ</p>
                <p class="text-4xl font-bold text-orange-400">
                    {{ number_format($payment->total_amount, 2) }} ฿
                </p>
            </div>

            {{-- Payment Details --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="bg-neutral-800/40 rounded-lg p-4 border border-neutral-700">
                    <p class="text-gray-400 mb-1">วันที่ชำระ</p>
                    <p class="text-white font-semibold">
                        {{ \Carbon\Carbon::parse($payment->paid_date)->format('d/m/Y') }}
                    </p>
                </div>
                <div class="bg-neutral-800/40 rounded-lg p-4 border border-neutral-700">
                    <p class="text-gray-400 mb-1">ใบแจ้งหนี้</p>
                    <p class="text-white font-semibold">
                        {{ $payment->invoice->invoice_code ?? '-' }}
                    </p>
                </div>
                <div class="bg-neutral-800/40 rounded-lg p-4 border border-neutral-700">
                    <p class="text-gray-400 mb-1">ห้อง</p>
                    <p class="text-white font-semibold">
                        {{ $payment->invoice->expense->lease->rooms->room_no ?? '-' }}
                    </p>
                </div>
                <div class="bg-neutral-800/40 rounded-lg p-4 border border-neutral-700">
                    <p class="text-gray-400 mb-1">ผู้เช่า</p>
                    <p class="text-white font-semibold">
                        {{ $payment->invoice->expense->lease->tenants->name ?? '-' }}
                    </p>
                </div>
            </div>

            {{-- Bank Info --}}
            @if($payment->bank)
            <div class="border-t border-neutral-700 pt-4">
                <h3 class="text-white font-semibold mb-3">ข้อมูลการโอน</h3>
                <div class="bg-neutral-800/40 rounded-lg p-4 border border-neutral-700 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">ธนาคาร</span>
                        <span class="text-white font-medium">{{ $payment->bank->bank_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">เลขที่บัญชี</span>
                        <span class="text-white font-medium">{{ $payment->bank->number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">ชื่อบัญชี</span>
                        <span class="text-white font-medium">{{ $payment->bank->account_name }}</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- Slip Image --}}
            @if($payment->pic_slip)
            <div class="border-t border-neutral-700 pt-4">
                <h3 class="text-white font-semibold mb-3">สลิปการชำระเงิน</h3>
                <div class="flex justify-start">
                    <button onclick="openSlipModal()"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg
                              bg-blue-600 text-white hover:bg-blue-500 transition-colors shadow-lg shadow-blue-900/20">
                        <i class="fas fa-receipt mr-2"></i>ดูสลิปการชำระเงิน
                    </button>
                </div>
            </div>
            @endif

            {{-- Note --}}
            @if($payment->note)
            <div class="border-t border-neutral-700 pt-4">
                <h3 class="text-white font-semibold mb-2">หมายเหตุ</h3>
                <div class="bg-neutral-800/40 rounded-lg p-4 border border-neutral-700">
                    <p class="text-gray-300 text-sm">{{ $payment->note }}</p>
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
                        <h3 class="text-xl font-bold text-white">สลิปการชำระเงิน</h3>
                    </div>
                    <button onclick="closeSlipModal()" 
                            class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-5 bg-neutral-800">
                <p class="text-gray-400 text-sm mb-3 text-center">
                    <i class="fas fa-info-circle mr-1"></i>คลิกที่รูปเพื่อดูแบบชัดเจน
                </p>
                <div class="flex justify-center">
                    <img id="slip-image"
                         src="{{ asset($payment->pic_slip) }}" 
                         alt="สลิปการชำระเงิน" 
                         class="max-w-full h-auto rounded-lg shadow-lg cursor-pointer transition-all duration-300 blur-md hover:blur-sm"
                         onclick="toggleSlipBlur(this)"
                         title="คลิกเพื่อดูแบบชัดเจน">
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="bg-neutral-900 px-6 py-4 flex justify-between items-center">
                <a href="{{ asset($payment->pic_slip) }}" 
                   download
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i>ดาวน์โหลด
                </a>
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

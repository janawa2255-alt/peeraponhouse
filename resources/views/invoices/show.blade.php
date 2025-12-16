@extends('layouts.app')

@section('content')
<style>
    @media print {
        @page {
            size: A4;
            margin: 1cm;
        }
        /* Hide sidebar, header, and other non-essential elements */
        nav, aside, .no-print, header, footer {
            display: none !important;
        }
        /* Reset text colors for printing */
        body, .text-white, .text-gray-200, .text-gray-300, .text-gray-400, .text-green-400 {
            color: black !important;
            background: white !important;
            font-family: 'Sarabun', sans-serif; /* Use a standard font if available, or fallback */
        }
        /* Ensure the main content takes full width and remove sidebar margins */
        main, .sidebar-expanded-margin {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        /* Container adjustments */
        .invoice-container {
            border: 1px solid #000 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            width: 100% !important;
            max-width: 800px !important; /* Limit width for better readability on A4 */
            margin: 0 auto !important; /* Center the container */
        }
        .bg-neutral-900, .bg-neutral-800, .bg-neutral-700 {
            background-color: white !important;
            border-bottom: 1px solid #ccc !important;
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
        }
        /* Typography */
        h1, h2, h3 {
            color: black !important;
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
    }
    .print-header {
        display: none;
    }
</style>

<div class="space-y-4">
    {{-- Print Header (Visible only in print) --}}
    <div class="print-header">
        <h1>พีรพล เฮ้าส์ (Peerapon House)</h1>
        <p>ใบแจ้งหนี้ / Invoice</p>
    </div>

    <div class="flex items-center justify-between no-print">
        <h1 class="text-2xl font-semibold text-white">
            รายละเอียดใบแจ้งหนี้
        </h1>
        <div class="flex gap-2">
            <button onclick="window.print()" 
                    class="px-4 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-500 transition-colors shadow-lg shadow-blue-900/20">
                <i class="fas fa-print mr-2"></i> พิมพ์ / บันทึกเป็น PDF
            </button>
            <a href="{{ route('backend.invoices.index') }}"
               class="px-4 py-2 text-sm font-medium rounded-lg border border-neutral-600 text-gray-200 hover:bg-neutral-800 transition-colors">
                ย้อนกลับ
            </a>
        </div>
    </div>

    <div class="bg-neutral-900 border border-neutral-700 rounded-xl overflow-hidden invoice-container">
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
                            {{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : 'ไม่มีกำหนด' }}
                        </span>
                    </p>
                    @if($expense)
                        <p>
                            <span class="font-semibold">รอบบิล:</span>
                            <span class="ml-2">
                                @php
                                    $months = [
                                        '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม',
                                        '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน',
                                        '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน',
                                        '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม',
                                    ];
                                    $monthName = $months[str_pad($expense->month, 2, '0', STR_PAD_LEFT)] ?? '';
                                    $buddhistYear = $expense->year + 543;
                                @endphp
                                {{ $monthName }} {{ $buddhistYear }}
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
            <div class="flex flex-wrap gap-3 no-print">
                @if($expense && $expense->pic_water)
                    <button onclick="openBillModal('water')"
                       class="px-4 py-2 text-sm rounded-lg bg-neutral-700 text-white hover:bg-neutral-600 transition-colors">
                        <i class="fas fa-image mr-2"></i>ดูภาพบิลค่าน้ำ
                    </button>
                @endif

                @if($expense && $expense->pic_elec)
                    <button onclick="openBillModal('elec')"
                       class="px-4 py-2 text-sm rounded-lg bg-neutral-700 text-white hover:bg-neutral-600 transition-colors">
                        <i class="fas fa-image mr-2"></i>ดูภาพบิลค่าไฟ
                    </button>
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

{{-- Bill Image Modal --}}
@if(($expense && $expense->pic_water) || ($expense && $expense->pic_elec))
<div id="billModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    {{-- Backdrop --}}
    <div id="billBackdrop" class="fixed inset-0 bg-black transition-opacity duration-300 ease-out opacity-0"></div>
    
    {{-- Modal Container --}}
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        {{-- Modal Content --}}
        <div id="billContent" 
             class="relative inline-block align-bottom bg-neutral-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all duration-300 ease-out sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full opacity-0 scale-95 translate-y-4">
            
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-white/20">
                            <i class="fas fa-file-invoice text-white text-lg"></i>
                        </div>
                        <h3 id="billModalTitle" class="text-xl font-bold text-white">ภาพบิล</h3>
                    </div>
                    <button onclick="closeBillModal()" 
                            class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-5 bg-neutral-800">
                <div class="flex justify-center">
                    <img id="billImage" 
                         src="" 
                         alt="ภาพบิล" 
                         class="max-w-full h-auto rounded-lg shadow-lg">
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="bg-neutral-900 px-6 py-4 flex justify-between items-center">
                <a id="billDownloadLink" 
                   href="" 
                   download
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i>ดาวน์โหลด
                </a>
                <button type="button" 
                        onclick="closeBillModal()"
                        class="px-5 py-2.5 bg-neutral-700 hover:bg-neutral-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>ปิด
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const billPaths = {
    water: "{{ $expense && $expense->pic_water ? asset($expense->pic_water) : '' }}",
    elec: "{{ $expense && $expense->pic_elec ? asset($expense->pic_elec) : '' }}"
};

// Open Bill modal with fade animation
function openBillModal(type) {
    const modal = document.getElementById('billModal');
    const backdrop = document.getElementById('billBackdrop');
    const content = document.getElementById('billContent');
    const image = document.getElementById('billImage');
    const title = document.getElementById('billModalTitle');
    const downloadLink = document.getElementById('billDownloadLink');
    
    // Set image source and title based on type
    if (type === 'water') {
        image.src = billPaths.water;
        title.textContent = 'ภาพบิลค่าน้ำ';
        downloadLink.href = billPaths.water;
    } else if (type === 'elec') {
        image.src = billPaths.elec;
        title.textContent = 'ภาพบิลค่าไฟ';
        downloadLink.href = billPaths.elec;
    }
    
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

// Close Bill modal with fade animation
function closeBillModal() {
    const modal = document.getElementById('billModal');
    const backdrop = document.getElementById('billBackdrop');
    const content = document.getElementById('billContent');
    
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
document.getElementById('billModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeBillModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('billModal').classList.contains('hidden')) {
        closeBillModal();
    }
});
</script>
@endif
@endsection

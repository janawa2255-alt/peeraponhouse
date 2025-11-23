@extends('layouts.tenant')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white !important; color: black !important; }
        * { color: black !important; }
        .bg-neutral-900, .bg-neutral-800, .bg-orange-500 { background: white !important; border: 1px solid #ddd !important; }
    }
</style>
<script>
    function saveAsImage() {
        const element = document.getElementById('payment-receipt');
        const button = document.getElementById('saveImageBtn');
        
        // Show loading state
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> กำลังบันทึก...';
        button.disabled = true;

        html2canvas(element, {
            backgroundColor: '#171717', // neutral-900
            scale: 2, // Higher quality
            logging: false,
            useCORS: true // For images from storage
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'payment-receipt-{{ $payment->invoice->invoice_code ?? "slip" }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();

            // Restore button
            button.innerHTML = originalText;
            button.disabled = false;
        }).catch(err => {
            console.error('Error saving image:', err);
            alert('เกิดข้อผิดพลาดในการบันทึกภาพ');
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }

    function saveAsPDF() {
        const element = document.getElementById('payment-receipt');
        const button = document.getElementById('savePdfBtn');
        
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> กำลังสร้าง PDF...';
        button.disabled = true;

        const opt = {
            margin: 10,
            filename: 'payment-{{ $payment->invoice->invoice_code ?? "receipt" }}.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
            pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
        };

        html2pdf().set(opt).from(element).save().then(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        }).catch(err => {
            console.error('Error saving PDF:', err);
            alert('เกิดข้อผิดพลาดในการบันทึก PDF');
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
</script>

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white mb-1">
                รายละเอียดการชำระเงิน
            </h1>
            <p class="text-sm text-gray-400">
                ข้อมูลการชำระเงินของคุณ
            </p>
        </div>
        <div class="no-print">
            <button id="savePdfBtn" onclick="saveAsPDF()" 
                    class="px-4 py-2 text-sm rounded-lg bg-red-600 text-white hover:bg-red-500 transition-colors shadow-lg shadow-red-900/20">
                <i class="fas fa-file-pdf mr-2"></i> บันทึกเป็น PDF
            </button>
        </div>
            <a href="{{ route('payments') }}" 
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
                <div class="bg-neutral-800/40 rounded-lg p-4 border border-neutral-700">
                    <img src="{{ asset('storage/' . $payment->pic_slip) }}" 
                         alt="สลิปการชำระเงิน"
                         class="max-h-96 mx-auto rounded-lg shadow-lg">
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
@endsection

@extends('layouts.tenant')

@section('content')
<div class="space-y-6">

    {{-- หัวข้อใหญ่ --}}
    <div>
        <h1 class="text-2xl font-bold text-white mb-1">
            ข้อมูลสัญญาเช่า
        </h1>
        <p class="text-sm text-gray-400">
            แสดงรายละเอียดสัญญาเช่าปัจจุบันของคุณ
        </p>
    </div>

    {{-- กล่องใหญ่ครอบทั้งหมด --}}
    <div class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl overflow-hidden shadow-lg">

        {{-- แถบหัวในกล่อง --}}
        <div class="bg-neutral-800/60 px-6 py-4 border-b border-neutral-700">
            <h2 class="text-white font-semibold">
                รายละเอียดสัญญาเช่า
            </h2>
        </div>

        {{-- เนื้อหาด้านใน --}}
        <div class="p-6 space-y-6 bg-neutral-900">

            {{-- แถวบน: ข้อมูลผู้เช่า + ข้อมูลห้อง/สถานะ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                {{-- ซ้าย: ผู้เช่า --}}
                <div class="bg-neutral-800/40 rounded-lg p-4 border border-neutral-700 space-y-2">
                    <h3 class="text-white font-semibold mb-3">ข้อมูลผู้เช่า</h3>
                    <div class="flex justify-between">
                        <span class="text-gray-400">ชื่อ-นามสกุล:</span>
                        <span class="text-white font-medium">{{ $tenant->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">อีเมล:</span>
                        <span class="text-white font-medium">{{ $tenant->email ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">เบอร์โทร:</span>
                        <span class="text-white font-medium">{{ $tenant->phone ?? '-' }}</span>
                    </div>
                </div>

                {{-- ขวา: ห้อง & สถานะ --}}
                <div class="bg-neutral-800/40 rounded-lg p-4 border border-neutral-700 space-y-2">
                    <h3 class="text-white font-semibold mb-3">ข้อมูลห้องเช่า</h3>
                    <div class="flex justify-between">
                        <span class="text-gray-400">ห้องเช่า:</span>
                        <span class="text-white font-medium">{{ $lease->rooms->room_no ?? '-' }}</span>
                    </div>

                    @php
                        // กำหนดข้อความสถานะสัญญาเช่าจากค่า status ในตาราง leases
                        // 0 = รออนุมัติ, 1 = ใช้งานอยู่, 2 = สิ้นสุดสัญญา, 3 = ยกเลิก
                        $statusMap = [
                            0 => ['label' => 'รออนุมัติ',  'class' => 'bg-yellow-500/20 text-yellow-200 border-yellow-500/40'],
                            1 => ['label' => 'ใช้งานอยู่', 'class' => 'bg-green-500/20 text-green-200 border-green-500/40'],
                            2 => ['label' => 'สิ้นสุดสัญญา', 'class' => 'bg-gray-500/20 text-gray-200 border-gray-500/40'],
                            3 => ['label' => 'ยกเลิก', 'class' => 'bg-red-500/20 text-red-200 border-red-500/40'],
                        ];
                        $statusConfig = $statusMap[$lease->status] ?? $statusMap[0];
                    @endphp

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">สถานะสัญญา:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusConfig['class'] }}">
                            {{ $statusConfig['label'] }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-400">วันที่เริ่ม:</span>
                        <span class="text-white font-medium">{{ optional($lease->start_date)->format('d/m/Y') ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">วันสิ้นสุด:</span>
                        <span class="text-white font-medium">{{ $lease->end_date ? $lease->end_date->format('d/m/Y') : 'ไม่มีกำหนด' }}</span>
                    </div>
                </div>
            </div>

            {{-- รายการค่าใช้จ่ายหลัก --}}
            <div class="bg-neutral-800/40 rounded-lg p-4 border border-neutral-700">
                <h3 class="text-white font-semibold mb-3">รายการค่าใช้จ่าย</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">ค่าเช่ารายเดือน:</span>
                        <span class="text-orange-400 font-semibold">
                            {{ number_format($lease->rent_amount ?? 0, 2) }} ฿
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">เงินมัดจำ:</span>
                        <span class="text-white font-semibold">
                            {{ number_format($lease->deposit ?? 0, 2) }} ฿
                        </span>
                    </div>
                </div>
            </div>

            {{-- หมายเหตุ --}}
            @if(!empty($lease->note))
            <div class="bg-neutral-800/40 rounded-lg p-4 border border-neutral-700">
                <h3 class="text-white font-semibold mb-2">หมายเหตุ</h3>
                <p class="text-gray-300 text-sm">{{ $lease->note }}</p>
            </div>
            @else
            <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
                <h3 class="text-blue-300 font-semibold mb-2">
                    <i class="fas fa-info-circle mr-2"></i>ข้อควรทราบ
                </h3>
                <ul class="list-disc list-inside text-sm text-blue-200 space-y-1">
                    <li>โปรดชำระค่าเช่าตามกำหนดทุกเดือน</li>
                    <li>หากต้องการต่อสัญญา กรุณาแจ้งล่วงหน้าอย่างน้อย 30 วัน</li>
                    <li>สามารถดูใบแจ้งหนี้และชำระเงินได้ที่เมนู "ใบแจ้งหนี้"</li>
                </ul>
            </div>
            @endif

            {{-- ปุ่มดูสำเนาบัตรประชาชน --}}
            @if (!empty($lease->pic_tenant))
            <div class="flex justify-end">
                <button onclick="openIdCardModal()"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg
                          bg-blue-600 text-white hover:bg-blue-500 transition-colors shadow-lg shadow-blue-900/20">
                    <i class="fas fa-id-card mr-2"></i>ดูสำเนาบัตรประชาชน
                </button>
            </div>
            @endif

        </div>
    </div>
</div>

{{-- ID Card Modal --}}
@if (!empty($lease->pic_tenant))
<div id="idCardModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    {{-- Backdrop --}}
    <div id="idCardBackdrop" class="fixed inset-0 bg-black transition-opacity duration-300 ease-out opacity-0"></div>
    
    {{-- Modal Container --}}
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        {{-- Modal Content --}}
        <div id="idCardContent" 
             class="relative inline-block align-bottom bg-neutral-900 rounded-2xl text-left overflow-hidden shadow-2xl border border-orange-500/20 transform transition-all duration-300 ease-out sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full opacity-0 scale-95 translate-y-4">
            
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-white/20">
                            <i class="fas fa-id-card text-white text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">สำเนาบัตรประชาชนผู้เช่า</h3>
                    </div>
                    <button onclick="closeIdCardModal()" 
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
                    <img id="id-card-image"
                         src="{{ asset($lease->pic_tenant) }}" 
                         alt="สำเนาบัตรประชาชน" 
                         class="max-w-full h-auto rounded-lg shadow-lg cursor-pointer transition-all duration-300 blur-md hover:blur-sm"
                         onclick="toggleIdCardBlur(this)"
                         title="คลิกเพื่อดูแบบชัดเจน">
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="bg-neutral-900 px-6 py-4 flex justify-between items-center border-t border-neutral-700">
                <a href="{{ asset($lease->pic_tenant) }}" 
                   download
                   class="px-4 py-2 bg-orange-600 hover:bg-orange-500 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i>ดาวน์โหลด
                </a>
                <button type="button" 
                        onclick="closeIdCardModal()"
                        class="px-5 py-2.5 bg-neutral-700 hover:bg-neutral-600 text-gray-200 text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>ปิด
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Open ID Card modal with fade animation
function openIdCardModal() {
    const modal = document.getElementById('idCardModal');
    const backdrop = document.getElementById('idCardBackdrop');
    const content = document.getElementById('idCardContent');
    
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

// Close ID Card modal with fade animation
function closeIdCardModal() {
    const modal = document.getElementById('idCardModal');
    const backdrop = document.getElementById('idCardBackdrop');
    const content = document.getElementById('idCardContent');
    
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
document.getElementById('idCardModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeIdCardModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('idCardModal').classList.contains('hidden')) {
        closeIdCardModal();
    }
});

// Toggle blur effect on ID card
function toggleIdCardBlur(img) {
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

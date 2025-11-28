@extends('layouts.app')

@section('content')
<div class="space-y-4">

    {{-- หัวข้อ --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">รายละเอียดสัญญาเช่า</h1>
            <p class="text-sm text-gray-400">
                ดูข้อมูลผู้เช่า ห้องเช่า และรายละเอียดสัญญาทั้งหมดจากหน้านี้
            </p>
        </div>

    </div>

    {{-- ตรวจสอบสถานะ --}}
    @php
        switch ((int) $lease->status) {
            case 1:
                $statusLabel = 'เช่าอยู่';
                $statusClass = 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40';
                break;
            case 2:
                $statusLabel = 'สิ้นสุดสัญญา';
                $statusClass = 'bg-gray-500/20 text-gray-300 border border-gray-500/40';
                break;
            case 3:
                $statusLabel = 'ยกเลิกสัญญา';
                $statusClass = 'bg-red-500/20 text-red-300 border border-red-500/40';
                break;
            default:
                $statusLabel = 'ไม่ระบุ';
                $statusClass = 'bg-gray-500/20 text-gray-300 border border-gray-500/40';
        }
    @endphp

    {{-- กล่องหลัก --}}
 {{-- กล่องหลัก --}}
<div class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 p-6 space-y-8">

    {{-- หมวด: ผู้เช่า + ห้อง --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- ผู้เช่า --}}
        <div class="space-y-2 text-sm">
            <h2 class="text-base font-semibold text-white mb-1">ข้อมูลผู้เช่า</h2>

            <p><span class="text-gray-400">ชื่อผู้เช่า:</span>
                <span class="text-gray-100">{{ optional($lease->tenants)->name }}</span></p>

            <p><span class="text-gray-400">อีเมล:</span>
                <span class="text-gray-100">{{ optional($lease->tenants)->email ?? '-' }}</span></p>

            <p><span class="text-gray-400">เบอร์โทร:</span>
                <span class="text-gray-100">{{ optional($lease->tenants)->phone ?? '-' }}</span></p>
        </div>

        {{-- สัญญาเช่า --}}
        <div class="space-y-2 text-sm">
            <h2 class="text-base font-semibold text-white mb-1">ข้อมูลสัญญาเช่า</h2>

            <p><span class="text-gray-400">หมายเลขห้อง:</span>
                <span class="text-gray-100">{{ optional($lease->rooms)->room_no }}</span></p>

            <p class="flex items-center gap-2">
                <span class="text-gray-400">สถานะสัญญา:</span>
                <span class="px-3 py-1 rounded-full text-xs {{ $statusClass }}">
                    {{ $statusLabel }}
                </span>
            </p>

            <p><span class="text-gray-400">วันที่เริ่ม:</span>
                <span class="text-gray-100">{{ \Carbon\Carbon::parse($lease->start_date)->format('d/m/Y') }}</span>
            </p>

            <p><span class="text-gray-400">วันสิ้นสุด:</span>
                <span class="text-gray-100">{{ $lease->end_date ? \Carbon\Carbon::parse($lease->end_date)->format('d/m/Y') : 'ไม่มีกำหนด' }}</span>
            </p>
        </div>

    </div>

    {{-- หมวด: ค่าใช้จ่าย + หมายเหตุ --}}
    <div class="border-t border-neutral-800 pt-4 grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
        
        {{-- ค่าใช้จ่าย --}}
        <div class="space-y-2">
            <h2 class="text-base font-semibold text-white mb-1">รายละเอียดค่าใช้จ่าย</h2>

            <p><span class="text-gray-400">ค่าเช่ารายเดือน:</span>
                <span class="text-gray-100">{{ number_format($lease->rent_amount, 0) }} ฿</span></p>

            <p><span class="text-gray-400">เงินมัดจำ:</span>
                <span class="text-gray-100">{{ number_format($lease->deposit ?? 0, 0) }} ฿</span></p>
        </div>

        {{-- หมายเหตุ --}}
        <div class="space-y-2">
            <h2 class="text-base font-semibold text-white mb-1">หมายเหตุ / เงื่อนไข</h2>

            <p class="text-gray-200 whitespace-pre-line">
                {{ $lease->note ?: '-' }}
            </p>

            @if ($lease->pic_tenant)
                <div class="space-y-2 mt-2">
                    <p class="text-gray-400 text-sm">สำเนาบัตรประชาชนผู้เช่า:</p>

                    <button onclick="openIdCardModal()"
                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg
                              bg-neutral-800 text-gray-100 border border-neutral-600
                              hover:bg-neutral-700 hover:border-orange-500 hover:text-orange-100 transition-colors">
                        <i class="fas fa-id-card mr-2"></i>ดูสำเนาบัตรประชาชน
                    </button>
                </div>
            @endif
        </div>

    </div>

    {{-- หมวด: ข้อมูลการยกเลิก (เฉพาะ status = 3) --}}
    @if ($lease->status == 3 && $lease->cancelLeases && $lease->cancelLeases->isNotEmpty())
        @php
            $cancel = $lease->cancelLeases->sortByDesc('request_date')->first();

            switch ((int) $cancel->status) {
                case 0:
                    $cancelStatusLabel = 'รออนุมัติ';
                    $cancelStatusClass = 'bg-yellow-500/20 text-yellow-200 border-yellow-500/40';
                    break;
                case 1:
                    $cancelStatusLabel = 'อนุมัติยกเลิกแล้ว';
                    $cancelStatusClass = 'bg-red-500/20 text-red-300 border-red-500/40';
                    break;
                case 2:
                    $cancelStatusLabel = 'ไม่อนุมัติ';
                    $cancelStatusClass = 'bg-gray-500/20 text-gray-300 border-gray-500/40';
                    break;
                default:
                    $cancelStatusLabel = 'ไม่ระบุ';
                    $cancelStatusClass = 'bg-gray-500/20 text-gray-300 border-gray-500/40';
            }
        @endphp

        <div class="border-t border-neutral-800 pt-4 text-sm">
            <h2 class="text-base font-semibold text-white mb-4">ข้อมูลการยกเลิกสัญญาเช่า</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- ฝั่งซ้าย --}}
                <div class="space-y-2">
                    <p>
                        <span class="text-gray-400">วันที่แจ้งยกเลิก:</span>
                        <span class="text-gray-100">
                            {{ \Carbon\Carbon::parse($cancel->request_date)->format('d/m/Y') }}
                        </span>
                    </p>

                    <p>
                        <span class="text-gray-400">สถานะคำขอ:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[0.7rem] border {{ $cancelStatusClass }}">
                            {{ $cancelStatusLabel }}
                        </span>
                    </p>

                    <p>
                        <span class="text-gray-400">ผู้ดำเนินการล่าสุด:</span>
                        <span class="text-gray-100">
                            {{ $cancel->created_by ?? '-' }}
                        </span>
                    </p>
                </div>

                {{-- ฝั่งขวา --}}
                <div class="space-y-1">
                    <p>
                        <span class="text-gray-400 ">เหตุผลการยกเลิกจากผู้เช่า:</span>
                        <span class="text-gray-100 whitespace-pre-line">
                            {{ $cancel->reason ?: '-' }}
                        </span>
                    </p>

                    <p>
                        <span class="text-gray-400 ">หมายเหตุจากเจ้าของ/ผู้ดูแล:</span>
                        <span class="text-gray-100 whitespace-pre-line">
                            {{ $cancel->note_owner ?: '-' }}
                        </span>
                    </p>
                </div>

            </div>
        </div>
    @endif
</div>

        </div>
        {{-- ปุ่ม --}}
        <div class="flex justify-end pt-4 border-t border-neutral-800">
            <a href="{{ route('backend.leases.index') }}"
               class="px-4 py-2 text-sm rounded-lg border border-gray-600 text-gray-200 hover:bg-gray-800">
                ย้อนกลับ
            </a>
        </div>

    </div>

</div>

{{-- ID Card Modal --}}
@if ($lease->pic_tenant)
<div id="idCardModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    {{-- Backdrop --}}
    <div id="idCardBackdrop" class="fixed inset-0 bg-black transition-opacity duration-300 ease-out opacity-0"></div>
    
    {{-- Modal Container --}}
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        {{-- Modal Content --}}
        <div id="idCardContent" 
             class="relative inline-block align-bottom bg-neutral-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all duration-300 ease-out sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full opacity-0 scale-95 translate-y-4">
            
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
            <div class="bg-neutral-900 px-6 py-4 flex justify-between items-center">
                <a href="{{ asset($lease->pic_tenant) }}" 
                   download
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i>ดาวน์โหลด
                </a>
                <button type="button" 
                        onclick="closeIdCardModal()"
                        class="px-5 py-2.5 bg-neutral-700 hover:bg-neutral-600 text-white text-sm font-medium rounded-lg transition-colors">
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

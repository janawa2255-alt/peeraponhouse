@extends('layouts.tenant')

@section('content')
<div class="space-y-6">
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

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-white mb-2">
            ข้อมูลสัญญาเช่า
        </h1>
    </div>

    {{-- Lease Info Card --}}
    <div class="bg-neutral-900/80 border border-neutral-700 rounded-xl overflow-hidden shadow-lg">
        <div class="bg-neutral-800 px-6 py-3 border-b border-neutral-700">
            <h2 class="text-white font-medium">รายละเอียดสัญญาเช่า</h2>
        </div>

        <div class="p-5 space-y-4">
            {{-- Basic Info Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1 text-sm">
                <div>
                    <span class="text-gray-400">ผู้เช่า:</span>
                    <span class="text-white ml-2">{{ $lease->tenants->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">ห้องเช่า:</span>
                    <span class="text-white ml-2">{{ $lease->rooms->room_no ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">อีเมล:</span>
                    <span class="text-white ml-2">{{ $lease->tenants->email ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">สถานะสัญญา:</span>
                    @php
                        $statusClasses = [
                            1 => 'bg-green-600 text-white',
                            2 => 'bg-gray-600 text-white',
                            3 => 'bg-red-600 text-white',
                        ];
                        $statusLabels = [
                            1 => 'เช่าอยู่',
                            2 => 'สิ้นสุด',
                            3 => 'ยกเลิก',
                        ];
                    @endphp
                    <span class="inline-block px-2 py-0.5 rounded text-xs ml-2 {{ $statusClasses[$lease->status] ?? 'bg-gray-600 text-white' }}">
                        {{ $statusLabels[$lease->status] ?? 'ไม่ทราบ' }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400">เบอร์โทร:</span>
                    <span class="text-white ml-2">{{ $lease->tenants->phone ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">วันที่เริ่ม:</span>
                    <span class="text-white ml-2">{{ \Carbon\Carbon::parse($lease->start_date)->format('d/m/Y') }}</span>
                </div>
                <div class="md:col-span-2">
                    <span class="text-gray-400">วันที่สิ้นสุด:</span>
                    <span class="text-white ml-2">{{ $lease->end_date ? \Carbon\Carbon::parse($lease->end_date)->format('d/m/Y') : 'ไม่มีกำหนด' }}</span>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-neutral-700"></div>

            {{-- Expense Info --}}
            <div>
                <h3 class="text-white font-medium mb-2">รายการ</h3>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">ค่าเช่าห้องต่อเดือน:</span>
                        <span class="text-white">{{ number_format($lease->rent_amount, 0) }} ฿</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">เงินมัดจำ:</span>
                        <span class="text-white">{{ number_format($lease->deposit ?? 0, 0) }} ฿</span>
                    </div>
                </div>
            </div>

            {{-- Note --}}
            @if($lease->note)
            <div>
                <h3 class="text-white font-medium mb-2">หมายเหตุ</h3>
                <div class="text-sm text-gray-300 whitespace-pre-line">
                    {{ $lease->note }}
                </div>
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="border-t border-neutral-700 pt-4 flex flex-wrap gap-3">
                @if($lease->pic_tenant)
                    <button onclick="openIdCardModal()"
                       class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded border border-blue-500 transition-colors">
                        <i class="fas fa-id-card mr-2"></i>ดูสำเนาบัตรประชาชน
                    </button>
                @else
                    <button disabled class="px-4 py-2 bg-neutral-700 text-gray-400 text-sm rounded border border-neutral-600 cursor-not-allowed">
                        <i class="fas fa-id-card mr-2"></i>ไม่มีสำเนาบัตรประชาชน
                    </button>
                @endif

                {{-- Cancel Request Status / Action Button --}}
                @if($cancelRequest)
                    @if($cancelRequest->status == 2)
                        {{-- ถ้าถูกปฏิเสธ ให้แสดงปุ่มยื่นคำขอใหม่ --}}
                        <button onclick="openCancelStatusModal()"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 border-red-500 text-white text-sm rounded border transition-colors">
                            <i class="fas fa-times-circle mr-2"></i>คำขอถูกปฏิเสธ - ดูรายละเอียด
                        </button>
                        <button onclick="openCancelModal()" 
                                class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm rounded border border-orange-500 transition-colors">
                            <i class="fas fa-redo mr-2"></i>ยื่นคำขอยกเลิกอีกครั้ง
                        </button>
                    @else
                        {{-- แสดงสถานะปกติ --}}
                        @php
                            $statusConfig = [
                                0 => ['label' => 'รออนุมัติ', 'class' => 'bg-yellow-600 hover:bg-yellow-700 border-yellow-500', 'icon' => 'fa-clock'],
                                1 => ['label' => 'อนุมัติแล้ว', 'class' => 'bg-green-600 hover:bg-green-700 border-green-500', 'icon' => 'fa-check-circle'],
                            ];
                            $config = $statusConfig[$cancelRequest->status] ?? $statusConfig[0];
                        @endphp
                        <button onclick="openCancelStatusModal()"
                                class="px-4 py-2 {{ $config['class'] }} text-white text-sm rounded border transition-colors">
                            <i class="fas {{ $config['icon'] }} mr-2"></i>สถานะคำขอยกเลิก: {{ $config['label'] }}
                        </button>
                    @endif
                @elseif($lease->status == 1)
                    {{-- Show cancel button only if no cancel request exists and lease is active --}}
                    <button onclick="openCancelModal()" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded border border-red-500 transition-colors">
                        <i class="fas fa-times-circle mr-2"></i>ขอยกเลิกสัญญาเช่า
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ID Card Modal --}}
@if($lease->pic_tenant)
<div id="idCardModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    {{-- Backdrop --}}
    <div id="idCardBackdrop" class="fixed inset-0 bg-black transition-opacity duration-300 ease-out opacity-0"></div>
    
    {{-- Modal Container --}}
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        {{-- Modal Content --}}
        <div id="idCardContent" 
             class="relative inline-block align-bottom bg-neutral-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all duration-300 ease-out sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full opacity-0 scale-95 translate-y-4">
            
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
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
@endif

{{-- Cancel Request Status Modal --}}
@if($cancelRequest)
<div id="cancelStatusModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    {{-- Backdrop --}}
    <div id="statusBackdrop" class="fixed inset-0 bg-black transition-opacity duration-300 ease-out opacity-0"></div>
    
    {{-- Modal Container --}}
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        {{-- Modal Content --}}
        <div id="statusContent" 
             class="relative inline-block align-bottom bg-neutral-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all duration-300 ease-out sm:my-8 sm:align-middle sm:max-w-lg sm:w-full opacity-0 scale-95 translate-y-4">
            
            {{-- Modal Header --}}
            @php
                $headerConfig = [
                    0 => ['bg' => 'from-yellow-600 to-yellow-700', 'icon' => 'fa-clock'],
                    1 => ['bg' => 'from-green-600 to-green-700', 'icon' => 'fa-check-circle'],
                    2 => ['bg' => 'from-red-600 to-red-700', 'icon' => 'fa-times-circle'],
                ];
                $header = $headerConfig[$cancelRequest->status] ?? $headerConfig[0];
            @endphp
            <div class="bg-gradient-to-r {{ $header['bg'] }} px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-white/20">
                            <i class="fas {{ $header['icon'] }} text-white text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">สถานะคำขอยกเลิกสัญญา</h3>
                    </div>
                    <button onclick="closeCancelStatusModal()" 
                            class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-5 space-y-4">
                {{-- Status Badge --}}
                <div class="text-center">
                    @php
                        $statusBadge = [
                            0 => ['label' => 'รออนุมัติ', 'class' => 'bg-yellow-500/20 text-yellow-200 border-yellow-500/40'],
                            1 => ['label' => 'อนุมัติแล้ว', 'class' => 'bg-green-500/20 text-green-200 border-green-500/40'],
                            2 => ['label' => 'ปฏิเสธคำขอ', 'class' => 'bg-red-500/20 text-red-200 border-red-500/40'],
                        ];
                        $badge = $statusBadge[$cancelRequest->status] ?? $statusBadge[0];
                    @endphp
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border {{ $badge['class'] }}">
                        {{ $badge['label'] }}
                    </span>
                </div>

                {{-- Request Info --}}
                <div class="bg-neutral-800 rounded-lg p-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">วันที่แจ้ง:</span>
                        <span class="text-white font-medium">{{ \Carbon\Carbon::parse($cancelRequest->request_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">ห้องเช่า:</span>
                        <span class="text-white font-medium">{{ $lease->rooms->room_no ?? '-' }}</span>
                    </div>
                </div>

                {{-- Reason --}}
                <div>
                    <label class="block text-gray-300 text-sm font-medium mb-2">
                        เหตุผลที่คุณแจ้ง:
                    </label>
                    <div class="bg-neutral-800 rounded-lg p-3 text-sm text-gray-200 whitespace-pre-line">
                        {{ $cancelRequest->reason }}
                    </div>
                </div>

                {{-- Admin Note (if exists) --}}
                @if($cancelRequest->note_owner)
                <div>
                    <label class="block text-gray-300 text-sm font-medium mb-2">
                        หมายเหตุจากเจ้าของหอพัก:
                    </label>
                    <div class="bg-neutral-800 rounded-lg p-3 text-sm text-gray-200 whitespace-pre-line border-l-4 {{ $cancelRequest->status == 2 ? 'border-red-500' : 'border-blue-500' }}">
                        {{ $cancelRequest->note_owner }}
                    </div>
                </div>
                @endif

                {{-- Info Message --}}
                @if($cancelRequest->status == 0)
                <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-3">
                    <p class="text-blue-200 text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        คำขอของคุณอยู่ระหว่างการพิจารณา กรุณารอการตอบกลับจากเจ้าของหอพัก
                    </p>
                </div>
                @elseif($cancelRequest->status == 2)
                <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-3">
                    <p class="text-red-200 text-sm">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        คำขอยกเลิกของคุณถูกปฏิเสธ
                    </p>
                </div>
                @endif
            </div>

            {{-- Modal Footer --}}
            <div class="bg-neutral-800/50 px-6 py-4 flex justify-end">
                <button type="button" 
                        onclick="closeCancelStatusModal()"
                        class="px-5 py-2.5 bg-neutral-700 hover:bg-neutral-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>ปิด
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Cancel Lease Modal --}}
<div id="cancelModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    {{-- Backdrop --}}
    <div id="modalBackdrop" class="fixed inset-0 bg-black transition-opacity duration-300 ease-out opacity-0"></div>
    
    {{-- Modal Container --}}
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        {{-- Modal Content --}}
        <div id="modalContent" 
             class="relative inline-block align-bottom bg-neutral-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all duration-300 ease-out sm:my-8 sm:align-middle sm:max-w-lg sm:w-full opacity-0 scale-95 translate-y-4">
            
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-white/20">
                            <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">ยืนยันการยกเลิกสัญญาเช่า</h3>
                    </div>
                    <button onclick="closeCancelModal()" 
                            class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <form action="{{ route('tenant.lease.cancel.request', $lease->lease_id) }}" method="POST">
                @csrf
                <div class="px-6 py-5 space-y-4">
                    {{-- Warning Message --}}
                    <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4">
                        <div class="flex gap-3">
                            <i class="fas fa-info-circle text-yellow-500 text-lg mt-0.5"></i>
                            <div class="text-sm text-yellow-200">
                                <p class="font-medium mb-1">คำเตือน</p>
                                <p class="text-yellow-300/80">การยกเลิกสัญญาเช่าจะต้องได้รับการอนุมัติจากเจ้าของหอพัก กรุณาระบุเหตุผลในการยกเลิกให้ชัดเจน</p>
                            </div>
                        </div>
                    </div>

                    {{-- Lease Info --}}
                    <div class="bg-neutral-800 rounded-lg p-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">ห้องเช่า:</span>
                            <span class="text-white font-medium">{{ $lease->rooms->room_no ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">ผู้เช่า:</span>
                            <span class="text-white font-medium">{{ $lease->tenants->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">ค่าเช่า/เดือน:</span>
                            <span class="text-white font-medium">{{ number_format($lease->rent_amount, 0) }} ฿</span>
                        </div>
                    </div>

                    {{-- Reason Input --}}
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">
                            เหตุผลในการยกเลิก <span class="text-red-500">*</span>
                        </label>
                        <textarea name="reason" 
                                  id="cancelReason"
                                  rows="4" 
                                  required
                                  placeholder="กรุณาระบุเหตุผลในการยกเลิกสัญญาเช่า..."
                                  class="w-full px-4 py-3 bg-neutral-800 border border-neutral-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all resize-none"></textarea>
                        <p class="text-gray-500 text-xs mt-1">ข้อมูลนี้จะถูกส่งไปยังเจ้าของหอพักเพื่อพิจารณา</p>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-neutral-800/50 px-6 py-4 flex justify-end gap-3">
                    <button type="button" 
                            onclick="closeCancelModal()"
                            class="px-5 py-2.5 bg-neutral-700 hover:bg-neutral-600 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i>ยกเลิก
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-lg shadow-red-600/20">
                        <i class="fas fa-check mr-2"></i>ยืนยันการยกเลิก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
@if($lease->pic_tenant)
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

// Close ID Card modal when clicking backdrop
document.getElementById('idCardModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
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
@endif

@if($cancelRequest)
// Open Cancel Status modal with fade animation
function openCancelStatusModal() {
    const modal = document.getElementById('cancelStatusModal');
    const backdrop = document.getElementById('statusBackdrop');
    const content = document.getElementById('statusContent');
    
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

// Close Cancel Status modal with fade animation
function closeCancelStatusModal() {
    const modal = document.getElementById('cancelStatusModal');
    const backdrop = document.getElementById('statusBackdrop');
    const content = document.getElementById('statusContent');
    
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
document.getElementById('cancelStatusModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeCancelStatusModal();
    }
});
@endif

// Open modal with fade animation
function openCancelModal() {
    const modal = document.getElementById('cancelModal');
    const backdrop = document.getElementById('modalBackdrop');
    const content = document.getElementById('modalContent');
    
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

// Close modal with fade animation
function closeCancelModal() {
    const modal = document.getElementById('cancelModal');
    const backdrop = document.getElementById('modalBackdrop');
    const content = document.getElementById('modalContent');
    
    // Trigger close animation
    backdrop.classList.remove('opacity-75');
    backdrop.classList.add('opacity-0');
    
    content.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
    content.classList.add('opacity-0', 'scale-95', 'translate-y-4');
    
    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('cancelReason').value = '';
    }, 300);
    
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

// Close modal when clicking backdrop
document.getElementById('cancelModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeCancelModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        @if($lease->pic_tenant)
        if (!document.getElementById('idCardModal').classList.contains('hidden')) {
            closeIdCardModal();
        } else 
        @endif
        @if($cancelRequest)
        if (!document.getElementById('cancelStatusModal').classList.contains('hidden')) {
            closeCancelStatusModal();
        } else 
        @endif
        if (!document.getElementById('cancelModal').classList.contains('hidden')) {
            closeCancelModal();
        }
    }
});
</script>
@endsection
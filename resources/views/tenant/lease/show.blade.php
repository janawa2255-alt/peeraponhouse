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
                    <span class="text-white ml-2">{{ $lease->end_date ? \Carbon\Carbon::parse($lease->end_date)->format('d/m/Y') : 'ไม่ระบุ' }}</span>
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
                    <a href="{{ asset('storage/' . $lease->pic_tenant) }}" 
                       target="_blank"
                       class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded border border-blue-500 transition-colors">
                        <i class="fas fa-id-card mr-2"></i>ดูสำเนาบัตรประชาชน
                    </a>
                @else
                    <button disabled class="px-4 py-2 bg-neutral-700 text-gray-400 text-sm rounded border border-neutral-600 cursor-not-allowed">
                        <i class="fas fa-id-card mr-2"></i>ไม่มีสำเนาบัตรประชาชน
                    </button>
                @endif

                {{-- Cancel Lease Button - แสดงเฉพาะสัญญาที่ active (status = 1) --}}
                @if($lease->status == 1)
                    <button onclick="openCancelModal()" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded border border-red-500 transition-colors">
                        <i class="fas fa-times-circle mr-2"></i>ขอยกเลิกสัญญาเช่า
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

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
    if (e.key === 'Escape' && !document.getElementById('cancelModal').classList.contains('hidden')) {
        closeCancelModal();
    }
});
</script>
@endsection

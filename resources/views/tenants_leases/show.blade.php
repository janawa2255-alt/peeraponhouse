{{-- resources/views/tenants_leases/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- หัวข้อใหญ่ --}}
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">
            ข้อมูลสัญญาเช่า
        </h1>
        <p class="text-sm text-gray-500">
            แสดงรายละเอียดสัญญาเช่าปัจจุบันของคุณ
        </p>
    </div>

    {{-- กล่องใหญ่ครอบทั้งหมด --}}
    <div class="bg-gray-100 border border-gray-300 rounded-xl overflow-hidden">

        {{-- แถบหัวในกล่อง --}}
        <div class="bg-gray-300 px-6 py-3">
            <h2 class="text-gray-900 font-semibold">
                รายละเอียดสัญญาเช่า
            </h2>
        </div>

        {{-- เนื้อหาด้านใน --}}
        <div class="px-8 py-6 space-y-6 bg-white">

            {{-- แถวบน: ข้อมูลผู้เช่า + ข้อมูลห้อง/สถานะ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm text-gray-800">
                {{-- ซ้าย: ผู้เช่า --}}
                <div class="space-y-1">
                    <p>
                        <span class="font-semibold">ผู้เช่า:</span>
                        {{ $tenant->full_name ?? ($tenant->first_name . ' ' . $tenant->last_name) }}
                    </p>
                    <p>
                        <span class="font-semibold">อีเมล:</span>
                        {{ $tenant->email ?? '-' }}
                    </p>
                    <p>
                        <span class="font-semibold">เบอร์โทร:</span>
                        {{ $tenant->phone ?? '-' }}
                    </p>
                </div>

                {{-- ขวา: ห้อง & สถานะ --}}
                <div class="space-y-1">
                    <p>
                        <span class="font-semibold">ห้องเช่า:</span>
                        {{ $lease->rooms->room_number ?? '-' }}
                    </p>

                    @php
                        // กำหนดข้อความสถานะสัญญาเช่าจากค่า status ในตาราง leases
                        // 0 = รออนุมัติ, 1 = ใช้งานอยู่, 2 = สิ้นสุดสัญญา
                        $statusMap = [
                            0 => ['label' => 'รออนุมัติ',  'class' => 'bg-yellow-500'],
                            1 => ['label' => 'ใช้งานอยู่', 'class' => 'bg-green-500'],
                            2 => ['label' => 'สิ้นสุดสัญญา', 'class' => 'bg-gray-500'],
                        ];
                        $statusConfig = $statusMap[$lease->status] ?? $statusMap[0];
                    @endphp

                    <p class="flex items-center gap-2">
                        <span class="font-semibold">สถานะสัญญา:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold text-white {{ $statusConfig['class'] }}">
                            {{ $statusConfig['label'] }}
                        </span>
                    </p>

                    <p>
                        <span class="font-semibold">วันที่เริ่ม:</span>
                        {{ optional($lease->start_date)->format('d/m/Y') }}
                    </p>
                    <p>
                        <span class="font-semibold">วันสิ้นสุด:</span>
                        {{ $lease->end_date ? $lease->end_date->format('d/m/Y') : 'ไม่มีกำหนด' }}
                    </p>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- รายการค่าใช้จ่ายหลัก --}}
            <div class="space-y-2 text-sm text-gray-800">
                <p class="font-semibold">รายการ</p>
                <p>
                    ค่าเช่ารายเดือน :
                    <span class="font-semibold">
                        {{ number_format($lease->rent_amount) }} ฿
                    </span>
                </p>
                <p>
                    เงินมัดจำ :
                    <span class="font-semibold">
                        {{ number_format($lease->deposit) }} ฿
                    </span>
                </p>
            </div>

            {{-- หมายเหตุ --}}
            <div class="space-y-1 text-sm text-gray-800">
                <p class="font-semibold">หมายเหตุ :</p>
                @if (!empty($lease->note))
                    <p>{{ $lease->note }}</p>
                @else
                    <ul class="list-disc list-inside text-gray-700">
                        <li>โปรดชำระค่าเช่าตามกำหนดทุกเดือน</li>
                        <li>หากต้องการต่อสัญญา กรุณาแจ้งล่วงหน้าอย่างน้อย 30 วัน</li>
                    </ul>
                @endif
            </div>

            {{-- ปุ่มดูสำเนาบัตรประชาชน --}}
            @if (!empty($lease->pic_tenant))
                <div>
                    <button onclick="openIdCardModal()"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md
                              border border-gray-400 bg-gray-100 text-gray-800
                              hover:bg-gray-200 transition-colors">
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
             class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all duration-300 ease-out sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full opacity-0 scale-95 translate-y-4">
            
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
            <div class="px-6 py-5 bg-gray-50">
                <div class="flex justify-center">
                    <img src="{{ asset($lease->pic_tenant) }}" 
                         alt="สำเนาบัตรประชาชน" 
                         class="max-w-full h-auto rounded-lg shadow-lg">
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="bg-white px-6 py-4 flex justify-between items-center border-t">
                <a href="{{ asset($lease->pic_tenant) }}" 
                   download
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i>ดาวน์โหลด
                </a>
                <button type="button" 
                        onclick="closeIdCardModal()"
                        class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-lg transition-colors">
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
</script>
@endif
@endsection

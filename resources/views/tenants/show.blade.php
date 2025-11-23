@extends('layouts.app')

@section('content')
<div class="space-y-4">

    {{-- กล่องใหญ่ครอบทั้งหมด --}}
    <div class="bg-neutral-900 border border-neutral-700 rounded-xl overflow-hidden">

        {{-- แถบหัวสีเทา --}}
        <div class="bg-neutral-700 px-6 py-3">
            <h1 class="text-white text-lg font-semibold">
                รายละเอียดโปรไฟล์ผู้เช่า
            </h1>
        </div>

        {{-- เนื้อหาด้านใน --}}
        <div class="px-8 py-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                {{-- คอลัมน์ซ้าย: รูปโปรไฟล์วงกลม --}}
                <div class="flex items-start justify-center">
                    @if ($tenant->avatar_path)
                        <img src="{{ asset($tenant->avatar_path) }}"
                             alt=""
                             class="w-32 h-32 rounded-full border border-neutral-600 object-cover">
                    @else
                        <div class="w-32 h-32 rounded-full border border-dashed border-neutral-500
                                    flex items-center justify-center text-xs text-gray-500">
                            ไม่มีรูปภาพ
                        </div>
                    @endif
                </div>

                {{-- คอลัมน์กลาง: ชื่อ / เบอร์โทร / เลขบัตร --}}
                <div class="space-y-3 text-sm">

                    <div>
                        <p class="text-gray-300 mb-1">ชื่อ-สกุล</p>
                        <div class="text-gray-1000">
                            {{ $tenant->name }}
                        </div>
                    </div>

                    <div>
                        <p class="text-gray-300 mb-1">เบอร์โทร</p>
                        <div class="text-gray-1000">
                            {{ $tenant->phone }}
                        </div>
                    </div>

                    <div>
                        <p class="text-gray-300 mb-1">เลขบัตรประชาชน</p>
                        <div class="flex items-center gap-2">
                            <span class="id-card-text text-gray-1000" style="filter: blur(5px); transition: filter 0.3s;">{{ $tenant->id_card }}</span>
                            <button onclick="toggleIdCard(this)" class="p-1 text-gray-400 hover:text-white transition-colors" title="แสดง/ซ่อน">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                </div>

                {{-- คอลัมน์ขวา: ที่อยู่ / อีเมล / ชื่อผู้ใช้ --}}
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-300 mb-1">อีเมล</p>
                        <div class="text-gray-1000">
                            {{ $tenant->email ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <p class="text-gray-300 mb-1">ชื่อผู้ใช้</p>
                        <div class=" text-gray-1000">
                            {{ $tenant->username }}
                        </div>
                    </div>
                      <div>
                        <p class="text-gray-300 mb-1">ที่อยู่</p>
                        <div class=" text-gray-100 ">
                            {{ $tenant->address ?? '-' }}
                        </div>
                    </div>

                </div>
            </div>

            {{-- แถวล่าง: ห้องเช่า + สถานะ --}}
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">

   
                <div>
                    <p class="text-gray-300 mb-1">สถานะ</p>
                    <div class="border border-neutral-600 bg-neutral-800 rounded
                                px-3 py-1.5 inline-block
                                {{ $tenant->status == 0 ? 'text-green-300' : 'text-red-300' }}">
                        {{ $tenant->status == 0 ? 'ใช้งานอยู่' : 'ยกเลิกใช้งาน' }}
                    </div>
                </div>
            </div>

            {{-- ปุ่มย้อนกลับ --}}
            <div class="mt-10 flex justify-end">
                <a href="{{ route('backend.tenants.index') }}"
                   class="px-5 py-2 text-sm rounded-lg bg-neutral-700 hover:bg-neutral-600
                          border border-neutral-600 text-gray-100">
                    ย้อนกลับ
                </a>
            </div>

        </div>
    </div>
</div>

<script>
function toggleIdCard(button) {
    const container = button.closest('div');
    const idCardText = container.querySelector('.id-card-text');
    const icon = button.querySelector('i');
    
    if (idCardText.style.filter === 'none' || idCardText.style.filter === '') {
        // Blur it
        idCardText.style.filter = 'blur(5px)';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        // Show it
        idCardText.style.filter = 'none';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection

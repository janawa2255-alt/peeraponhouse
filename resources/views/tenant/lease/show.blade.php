@extends('layouts.tenant')

@section('content')
<div class="space-y-6">
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

        <div class="p-6 space-y-5">
            {{-- Basic Info Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 text-sm">
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
                <h3 class="text-white font-medium mb-3">รายการ</h3>
                <div class="space-y-2 text-sm">
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

            {{-- ID Card Button --}}
            <div class="border-t border-neutral-700 pt-4">
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
            </div>
        </div>
    </div>
</div>
@endsection

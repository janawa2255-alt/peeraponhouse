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
                        {{ optional($lease->end_date)->format('d/m/Y') }}
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
                    <a href="{{ asset($lease->pic_tenant) }}"
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md
                              border border-gray-400 bg-gray-100 text-gray-800
                              hover:bg-gray-200">
                        ดูสำเนาบัตรประชาชน
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">
                รายละเอียดคำขอยกเลิกสัญญาเช่า
            </h1>
            <p class="text-sm text-gray-400">
                ตรวจสอบข้อมูลการแจ้งยกเลิกจากผู้เช่า ก่อนกดอนุมัติ
            </p>
        </div>

        <a href="{{ route('backend.cancel_lease.index') }}"
           class="px-4 py-2 text-sm rounded-lg border border-gray-600 text-gray-200 hover:bg-gray-800">
            ย้อนกลับ
        </a>
    </div>

    {{-- แสดงสถานะคำขอ --}}
    @php
        switch ((int) $cancel->status) {
            case 0:
                $statusLabel = 'รออนุมัติ';
                $statusClass = 'bg-yellow-500/20 text-yellow-200 border-yellow-500/40';
                break;
            case 1:
                $statusLabel = 'อนุมัติแล้ว';
                $statusClass = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40';
                break;
            case 2:
                $statusLabel = 'ไม่อนุมัติ';
                $statusClass = 'bg-red-500/20 text-red-300 border-red-500/40';
                break;
            default:
                $statusLabel = 'ไม่ระบุ';
                $statusClass = 'bg-gray-500/20 text-gray-300 border-gray-500/40';
        }
    @endphp

    <div class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 p-6 space-y-6">

        {{-- ข้อมูลผู้เช่า + ห้อง --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div class="space-y-1">
                <h2 class="text-base font-semibold text-white mb-1">ข้อมูลผู้เช่า</h2>
                <p><span class="text-gray-400">ชื่อผู้เช่า:</span>
                    <span class="text-gray-100">{{ optional(optional($cancel->lease)->tenants)->name ?? '-' }}</span></p>
                <p><span class="text-gray-400">เบอร์โทร:</span>
                    <span class="text-gray-100">{{ optional(optional($cancel->lease)->tenants)->phone ?? '-' }}</span></p>
                <p><span class="text-gray-400">อีเมล:</span>
                    <span class="text-gray-100">{{ optional(optional($cancel->lease)->tenants)->email ?? '-' }}</span></p>
            </div>

            <div class="space-y-1">
                <h2 class="text-base font-semibold text-white mb-1">ข้อมูลห้องเช่า</h2>
                <p><span class="text-gray-400">ห้อง:</span>
                    <span class="text-gray-100">{{ optional(optional($cancel->lease)->rooms)->room_no ?? '-' }}</span></p>
                <p><span class="text-gray-400">วันที่เริ่มสัญญา:</span>
                    <span class="text-gray-100">
                        @if(optional($cancel->lease)->start_date)
                            {{ \Carbon\Carbon::parse($cancel->lease->start_date)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </span>
                </p>
                <p><span class="text-gray-400">วันสิ้นสุดสัญญา:</span>
                    <span class="text-gray-100">
                        @if(optional($cancel->lease)->end_date)
                            {{ \Carbon\Carbon::parse($cancel->lease->end_date)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </span>
                </p>
                <p><span class="text-gray-400">สถานะคำขอ:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[0.7rem] border {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </p>
            </div>
        </div>

        {{-- ข้อมูลคำขอยกเลิก --}}
        <div class="border-t border-neutral-800 pt-4 space-y-3 text-sm">
            <h2 class="text-base font-semibold text-white mb-1">รายละเอียดการแจ้งยกเลิก</h2>

            <p class="text-gray-300">
                <span class="text-gray-400">วันที่แจ้งยกเลิก:</span>
                {{ \Carbon\Carbon::parse($cancel->request_date)->format('d/m/Y') }}
            </p>

            <p class="text-gray-300">
                <span class="text-gray-400">ผู้แจ้ง:</span>
                {{ $cancel->created_by }}
            </p>

            <div>
                <span class="text-gray-400 block mb-1">เหตุผลที่ผู้เช่าแจ้ง:</span>
                <div class="px-3 py-2 rounded-lg bg-neutral-800 text-gray-100 whitespace-pre-line">
                    {{ $cancel->reason }}
                </div>
            </div>

            @if($cancel->note_owner)
                <div>
                    <span class="text-gray-400 block mb-1">หมายเหตุจากเจ้าของ (เดิม):</span>
                    <div class="px-3 py-2 rounded-lg bg-neutral-800 text-gray-200 whitespace-pre-line">
                        {{ $cancel->note_owner }}
                    </div>
                </div>
            @endif
        </div>

        {{-- ฟอร์มอนุมัติ (เฉพาะถ้ายังรออนุมัติ) --}}
        @if ($cancel->status == 0)
            <div class="border-t border-neutral-800 pt-4 space-y-3">
                <h2 class="text-base font-semibold text-white mb-1">การดำเนินการของเจ้าของหอ</h2>

                <form action="{{ route('backend.cancel-leases.approve', $cancel->cancel_id) }}" method="POST" class="space-y-3">
                    @csrf

                    <label class="block text-sm text-gray-300 mb-1">
                        หมายเหตุเจ้าของ (ถ้ามี)
                    </label>
                    <textarea name="note_owner" rows="2"
                        class="w-full px-3 py-2 rounded-lg bg-neutral-800 border border-gray-600 text-gray-100
                               focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('note_owner') }}</textarea>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('backend.cancel_lease.index') }}"
                           class="px-4 py-2 text-sm rounded-lg border border-gray-600 text-gray-300 hover:bg-gray-700">
                            ยกเลิก / กลับหน้ารายการ
                        </a>

                        <button type="submit"
                                class="px-4 py-2 text-sm rounded-lg bg-emerald-600 text-white hover:bg-emerald-500">
                            อนุมัติการยกเลิกสัญญา
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="border-t border-neutral-800 pt-4 text-sm text-gray-300">
                คำขอนี้ถูกดำเนินการแล้ว ไม่สามารถอนุมัติซ้ำได้
            </div>
        @endif
        

    </div>
</div>
@endsection

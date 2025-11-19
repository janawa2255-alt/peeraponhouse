@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-neutral-900 border border-neutral-700 rounded-2xl shadow-lg p-6 space-y-6">

    <h1 class="text-xl font-semibold text-white">
        ยกเลิกสัญญาเช่าห้อง {{ optional($lease->rooms)->room_no }}
    </h1>

    <p class="text-gray-300 text-sm">
        ผู้เช่า: <span class="text-white">{{ optional($lease->tenants)->name }}</span>
    </p>

    <p class="text-gray-300 text-sm">
        กรุณาระบุเหตุผลการยกเลิกและยืนยันการดำเนินการ
    </p>

    <form action="{{ route('leases.cancel', $lease->lease_id) }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="text-sm text-gray-300">เหตุผลการยกเลิก <span class="text-red-400">*</span></label>
            <textarea name="reason" rows="3"
                class="w-full px-3 py-2 rounded-lg bg-neutral-800 border border-gray-600 text-gray-100"
                required>{{ old('reason') }}</textarea>
        </div>

        <div>
            <label class="text-sm text-gray-300">หมายเหตุเจ้าของ</label>
            <textarea name="note_owner" rows="2"
                class="w-full px-3 py-2 rounded-lg bg-neutral-800 border border-gray-600 text-gray-100">{{ old('note_owner') }}</textarea>
        </div>

        <div class="flex justify-end gap-2 pt-4 border-t border-gray-700">
            <a href="{{ route('leases.index') }}"
               class="px-4 py-2 text-sm rounded-lg border border-gray-600 text-gray-300 hover:bg-gray-700">
                ย้อนกลับ
            </a>

            <button type="submit"
                class="px-4 py-2 text-sm rounded-lg bg-red-600 text-white hover:bg-red-500">
                ยืนยันการยกเลิก
            </button>
        </div>

    </form>
</div>
@endsection

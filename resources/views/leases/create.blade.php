@extends('layouts.app')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">
                เพิ่มสัญญาเช่าห้องใหม่
            </h1>
            <p class="text-sm text-gray-400">
                เลือกผู้เช่าและห้องที่ต้องการทำสัญญา ระบบจะไม่อนุญาตให้มีสัญญาซ้อนของห้องเดียวกันที่ยังเช่าอยู่
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-3 rounded-lg border border-red-500/40 bg-red-500/10 text-sm text-red-200">
            กรุณาตรวจสอบข้อมูลที่กรอกอีกครั้ง
        </div>
    @endif

    <div class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 p-6">
        <form action="{{ route('backend.leases.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- ผู้เช่า --}}
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        ผู้เช่า <span class="text-red-400">*</span>
                    </label>
                    <select name="tenant_id"
                            class="w-full px-3 py-2 rounded-lg bg-neutral-900/60 border border-gray-600 text-gray-100
                                   focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">-- เลือกผู้เช่า --</option>
                        @foreach ($tenants as $tenant)
                            <option value="{{ $tenant->tenant_id }}" {{ old('tenant_id') == $tenant->tenant_id ? 'selected' : '' }}>
                                {{ $tenant->name }} ({{ $tenant->phone }})
                            </option>
                        @endforeach
                    </select>
                    @error('tenant_id')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ห้องเช่า --}}
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        ห้องเช่า <span class="text-red-400">*</span>
                    </label>
                    <select name="room_id" id="roomSelect"
                            class="w-full px-3 py-2 rounded-lg bg-neutral-900/60 border border-gray-600 text-gray-100
                                   focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">-- เลือกห้อง --</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->room_id }}"
                                    data-rent="{{ $room->base_rent }}"
                                    {{ old('room_id') == $room->room_id ? 'selected' : '' }}>
                                ห้อง {{ $room->room_no }} (ค่าเช่า {{ number_format($room->base_rent,0) }} ฿)
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- วันที่เริ่ม --}}
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        วันที่เริ่มสัญญา <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="start_date_display"
                           value="{{ old('start_date') ? \Carbon\Carbon::parse(old('start_date'))->format('d/m/Y') : '' }}"
                           placeholder="วว/ดด/ปปปป"
                           class="w-full px-3 py-2 rounded-lg bg-neutral-900/80 border border-gray-600 text-gray-100
                                  focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                           readonly>
                    <input type="hidden" name="start_date" id="start_date" value="{{ old('start_date') }}">
                    @error('start_date')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- วันที่สิ้นสุด --}}
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        วันสิ้นสุดสัญญา <span class="text-400">(ถ้าไม่มีกำหนด ไม่ต้องกรอก)</span>
                    </label>
                    <input type="text" id="end_date_display"
                           value="{{ old('end_date') ? \Carbon\Carbon::parse(old('end_date'))->format('d/m/Y') : '' }}"
                           placeholder="วว/ดด/ปปปป"
                           class="w-full px-3 py-2 rounded-lg bg-neutral-900/80 border border-gray-600 text-gray-100
                                  focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                           readonly>
                    <input type="hidden" name="end_date" id="end_date" value="{{ old('end_date') }}">
                    @error('end_date')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- ค่าเช่ารายเดือน --}}
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        ค่าเช่ารายเดือน (บาท) <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="rent_amount" step="1" min="0"
                           id="rentAmount"
                           value="{{ old('rent_amount') }}"
                           class="w-full px-3 py-2 rounded-lg bg-neutral-900/80 border border-gray-600 text-gray-100
                                  focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                           placeholder="จะเติมอัตโนมัติเมื่อเลือกห้อง">
                    @error('rent_amount')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- เงินมัดจำ --}}
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        เงินมัดจำ (บาท)
                    </label>
                    <input type="number" name="deposit" step="1" min="0"
                           value="{{ old('deposit') }}"
                           class="w-full px-3 py-2 rounded-lg bg-neutral-900/80 border border-gray-600 text-gray-100
                                  focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    @error('deposit')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- หมายเหตุ --}}
            <div>
                <label class="block text-sm font-medium text-gray-200 mb-1">
                    หมายเหตุ
                </label>

                <textarea name="note" rows="3"
                    class="w-full px-3 py-2 rounded-lg bg-neutral-900/80 border border-gray-600 text-gray-100
                        focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                    placeholder="เช่น ชำระทุกวันที่ 1 ของเดือน, ห้ามเลี้ยงสัตว์ ฯลฯ">{{ old('note') }}</textarea>

                @error('note')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- อัปโหลดสำเนาบัตรประชาชน --}}
           <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-200 mb-1">
                    อัปโหลดสำเนาบัตรประชาชนผู้เช่า <span class="text-red-400">*</span>
                </label>

                <input type="file" name="pic_tenant" accept="image/*" required
                    class="block px-2 py-2 text-sm text-gray-300
                        file:mr-3 file:px-3 file:py-2
                        file:rounded-lg file:border-0
                        file:bg-orange-500/80 file:text-white
                        hover:file:bg-orange-500
                        bg-neutral-900/70 border border-gray-600 rounded-lg
                        focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">

                @error('pic_tenant')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror

                <p class="text-[11px] text-gray-500">
                    รองรับไฟล์ .jpg, .jpeg, .png ขนาดไม่เกิน 4 MB
                </p>
            </div>


            {{-- ปุ่ม --}}
            <div class="flex items-center justify-end gap-2 pt-2">
                <a href="{{ route('backend.leases.index') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg border border-gray-500
                          text-gray-200 hover:bg-gray-800 transition">
                    ยกเลิก
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg
                               bg-gradient-to-r from-emerald-500 to-emerald-600 text-white
                               hover:from-emerald-400 hover:to-emerald-500 shadow-md shadow-emerald-900/40
                               focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-neutral-900">
                    บันทึกสัญญา
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Flatpickr CSS --}}
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
@endpush

{{-- JS เติมค่าเช่าอัตโนมัติตามห้อง และ Date Picker --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Auto-fill rent amount
        const roomSelect = document.getElementById('roomSelect');
        const rentInput  = document.getElementById('rentAmount');

        if (roomSelect && rentInput) {
            roomSelect.addEventListener('change', () => {
                const selected = roomSelect.options[roomSelect.selectedIndex];
                const rent = selected.getAttribute('data-rent');
                if (rent) {
                    rentInput.value = rent;
                }
            });
        }

        // Date Picker for start_date
        flatpickr("#start_date_display", {
            dateFormat: "d/m/Y",
            altInput: false,
            allowInput: false,
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    // Convert to Y-m-d format for backend
                    const date = selectedDates[0];
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    document.getElementById('start_date').value = `${year}-${month}-${day}`;
                }
            }
        });

        // Date Picker for end_date
        flatpickr("#end_date_display", {
            dateFormat: "d/m/Y",
            altInput: false,
            allowInput: false,
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    // Convert to Y-m-d format for backend
                    const date = selectedDates[0];
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    document.getElementById('end_date').value = `${year}-${month}-${day}`;
                }
            }
        });
    });
</script>
@endpush

@endsection

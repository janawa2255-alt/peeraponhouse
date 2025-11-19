
@extends('layouts.app')
@section('content')
@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-white">
            บันทึกค่าใช้จ่ายในใบแจ้งหนี้
        </h1>

        <a href="{{ route('invoices.index') }}"
           class="px-4 py-2 text-sm font-medium rounded-lg border border-neutral-600 text-gray-200 hover:bg-neutral-800">
            ย้อนกลับ
        </a>
    </div>

    <div class="bg-neutral-900 border border-neutral-700 rounded-xl overflow-hidden">
        <div class="bg-neutral-700 px-6 py-3">
            <h2 class="text-white font-semibold">
                ข้อมูลใบแจ้งหนี้
            </h2>
        </div>

        <form action="{{ route('invoices.store') }}" method="POST" enctype="multipart/form-data" class="px-8 py-6 space-y-6">
            @csrf

            {{-- แถวบน: ห้อง / เดือน / ปี --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        เลือกห้อง
                    </label>
                    <select name="lease_id" id="lease_id"
                            class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-gray-100 text-sm px-3 py-2">
                        <option value="">-- เลือกห้อง --</option>
                        @foreach($leases as $lease)
                            <option value="{{ $lease->lease_id }}"
                                    data-rent="{{ $lease->rent_amount }}"
                                    @selected(old('lease_id') == $lease->lease_id)>
                                {{ $lease->rooms->room_no }} - {{ $lease->tenants->full_name }}
                            </option>
                        @endforeach
                    </select>

                    @error('lease_id')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        เดือน
                    </label>
                    <select name="month" id="month"
                            class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-gray-100 text-sm px-3 py-2">
                        @php
                            $months = [
                                '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม',
                                '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน',
                                '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน',
                                '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม',
                            ];
                        @endphp
                        @foreach($months as $value => $label)
                            <option value="{{ $value }}" @selected(old('month') == $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('month')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        ปี
                    </label>
                    <input type="number" name="year" id="year"
                           value="{{ old('year', now()->year) }}"
                           class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-gray-100 text-sm px-3 py-2">
                    @error('year')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- แถว: วันที่บันทึก / กำหนดชำระ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        วันที่บันทึกใบแจ้งหนี้
                    </label>
                    <input type="date" name="invoice_date"
                           value="{{ old('invoice_date', now()->toDateString()) }}"
                           class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-gray-100 text-sm px-3 py-2">
                    @error('invoice_date')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        กำหนดชำระ
                        <span class="text-xs text-gray-400">(ตั้งอัตโนมัติเป็นวันที่ 5 ของเดือนถัดไป แก้ไขได้)</span>
                    </label>
                    <input type="date" name="due_date" id="due_date"
                           value="{{ old('due_date') }}"
                           class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-gray-100 text-sm px-3 py-2">
                    @error('due_date')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- แถวเลขมิเตอร์ + ค่าไฟ + ค่าเช่า --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        เลขมิเตอร์น้ำก่อน
                    </label>
                    <input type="number" name="prev_water" id="prev_water"
                        value="{{ old('prev_water') }}"
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-gray-100 text-sm px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        เลขมิเตอร์น้ำใหม่
                    </label>
                    <input type="number" name="curr_water" id="curr_water"
                        value="{{ old('curr_water') }}"
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-gray-100 text-sm px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        ค่าน้ำต่อหน่วย (บาท)
                    </label>
                    <input type="number" name="water_rate" id="water_rate" step="1" min="0"
                        value="{{ old('water_rate', config('rent.water_rate', 20)) }}"
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-gray-100 text-sm px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        ค่าไฟตามบิล
                    </label>
                    <input type="number" name="elec_total" id="elec_total"
                        value="{{ old('elec_total') }}"
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-gray-100 text-sm px-3 py-2">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        ค่าเช่าห้อง (ดึงจากสัญญาเช่า)
                    </label>
                    <input type="number" name="room_rent" id="room_rent"
                        value="{{ old('room_rent') }}"
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-gray-100 text-sm px-3 py-2"
                        readonly>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        ส่วนลด (ถ้ามี)
                    </label>
                    <input type="number" name="discount" id="discount" min="0" step="1"
                        value="{{ old('discount', 0) }}"
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-gray-100 text-sm px-3 py-2">
                    @error('discount')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        ยอดรวมสุทธิ (หลังหักส่วนลด)
                    </label>
                    <input type="number" name="total_amount" id="total_amount"
                           value="{{ old('total_amount') }}"
                           class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-gray-100 text-sm px-3 py-2"
                           readonly>
                </div>
            </div>

            {{-- แถวอัปโหลดบิล --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        อัปโหลดบิลค่าน้ำ
                    </label>
                    <input type="file" name="pic_water"
                           class="block w-full text-sm text-gray-200
                                  file:mr-4 file:py-1.5 file:px-3
                                  file:rounded-md file:border-0
                                  file:bg-neutral-700 file:text-gray-100 hover:file:bg-neutral-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        อัปโหลดบิลค่าไฟ
                    </label>
                    <input type="file" name="pic_elec"
                           class="block w-full text-sm text-gray-200
                                  file:mr-4 file:py-1.5 file:px-3
                                  file:rounded-md file:border-0
                                  file:bg-neutral-700 file:text-gray-100 hover:file:bg-neutral-600">
                </div>
            </div>

            {{-- ตารางสรุปรายการแบบรีลไทม์ --}}
            <div class="mt-6 border border-neutral-800 rounded-lg overflow-hidden">
                <div class="bg-neutral-800 px-4 py-2">
                    <h3 class="text-sm font-semibold text-gray-100">
                        สรุปรายการค่าใช้จ่าย (ตรวจสอบก่อนบันทึก)
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-gray-200">
                        <thead class="bg-neutral-900/80">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium">รายการ</th>
                                <th class="px-4 py-2 text-right font-medium">จำนวน / หน่วย</th>
                                <th class="px-4 py-2 text-right font-medium">ราคา/หน่วย (บาท)</th>
                                <th class="px-4 py-2 text-right font-medium">เป็นเงิน (บาท)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t border-neutral-800">
                                <td class="px-4 py-2">ค่าน้ำ</td>
                                <td class="px-4 py-2 text-right">
                                    <span id="summary-water-units">0</span> หน่วย
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <span id="summary-water-rate">{{ (int) config('rent.water_rate', 20) }}</span>
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <span id="summary-water-total">0</span>
                                </td>
                            </tr>
                            <tr class="border-t border-neutral-800">
                                <td class="px-4 py-2">ค่าไฟตามบิล</td>
                                <td class="px-4 py-2 text-right">-</td>
                                <td class="px-4 py-2 text-right">-</td>
                                <td class="px-4 py-2 text-right">
                                    <span id="summary-elec-total">0</span>
                                </td>
                            </tr>
                            <tr class="border-t border-neutral-800">
                                <td class="px-4 py-2">ค่าเช่าห้อง</td>
                                <td class="px-4 py-2 text-right">-</td>
                                <td class="px-4 py-2 text-right">-</td>
                                <td class="px-4 py-2 text-right">
                                    <span id="summary-rent-total">0</span>
                                </td>
                            </tr>
                            <tr class="border-t border-neutral-800">
                                <td class="px-4 py-2">ส่วนลด</td>
                                <td class="px-4 py-2 text-right">-</td>
                                <td class="px-4 py-2 text-right">-</td>
                                <td class="px-4 py-2 text-right">
                                    -<span id="summary-discount">0</span>
                                </td>
                            </tr>
                            <tr class="border-t border-neutral-800 bg-neutral-900/80">
                                <td class="px-4 py-2 font-semibold" colspan="3">
                                    ยอดรวมสุทธิ
                                </td>
                                <td class="px-4 py-2 text-right font-semibold">
                                    <span id="summary-grand-total">0</span> บาท
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ปุ่ม --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-800">
                <a href="{{ route('invoices.index') }}"
                   class="px-4 py-2 text-sm font-medium rounded-lg bg-red-600/90 text-white hover:bg-red-500">
                    ยกเลิก
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-green-600/90 text-white hover:bg-green-500">
                    บันทึกข้อมูล
                </button>
            </div>
        </form>
    </div>
</div>

{{-- script คำนวณยอดรวม + อัปเดตตาราง + กำหนดชำระ auto + ดึงค่าเช่าห้อง --}}
@push('scripts')
<script>
    function recalcTotal() {
        const prev  = parseFloat(document.getElementById('prev_water').value) || 0;
        const curr  = parseFloat(document.getElementById('curr_water').value) || 0;
        const elec  = parseFloat(document.getElementById('elec_total').value) || 0;
        const rent  = parseFloat(document.getElementById('room_rent').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;

        const waterRateInput = document.getElementById('water_rate');
        const waterRate = parseFloat(waterRateInput.value) || {{ (int) (config('rent.water_rate', 20)) }};

        const units = Math.max(curr - prev, 0);
        const waterTotal = units * waterRate;

        const subtotal = waterTotal + elec + rent;
        const grand    = Math.max(subtotal - discount, 0); // กันติดลบ

        // อัปเดต input ยอดรวมสุทธิ
        document.getElementById('total_amount').value = grand.toFixed(0);

        // อัปเดตตารางสรุปรายการ
        const elUnits    = document.getElementById('summary-water-units');
        const elRate     = document.getElementById('summary-water-rate');
        const elWTotal   = document.getElementById('summary-water-total');
        const elElec     = document.getElementById('summary-elec-total');
        const elRent     = document.getElementById('summary-rent-total');
        const elDisc     = document.getElementById('summary-discount');
        const elGrand    = document.getElementById('summary-grand-total');

        if (elUnits)  elUnits.textContent  = units.toFixed(0);
        if (elRate)   elRate.textContent   = waterRate.toFixed(0);
        if (elWTotal) elWTotal.textContent = waterTotal.toFixed(0);
        if (elElec)   elElec.textContent   = elec.toFixed(0);
        if (elRent)   elRent.textContent   = rent.toFixed(0);
        if (elDisc)   elDisc.textContent   = discount.toFixed(0);
        if (elGrand)  elGrand.textContent  = grand.toFixed(0);
    }

    // ดึงค่าเช่าห้องจาก option ที่เลือก (data-rent)
    function updateRoomRentFromLease() {
        const select = document.getElementById('lease_id');
        const rentInput = document.getElementById('room_rent');
        if (!select || !rentInput) return;

        const option = select.options[select.selectedIndex];
        if (option && option.dataset.rent) {
            rentInput.value = option.dataset.rent;
        } else {
            rentInput.value = '';
        }

        recalcTotal();
    }

    // ตั้งกำหนดชำระอัตโนมัติเป็นวันที่ 5 ของ "เดือนถัดไป" จาก month/year ที่เลือก
    function updateDueDate() {
        const monthEl = document.getElementById('month');
        const yearEl  = document.getElementById('year');
        const dueEl   = document.getElementById('due_date');

        if (!monthEl || !yearEl || !dueEl) return;

        const m = parseInt(monthEl.value);
        const y = parseInt(yearEl.value);

        if (!m || !y) return;

        let nextMonth = m + 1;
        let nextYear  = y;

        if (nextMonth > 12) {
            nextMonth = 1;
            nextYear  = y + 1;
        }

        const mm = String(nextMonth).padStart(2, '0');
        const dueStr = `${nextYear}-${mm}-05`;

        // ถ้ายังไม่มีค่า (เช่น ตอนแรกเข้า) หรือค่าเก่าตรงกับ pattern เดิมก็เซ็ตใหม่ได้
        if (!dueEl.value) {
            dueEl.value = dueStr;
        } else {
            // ถ้าอยากให้เปลี่ยนทุกครั้งที่เปลี่ยนเดือน/ปี ก็ใช้บรรทัดด้านล่างแทน
            // dueEl.value = dueStr;
        }
    }

    ['prev_water', 'curr_water', 'elec_total', 'room_rent', 'water_rate', 'discount'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', recalcTotal);
        }
    });

    const leaseSelect = document.getElementById('lease_id');
    if (leaseSelect) {
        leaseSelect.addEventListener('change', updateRoomRentFromLease);
    }

    const monthEl = document.getElementById('month');
    const yearEl  = document.getElementById('year');
    if (monthEl) monthEl.addEventListener('change', updateDueDate);
    if (yearEl)  yearEl.addEventListener('input', updateDueDate);

    // เรียกครั้งแรกตอนโหลดหน้า เพื่อเซ็ตค่าเริ่มต้น
    updateRoomRentFromLease();
    updateDueDate();
    recalcTotal();
</script>
@endpush

@endsection

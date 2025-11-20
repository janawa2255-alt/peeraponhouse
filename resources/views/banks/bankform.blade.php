@csrf

<div class="space-y-4">

    {{-- ประเภทบัญชี --}}
    <div>
        <label class="text-sm text-gray-200">ประเภทบัญชี *</label>
        @php
            $code = old('bank_code', $bank->bank_code ?? '');
        @endphp
        <select name="bank_code"
            class="w-full px-3 py-2 rounded-lg bg-neutral-900/60 border border-orange-500/20 text-gray-200">
            <!-- <option value="1" {{ $code == 1 ? 'selected' : '' }}>เงินสด</option> -->
            <option value="0" {{ $code == 0 ? 'selected' : '' }}>สแกนจ่าย (QR)</option>
            <option value="1" {{ $code == 1 ? 'selected' : '' }}>โอนผ่านธนาคาร</option>
        </select>
    </div>

    {{-- ชื่อธนาคาร --}}
    <div>
        <label class="text-sm text-gray-200">ชื่อธนาคาร</label>
        <input type="text" name="bank_name"
            value="{{ old('bank_name', $bank->bank_name ?? '') }}"
            class="w-full px-3 py-2 rounded-lg bg-neutral-900/60 border border-gray-600 text-gray-200">
    </div>

    {{-- ชื่อบัญชี --}}
    <div>
        <label class="text-sm text-gray-200">ชื่อบัญชี</label>
        <input type="text" name="account_name"
            value="{{ old('account_name', $bank->account_name ?? '') }}"
            class="w-full px-3 py-2 rounded-lg bg-neutral-900/60 border border-gray-600 text-gray-200">
    </div>

    {{-- เลขบัญชี --}}
    <div>
        <label class="text-sm text-gray-200">เลขบัญชี</label>
        <input type="text" name="number"
            value="{{ old('number', $bank->number ?? '') }}"
            class="w-full px-3 py-2 rounded-lg bg-neutral-900/60 border border-gray-600 text-gray-200">
    </div>

    {{-- QR Code --}}
    <div>
        <label class="text-sm text-gray-200">QR Code (ถ้ามี)</label>
        <input type="file" name="qrcode_pic" class="border border-gray-600 px-3 py-2 rounded-lg block text-gray-300">
    </div>

    {{-- สถานะ --}}
    <div>
        <label class="text-sm text-gray-200">สถานะ *</label>
        @php
            $status = old('status', $bank->status ?? 1);
        @endphp
        <select name="status"
            class="w-full px-3 py-2 rounded-lg bg-neutral-900/60 border border-gray-600 text-gray-200">
            <option value="1" {{ $status == 1 ? 'selected' : '' }}>เปิดใช้งานอยู่</option>
            <option value="0" {{ $status == 0 ? 'selected' : '' }}>ปิดใช้งาน</option>
        </select>
    </div>

    {{-- ปุ่ม --}}
    <div class="flex justify-end gap-2 pt-4">
        <a href="{{ route('backend.banks.index') }}" class="px-4 py-2 rounded-lg border text-gray-200">ยกเลิก</a>
        <button class="px-4 py-2 rounded-lg bg-orange-600 text-white shadow">บันทึกข้อมูล</button>
    </div>

</div>

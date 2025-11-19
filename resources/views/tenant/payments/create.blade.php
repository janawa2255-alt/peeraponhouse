@extends('layouts.tenant')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-white mb-2">
            แบบฟอร์มแจ้งชำระเงิน
        </h1>
    </div>

    {{-- Payment Form Card --}}
    <div class="bg-neutral-900/80 border border-neutral-700 rounded-xl overflow-hidden">
        <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="invoice_id" value="{{ $invoice->invoice_id }}">

            {{-- Invoice Info --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-gray-400">เลขที่ใบแจ้งหนี้:</span>
                    <input type="text" 
                           value="{{ $invoice->invoice_code }}" 
                           readonly
                           class="w-full mt-1 px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white">
                </div>
                <div>
                    <span class="text-gray-400">ห้องเช่า:</span>
                    <input type="text" 
                           value="{{ $invoice->expense->lease->rooms->room_no ?? '-' }}" 
                           readonly
                           class="w-full mt-1 px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white">
                </div>
                <div>
                    <span class="text-gray-400">ยอดที่ต้องชำระ:</span>
                    <input type="text" 
                           name="amount"
                           value="{{ number_format($invoice->expense->total_amount ?? 0, 2) }}" 
                           readonly
                           class="w-full mt-1 px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white">
                    <input type="hidden" name="amount" value="{{ $invoice->expense->total_amount ?? 0 }}">
                </div>
                <div>
                    <span class="text-gray-400">วันที่ชำระเงิน:</span>
                    <input type="date" 
                           name="paid_date" 
                           value="{{ date('Y-m-d') }}"
                           required
                           class="w-full mt-1 px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white focus:outline-none focus:border-orange-500">
                    @error('paid_date')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-neutral-700"></div>

            {{-- Bank Selection & Upload --}}
            <div>
                <h3 class="text-white font-medium mb-3">วิธีการชำระเงิน/สลิปโอนเงิน</h3>
                
                <div class="space-y-4">
                    {{-- Bank Selection --}}
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">วิธีการชำระ:</label>
                        <select name="bank_id" 
                                id="bank_select"
                                required
                                class="w-full md:w-1/3 px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white focus:outline-none focus:border-orange-500">
                            <option value="">เลือกธนาคาร</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->bank_id }}" 
                                        data-bank-name="{{ $bank->bank_name }}"
                                        data-account-number="{{ $bank->number }}"
                                        data-account-name="{{ $bank->account_name ?? '-' }}">
                                    {{ $bank->bank_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('bank_id')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bank Account Info --}}
                    <div id="bank-info" class="hidden bg-neutral-800 rounded-lg p-4 border border-neutral-600">
                        <h4 class="text-white font-medium mb-3">ข้อมูลบัญชีรับเงิน</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">ธนาคาร:</span>
                                <span class="text-white" id="info-bank-name">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">เลขที่บัญชี:</span>
                                <span class="text-white" id="info-account-number">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">ชื่อบัญชี:</span>
                                <span class="text-white" id="info-account-name">-</span>
                            </div>
                        </div>
                    </div>

                    {{-- File Upload --}}
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">อัปโหลดสลิปหรือภาพถ่ายหน้าเล่ม:</label>
                        <div class="flex items-start gap-4">
                            {{-- Upload Button --}}
                            <label class="inline-block px-4 py-2 bg-neutral-700 hover:bg-neutral-600 text-white text-sm rounded cursor-pointer transition-colors">
                                แนบไฟล์
                                <input type="file" 
                                       name="slip_image" 
                                       id="slip_image"
                                       accept="image/*"
                                       required
                                       class="hidden">
                            </label>
                            <span id="file-name" class="text-gray-400 text-sm py-2">ยังไม่ได้เลือกไฟล์</span>
                        </div>
                        @error('slip_image')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        
                        {{-- Image Preview --}}
                        <div id="image-preview" class="mt-4 hidden">
                            <img id="preview-img" src="" alt="Preview" class="max-w-md max-h-64 rounded border border-neutral-600">
                        </div>
                    </div>

                    {{-- Note --}}
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">หมายเหตุ:</label>
                        <textarea name="note" 
                                  rows="3"
                                  placeholder="ระบุหมายเหตุเพิ่มเติม (ถ้ามี)"
                                  class="w-full px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white focus:outline-none focus:border-orange-500"></textarea>
                    </div>
                </div>
            </div>

            {{-- Divider --}}
            {{-- <div class="border-t border-neutral-700"></div> --}}

            {{-- Info Text --}}
            {{-- <div class="bg-neutral-800 rounded-lg p-4">
                <p class="text-gray-300 text-sm mb-2">
                    ตำแหน่งสลิปที่บ่งบอกการโอนเงินที่
                </p>
                <p class="text-gray-300 text-sm">
                    พีระพลเฮ้าส์ 99/9 หมู่ 3 ถนนเอเชีย ตำบลเมือง จังหวัดพระนครศรีอยุธยา 67000
                </p>
            </div> --}}

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3 pt-4">
                <button type="button" 
                        onclick="window.history.back()"
                        class="px-6 py-2 bg-red-600 hover:bg-red-500 text-white rounded transition-colors">
                    ยกเลิก
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-green-600 hover:bg-green-500 text-white rounded transition-colors">
                    ยืนยัน
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Show bank account info when bank is selected
document.getElementById('bank_select').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const bankInfo = document.getElementById('bank-info');
    
    if (this.value) {
        // Get data from selected option
        const bankName = selectedOption.getAttribute('data-bank-name');
        const accountNumber = selectedOption.getAttribute('data-account-number');
        const accountName = selectedOption.getAttribute('data-account-name');
        
        // Update info display
        document.getElementById('info-bank-name').textContent = bankName || '-';
        document.getElementById('info-account-number').textContent = accountNumber || '-';
        document.getElementById('info-account-name').textContent = accountName || '-';
        
        // Show bank info
        bankInfo.classList.remove('hidden');
    } else {
        // Hide bank info
        bankInfo.classList.add('hidden');
    }
});

// Show file name when selected
document.getElementById('slip_image').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || 'ยังไม่ได้เลือกไฟล์';
    document.getElementById('file-name').textContent = fileName;
    
    // Show preview
    if (e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(e.target.files[0]);
    } else {
        document.getElementById('image-preview').classList.add('hidden');
    }
});
</script>
@endsection

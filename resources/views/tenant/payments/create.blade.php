@extends('layouts.tenant')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
@endpush

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
                    <input type="text" 
                           id="paid_date_display"
                           placeholder="วัน/เดือน/ปี (เช่น 25/11/2568)"
                           required
                           class="w-full mt-1 px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white focus:outline-none focus:border-orange-500">
                    <input type="hidden" name="paid_date" id="paid_date" value="{{ date('Y-m-d') }}">
                    @error('paid_date')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-neutral-700"></div>

            {{-- Bank Selection & Upload --}}
            <div>
                <h3 class="text-white font-medium mb-3">วิธีการชำระเงิน</h3>
                
                <div class="space-y-4">
                    {{-- Payment Type Selection --}}
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">เลือกประเภทการชำระ: <span class="text-red-400">*</span></label>
                        <select name="payment_type" 
                                id="payment_type_select"
                                required
                                class="w-full md:w-1/2 px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white focus:outline-none focus:border-orange-500">
                            <option value="">-- เลือกประเภทการชำระ --</option>
                            @php
                                $bankTypes = $banks->groupBy('bank_code');
                            @endphp
                            @if($bankTypes->has(0))
                                <option value="0">💳 สแกนจ่าย (พร้อมเพย์)</option>
                            @endif
                            @if($bankTypes->has(1))
                                <option value="1">🏦 โอนผ่านธนาคาร</option>
                            @endif
                            @if($bankTypes->has(2))
                                <option value="2">💵 เงินสด</option>
                            @endif
                        </select>
                    </div>

                    {{-- Bank Account Selection (shows after type is selected) --}}
                    <div id="bank_selection_container" class="hidden">
                        <label class="block text-gray-400 text-sm mb-2">เลือกบัญชี: <span class="text-red-400">*</span></label>
                        <select name="bank_id" 
                                id="bank_select"
                                class="w-full md:w-2/3 px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white focus:outline-none focus:border-orange-500">
                            <option value="">-- เลือกบัญชี --</option>
                        </select>
                        @error('bank_id')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bank Transfer Info (bank_code = 1) --}}
                    <div id="bank-transfer-info" class="hidden bg-neutral-800 rounded-lg p-4 border border-neutral-600">
                        <h4 class="text-white font-medium mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            ข้อมูลบัญชีรับเงิน
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">ธนาคาร:</span>
                                <span class="text-white font-medium" id="info-bank-name">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">เลขที่บัญชี:</span>
                                <span class="text-white font-medium" id="info-account-number">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">ชื่อบัญชี:</span>
                                <span class="text-white font-medium" id="info-account-name">-</span>
                            </div>
                        </div>
                        <div class="mt-3 p-3 bg-orange-500/10 border border-orange-500/30 rounded text-orange-200 text-sm">
                            <strong>⚠️ หมายเหตุ:</strong> กรุณาโอนเงินเข้าบัญชีด้านบนและแนบสลิปการโอนเงิน
                        </div>
                    </div>

                    {{-- QR Code / PromptPay Info (bank_code = 0) --}}
                    <div id="qr-payment-info" class="hidden bg-neutral-800 rounded-lg p-4 border border-neutral-600">
                        <h4 class="text-white font-medium mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                            สแกน QR Code เพื่อชำระเงิน
                        </h4>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <div class="space-y-2 text-sm mb-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">ชื่อบัญชี:</span>
                                        <span class="text-white font-medium" id="qr-account-name">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">เลขพร้อมเพย์:</span>
                                        <span class="text-white font-medium" id="qr-account-number">-</span>
                                    </div>
                                </div>
                                <div class="p-3 bg-orange-500/10 border border-orange-500/30 rounded text-orange-200 text-sm">
                                    <strong>📱 วิธีชำระ:</strong> สแกน QR Code ด้านล่างด้วยแอพธนาคารของคุณและแนบสลิปการโอนเงิน
                                </div>
                            </div>
                            <div class="flex justify-center items-center">
                                <img id="qrcode-image" 
                                     src="" 
                                     alt="QR Code" 
                                     class="w-48 h-48 object-contain bg-white rounded-lg border-2 border-orange-500/40 shadow-lg cursor-pointer hover:scale-105 transition-transform"
                                     onclick="window.open(this.src, '_blank')">
                            </div>
                        </div>
                    </div>

                    {{-- Cash Payment Info (bank_code = 2) - ไม่แสดงอะไรเลย --}}
                    <div id="cash-payment-info" class="hidden">
                        {{-- ไม่มีข้อมูลแสดง สำหรับเงินสด --}}
                    </div>

                    {{-- File Upload --}}
                    <div id="slip-upload-section">
                        <label class="block text-gray-400 text-sm mb-2">
                            อัปโหลดสลิปการโอนเงิน: 
                            <span id="slip-required-indicator" class="text-red-400">*</span>
                            <span id="slip-optional-indicator" class="text-gray-500 hidden">(ไม่บังคับ)</span>
                        </label>
                        <div class="flex items-start gap-4">
                            {{-- Upload Button --}}
                            <label class="inline-block px-4 py-2 bg-neutral-700 hover:bg-neutral-600 text-white text-sm rounded cursor-pointer transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                แนบไฟล์
                                <input type="file" 
                                       name="slip_image" 
                                       id="slip_image"
                                       accept="image/*"
                                       class="hidden">
                            </label>
                            <span id="file-name" class="text-gray-400 text-sm py-2">ยังไม่ได้เลือกไฟล์</span>
                        </div>
                        @error('slip_image')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        
                        {{-- Image Preview --}}
                        <div id="image-preview" class="mt-4 hidden">
                            <p class="text-gray-400 text-sm mb-2">ตัวอย่างภาพ:</p>
                            <img id="preview-img" src="" alt="Preview" class="max-w-md max-h-64 rounded border border-neutral-600 shadow-lg">
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

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    const allBanks = @json($banks);
    const storageBaseUrl = "{{ asset('storage') }}/";

    const paymentTypeSelect = document.getElementById('payment_type_select');
    const bankSelect = document.getElementById('bank_select');
    const bankSelectionContainer = document.getElementById('bank_selection_container');

    // Info sections
    const bankTransferInfo = document.getElementById('bank-transfer-info');
    const qrPaymentInfo = document.getElementById('qr-payment-info');
    const cashPaymentInfo = document.getElementById('cash-payment-info');

    // Slip
    const slipInput = document.getElementById('slip_image');
    const slipRequiredIndicator = document.getElementById('slip-required-indicator');
    const slipOptionalIndicator = document.getElementById('slip-optional-indicator');

    function resetInfo() {
        bankTransferInfo.classList.add('hidden');
        qrPaymentInfo.classList.add('hidden');
        cashPaymentInfo.classList.add('hidden');
    }

    function updateSlipRequirement(required) {
        if (required) {
            slipInput.setAttribute('required', 'required');
            slipRequiredIndicator.classList.remove('hidden');
            slipOptionalIndicator.classList.add('hidden');
        } else {
            slipInput.removeAttribute('required');
            slipRequiredIndicator.classList.add('hidden');
            slipOptionalIndicator.classList.remove('hidden');
        }
    }

    paymentTypeSelect.addEventListener('change', function() {
        const type = this.value;
        resetInfo();
        
        // Clear bank select but keep default
        bankSelect.innerHTML = '<option value="">-- เลือกบัญชี --</option>';
        
        if (!type) {
            bankSelectionContainer.classList.add('hidden');
            return;
        }

        const filteredBanks = allBanks.filter(b => b.bank_code == type);

        if (type == '2') { // Cash
            // Auto-select the first cash account if available
            if (filteredBanks.length > 0) {
                const cashBank = filteredBanks[0];
                const option = new Option(cashBank.bank_name, cashBank.bank_id);
                option.selected = true;
                bankSelect.add(option);
            }
            bankSelectionContainer.classList.add('hidden');
            cashPaymentInfo.classList.remove('hidden');
            updateSlipRequirement(false);
        } else {
            // Transfer or QR
            bankSelectionContainer.classList.remove('hidden');
            filteredBanks.forEach(bank => {
                const option = new Option(bank.bank_name + ' (' + bank.number + ')', bank.bank_id);
                bankSelect.add(option);
            });
            updateSlipRequirement(true);
        }
    });

    bankSelect.addEventListener('change', function() {
        const bankId = this.value;
        resetInfo();
        
        if (!bankId) return;
        
        const bank = allBanks.find(b => b.bank_id == bankId);
        if (!bank) return;

        if (bank.bank_code == 1) { // Transfer
            document.getElementById('info-bank-name').textContent = bank.bank_name;
            document.getElementById('info-account-number').textContent = bank.number;
            document.getElementById('info-account-name').textContent = bank.account_name || '-';
            bankTransferInfo.classList.remove('hidden');
            updateSlipRequirement(true);
        } else if (bank.bank_code == 0) { // QR
            document.getElementById('qr-account-name').textContent = bank.account_name || '-';
            document.getElementById('qr-account-number').textContent = bank.number;
            if (bank.qrcode_pic) {
                 document.getElementById('qrcode-image').src = storageBaseUrl + bank.qrcode_pic;
            }
            qrPaymentInfo.classList.remove('hidden');
            updateSlipRequirement(true);
        } else if (bank.bank_code == 2) {
            cashPaymentInfo.classList.remove('hidden');
            updateSlipRequirement(false);
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

    // Flatpickr for date picker
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#paid_date_display", {
            dateFormat: "d/m/Y",
            defaultDate: new Date(),
            allowInput: true,
            locale: {
                firstDayOfWeek: 0,
                weekdays: {
                    shorthand: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
                    longhand: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์']
                },
                months: {
                    shorthand: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
                    longhand: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม']
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    const date = selectedDates[0];
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    document.getElementById('paid_date').value = `${year}-${month}-${day}`;
                }
            }
        });
    });
</script>
@endsection

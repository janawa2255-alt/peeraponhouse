@extends('layouts.app')

@section('content')
<div class="space-y-4">

    {{-- หัวข้อหน้า --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">
                จัดการบัญชีธนาคาร
            </h1>
            <p class="text-sm text-gray-400">
                เพิ่ม แก้ไข หรือลบบัญชีธนาคาร / ช่องทางชำระเงินที่ใช้ในระบบ
            </p>
        </div>

        <a href="{{ route('backend.banks.create') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg
                  bg-gradient-to-r from-orange-500 to-orange-600 text-white
                  hover:from-orange-400 hover:to-orange-500 shadow-md shadow-orange-900/40
                  focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-neutral-900">
            + เพิ่มบัญชีธนาคาร
        </a>
    </div>

    {{-- flash message --}}
    @if (session('success'))
        <div class="p-3 rounded-lg border border-green-500/40 bg-green-500/10 text-sm text-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- ตารางรายการ --}}
    <div class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 overflow-hidden">
        <table class="min-w-full text-sm text-left text-gray-200">
            <thead class="bg-neutral-900/90 text-xs uppercase text-gray-400 border-b border-orange-500/30">
                <tr>
                    <th class="px-4 py-3">ลำดับ</th>
                    <th class="px-4 py-3">ประเภท</th>
                    <th class="px-4 py-3">ชื่อธนาคาร</th>
                    <th class="px-4 py-3">ชื่อบัญชี</th>
                    <th class="px-4 py-3">เลขบัญชี</th>
                    <th class="px-4 py-3">QR Code</th>
                    <th class="px-4 py-3">สถานะ</th>
                    <th class="px-4 py-3 text-right">จัดการ</th>
                </tr>
            </thead>

            <tbody>
            @forelse ($banks as $bank)
                <tr class="border-t border-neutral-800 hover:bg-neutral-800/70">

                    {{-- ลำดับ --}}
                    <td class="px-4 py-3 text-gray-400">
                        {{ $loop->iteration }}
                    </td>

                    {{-- ประเภท (จาก bank_code) --}}
                    <td class="px-4 py-3">
                        @php
                            $code = (int) $bank->bank_code;
                            $typeLabel = match ($code) {
                                // 1 => 'เงินสด',
                                0 => 'สแกนจ่าย (QR)',
                                1 => 'โอนผ่านธนาคาร',
                                default => 'ไม่ระบุ',
                            };

                            $typeClass = match ($code) {
                                1 => 'bg-amber-500/15 text-amber-200 border-amber-500/40',
                                2 => 'bg-sky-500/15 text-sky-200 border-sky-500/40',
                                3 => 'bg-emerald-500/15 text-emerald-200 border-emerald-500/40',
                                default => 'bg-gray-500/15 text-gray-200 border-gray-500/40',
                            };
                        @endphp

                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[0.7rem] border {{ $typeClass }}">
                            {{ $typeLabel }}
                        </span>
                    </td>

                    {{-- ชื่อธนาคาร --}}
                    <td class="px-4 py-3 font-medium text-white">
                        {{ $bank->bank_name ?? '-' }}
                    </td>

                    {{-- ชื่อบัญชี --}}
                    <td class="px-4 py-3 text-gray-300">
                        {{ $bank->account_name ?? '-' }}
                    </td>

                    {{-- เลขบัญชี --}}
                    <td class="px-4 py-3 text-gray-300">
                        {{ $bank->number ?? '-' }}
                    </td>

                    {{-- QR Code --}}
                    <td class="px-4 py-3">
                        @if (!empty($bank->qrcode_pic))
                            <img src="{{ asset('storage/'.$bank->qrcode_pic) }}"
                                 alt="QR {{ $bank->bank_name }}"
                                 class="h-10 w-10 rounded-md object-cover border border-neutral-700">
                        @else
                            <span class="text-xs text-gray-500">ไม่มี</span>
                        @endif
                    </td>

                    {{-- สถานะ 1=เปิดใช้งานอยู่, 2=ปิดใช้งาน --}}
                    <td class="px-4 py-3">
                        @php
                            $isActive = (int)$bank->status === 1;
                            $statusLabel = $isActive ? 'เปิดใช้งานอยู่' : 'ปิดใช้งาน';
                            $badgeClass = $isActive
                                ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40'
                                : 'bg-gray-500/20 text-gray-300 border-gray-500/40';
                        @endphp

                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[0.7rem] border {{ $badgeClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>

                    {{-- ปุ่มจัดการ --}}
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('backend.banks.edit', $bank->bank_id) }}"
                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg
                                  bg-amber-500/20 text-amber-200 border border-amber-500/40
                                  hover:bg-amber-500/30">
                            แก้ไข
                        </a>

                        <form action="{{ route('backend.banks.destroy', $bank->bank_id) }}"
                              method="POST"
                              class="inline-block"
                              onsubmit="return confirm('ยืนยันการลบบัญชีธนาคารนี้หรือไม่?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg
                                           bg-red-500/20 text-red-200 border border-red-500/40
                                           hover:bg-red-500/30">
                                ลบ
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-6 text-center text-gray-400">
                        ยังไม่มีข้อมูลบัญชีธนาคารในระบบ
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

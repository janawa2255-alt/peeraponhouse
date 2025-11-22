@extends('layouts.tenant')

@section('content')
<div class="space-y-6">
    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-green-600/20 border border-green-600/40 text-green-200 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-white mb-2">
            ประวัติการชำระ
        </h1>
    </div>

    {{-- Payments Table --}}
    @if($payments->count() > 0)
    <div class="bg-neutral-900/80 border border-neutral-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
            <thead class="bg-neutral-800 border-b border-neutral-700">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-300 font-medium">เลขที่ใบแจ้งหนี้</th>
                    <th class="px-4 py-3 text-left text-gray-300 font-medium">ห้องเช่า</th>
                    <th class="px-4 py-3 text-right text-gray-300 font-medium">ยอดที่ชำระ</th>
                    <th class="px-4 py-3 text-center text-gray-300 font-medium">ยอดที่ต้องชำระ</th>
                    <th class="px-4 py-3 text-center text-gray-300 font-medium">วันที่ชำระเงิน</th>
                    <th class="px-4 py-3 text-center text-gray-300 font-medium">สถานะ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-700">
                @foreach($payments as $payment)
                <tr class="hover:bg-neutral-800/50 transition-colors">
                    <td class="px-4 py-3 text-white">{{ $payment->invoice->invoice_code ?? '-' }}</td>
                    <td class="px-4 py-3 text-white">{{ $payment->invoice->expense->lease->rooms->room_no ?? '-' }}</td>
                    <td class="px-4 py-3 text-right text-white font-medium">
                        {{ number_format($payment->total_amount, 0) }} ฿
                    </td>
                    <td class="px-4 py-3 text-center text-white">
                        {{ number_format($payment->invoice->expense->total_amount ?? 0, 0) }} ฿
                    </td>
                    <td class="px-4 py-3 text-center text-white">
                        {{ \Carbon\Carbon::parse($payment->paid_date)->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        @php
                            $statusConfig = [
                                0 => ['text' => 'รอตรวจสอบ', 'class' => 'bg-gray-600/20 text-gray-400 border-gray-600/30'],
                                2 => ['text' => 'ปฏิเสธ', 'class' => 'bg-red-600/20 text-red-400 border-red-600/30'],
                                1 => ['text' => 'ยืนยันแล้ว', 'class' => 'bg-green-600/20 text-green-400 border-green-600/30'],
                            ];
                            $config = $statusConfig[$payment->status] ?? ['text' => 'ไม่ทราบ', 'class' => 'bg-gray-600/20 text-gray-400 border-gray-600/30'];
                        @endphp
                        <span class="inline-block px-2 py-1 rounded text-xs border {{ $config['class'] }}">
                            {{ $config['text'] }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $payments->links() }}
    </div>
    @else
    {{-- Empty State --}}
    <div class="bg-neutral-900/80 border border-neutral-700 rounded-xl p-12 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-neutral-800 mb-4">
            <i class="fas fa-money-bill-wave text-2xl text-gray-400"></i>
        </div>
        <h3 class="text-white font-semibold mb-2">ยังไม่มีประวัติการชำระเงิน</h3>
        <p class="text-gray-400 text-sm">เมื่อคุณชำระเงินแล้ว ประวัติจะแสดงที่นี่</p>
    </div>
    @endif
</div>
@endsection

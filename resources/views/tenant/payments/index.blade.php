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
    {{-- Desktop Table View --}}
    <div class="hidden md:block bg-neutral-900/80 border border-neutral-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full text-sm">
            <thead class="bg-neutral-800 border-b border-neutral-700">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-300 font-medium whitespace-nowrap">เลขที่ใบแจ้งหนี้</th>
                    <th class="px-4 py-3 text-left text-gray-300 font-medium whitespace-nowrap">ห้องเช่า</th>
                    <th class="px-4 py-3 text-right text-gray-300 font-medium whitespace-nowrap">ยอดที่ชำระ</th>
                    <th class="px-4 py-3 text-center text-gray-300 font-medium whitespace-nowrap">ยอดที่ต้องชำระ</th>
                    <th class="px-4 py-3 text-center text-gray-300 font-medium whitespace-nowrap">วันที่ชำระเงิน</th>
                    <th class="px-4 py-3 text-center text-gray-300 font-medium whitespace-nowrap">สถานะ</th>
                    <th class="px-4 py-3 text-center text-gray-300 font-medium whitespace-nowrap">รายละเอียด</th>
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
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('payments.show', $payment->id) }}" 
                           class="inline-block px-3 py-1.5 bg-neutral-700 hover:bg-neutral-600 text-white text-xs rounded transition-colors border border-neutral-600">
                            ดูรายละเอียด
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile Card View --}}
    <div class="grid grid-cols-1 gap-4 md:hidden">
        @foreach($payments as $payment)
            @php
                $statusConfig = [
                    0 => ['text' => 'รอตรวจสอบ', 'class' => 'bg-gray-500/20 text-gray-400 border-gray-500/30', 'bg' => 'bg-gray-500'],
                    2 => ['text' => 'ปฏิเสธ', 'class' => 'bg-red-500/20 text-red-400 border-red-500/30', 'bg' => 'bg-red-500'],
                    1 => ['text' => 'ยืนยันแล้ว', 'class' => 'bg-green-500/20 text-green-400 border-green-500/30', 'bg' => 'bg-green-500'],
                ];
                $config = $statusConfig[$payment->status] ?? ['text' => 'ไม่ทราบ', 'class' => 'bg-gray-500/20 text-gray-400 border-gray-500/30', 'bg' => 'bg-gray-500'];
            @endphp
            <div class="bg-neutral-900/90 border border-neutral-800 rounded-xl p-4 shadow-lg relative overflow-hidden">
                {{-- Status Strip --}}
                <div class="absolute top-0 left-0 w-1 h-full {{ $config['bg'] }}"></div>

                <div class="flex justify-between items-start mb-3 pl-2">
                    <div>
                        <h3 class="text-white font-bold text-lg">{{ $payment->invoice->invoice_code ?? '-' }}</h3>
                        <p class="text-xs text-gray-400">วันที่ชำระ: {{ \Carbon\Carbon::parse($payment->paid_date)->format('d/m/Y') }}</p>
                    </div>
                    <span class="px-2 py-1 rounded text-[10px] font-bold border {{ $config['class'] }}">
                        {{ $config['text'] }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2 mb-4 pl-2 text-sm">
                    <div class="bg-neutral-800/50 p-2 rounded border border-neutral-800">
                        <p class="text-[10px] text-gray-500 mb-0.5">ห้องเช่า</p>
                        <p class="text-gray-200 font-medium">{{ $payment->invoice->expense->lease->rooms->room_no ?? '-' }}</p>
                    </div>
                    <div class="bg-neutral-800/50 p-2 rounded border border-neutral-800">
                        <p class="text-[10px] text-gray-500 mb-0.5">ยอดที่ต้องชำระ</p>
                        <p class="text-gray-200 font-medium">{{ number_format($payment->invoice->expense->total_amount ?? 0, 0) }} ฿</p>
                    </div>
                </div>

                <div class="flex justify-between items-center pl-2 bg-neutral-800/30 p-2 rounded-lg mb-3">
                    <span class="text-gray-400 text-sm">ยอดที่ชำระจริง</span>
                    <span class="text-xl font-bold text-orange-400">
                        {{ number_format($payment->total_amount, 0) }} ฿
                    </span>
                </div>

                <div class="pl-2">
                    <a href="{{ route('payments.show', $payment->id) }}" 
                       class="block w-full py-2 text-center bg-neutral-700 hover:bg-neutral-600 text-white text-sm rounded-lg transition-colors border border-neutral-600">
                        ดูรายละเอียด
                    </a>
                </div>
            </div>
        @endforeach
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

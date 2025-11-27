@extends('layouts.tenant')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-white mb-2">
            ใบแจ้งหนี้ของฉัน
        </h1>
    </div>

    {{-- Invoices Table --}}
    @if($invoices->count() > 0)
    {{-- Desktop Table View --}}
    <div class="hidden md:block bg-neutral-900/80 border border-neutral-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full text-sm">
            <thead class="bg-neutral-800 border-b border-neutral-700">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-300 font-medium whitespace-nowrap">เลขที่ใบแจ้งหนี้</th>
                    <th class="px-4 py-3 text-left text-gray-300 font-medium whitespace-nowrap">เดือน</th>
                    <th class="px-4 py-3 text-right text-gray-300 font-medium whitespace-nowrap">ยอดรวม</th>
                    <th class="px-4 py-3 text-center text-gray-300 font-medium whitespace-nowrap">ครบกำหนด</th>
                    <th class="px-4 py-3 text-center text-gray-300 font-medium whitespace-nowrap">สถานะ</th>
                    <th class="px-4 py-3 text-center text-gray-300 font-medium whitespace-nowrap">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-700">
                @foreach($invoices as $invoice)
                <tr class="hover:bg-neutral-800/50 transition-colors">
                    <td class="px-4 py-3 text-white">{{ $invoice->invoice_code }}</td>
                    <td class="px-4 py-3 text-white">
                        {{ \Carbon\Carbon::create($invoice->expense->year, $invoice->expense->month, 1)->locale('th')->translatedFormat('F Y') }}
                    </td>
                    <td class="px-4 py-3 text-right text-white font-medium">
                        {{ number_format($invoice->expense->total_amount ?? 0, 0) }} ฿
                    </td>
                    <td class="px-4 py-3 text-center text-white">
                        {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') : 'ไม่มีกำหนด' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        @php
                            $statusConfig = [
                                0 => ['label' => 'รอชำระ', 'class' => 'bg-yellow-600'],
                                1 => ['label' => 'ชำระแล้ว', 'class' => 'bg-blue-600'],
                                2 => ['label' => 'ยกเลิก', 'class' => 'bg-red-600'],
                                3 => ['label' => 'เกินกำหนด', 'class' => 'bg-red-600'],
                            ];
                            $config = $statusConfig[$invoice->status] ?? ['label' => '-', 'class' => 'bg-gray-600'];
                        @endphp
                        <span class="inline-block px-2 py-1 rounded text-xs text-white {{ $config['class'] }}">
                            {{ $config['label'] }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('invoices.show', $invoice->invoice_id) }}" 
                               class="inline-block px-2 py-1 bg-green-600 hover:bg-green-500 text-white text-xs rounded transition-colors">
                                ดูรายละเอียด
                            </a>
                            @if($invoice->status == 0)
                            <a href="{{ route('payments.create', $invoice->invoice_id) }}" 
                               class="inline-block px-2 py-1 bg-orange-600 hover:bg-orange-500 text-white text-xs rounded transition-colors">
                                ชำระเงิน
                            </a>
                            @endif
                            {{-- <button class="inline-block px-2 py-1 bg-red-600 hover:bg-red-500 text-white text-xs rounded transition-colors">
                                เพิ่มหมายเหตุ
                            </button> --}}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile Card View --}}
    <div class="grid grid-cols-1 gap-4 md:hidden">
        @foreach($invoices as $invoice)
            @php
                $statusConfig = [
                    0 => ['label' => 'รอชำระ', 'class' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30', 'bg' => 'bg-yellow-500'],
                    1 => ['label' => 'ชำระแล้ว', 'class' => 'bg-blue-500/20 text-blue-400 border-blue-500/30', 'bg' => 'bg-blue-500'],
                    2 => ['label' => 'ยกเลิก', 'class' => 'bg-red-500/20 text-red-400 border-red-500/30', 'bg' => 'bg-red-500'],
                    3 => ['label' => 'เกินกำหนด', 'class' => 'bg-red-500/20 text-red-400 border-red-500/30', 'bg' => 'bg-red-500'],
                ];
                $config = $statusConfig[$invoice->status] ?? ['label' => '-', 'class' => 'bg-gray-500/20 text-gray-400 border-gray-500/30', 'bg' => 'bg-gray-500'];
            @endphp
            <div class="bg-neutral-900/90 border border-neutral-800 rounded-xl p-4 shadow-lg relative overflow-hidden">
                {{-- Status Strip --}}
                <div class="absolute top-0 left-0 w-1 h-full {{ $config['bg'] }}"></div>

                <div class="flex justify-between items-start mb-3 pl-2">
                    <div>
                        <h3 class="text-white font-bold text-lg">
                            {{ \Carbon\Carbon::create($invoice->expense->year, $invoice->expense->month, 1)->locale('th')->translatedFormat('F Y') }}
                        </h3>
                        <p class="text-xs text-gray-400">{{ $invoice->invoice_code }}</p>
                    </div>
                    <span class="px-2 py-1 rounded text-[10px] font-bold border {{ $config['class'] }}">
                        {{ $config['label'] }}
                    </span>
                </div>

                <div class="flex justify-between items-center mb-4 pl-2 bg-neutral-800/30 p-2 rounded-lg">
                    <span class="text-gray-400 text-sm">ยอดรวม</span>
                    <span class="text-xl font-bold text-orange-400">
                        {{ number_format($invoice->expense->total_amount ?? 0, 0) }} ฿
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2 pl-2 text-sm mb-4">
                    <div class="bg-neutral-800/50 p-2 rounded border border-neutral-800">
                        <p class="text-[10px] text-gray-500 mb-0.5">ครบกำหนด</p>
                        <p class="text-gray-200 font-medium">{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') : 'ไม่มีกำหนด' }}</p>
                    </div>
                </div>

                <div class="flex flex-col gap-2 pl-2">
                    <div class="flex gap-2">
                        <a href="{{ route('invoices.show', $invoice->invoice_id) }}" 
                           class="flex-1 py-2 rounded-lg bg-neutral-700 text-white text-xs font-medium text-center hover:bg-neutral-600 transition-colors border border-neutral-600">
                            ดูรายละเอียด
                        </a>
                        @if($invoice->status == 0)
                            <a href="{{ route('payments.create', $invoice->invoice_id) }}" 
                               class="flex-1 py-2 rounded-lg bg-orange-600 text-white text-xs font-medium text-center hover:bg-orange-500 transition-colors shadow-lg shadow-orange-900/20">
                                ชำระเงิน
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $invoices->links() }}
    </div>
    @else
    {{-- Empty State --}}
    <div class="bg-neutral-900/80 border border-neutral-700 rounded-xl p-12 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-neutral-800 mb-4">
            <i class="fas fa-file-invoice text-2xl text-gray-400"></i>
        </div>
        <h3 class="text-white font-semibold mb-2">ยังไม่มีใบแจ้งหนี้</h3>
        <p class="text-gray-400 text-sm">ใบแจ้งหนี้จะถูกออกให้อัตโนมัติทุกเดือน</p>
    </div>
    @endif
</div>
@endsection

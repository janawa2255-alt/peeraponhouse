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
    <div class="bg-neutral-900/80 border border-neutral-700 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-neutral-800 border-b border-neutral-700">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-300 font-medium">เลขที่ใบแจ้งหนี้</th>
                    <th class="px-4 py-3 text-left text-gray-300 font-medium">เดือน</th>
                    <th class="px-4 py-3 text-right text-gray-300 font-medium">ยอดรวม</th>
                    <th class="px-4 py-3 text-center text-gray-300 font-medium">ครบกำหนด</th>
                    <th class="px-4 py-3 text-center text-gray-300 font-medium">สถานะ</th>
                    <th class="px-4 py-3 text-center text-gray-300 font-medium">จัดการ</th>
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
                        {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}
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

@extends('layouts.tenant')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-white mb-2">พีระพลเฮ้าส์</h1>
    </div>

    <!-- ประกาศต่างๆ -->
    @if($announcements->count() > 0)
    <div class="bg-neutral-900/80 border border-neutral-700 rounded-xl overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-white mb-4">ประกาศต่างๆ</h2>
            
            <div class="space-y-4">
                @foreach($announcements as $announcement)
                <div class="bg-neutral-800/60 rounded-lg p-6">
                    <h3 class="text-white font-medium mb-2">{{ $announcement->title }}</h3>
                    <p class="text-gray-300 text-sm leading-relaxed mb-3">
                        {{ $announcement->content }}
                    </p>
                    
                    @if($announcement->image_path)
                    <div class="mt-3">
                        <img src="{{ asset('storage/' . $announcement->image_path) }}" 
                             alt="{{ $announcement->title }}" 
                             class="max-w-sm w-full rounded-lg border border-neutral-700 cursor-pointer hover:opacity-80 transition-opacity"
                             onclick="window.open(this.src, '_blank')">
                    </div>
                    @endif
                    
                    <p class="text-xs text-gray-500 mt-3">
                        ประกาศเมื่อ: {{ $announcement->created_at->locale('th')->diffForHumans() }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if($currentLease)

        <!-- ข้อมูลสัญญาเช่า -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- ข้อมูลห้อง -->
            <div class="bg-neutral-900/80 border border-neutral-700 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">
                    <i class="fas fa-home mr-2 text-orange-500"></i>
                    ข้อมูลห้อง
                </h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-400 text-sm">ห้อง</p>
                        <p class="text-white font-medium">{{ $currentLease->rooms->room_no ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">ค่าเช่า/เดือน</p>
                        <p class="text-white font-medium">{{ number_format($stats['room_rent'] ?? 0) }} บาท</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">เงินประกัน</p>
                        <p class="text-white font-medium">{{ number_format($currentLease->deposit) }} บาท</p>
                    </div>
                </div>
            </div>

            <!-- ข้อมูลสัญญา -->
            <div class="bg-neutral-900/80 border border-neutral-700 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">
                    <i class="fas fa-file-contract mr-2 text-orange-500"></i>
                    ข้อมูลสัญญาเช่า
                </h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-400 text-sm">วันที่เริ่ม</p>
                        <p class="text-white font-medium">{{ $currentLease->start_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">วันที่สิ้นสุด</p>
                        <p class="text-white font-medium">{{ $currentLease->end_date ? $currentLease->end_date->format('d/m/Y') : 'ไม่มีกำหนด' }}</p>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('lease.show') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-500 text-white text-sm rounded transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            ดูสัญญาเช่า
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ใบแจ้งหนี้ล่าสุด -->
        @if($recentInvoices->count() > 0)
        <div class="bg-neutral-900/80 border border-neutral-700 rounded-xl overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-white">
                        <i class="fas fa-file-invoice-dollar mr-2 text-orange-500"></i>
                        ใบแจ้งหนี้ล่าสุด
                    </h2>
                    <a href="{{ route('invoices') }}" class="text-orange-500 hover:text-orange-400 text-sm font-medium">
                        ดูทั้งหมด <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full w-full text-sm">
                        <thead class="bg-neutral-800 border-b border-neutral-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-gray-300 font-medium whitespace-nowrap">เลขที่</th>
                                <th class="px-4 py-3 text-left text-gray-300 font-medium whitespace-nowrap">เดือน</th>
                                <th class="px-4 py-3 text-right text-gray-300 font-medium whitespace-nowrap">ยอดเงิน</th>
                                <th class="px-4 py-3 text-center text-gray-300 font-medium whitespace-nowrap">สถานะ</th>
                                <th class="px-4 py-3 text-center text-gray-300 font-medium whitespace-nowrap">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-700">
                            @foreach($recentInvoices as $invoice)
                            <tr class="hover:bg-neutral-800/50">
                                <td class="px-4 py-3 text-white">{{ $invoice->invoice_code }}</td>
                                <td class="px-4 py-3 text-white">
                                    {{ \Carbon\Carbon::create($invoice->expense->year, $invoice->expense->month, 1)->locale('th')->translatedFormat('F Y') }}
                                </td>
                                <td class="px-4 py-3 text-right text-white font-medium">{{ number_format($invoice->expense->total_amount ?? 0, 0) }} ฿</td>
                                <td class="px-4 py-3 text-center">
                                    @if($invoice->status == 0)
                                        <span class="inline-block px-2 py-1 rounded text-xs text-white bg-yellow-600">รอชำระ</span>
                                    @else
                                        <span class="inline-block px-2 py-1 rounded text-xs text-white bg-green-600">ชำระแล้ว</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('invoices.show', $invoice->invoice_id) }}" 
                                           class="inline-block px-2 py-1 bg-green-600 hover:bg-green-500 text-white text-xs rounded">
                                            ดูรายละเอียด
                                        </a>
                                        @if($invoice->status == 0)
                                        <a href="{{ route('payments.create', $invoice->invoice_id) }}" 
                                           class="inline-block px-2 py-1 bg-orange-600 hover:bg-orange-500 text-white text-xs rounded">
                                            ชำระเงิน
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    @else
        <!-- ไม่มีสัญญาเช่า -->
        <div class="bg-neutral-800 border border-neutral-700 rounded-lg p-12 text-center">
            <i class="fas fa-home text-neutral-600 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-white mb-2">ไม่พบสัญญาเช่า</h3>
            <p class="text-neutral-400">ขณะนี้คุณยังไม่มีสัญญาเช่าที่ใช้งานอยู่</p>
        </div>
    @endif
</div>
@endsection

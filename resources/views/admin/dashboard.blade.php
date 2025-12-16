@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Dashboard</h1>
            <p class="text-sm text-neutral-400 mt-1">ภาพรวมการจัดการระบบห้องเช่า</p>
        </div>
        <div class="text-right">
            <p class="text-xs text-neutral-400">วันที่</p>
            <p class="text-white font-medium">{{ now()->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Stats Cards Row 1 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- จำนวนห้องทั้งหมด -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">ห้องทั้งหมด</p>
                    <p class="text-3xl font-bold mt-2">{{ \App\Models\Room::count() }}</p>
                    <p class="text-xs text-blue-100 mt-1">
                        ว่าง: {{ \App\Models\Room::where('status', 0)->count() }} ห้อง | มีผู้เช่า: {{ \App\Models\Room::where('status', 1)->count() }} ห้อง
                    </p>
                </div>
                <div class="bg-white/20 p-4 rounded-lg">
                    <i class="fas fa-home text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- ผู้เช่าปัจจุบัน -->
        <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-lg p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">ผู้เช่าทั้งหมด</p>
                    <p class="text-3xl font-bold mt-2">{{ \App\Models\Tenant::where('status', 0)->count() }}</p>
                    <p class="text-xs text-green-100 mt-1">
                        กำลังเช่า: {{ \App\Models\Lease::where('status', 1)->count() }} สัญญา
                    </p>
                </div>
                <div class="bg-white/20 p-4 rounded-lg">
                    <i class="fas fa-users text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- รายได้เดือนนี้ -->
        <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-lg p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">รายได้เดือนนี้</p>
                    <p class="text-3xl font-bold mt-2">
                        {{ number_format(\App\Models\Payment::whereMonth('paid_date', now()->month)->whereYear('paid_date', now()->year)->where('status', 1)->sum('total_amount')) }}
                    </p>
                    <p class="text-xs text-purple-100 mt-1">บาท</p>
                </div>
                <div class="bg-white/20 p-4 rounded-lg">
                    <i class="fas fa-dollar-sign text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- ใบแจ้งหนี้ค้างชำระ -->
        <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-lg p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm">ค้างชำระ</p>
                    <p class="text-3xl font-bold mt-2">{{ \App\Models\Invoice::whereIn('status', [0, 2])->count() }}</p>
                    <p class="text-xs text-red-100 mt-1">
                        {{ number_format(\App\Models\Invoice::whereIn('status', [0, 2])->join('expenses', 'invoices.ex_id', '=', 'expenses.ex_id')->sum('expenses.total_amount')) }} บาท
                    </p>
                </div>
                <div class="bg-white/20 p-4 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row 2 -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- อัตราการเข้าพัก -->
        <div class="bg-neutral-800 border border-neutral-700 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-white font-semibold">อัตราการเข้าพัก</h3>
                <i class="fas fa-chart-pie text-orange-500"></i>
            </div>
            @php
                $totalRooms = \App\Models\Room::count();
                $occupiedRooms = \App\Models\Room::where('status', 1)->count();
                $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;
            @endphp
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-neutral-400 text-sm">ห้องที่มีผู้เช่า</span>
                    <span class="text-white font-medium">{{ $occupiedRooms }} / {{ $totalRooms }}</span>
                </div>
                <div class="w-full bg-neutral-700 rounded-full h-3">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 h-3 rounded-full transition-all" style="width: {{ $occupancyRate }}%"></div>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-bold text-white">{{ number_format($occupancyRate, 1) }}%</span>
                </div>
            </div>
        </div>

        <!-- พนักงาน -->
        <div class="bg-neutral-800 border border-neutral-700 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-white font-semibold">พนักงาน</h3>
                <i class="fas fa-user-tie text-blue-500"></i>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-neutral-400 text-sm">พนักงานที่ใช้งานอยู่</span>
                    <span class="text-white font-medium">{{ \App\Models\Employee::where('status', 0)->count() }} คน</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-neutral-400 text-sm">พนักงานทั้งหมด</span>
                    <span class="text-white font-medium">{{ \App\Models\Employee::count() }} คน</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-neutral-400 text-sm">ไม่ใช้งาน/ลาออก</span>
                    <span class="text-neutral-400 font-medium">{{ \App\Models\Employee::where('status', 1)->count() }} คน</span>
                </div>
            </div>
        </div>

        <!-- สัญญาเช่า -->
        <div class="bg-neutral-800 border border-neutral-700 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-white font-semibold">สัญญาเช่า</h3>
                <i class="fas fa-file-contract text-green-500"></i>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-neutral-400 text-sm">สัญญาที่ใช้งานอยู่</span>
                    <span class="text-white font-medium">{{ \App\Models\Lease::where('status', 1)->count() }} สัญญา</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-neutral-400 text-sm">ใกล้หมดอายุ (30 วัน)</span>
                    <span class="text-yellow-500 font-medium">
                        {{ \App\Models\Lease::where('status', 1)->where('end_date', '<=', now()->addDays(30))->count() }} สัญญา
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-neutral-400 text-sm">สัญญาที่ยกเลิก</span>
                    <span class="text-neutral-400 font-medium">{{ \App\Models\Lease::where('status', 3)->count() }} สัญญา</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- ใบแจ้งหนี้ค้างชำระล่าสุด -->
        <div class="bg-neutral-800 border border-neutral-700 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">
                    <i class="fas fa-file-invoice-dollar mr-2 text-red-500"></i>
                    ใบแจ้งหนี้ค้างชำระ
                </h2>
                <a href="{{ route('backend.invoices.index') }}" class="text-orange-500 hover:text-orange-400 text-sm font-medium">
                    ดูทั้งหมด <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-2">
                @php
                    $unpaidInvoices = \App\Models\Invoice::with(['expense.lease.rooms', 'expense.lease.tenants'])
                        ->whereIn('status', [0, 2])
                        ->orderBy('due_date', 'asc')
                        ->limit(5)
                        ->get();
                @endphp
                @forelse($unpaidInvoices as $invoice)
                    <div class="flex items-center justify-between p-3 bg-neutral-700/50 rounded-lg hover:bg-neutral-700 transition-colors">
                        <div class="flex-1">
                            <p class="text-white font-medium text-sm">{{ $invoice->invoice_code }}</p>
                            <p class="text-neutral-400 text-xs mt-1">
                                ห้อง {{ $invoice->expense->lease->rooms->room_no ?? 'N/A' }} - 
                                {{ $invoice->expense->lease->tenants->name ?? 'N/A' }}
                            </p>
                            <p class="text-xs mt-1 {{ optional($invoice->due_date)->isPast() ? 'text-red-400' : 'text-yellow-400' }}">
                                <i class="fas fa-clock mr-1"></i>
                                ครบกำหนด: {{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : 'ไม่มีกำหนด' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-white font-semibold">{{ number_format($invoice->expense->total_amount ?? 0) }}</p>
                            <p class="text-neutral-400 text-xs">บาท</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-neutral-400">
                        <i class="fas fa-check-circle text-4xl text-green-500 mb-2"></i>
                        <p class="text-sm">ไม่มีใบแจ้งหนี้ค้างชำระ</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- การชำระเงินล่าสุด -->
        <div class="bg-neutral-800 border border-neutral-700 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">
                    <i class="fas fa-money-bill-wave mr-2 text-green-500"></i>
                    การชำระเงินล่าสุด
                </h2>
                <a href="{{ route('backend.payments.index') }}" class="text-orange-500 hover:text-orange-400 text-sm font-medium">
                    ดูทั้งหมด <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-2">
                @php
                    $recentPayments = \App\Models\Payment::with(['invoice.expense.lease.rooms', 'invoice.expense.lease.tenants'])
                        ->where('status', 1)
                        ->orderBy('paid_date', 'desc')
                        ->limit(5)
                        ->get();
                @endphp
                @forelse($recentPayments as $payment)
                    <div class="flex items-center justify-between p-3 bg-neutral-700/50 rounded-lg hover:bg-neutral-700 transition-colors">
                        <div class="flex-1">
                            <p class="text-white font-medium text-sm">{{ $payment->invoice->invoice_code ?? 'N/A' }}</p>
                            <p class="text-neutral-400 text-xs mt-1">
                                ห้อง {{ $payment->invoice->expense->lease->rooms->room_no ?? 'N/A' }} - 
                                {{ $payment->invoice->expense->lease->tenants->name ?? 'N/A' }}
                            </p>
                            <p class="text-xs mt-1 text-neutral-400">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $payment->paid_date->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-green-400 font-semibold">{{ number_format($payment->total_amount) }}</p>
                            <p class="text-neutral-400 text-xs">{{ $payment->method_label }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-neutral-400">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p class="text-sm">ยังไม่มีการชำระเงิน</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-neutral-800 border border-neutral-700 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-white mb-4">
            <i class="fas fa-bolt mr-2 text-yellow-500"></i>
            เมนูด่วน
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="{{ route('backend.rooms.index') }}" class="flex flex-col items-center p-4 bg-neutral-700 hover:bg-neutral-600 rounded-lg transition-colors group">
                <i class="fas fa-home text-2xl text-blue-500 mb-2 group-hover:scale-110 transition-transform"></i>
                <span class="text-white text-sm text-center">จัดการห้อง</span>
            </a>
            <a href="{{ route('backend.tenants.index') }}" class="flex flex-col items-center p-4 bg-neutral-700 hover:bg-neutral-600 rounded-lg transition-colors group">
                <i class="fas fa-users text-2xl text-green-500 mb-2 group-hover:scale-110 transition-transform"></i>
                <span class="text-white text-sm text-center">จัดการผู้เช่า</span>
            </a>
            <a href="{{ route('backend.leases.index') }}" class="flex flex-col items-center p-4 bg-neutral-700 hover:bg-neutral-600 rounded-lg transition-colors group">
                <i class="fas fa-file-contract text-2xl text-purple-500 mb-2 group-hover:scale-110 transition-transform"></i>
                <span class="text-white text-sm text-center">สัญญาเช่า</span>
            </a>
            <a href="{{ route('backend.invoices.index') }}" class="flex flex-col items-center p-4 bg-neutral-700 hover:bg-neutral-600 rounded-lg transition-colors group">
                <i class="fas fa-file-invoice-dollar text-2xl text-orange-500 mb-2 group-hover:scale-110 transition-transform"></i>
                <span class="text-white text-sm text-center">ใบแจ้งหนี้</span>
            </a>
            <a href="{{ route('backend.payments.index') }}" class="flex flex-col items-center p-4 bg-neutral-700 hover:bg-neutral-600 rounded-lg transition-colors group">
                <i class="fas fa-money-bill-wave text-2xl text-green-500 mb-2 group-hover:scale-110 transition-transform"></i>
                <span class="text-white text-sm text-center">การชำระเงิน</span>
            </a>
            <a href="{{ route('backend.employees.index') }}" class="flex flex-col items-center p-4 bg-neutral-700 hover:bg-neutral-600 rounded-lg transition-colors group">
                <i class="fas fa-user-tie text-2xl text-blue-500 mb-2 group-hover:scale-110 transition-transform"></i>
                <span class="text-white text-sm text-center">พนักงาน</span>
            </a>
        </div>
    </div>
</div>
@endsection

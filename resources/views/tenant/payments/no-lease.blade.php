@extends('layouts.tenant')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
        {{-- Icon --}}
        <div class="mb-6">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-neutral-800/60 border border-orange-500/20">
                <svg class="w-10 h-10 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.598 1M12 8V6m0 10v2m8-8a8 8 0 11-16 0 8 8 0 0116 0z"/>
                </svg>
            </div>
        </div>

        {{-- Message --}}
        <h1 class="text-2xl font-bold text-white mb-3">
            ยังไม่มีประวัติการชำระเงิน
        </h1>
        <p class="text-gray-400 mb-6">
            คุณยังไม่มีสัญญาเช่าที่ใช้งานอยู่ในขณะนี้<br>
            จึงยังไม่มีรายการชำระเงินให้แสดงผล
        </p>

        {{-- User Info --}}
        <div class="bg-neutral-900/60 border border-neutral-700/50 rounded-xl p-4 mb-6">
            <p class="text-sm text-gray-400 mb-1">ข้อมูลผู้เช่า</p>
            <p class="text-white font-semibold">{{ $tenant->name }}</p>
            <p class="text-sm text-gray-400">{{ $tenant->email }}</p>
        </div>

        {{-- Info Box --}}
        <div class="bg-blue-900/20 border border-blue-500/30 rounded-lg p-4 mb-6 text-left">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm text-blue-200 font-medium mb-1">ข้อมูล</p>
                    <p class="text-xs text-blue-300/80">
                        เมื่อมีการออกใบแจ้งหนี้และคุณทำการชำระเงินแล้ว<br>
                        ระบบจะแสดงประวัติการชำระเงินของคุณในหน้านี้
                    </p>
                </div>
            </div>
        </div>

        {{-- Back Button --}}
        <a href="{{ route('home') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-orange-600/90 text-white hover:bg-orange-500/90 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            กลับหน้าแรก
        </a>
    </div>
</div>
@endsection

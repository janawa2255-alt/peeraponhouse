@extends('layouts.tenant')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
        {{-- Icon --}}
        <div class="mb-6">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-neutral-800/60 border border-orange-500/20">
                <svg class="w-10 h-10 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>

        {{-- Message --}}
        <h1 class="text-2xl font-bold text-white mb-3">
            ยังไม่มีสัญญาเช่า
        </h1>
        <p class="text-gray-400 mb-6">
            คุณยังไม่มีสัญญาเช่าที่ใช้งานอยู่ในขณะนี้<br>
            กรุณาติดต่อเจ้าของหอหรือแอดมินเพื่อทำสัญญาเช่า
        </p>

        {{-- User Info --}}
        <div class="bg-neutral-900/60 border border-neutral-700/50 rounded-xl p-4 mb-6">
            <p class="text-sm text-gray-400 mb-1">ข้อมูลผู้เช่า</p>
            <p class="text-white font-semibold">{{ $tenant->name }}</p>
            <p class="text-sm text-gray-400">{{ $tenant->email }}</p>
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

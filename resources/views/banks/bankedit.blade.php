@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    <h1 class="text-2xl font-semibold text-white">แก้ไขบัญชีธนาคาร</h1>
    <p class="text-sm text-gray-400">ปรับปรุงข้อมูลบัญชีธนาคารตามต้องการ</p>

    <form action="{{ route('backend.banks.update', $bank->bank_id) }}" method="POST" enctype="multipart/form-data"
          class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 p-6">
        @method('PUT')
        @include('banks.bankform')
    </form>
</div>
@endsection

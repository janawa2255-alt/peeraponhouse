@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    <h1 class="text-2xl font-semibold text-white">เพิ่มบัญชีธนาคาร</h1>
    <p class="text-sm text-gray-400">กรอกข้อมูลบัญชีธนาคารที่จะใช้สำหรับรับชำระเงิน</p>

    <form action="{{ route('backend.banks.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 p-6">
        @include('banks.bankform')
    </form>
</div>
@endsection

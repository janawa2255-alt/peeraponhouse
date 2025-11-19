
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    <div>
        <h1 class="text-2xl font-semibold text-white">
            เพิ่มห้องเช่าใหม่
        </h1>
        <p class="text-sm text-gray-400">
            กรอกข้อมูลห้องเช่าให้ครบถ้วน แล้วกดบันทึกเพื่อเพิ่มเข้าระบบ
        </p>
    </div>

    @if ($errors->any())
        <div class="p-3 rounded-lg border border-red-500/40 bg-red-500/10 text-sm text-red-200">
            กรุณาตรวจสอบข้อมูลที่กรอกอีกครั้ง
        </div>
    @endif

    <div class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 p-5">
        <form action="{{ route('rooms.store') }}" method="POST" class="space-y-4">
            @include('rooms._form')
        </form>
    </div>
</div>
@endsection

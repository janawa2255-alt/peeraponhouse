@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    <div>
        <h1 class="text-2xl font-semibold text-white">
            แก้ไขข้อมูลพนักงาน : {{ $emp->name }}
        </h1>
        <p class="text-sm text-gray-400">
            ปรับปรุงรายละเอียดพนักงาน แล้วกดบันทึกเพื่ออัปเดตข้อมูล
        </p>
    </div>

    @if ($errors->any())
        <div class="p-3 rounded-lg border border-red-500/40 bg-red-500/10 text-sm text-red-200">
            กรุณาตรวจสอบข้อมูลที่กรอกอีกครั้ง
        </div>
    @endif

    <div class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 p-5">
        <form action="{{ route('backend.employees.update', $emp->emp_id) }}" method="POST" class="space-y-4">
            @method('PUT')
            @include('employees._form', ['employee' => $emp])
        </form>
    </div>
</div>
@endsection

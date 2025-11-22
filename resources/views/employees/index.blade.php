@extends('layouts.app')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">
                จัดการข้อมูลพนักงาน
            </h1>
            <p class="text-sm text-gray-400">
                ดูรายการพนักงาน เพิ่ม แก้ไข หรือลบข้อมูลได้จากหน้านี้
            </p>
        </div>

        <a href="{{ route('backend.employees.create') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg
                  bg-gradient-to-r from-orange-500 to-orange-600 text-white
                  hover:from-orange-400 hover:to-orange-500 shadow-md shadow-orange-900/40
                  focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-neutral-900">
            + เพิ่มพนักงาน
        </a>
    </div>

    @if (session('success'))
        <div class="p-3 rounded-lg border border-green-500/40 bg-green-500/10 text-sm text-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-200">
    <thead class="bg-neutral-900/90 text-xs uppercase text-gray-400 border-b border-orange-500/30">
        <tr>
            <th class="px-4 py-3">ลำดับ</th>
            <th class="px-4 py-3">ชื่อพนักงาน</th>
            <th class="px-4 py-3">อีเมล</th>
            <th class="px-4 py-3">ชื่อผู้ใช้</th>
            <th class="px-4 py-3">เบอร์โทร</th>
            <th class="px-4 py-3">สถานะ</th>
            <th class="px-4 py-3 text-right">จัดการ</th>
        </tr>
    </thead>

    <tbody>
    @forelse ($employees as $employee)
        <tr class="border-t border-neutral-800 hover:bg-neutral-800/70">

            {{-- ⭐ ลำดับ (แทน emp_id) --}}
            <td class="px-4 py-3 text-gray-400">
                {{ $loop->iteration }}
            </td>

            <td class="px-4 py-3 font-medium text-white">
                {{ $employee->name }}
            </td>

            <td class="px-4 py-3 text-gray-300">
                {{ $employee->email }}
            </td>

            <td class="px-4 py-3 text-gray-300">
                {{ $employee->username }}
            </td>

            <td class="px-4 py-3 text-gray-300">
                {{ $employee->phone }}
            </td>

            {{-- ⭐ สถานะ: 0 = ใช้งาน , 1 = ออก --}}
            <td class="px-4 py-3">
                @php
                    $isActive = (int)$employee->status === 0;  // ← แก้ตรงนี้ให้ถูกกับตาราง
                    $badgeClass = $isActive
                        ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40'
                        : 'bg-gray-500/20 text-gray-300 border-gray-500/40';
                @endphp

                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[0.7rem] border {{ $badgeClass }}">
                    {{ $isActive ? 'ใช้งานอยู่' : 'ปิดใช้งาน' }}
                </span>
            </td>

            <td class="px-4 py-3 text-right space-x-2">
                <a href="{{ route('backend.employees.edit', $employee->emp_id) }}"
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg
                          bg-amber-500/20 text-amber-200 border border-amber-500/40
                          hover:bg-amber-500/30">
                    แก้ไข
                </a>

                <form action="{{ route('backend.employees.destroy', $employee->emp_id) }}"
                      method="POST"
                      class="inline-block"
                      onsubmit="return confirm('ยืนยันการลบพนักงาน {{ $employee->name }} ใช่หรือไม่?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg
                                   bg-red-500/20 text-red-200 border border-red-500/40
                                   hover:bg-red-500/30">
                        ลบ
                    </button>
                </form>
            </td>
        </tr>

    @empty
        <tr>
            <td colspan="7" class="px-4 py-6 text-center text-gray-400">
                ยังไม่มีข้อมูลพนักงานในระบบ
            </td>
        </tr>
    @endforelse
    </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

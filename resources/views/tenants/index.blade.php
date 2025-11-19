@extends('layouts.app')
 
@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">
                จัดการข้อมูลผู้เช่า
            </h1>
            <p class="text-sm text-gray-400">
                ดูรายการผู้เช่า เพิ่ม แก้ไข หรือลบข้อมูลได้จากหน้านี้
            </p>
        </div>

        <a href="{{ route('tenants.create') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg
                  bg-gradient-to-r from-orange-500 to-orange-600 text-white
                  hover:from-orange-400 hover:to-orange-500 shadow-md shadow-orange-900/40
                  focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-neutral-900">
            + เพิ่มผู้เช่า
        </a>
    </div>

    @if (session('success'))
        <div class="p-3 rounded-lg border border-green-500/40 bg-green-500/10 text-sm text-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 overflow-hidden">
        <table class="min-w-full text-sm text-left text-gray-200">
            <thead class="bg-neutral-900/90 text-xs uppercase text-gray-400 border-b border-orange-500/30">
                <tr>
                    <th class="px-4 py-3 text-left">รูป</th>
                    <th class="px-4 py-3 ">ชื่อผู้เช่า</th>
                    <th class="px-4 py-3 ">เบอร์โทร</th>
                    <th class="px-4 py-3 ">บัตรประชาชน</th>
                    <th class="px-4 py-3 ">ชื่อผู้ใช้</th>
                    <th class="px-4 py-3 text-center">สถานะ</th>
                    <th class="px-4 py-3 text-center">จัดการ</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($tenants as $tenant)
                    <tr    class="w-12 h-12 overflow-hidden  rounded-full border border-gray-600">
                        <td class="px-4 py-3 ">
                            @if ($tenant->avatar_path)
                                <img src="{{ asset($tenant->avatar_path) }}"
                                     alt=""
                                     class="w-16 h-16 rounded-lg border border-gray-600"
                                      style="max-width: 48px; max-height: 48px;">
                            @else
                                <div class="w-12 h-12 rounded-full border border-dashed border-gray-600
                                            flex items-center justify-center text-[10px] text-gray-400">
                                    ไม่มีรูป
                                </div>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-left">
                            {{ $tenant->name }}
                        </td>
                        <td class="px-4 py-3 text-left">
                            {{ $tenant->phone }}
                        </td>
                        <td class="px-4 py-3 text-left">
                            {{ $tenant->id_card }}
                        </td>
                        <td class="px-4 py-3 text-left">
                            {{ $tenant->username }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            @if ($tenant->status == 0)
                                <span class="px-2 py-1 rounded-full bg-green-500/20 text-green-200 text-xs">
                                    ใช้งาน
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-red-500/20 text-red-200 text-xs">
                                    ไม่ใช้งาน
                                </span>
                            @endif
                        </td>

                        {{-- ปุ่มดูรายละเอียด --}}
                        <td class="px-2 py-3 text-center">
                            <a href="{{ route('tenants.show', $tenant->tenant_id) }}"
                               class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium
                                      bg-neutral-700 hover:bg-neutral-600 text-gray-100 border border-neutral-600">
                                ดูรายละเอียด
                            </a>
                      

                        {{-- ปุ่มแก้ไข / ลบ --}}
                   
                            <a href="{{ route('tenants.edit', $tenant->tenant_id) }}"
                               class="inline-flex  px-3 py-1.5 text-xs font-medium rounded-lg
                                      bg-amber-500/20 text-amber-200 border border-amber-500/40
                                      hover:bg-amber-500/30">
                                แก้ไข
                            </a>

                            <form action="{{ route('tenants.destroy', $tenant->tenant_id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg
                                               bg-red-500/20 text-red-200 border border-red-500/40
                                               hover:bg-red-500/30"
                                        onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบผู้เช่านี้?')">
                                    ลบ
                                </button>
                            </form>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
@endsection

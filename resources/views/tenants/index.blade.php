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

        <a href="{{ route('backend.tenants.create') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg
                  bg-gradient-to-r from-orange-500 to-orange-600 text-white
                  hover:from-orange-400 hover:to-orange-500 shadow-md shadow-orange-900/40
                  focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-neutral-900">
            + เพิ่มผู้เช่า
        </a>
    </div>

    @if (session('success'))
        <div class="p-3 rounded-lg border border-green-500/40 bg-green-500/10 text-sm text-green-200 flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="p-3 rounded-lg border border-red-500/40 bg-red-500/10 text-sm text-red-200 flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- ฟอร์มค้นหาและกรอง --}}
    <div class="bg-neutral-900/60 border border-neutral-700 rounded-xl p-4">
        <form method="GET" action="{{ route('backend.tenants.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-300 mb-1">ค้นหาชื่อผู้เช่า</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="พิมพ์ชื่อผู้เช่า..."
                       class="w-full px-3 py-2 rounded-lg bg-neutral-900 border border-gray-600 text-gray-100
                              focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div class="w-full md:w-1/3">
                <label class="block text-sm font-medium text-gray-300 mb-1">กรองสถานะ</label>
                <select name="status"
                        class="w-full px-3 py-2 rounded-lg bg-neutral-900 border border-gray-600 text-gray-100
                               focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">ทั้งหมด</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>เช่าอยู่</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>ยกเลิก</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-orange-500 text-white hover:bg-orange-600
                               focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <i class="fas fa-search mr-1"></i> ค้นหา
                </button>
                <a href="{{ route('backend.tenants.index') }}"
                   class="px-4 py-2 rounded-lg bg-neutral-700 text-gray-200 hover:bg-neutral-600
                          focus:outline-none focus:ring-2 focus:ring-gray-500">
                    <i class="fas fa-redo mr-1"></i> รีเซ็ต
                </a>
            </div>
        </form>
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 overflow-hidden">
        <div class="overflow-x-auto">
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
                    <tr class="border-b border-neutral-800 hover:bg-neutral-800/50 transition-colors">
                        <td class="px-4 py-3">
                            @if ($tenant->avatar_path)
                                <img src="{{ asset($tenant->avatar_path) }}"
                                     alt=""
                                     class="w-10 h-10 rounded-full object-cover border border-gray-600">
                            @else
                                <div class="w-10 h-10 rounded-full border border-dashed border-gray-600
                                            flex items-center justify-center text-[10px] text-gray-400 bg-neutral-800">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </td>

                        <td class="px-4 py-3 font-medium text-white">
                            {{ $tenant->name }}
                        </td>
                        <td class="px-4 py-3 text-gray-300">
                            {{ $tenant->phone }}
                        </td>
                        <td class="px-4 py-3 text-gray-300">
                            <div class="flex items-center gap-2">
                                <span class="id-card-text" style="filter: blur(5px); transition: filter 0.3s;">{{ $tenant->id_card }}</span>
                                <button onclick="toggleIdCard(this)" class="id-card-toggle p-1 text-gray-400 hover:text-white transition-colors" title="แสดง/ซ่อน">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-300">
                            {{ $tenant->username }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            @if ($tenant->status == 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 mr-1.5"></span>
                                    ใช้งาน
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400 mr-1.5"></span>
                                    ไม่ใช้งาน
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('backend.tenants.show', $tenant->tenant_id) }}"
                                   class="p-1.5 rounded-lg bg-neutral-700 hover:bg-neutral-600 text-gray-300 hover:text-white transition-colors"
                                   title="ดูรายละเอียด">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('backend.tenants.edit', $tenant->tenant_id) }}"
                                   class="p-1.5 rounded-lg bg-amber-500/10 hover:bg-amber-500/20 text-amber-500 hover:text-amber-400 transition-colors"
                                   title="แก้ไข">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile Card View --}}
    <div class="grid grid-cols-1 gap-4 md:hidden">
        @foreach ($tenants as $tenant)
        <div class="bg-neutral-900/90 border border-neutral-800 rounded-xl p-4 shadow-lg relative overflow-hidden">
            {{-- Status Strip --}}
            <div class="absolute top-0 left-0 w-1 h-full {{ $tenant->status == 0 ? 'bg-green-500' : 'bg-red-500' }}"></div>
            
            <div class="flex items-start justify-between mb-3 pl-2">
                <div class="flex items-center gap-3">
                    @if ($tenant->avatar_path)
                        <img src="{{ asset($tenant->avatar_path) }}" class="w-12 h-12 rounded-full object-cover border-2 border-neutral-700 shadow-sm">
                    @else
                        <div class="w-12 h-12 rounded-full bg-neutral-800 border-2 border-neutral-700 flex items-center justify-center text-gray-400">
                            <i class="fas fa-user text-lg"></i>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-white font-semibold text-base">{{ $tenant->name }}</h3>
                        <p class="text-xs text-gray-400">ID: {{ $tenant->username }}</p>
                    </div>
                </div>
                <div>
                    @if ($tenant->status == 0)
                        <span class="px-2 py-1 rounded text-[10px] font-bold bg-green-500/20 text-green-400 border border-green-500/30">
                            ACTIVE
                        </span>
                    @else
                        <span class="px-2 py-1 rounded text-[10px] font-bold bg-red-500/20 text-red-400 border border-red-500/30">
                            INACTIVE
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 mb-4 pl-2 text-sm">
                <div class="bg-neutral-800/50 p-2 rounded border border-neutral-800">
                    <p class="text-[10px] text-gray-500 mb-0.5">เบอร์โทร</p>
                    <p class="text-gray-200 font-medium">{{ $tenant->phone }}</p>
                </div>
                <div class="bg-neutral-800/50 p-2 rounded border border-neutral-800">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-[10px] text-gray-500 mb-0.5">บัตรประชาชน</p>
                            <p class="id-card-text text-gray-200 font-medium truncate" style="filter: blur(4px); transition: filter 0.3s;">{{ $tenant->id_card }}</p>
                        </div>
                        <button onclick="toggleIdCard(this)" class="id-card-toggle p-1 text-gray-400 hover:text-white transition-colors ml-1" title="แสดง/ซ่อน">
                            <i class="fas fa-eye-slash text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 pl-2">
                <a href="{{ route('backend.tenants.show', $tenant->tenant_id) }}" 
                   class="flex-1 py-2 rounded-lg bg-neutral-700 text-white text-xs font-medium text-center hover:bg-neutral-600 transition-colors border border-neutral-600">
                    ดูข้อมูล
                </a>
                <a href="{{ route('backend.tenants.edit', $tenant->tenant_id) }}" 
                   class="flex-1 py-2 rounded-lg bg-amber-500/10 text-amber-500 text-xs font-medium text-center hover:bg-amber-500/20 transition-colors border border-amber-500/30">
                    แก้ไข
                </a>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $tenants->appends(request()->query())->links() }}
    </div>
</div>

<script>
function toggleIdCard(button) {
    const container = button.closest('td, .bg-neutral-800\/50');
    const idCardText = container.querySelector('.id-card-text');
    const icon = button.querySelector('i');
    
    if (idCardText.style.filter === 'none' || idCardText.style.filter === '') {
        // Blur it
        idCardText.style.filter = 'blur(5px)';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        // Show it
        idCardText.style.filter = 'none';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection

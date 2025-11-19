<!-- Top Navigation Bar -->
<nav class="fixed top-0 z-50 w-full bg-neutral-800 border-b border-neutral-700">
    <div class="px-4 py-3">
        <div class="flex items-center justify-between">
            <!-- Brand -->
            <div class="flex items-center gap-2">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-orange-500 to-orange-600 text-white text-lg font-bold shadow-lg">
                    พ
                </span>
                <span class="text-xl font-bold text-white">พีระพลเฮาส์</span>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}" 
                   class="flex items-center gap-2 px-4 py-2 rounded-lg {{ request()->routeIs('home') ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }} transition-colors">
                    <i class="fas fa-home"></i>
                    <span>หน้าหลัก</span>
                </a>
                <a href="{{ route('lease.show') }}" 
                   class="flex items-center gap-2 px-4 py-2 rounded-lg {{ request()->routeIs('lease.*') ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }} transition-colors">
                    <i class="fas fa-file-contract"></i>
                    <span>สัญญาเช่า</span>
                </a>
                <a href="{{ route('invoices') }}" 
                   class="flex items-center gap-2 px-4 py-2 rounded-lg {{ request()->routeIs('invoices*') ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }} transition-colors">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>ใบแจ้งหนี้</span>
                </a>
                <a href="{{ route('payments') }}" 
                   class="flex items-center gap-2 px-4 py-2 rounded-lg {{ request()->routeIs('payments*') ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }} transition-colors">
                    <i class="fas fa-history"></i>
                    <span>ประวัติการชำระ</span>
                </a>
                {{-- <a href="{{ route('profile') }}" 
                   class="flex items-center gap-2 px-4 py-2 rounded-lg {{ request()->routeIs('profile*') ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }} transition-colors">
                    <i class="fas fa-user-circle"></i>
                    <span>โปรไฟล์</span>
                </a> --}}
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" type="button" class="md:hidden inline-flex items-center p-2 text-sm text-neutral-400 rounded-lg hover:bg-neutral-700">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"></path>
                </svg>
            </button>
            
            <!-- Right side menu -->
            <div class="hidden md:flex items-center gap-3">
                @php
                    $tenantSession = session('auth_tenant');
                @endphp
                <a href="{{ route('profile') }}" class="flex items-center gap-3 hover:bg-neutral-700/50 rounded-lg px-3 py-2 transition-colors">
                    <div class="text-right">
                        <p class="text-sm font-medium text-white">
                            {{ $tenantSession['name'] ?? 'ผู้เช่า' }}
                        </p>
                        <p class="text-xs text-neutral-400">ผู้เช่า</p>
                    </div>
                    @if(!empty($tenantSession['avatar_path']) && file_exists(public_path($tenantSession['avatar_path'])))
                        <img src="{{ asset($tenantSession['avatar_path']) }}"
                             alt="{{ $tenantSession['name'] ?? 'Tenant' }}"
                             class="h-10 w-10 rounded-full object-cover shadow-md ring-2 ring-orange-500/40">
                    @else
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 text-white font-semibold">
                            {{ mb_strtoupper(mb_substr($tenantSession['name'] ?? 'ผ', 0, 1, 'UTF-8'), 'UTF-8') }}
                        </div>
                    @endif
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-neutral-300 rounded-lg hover:bg-neutral-700 hover:text-white transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>ออกจากระบบ</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div id="mobile-menu" class="hidden md:hidden border-t border-neutral-700">
        <div class="px-2 py-3 space-y-1">
            <a href="{{ route('home') }}" 
               class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('home') ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700' }} transition-colors">
                <i class="fas fa-home w-5"></i>
                <span>หน้าหลัก</span>
            </a>
            <a href="{{ route('lease.show') }}" 
               class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('lease.*') ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700' }} transition-colors">
                <i class="fas fa-file-contract w-5"></i>
                <span>สัญญาเช่า</span>
            </a>
            <a href="{{ route('invoices') }}" 
               class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('invoices*') ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700' }} transition-colors">
                <i class="fas fa-file-invoice-dollar w-5"></i>
                <span>ใบแจ้งหนี้</span>
            </a>
            <a href="{{ route('payments') }}" 
               class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('payments*') ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700' }} transition-colors">
                <i class="fas fa-history w-5"></i>
                <span>ประวัติการชำระ</span>
            </a>
            <a href="{{ route('profile') }}" 
               class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('profile*') ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700' }} transition-colors">
                <i class="fas fa-user-circle w-5"></i>
                <span>โปรไฟล์</span>
            </a>
            <div class="border-t border-neutral-700 pt-2 mt-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg text-neutral-300 hover:bg-neutral-700 transition-colors">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>ออกจากระบบ</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
// Mobile menu toggle
document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
});
</script>

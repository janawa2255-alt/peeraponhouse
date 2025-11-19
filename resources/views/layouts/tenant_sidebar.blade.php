<!-- Tenant Sidebar -->
<aside id="tenant-sidebar"
    class="fixed top-16 left-0 z-40 w-64 h-[calc(100vh-4rem)] transition-transform -translate-x-full sm:translate-x-0 bg-neutral-800 border-r border-neutral-700"
    aria-label="Sidebar">
    <div class="h-full px-3 py-6 overflow-y-auto">
        <!-- Logo (hidden on mobile, shown on desktop) -->
        <div class="hidden sm:flex items-center ps-2.5 mb-6">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-md bg-orange-600 text-white font-bold shadow">PH</span>
            <span class="sidebar-text ms-3 text-lg font-semibold whitespace-nowrap text-white">{{ config('app.name', 'Peerapon House') }}</span>
        </div>

        <!-- Navigation Menu -->
        <ul class="space-y-2 font-medium">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('home') }}"
                    class="flex items-center p-3 rounded-lg {{ request()->routeIs('home') ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }} transition-colors group">
                    <i class="fas fa-home w-5 text-center {{ request()->routeIs('home') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}"></i>
                    <span class="sidebar-text ms-3">หน้าแรก</span>
                </a>
            </li>

            <!-- สัญญาเช่า -->
            <li>
                <a href="{{ route('lease.show') }}"
                    class="flex items-center p-3 rounded-lg {{ request()->routeIs('lease.*') ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }} transition-colors group">
                    <i class="fas fa-file-contract w-5 text-center {{ request()->routeIs('lease.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}"></i>
                    <span class="sidebar-text ms-3">สัญญาเช่า</span>
                </a>
            </li>

            <!-- ใบแจ้งหนี้ -->
            <li>
                <a href="{{ route('invoices') }}"
                    class="flex items-center p-3 rounded-lg {{ request()->routeIs('invoices*') ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }} transition-colors group">
                    <i class="fas fa-file-invoice-dollar w-5 text-center {{ request()->routeIs('invoices*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}"></i>
                    <span class="sidebar-text ms-3">ใบแจ้งหนี้</span>
                </a>
            </li>

            <!-- ประวัติการชำระเงิน -->
            <li>
                <a href="{{ route('payments') }}"
                    class="flex items-center p-3 rounded-lg {{ request()->routeIs('payments*') ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }} transition-colors group">
                    <i class="fas fa-history w-5 text-center {{ request()->routeIs('payments*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}"></i>
                    <span class="sidebar-text ms-3">ประวัติการชำระเงิน</span>
                </a>
            </li>

            <!-- โปรไฟล์ส่วนตัว -->
            <li>
                <a href="{{ route('profile') }}"
                    class="flex items-center p-3 rounded-lg {{ request()->routeIs('profile*') ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }} transition-colors group">
                    <i class="fas fa-user-circle w-5 text-center {{ request()->routeIs('profile*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}"></i>
                    <span class="sidebar-text ms-3">โปรไฟล์ส่วนตัว</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
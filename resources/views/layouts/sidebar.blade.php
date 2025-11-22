@php
// Centralized navigation structure for cleaner, scalable markup
$nav = [
'ทั่วไป' => [
[
'label' => 'หน้าแรก',
'href' => route('backend.dashboard'),
'patterns' => ['backend', 'dashboard'],
'badge' => null,
'icon' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
    <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
    <path
        d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
</svg>'
],
 [
        'label' => 'ข้อมูลห้องเช่า',
        'href' => route('backend.rooms.index'),
        'patterns' => ['rooms', 'rooms/*'],
        'badge' => null,
        'icon' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 20 20">
            <path d="M2 10.5 10 3l8 7.5V17a1 1 0 0 1-1 1h-4.5v-4.5h-5V18H3a1 1 0 0 1-1-1v-6.5Z" />
        </svg>',
    ],
    [
        'label' => 'ข้อมูลพนักงาน',
        'href' => route('backend.employees.index'),
        'patterns' => ['employees', 'employees/*'],
        'badge' => null,
        'icon' => '
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1v7z"/>
                <path d="M6.376 18.91a6 6 0 0 1 11.249.003"/>
                <circle cx="12" cy="11" r="4"/>
            </svg>
            ',
        ],
    [
        'label' => 'บัญชีธนาคาร',
        'href' => route('backend.banks.index'),
        'patterns' => ['banks', 'banks/*'],
        'badge' => null,
        'icon' => '
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/>
                <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/>
                <path d="M12 18V6"/>
            </svg> ',
        ],
    ],
'ข้อมูลผู้เช่า' => [

                [
                'label' => 'ข้อมูลผู้เช่า',
                'href' => route('backend.tenants.index'),
                'patterns' => ['products', 'products/*'],
                'badge' => null,
                            'icon' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                <path
                    d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
            </svg>'
        ],
    [
            'label' => 'ข้อมูลสัญญาเช่า',
            'href' => route('backend.leases.index'),
            'patterns' => ['leases', 'leases/*'],
            'badge' => null,
                            'icon' => '
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M8 2v4"/>
                    <path d="M12 2v4"/>
                    <path d="M16 2v4"/>
                    <rect x="4" y="4" width="16" height="18" rx="2"/>
                    <path d="M8 10h6"/>
                    <path d="M8 14h8"/>
                    <path d="M8 18h5"/>
                </svg>',
        ],
    [
            'label' => 'รายการยกเลิกสัญญาเช่า',
            'href' =>  route('backend.cancel_lease.index'),
            'patterns' => ['cancel_lease', 'cancel_lease/*'],
            'badge' => null,
            'icon' => '
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 22H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.706.706l3.588 3.588A2.4 2.4 0 0 1 20 8v5"/>
                    <path d="M14 2v5a1 1 0 0 0 1 1h5"/>
                    <path d="m15 17 5 5"/>
                    <path d="m20 17-5 5"/>
                </svg>',
        ],
    ],

'การชำระเงิน' => [
    [
                 'label' => 'รายการใบแจ้งหนี้',
            'href' => route('backend.invoices.index'),
            'patterns' => ['invoices', 'invoices/*'],
            'badge' => null,
            'icon' => '
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 2v20l3-2 3 2 3-2 3 2 3-2V2z"/>
                    <path d="M8 7h8"/>
                    <path d="M8 11h8"/>
                    <path d="M8 15h5"/>
                </svg>
            ',

        ],
    [
            'label' => 'ข้อมูลการแจ้งชำระเงิน',
            'href' => route('backend.payments.index'),
            'patterns' => ['payments', 'payments/*'],
            'badge' => '',
            'icon' => '
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 18H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5"/>
                    <path d="M18 12h.01"/>
                    <path d="M19 22v-6"/>
                    <path d="m22 19-3-3-3 3"/>
                    <path d="M6 12h.01"/>
                    <circle cx="12" cy="12" r="2"/>
                </svg>
                ',

        ],
    [
            'label' => 'จัดการประกาศ',
            'href' => route('backend.announcements.index'),
            'patterns' => ['announcements', 'announcements/*'],
            'badge' => '',
            'icon' => '
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22v-6"/>
                    <path d="M12 16V6"/>
                    <path d="M15.4 9.4a2 2 0 1 0 0-2.8 2 2 0 1 0 0 2.8Z"/>
                    <path d="M6 10v1a4 4 0 0 0 4 4h1"/>
                    <circle cx="10" cy="4" r="2"/>
                    <path d="M21 10V8a4 4 0 0 0-8 0v2"/>
                </svg>
                ',
        ],
  ],
'รายงาน' => [
    [
        'label' => 'รายงานรายได้',
        'href' => route('backend.reports.income'),
        'patterns' => ['reports/income', 'reports/income/*'],
        'badge' => null,
        'icon' => '
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        ',
    ],
    [
        'label' => 'รายงานยอดค้างชำระ',
        'href' => route('backend.reports.outstanding'),
        'patterns' => ['reports/outstanding', 'reports/outstanding/*'],
        'badge' => null,
        'icon' => '
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 6v6l4 2"/>
                <path d="M16.24 7.76l-1.5 1.5"/>
            </svg>
        ',
    ],
]    
// 'Account' => [
// [
//             'label' => 'Sign In',
//             'href' => '#',
//             'patterns' => ['login'],
//             'badge' => null,
//             'icon' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
//                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
//                     d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3" />
//             </svg>'
//         ],
//     [
//             'label' => 'Sign Up',
//             'href' => '#',
//             'patterns' => ['register'],
//             'badge' => null,
//             'icon' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
//                 <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z" />
//                 <path
//                     d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1.97 1.97 0 0 0-1.933-2H7v5a2 2 0 0 1-2 2H0v11a1.969 1.969 0 0 0 1.933 2h12.134A1.97 1.97 0 0 0 16 18v-3.093l-1.546 1.546c-.413.413-.94.695-1.513.81l-3.4.679a2.947 2.947 0 0 1-1.85-.227 2.96 2.96 0 0 1-1.635-3.257l.681-3.397Z" />
//                 <path
//                     d="M8.961 16a.93.93 0 0 0 .189-.019l3.4-.679a.961.961 0 0 0 .49-.263l6.118-6.117a2.884 2.884 0 0 0-4.079-4.078l-6.117 6.117a.96.96 0 0 0-.263.491l-.679 3.4A.961.961 0 0 0 8.961 16Zm7.477-9.8a.958.958 0 0 1 .68-.281.961.961 0 0 1 .682 1.644l-.315.315-1.36-1.36.313-.318Zm-5.911 5.911 4.236-4.236 1.359 1.359-4.236 4.237-1.7.339.341-1.699Z" />
//             </svg>'
//         ],
//     ],
];
@endphp

{{-- Mobile Top Navigation Bar --}}
<nav class="fixed top-0 z-30 w-full bg-neutral-900/90 backdrop-blur-md border-b border-orange-500/20 sm:hidden h-16 flex items-center px-4 justify-between shadow-lg shadow-black/20">
    <div class="flex items-center gap-3">
        <button type="button" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
            class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-white hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-orange-500/50 transition-colors">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path clip-rule="evenodd" fill-rule="evenodd"
                    d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z" />
            </svg>
        </button>
        <a href="{{ url('/backend') }}" class="flex items-center gap-2">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-orange-600 to-orange-500 text-white font-bold text-sm shadow-md">PH</span>
            <span class="text-lg font-bold text-white tracking-tight">Peerapon House</span>
        </a>
    </div>
</nav>

<!-- Mobile Backdrop Overlay -->
<div id="sidebar-backdrop" class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm hidden sm:hidden" aria-hidden="true"></div>

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-all duration-300 -translate-x-full sm:translate-x-0 will-change-transform group/sidebar"
    aria-label="Sidebar">
    <div
        class="flex flex-col h-full overflow-y-auto bg-neutral-900/90 backdrop-blur-xl text-gray-200 border-r border-orange-500/20 shadow-2xl shadow-black/40">
        <div class="flex items-center gap-2 px-3 pt-4 pb-3">
            <a href="{{ url('/backend') }}" class="flex items-center gap-3 group/logo">
                <span
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-orange-600 to-orange-500 text-white font-semibold shadow ring-1 ring-white/10">PH</span>
                <span class="sidebar-text text-lg font-semibold tracking-tight text-white">Peerapon House</span>
            </a>
            
            <!-- Mobile Close Button -->
            <button id="mobileCloseBtn" type="button" aria-label="Close sidebar"
                class="ml-auto inline-flex sm:hidden items-center justify-center h-10 w-10 rounded-lg text-gray-300 hover:text-white hover:bg-red-500/25 focus:outline-none focus:ring-2 focus:ring-red-500/40 transition-colors">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
            
            <!-- Desktop Collapse Button -->
            <button id="sidebarCollapseBtn" type="button" aria-label="Toggle sidebar" aria-expanded="true"
                class="ml-auto hidden sm:inline-flex items-center justify-center h-10 w-10 rounded-lg text-gray-300 hover:text-white hover:bg-orange-500/25 focus:outline-none focus:ring-2 focus:ring-orange-500/40 transition-colors">
                <svg data-icon-collapse class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 3H3" />
                    <path d="M21 9H3" />
                    <path d="M21 15H3" />
                    <path d="M21 21H3" />
                </svg>
                <svg data-icon-expand class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3h7v7H3z" />
                    <path d="M14 3h7v7h-7z" />
                    <path d="M14 14h7v7h-7z" />
                    <path d="M3 14h7v7H3z" />
                </svg>
            </button>
        </div>
        <div class="px-3 pb-2">
            <div class="h-px w-full bg-gradient-to-r from-transparent via-orange-500/30 to-transparent"></div>
        </div>

        @foreach($nav as $section => $items)
        <div class="px-4 mt-4 mb-2 text-[0.65rem] font-semibold tracking-wider uppercase text-gray-400/80 sidebar-text">
            {{ $section }}</div>
        <ul class="space-y-1 px-2 font-medium">
            @foreach($items as $item)
            @php $isActive = collect($item['patterns'])->contains(fn($p) => request()->is($p)); @endphp
            <li>
                <a href="{{ $item['href'] }}" aria-current="{{ $isActive ? 'page' : 'false' }}"
                    title="{{ $item['label'] }}"
                    class="group flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500/40
                            {{ $isActive ? 'bg-orange-500/20 text-white shadow-inner ring-1 ring-orange-500/40' : 'text-gray-300 hover:text-white hover:bg-orange-500/15 hover:shadow-md' }}">
                    <span class="shrink-0 {{ $isActive ? 'text-orange-300' : 'text-gray-400 group-hover:text-white' }}"
                        aria-hidden="true">{!! $item['icon'] !!}</span>
                    <span class="flex-1 sidebar-text truncate">{{ $item['label'] }}</span>
                    @if($item['badge'])
                    <span
                        class="sidebar-badge inline-flex items-center justify-center px-2 text-[0.6rem] font-medium text-orange-100 bg-orange-500/25 rounded-full ring-1 ring-orange-400/40">{{ $item['badge'] }}</span>
                    @endif
                    <!-- @if($isActive)
                    <span
                        class="absolute inset-y-0 left-0 w-1 rounded-r bg-gradient-to-b from-orange-400 to-orange-600"></span>
                    @endif -->
                </a>
            </li>
            @endforeach
        </ul>
        @endforeach

        <div class="mt-auto px-4 py-4 space-y-3 border-t border-orange-500/20">
            @php
                $owner = session('auth_owner');
            @endphp
            
            @if($owner)
            {{-- User Profile Card --}}
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-br from-neutral-800/80 to-neutral-800/60 backdrop-blur ring-1 ring-orange-500/20 shadow-lg">
                {{-- @if(!empty($owner['avatar_path']) && file_exists(public_path($owner['avatar_path'])))
                    
                    <img src="{{ asset($owner['avatar_path']) }}" 
                         alt="{{ $owner['name'] }}"
                         class="h-10 w-10 rounded-lg object-cover shadow-md ring-2 ring-orange-500/30">
                @else
                    
                    <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-orange-600 to-orange-500 text-white flex items-center justify-center text-sm font-bold shadow-md">
                        {{ mb_strtoupper(mb_substr($owner['name'] ?? 'A', 0, 1, 'UTF-8'), 'UTF-8') }}
                    </div>
                @endif --}}
                <div class="min-w-0 sidebar-text flex-1">
                    <p class="text-xs font-semibold text-white truncate">
                        {{ $owner['name'] ?? 'Admin' }}
                    </p>
                    <p class="text-[10px] text-gray-400 truncate">
                        {{ $owner['email'] ?? $owner['username'] ?? 'admin' }}
                    </p>
                </div>
            </div>

            {{-- Logout Button --}}
            <form action="{{ route('backend.logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium 
                           bg-red-600/20 text-red-200 border border-red-500/40 hover:bg-red-600/30 hover:border-red-500/60
                           active:scale-[.98] focus:outline-none focus:ring-2 focus:ring-red-500/50 transition-all">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    <span class="sidebar-text">ออกจากระบบ</span>
                </button>
            </form>
            @else
            {{-- Guest State --}}
            <div class="flex items-center gap-3 p-3 rounded-xl bg-neutral-800/60 backdrop-blur ring-1 ring-white/5">
                <div class="h-9 w-9 rounded-lg bg-gradient-to-br from-orange-600 to-orange-500 text-white flex items-center justify-center text-xs font-bold">
                    ?
                </div>
                <div class="min-w-0 sidebar-text">
                    <p class="text-xs font-medium text-white truncate">
                        Welcome
                    </p>
                    <p class="text-[10px] text-gray-400 truncate">
                        Please sign in
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</aside>

<script>
(function() {
    const aside = document.getElementById('logo-sidebar');
    const collapseBtn = document.getElementById('sidebarCollapseBtn');
    const mobileToggleBtn = document.querySelector('[data-drawer-toggle="logo-sidebar"]');
    const backdrop = document.getElementById('sidebar-backdrop');
    
    if (!aside) return;

    // Desktop collapse functionality
    const setCollapsed = (collapsed) => {
        collapseBtn.setAttribute('aria-expanded', (!collapsed).toString());
        aside.classList.toggle('collapsed', collapsed);
        // Toggle icon visibility
        collapseBtn.querySelector('[data-icon-collapse]')?.classList.toggle('hidden', collapsed);
        collapseBtn.querySelector('[data-icon-expand]')?.classList.toggle('hidden', !collapsed);
        // Text & badges
        aside.querySelectorAll('.sidebar-text').forEach(el => el.classList.toggle('hidden', collapsed));
        aside.querySelectorAll('.sidebar-badge').forEach(el => el.classList.toggle('hidden', collapsed));
    };

    collapseBtn?.addEventListener('click', () => {
        const nowCollapsed = !aside.classList.contains('collapsed');
        setCollapsed(nowCollapsed);
    });

    // Mobile menu toggle functionality
    const toggleMobileMenu = (show) => {
        if (show) {
            aside.classList.remove('-translate-x-full');
            backdrop?.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent body scroll
        } else {
            aside.classList.add('-translate-x-full');
            backdrop?.classList.add('hidden');
            document.body.style.overflow = ''; // Restore body scroll
        }
    };

    // Mobile toggle button click
    mobileToggleBtn?.addEventListener('click', () => {
        const isHidden = aside.classList.contains('-translate-x-full');
        toggleMobileMenu(isHidden);
    });

    // Mobile close button click
    const mobileCloseBtn = document.getElementById('mobileCloseBtn');
    mobileCloseBtn?.addEventListener('click', () => {
        toggleMobileMenu(false);
    });

    // Backdrop click to close
    backdrop?.addEventListener('click', () => {
        toggleMobileMenu(false);
    });

    // Close on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !aside.classList.contains('-translate-x-full')) {
            toggleMobileMenu(false);
        }
    });

    // Close menu when clicking a link on mobile
    if (window.innerWidth < 640) {
        aside.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                toggleMobileMenu(false);
            });
        });
    }

    // Swipe to close gesture
    let touchStartX = 0;
    let touchEndX = 0;

    aside.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    aside.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });

    function handleSwipe() {
        const swipeDistance = touchEndX - touchStartX;
        // Swipe left to close (at least 50px)
        if (swipeDistance < -50 && window.innerWidth < 640) {
            toggleMobileMenu(false);
        }
    }
})();
</script>

<style>
/* Collapsed state adjustments (minimal custom CSS to complement Tailwind) */
#logo-sidebar.collapsed {
    width: 5rem;
}

/* 80px */
#logo-sidebar.collapsed .group/logo span.inline-flex {
    transform: scale(.9);
}

#logo-sidebar.collapsed .group/sidebar ul {
    padding-left: .25rem;
    padding-right: .25rem;
}

#logo-sidebar.collapsed a {
    justify-content: center;
}

#logo-sidebar.collapsed a .sidebar-text,
#logo-sidebar.collapsed .sidebar-badge,
#logo-sidebar.collapsed .sidebar-text.section-title {
    display: none;
}

#logo-sidebar.collapsed button#sidebarCollapseBtn {
    background: transparent;
}

#logo-sidebar.collapsed [title] {
    position: relative;
}

#logo-sidebar.collapsed [title]:hover::after {
    content: attr(title);
    position: absolute;
    left: 100%;
    top: 50%;
    transform: translateY(-50%) translateX(.5rem);
    background: #fff;
    color: #000;
    font-size: .65rem;
    padding: .25rem .4rem;
    border-radius: .375rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, .4);
    white-space: nowrap;
}
</style>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;400;600&family=Prompt:wght@200;400;600&display=swap"
        rel="stylesheet">
    <style>
    body {
        font-family: 'Prompt', 'Kanit', sans-serif;
    }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    @media (min-width:640px){
        body.sidebar-collapsed .sidebar-expanded-margin { margin-left:5rem; }
        body:not(.sidebar-collapsed) .sidebar-expanded-margin { margin-left:16rem; }
    }
    #logo-sidebar { transition: width .25s ease, background-color .25s ease; }
    #logo-sidebar.collapsed { width:5rem; }
    #logo-sidebar .sidebar-text { transition: opacity .15s ease; }
    #logo-sidebar.collapsed .sidebar-text { opacity:0; pointer-events:none; }
    #logo-sidebar.collapsed .sidebar-badge { display:none; }
    </style>

    @stack('styles')
</head>

<body class="min-h-screen flex flex-col bg-neutral-950 text-gray-200 selection:bg-orange-500 selection:text-white overflow-x-hidden">

    {{-- Background Effects --}}
    <div class="fixed inset-0 z-[-1] pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_50%_0%,rgba(249,115,22,0.15),transparent_50%)]"></div>
        <div class="absolute bottom-0 right-0 w-full h-1/2 bg-[radial-gradient(circle_at_100%_100%,rgba(249,115,22,0.1),transparent_50%)]"></div>
    </div>

    <div class="flex sidebar-expanded-margin transition-all duration-300 ease-in-out relative z-0">
        @include('layouts.sidebar')
        
        <main class="flex-1 min-h-screen w-full p-4 sm:p-6 lg:p-8">
            <div class="mx-auto max-w-7xl animate-fade-in-up">
                @yield('content')
            </div>
        </main>
    </div>
    
    <footer class="sidebar-expanded-margin transition-all duration-300 ease-in-out border-t border-neutral-800/50 bg-neutral-900/30 backdrop-blur-sm mt-auto">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
             <p class="text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} Peerapon House. All rights reserved.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    @stack('scripts')
    <script>
    (function(){
        const aside = document.getElementById('logo-sidebar');
        if(!aside) return;
        const collapseBtn = document.getElementById('sidebarCollapseBtn');
        function setState(collapsed){
            document.body.classList.toggle('sidebar-collapsed', collapsed);
            aside.classList.toggle('collapsed', collapsed);
            if(collapseBtn){
                collapseBtn.setAttribute('aria-expanded', (!collapsed).toString());
                collapseBtn.querySelector('[data-icon-expand]').classList.toggle('hidden', !collapsed);
                collapseBtn.querySelector('[data-icon-collapse]').classList.toggle('hidden', collapsed);
            }
        }

        const saved = localStorage.getItem('sidebar-collapsed') === 'true';
        setState(saved);
        if(collapseBtn){
            collapseBtn.addEventListener('click', ()=>{
                const willCollapse = !document.body.classList.contains('sidebar-collapsed');
                setState(willCollapse);
                localStorage.setItem('sidebar-collapsed', willCollapse);
            });
        }
    })();
    </script>
</body>


</html>
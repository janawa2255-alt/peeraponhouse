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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

</head>

<body class="min-h-screen flex flex-col">


    <div class="p-4 sidebar-expanded-margin">
        @include('layouts.sidebar')
        <main class="flex-1 flex flex-col p-6 background-blur-xl">
            @yield('content')
        </main>
    </div>
    @include('layouts.footer')

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
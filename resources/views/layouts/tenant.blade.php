<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Peerapon House') }} - ระบบผู้เช่า</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;400;600&family=Prompt:wght@200;400;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Prompt', 'Kanit', sans-serif;
        }
    </style>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-neutral-900 via-neutral-800 to-neutral-900 text-neutral-200">
    
    @include('layouts.tenant_nav')

    <!-- Main Content -->
    <div class="pt-20">
        <main class="px-4 py-8 min-h-screen">
            <div class="mx-auto max-w-screen-2xl">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>

</html>

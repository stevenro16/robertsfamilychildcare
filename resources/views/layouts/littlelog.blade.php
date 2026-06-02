<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LittleLog — Roberts Family ChildCare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 min-h-screen flex flex-col overflow-hidden">

{{-- Top bar --}}
<header class="shrink-0 bg-white border-b border-slate-200 px-6 py-3 flex items-center gap-4">
    <a href="{{ route('portal.dashboard') }}"
       class="flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
        <x-icon name="chevron-left" class="w-4 h-4" />
        Dashboard
    </a>
    <div class="w-px h-5 bg-slate-200"></div>
    <div class="flex items-center gap-2">
        <x-icon name="clipboard-list" class="w-5 h-5 text-primary-500" />
        <span class="text-lg font-bold text-slate-800 tracking-tight">LittleLog</span>
    </div>
    <div class="ml-auto flex items-center gap-3 text-sm text-slate-500">
        <x-icon name="calendar" class="w-4 h-4" />
        {{ now()->format('l, F j, Y') }}
        <span class="font-semibold text-slate-700" id="ll-clock"></span>
    </div>
</header>

{{-- Page content fills remaining height --}}
<div class="flex-1 overflow-hidden">
    @yield('littlelog-content')
</div>

<style>[x-cloak]{display:none!important}</style>

<script>
    // Live clock
    function updateClock() {
        const el = document.getElementById('ll-clock');
        if (el) el.textContent = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }
    updateClock();
    setInterval(updateClock, 1000);
</script>

@stack('scripts')
</body>
</html>

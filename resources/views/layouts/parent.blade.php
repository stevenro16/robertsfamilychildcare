<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Parent Portal') — Roberts Family ChildCare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen">
<div class="flex min-h-screen" x-data="{ menuOpen: false }">

    {{-- Sidebar --}}
    <aside :class="menuOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col lg:translate-x-0 lg:static lg:inset-auto transition-transform duration-200">

        <div class="px-4 py-5 border-b border-slate-100">
            <img src="{{ asset('logo.png') }}" alt="Roberts Family ChildCare" class="h-12 w-auto">
        </div>

        <nav class="flex-1 px-2 py-4 space-y-0.5">
            @php
                $parent = auth('parent')->user();
                $nav = [
                    ['route' => 'parent.dashboard',       'label' => 'My Children',  'icon' => 'users'],
                    ['route' => 'parent.messages.index',  'label' => 'Messages',     'icon' => 'message-circle'],
                ];
            @endphp

            @foreach($nav as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                          {{ request()->routeIs(str_replace('.index', '.*', $item['route'])) ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                    <x-icon name="{{ $item['icon'] }}" class="w-4 h-4 shrink-0" />
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="px-4 py-4 border-t border-slate-100">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-sm">
                    {{ strtoupper(substr($parent->contact?->name ?? $parent->username, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-700 truncate">
                        {{ $parent->contact ? $parent->contact->name : $parent->username }}
                    </p>
                    <p class="text-xs text-slate-400">Parent Portal</p>
                </div>
            </div>
            <form method="POST" action="{{ route('parent.logout') }}">
                @csrf
                <button class="btn-ghost w-full text-xs justify-start gap-2">
                    <x-icon name="log-out" class="w-3.5 h-3.5" /> Sign Out
                </button>
            </form>
        </div>
    </aside>

    <div x-show="menuOpen" @click="menuOpen = false" class="fixed inset-0 bg-black/30 z-40 lg:hidden" x-cloak></div>

    <div class="flex-1 min-w-0 flex flex-col">
        <header class="lg:hidden flex items-center gap-3 px-4 py-3 bg-white border-b border-slate-200">
            <button @click="menuOpen = true" class="p-1.5 rounded-lg hover:bg-slate-100">
                <x-icon name="menu" class="w-5 h-5 text-slate-600" />
            </button>
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-8 w-auto">
        </header>

        <main class="flex-1 p-6 lg:p-8 overflow-auto">
            @yield('parent-content')
        </main>
    </div>
</div>

<style>[x-cloak]{display:none!important}</style>
@stack('scripts')
</body>
</html>

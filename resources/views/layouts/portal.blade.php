<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal') — Roberts Family ChildCare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#C0C0C0] min-h-screen">
<div class="portal-shell flex min-h-screen" x-data="portalShell()" x-init="init()">

    {{-- Sidebar --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col lg:translate-x-0 lg:static lg:inset-auto transition-transform duration-200"
    >
        {{-- Logo --}}
        <div class="px-4 pt-4 pb-2 border-b border-slate-100">
            <a href="{{ route('home') }}">
                <img src="{{ asset('logo.png') }}" alt="Roberts Family ChildCare" class="h-48 w-auto">
            </a>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto px-2 py-4 space-y-0.5">
            @php
                $isAdmin = auth()->user()->role === 'ADMIN';
                $nav = [
                    ['route' => 'portal.dashboard',       'label' => 'Dashboard',       'icon' => 'home'],
                    ['route' => 'portal.inquiries.index', 'label' => 'Inquiries',        'icon' => 'message-circle'],
                    ['route' => 'portal.children.index',  'label' => 'Children',         'icon' => 'users'],
                    ['route' => 'portal.staff.index',     'label' => 'Staff',            'icon' => 'star'],
                    ['route' => 'portal.gallery.index',   'label' => 'Gallery',          'icon' => 'image-plus'],
                    ['route' => 'portal.messages.index',  'label' => 'Messages',         'icon' => 'message-circle', 'badge' => $unread ?? 0],
                    ['route' => 'portal.testimonials.index','label' => 'Testimonials',   'icon' => 'quote'],
                    ['route' => 'portal.parent-portals.index','label' => 'Parent Portals','icon' => 'users'],
                ];
                if ($isAdmin) {
                    $nav[] = ['route' => 'portal.settings.index', 'label' => 'Settings', 'icon' => 'shield'];
                }
            @endphp

            @foreach($nav as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center justify-between gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                          {{ request()->routeIs(str_replace('.index', '.*', $item['route'])) ? 'bg-white text-primary-700 shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                    <span class="flex items-center gap-2.5">
                        <x-icon name="{{ $item['icon'] }}" class="w-4 h-4 shrink-0" />
                        {{ $item['label'] }}
                    </span>
                    @if(!empty($item['badge']) && $item['badge'] > 0)
                        <span class="bg-primary-500 text-white text-xs rounded-full px-1.5 py-0.5 leading-none">{{ $item['badge'] }}</span>
                    @endif
                </a>
            @endforeach
        </nav>

        {{-- User info --}}
        <div class="px-4 py-4 border-t border-slate-100">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-700 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('staff.logout') }}">
                @csrf
                <button class="btn-ghost w-full text-xs justify-start gap-2">
                    <x-icon name="log-out" class="w-3.5 h-3.5" /> Sign Out
                </button>
            </form>
        </div>
    </aside>

    {{-- Sidebar overlay (mobile) --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/30 z-40 lg:hidden" x-cloak></div>

    {{-- Main content --}}
    <div class="flex-1 min-w-0 flex flex-col">
        {{-- Top bar (mobile) --}}
        <header class="lg:hidden flex items-center gap-3 px-4 py-3 bg-white border-b border-slate-200">
            <button @click="sidebarOpen = true" class="p-1.5 rounded-lg hover:bg-slate-100">
                <x-icon name="menu" class="w-5 h-5 text-slate-600" />
            </button>
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-8 w-auto">
        </header>

        <main class="flex-1 p-6 lg:p-8 overflow-auto">
            @yield('portal-content')
        </main>
    </div>
</div>

<style>[x-cloak]{display:none!important}</style>

@push('scripts')
<script>
function portalShell() {
    return {
        sidebarOpen: false,
        init() {
            // Poll unread messages every 30s
            setInterval(async () => {
                try {
                    const r = await fetch('{{ route('portal.messages.unread') }}');
                    const d = await r.json();
                    // Update badge via Alpine reactivity (simple approach: reload nav)
                } catch {}
            }, 30000);
        }
    }
}
</script>
@endpush

@stack('scripts')
</body>
</html>

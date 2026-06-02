<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Roberts Family ChildCare')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-surface">

@php
    $loggedInStaff  = auth()->guard('web')->check();
    $loggedInParent = auth()->guard('parent')->check();
@endphp

{{-- Top-level wrapper owns all Alpine state so the modal can live outside the header --}}
<div x-data="{
    navOpen: false,
    scrolled: false,
    loginOpen: {{ session('login_error') ? 'true' : 'false' }},
    loginError: {{ session('login_error') ? json_encode(session('login_error')) : 'null' }},
    username: $persist('').as('rfcc_remembered_username'),
    remember: $persist(false).as('rfcc_remember_username'),
    loading: false
}" x-init="
    @if(old('username')) username = {{ json_encode(old('username')) }}; @endif
    window.addEventListener('scroll', () => scrolled = window.scrollY > 10)
">

    {{-- Navbar --}}
    <header
        :class="scrolled ? 'bg-white/95 backdrop-blur-sm shadow-sm' : 'bg-white/90 backdrop-blur-sm'"
        class="fixed top-0 inset-x-0 z-40 transition-all duration-200"
    >
        <div class="wide">
            <div class="flex items-center justify-between h-24">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center hover:opacity-85 transition-opacity">
                    <img src="{{ asset('logo.png') }}" alt="Roberts Family ChildCare" class="h-20 w-auto">
                </a>

                {{-- Desktop nav --}}
                <nav class="hidden lg:flex items-center gap-1">
                    @php
                        $navLinks = [
                            ['href' => route('home'),         'label' => 'Home',         'active' => request()->routeIs('home')],
                            ['href' => route('about'),        'label' => 'About',        'active' => request()->routeIs('about')],
                            ['href' => route('staff'),        'label' => 'Staff',        'active' => request()->routeIs('staff')],
                            ['href' => route('gallery'),      'label' => 'Gallery',      'active' => request()->routeIs('gallery')],
                            ['href' => route('testimonials'), 'label' => 'Testimonials', 'active' => request()->routeIs('testimonials')],
                        ];
                    @endphp
                    @foreach($navLinks as $link)
                        <a href="{{ $link['href'] }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $link['active'] ? 'text-primary-600 bg-primary-50' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </nav>

                {{-- CTA + Login (desktop) --}}
                <div class="hidden lg:flex items-center gap-2">
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg border border-accent text-accent hover:bg-accent hover:text-white text-sm font-medium transition-colors">
                        Inquire
                    </a>
                    @if($loggedInStaff)
                        <a href="{{ route('portal.dashboard') }}"
                           class="text-sm font-medium text-primary-600 hover:text-primary-800 px-3 py-1.5 rounded-lg hover:bg-primary-50 transition-colors flex items-center gap-1.5">
                            <x-icon name="layout" class="w-4 h-4" />
                            Admin Panel
                        </a>
                    @elseif($loggedInParent)
                        <a href="{{ route('parent.dashboard') }}"
                           class="text-sm font-medium text-primary-600 hover:text-primary-800 px-3 py-1.5 rounded-lg hover:bg-primary-50 transition-colors flex items-center gap-1.5">
                            <x-icon name="layout" class="w-4 h-4" />
                            Parent Portal
                        </a>
                    @else
                        <button @click="loginOpen = true"
                                class="text-sm font-medium text-slate-500 hover:text-slate-800 px-3 py-1.5 rounded-lg hover:bg-slate-100 transition-colors flex items-center gap-1.5">
                            <x-icon name="log-out" class="w-4 h-4 rotate-180" />
                            Login
                        </button>
                    @endif
                </div>

                {{-- Mobile hamburger --}}
                <button type="button" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 transition-colors"
                        @click="navOpen = !navOpen" aria-label="Toggle menu">
                    <x-icon name="menu" class="w-5 h-5" x-show="!navOpen" />
                    <x-icon name="x" class="w-5 h-5" x-show="navOpen" x-cloak />
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="navOpen" x-cloak class="lg:hidden bg-white border-t border-slate-100 px-4 py-3 space-y-1">
            @foreach($navLinks as $link)
                <a href="{{ $link['href'] }}" @click="navOpen = false"
                   class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $link['active'] ? 'text-primary-600 bg-primary-50' : 'text-slate-600 hover:bg-slate-50' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <a href="{{ route('contact') }}" @click="navOpen = false" class="btn-accent w-full mt-2 text-sm">Inquire Now</a>
            @if($loggedInStaff)
                <a href="{{ route('portal.dashboard') }}" @click="navOpen = false"
                   class="block w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-primary-600 hover:bg-primary-50">
                    Admin Panel
                </a>
            @elseif($loggedInParent)
                <a href="{{ route('parent.dashboard') }}" @click="navOpen = false"
                   class="block w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-primary-600 hover:bg-primary-50">
                    Parent Portal
                </a>
            @else
                <button @click="navOpen = false; loginOpen = true"
                        class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-slate-500 hover:bg-slate-50">
                    Login
                </button>
            @endif
        </div>
    </header>

    {{-- Page content --}}
    <main class="pt-24">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-800 text-slate-300">
        <div class="wide py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div>
                    <div class="mb-4">
                        <img src="{{ asset('logo.png') }}" alt="Roberts Family ChildCare" class="h-24 w-auto opacity-90">
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Safe, nurturing childcare for children 6 months to 10 years old in the Highland, CA area.
                    </p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-4">Contact</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-2">
                            <x-icon name="map-pin" class="w-4 h-4 mt-0.5 text-primary-400 shrink-0" />
                            <span>Highland, CA 92346</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <x-icon name="phone" class="w-4 h-4 text-primary-400 shrink-0" />
                            <a href="tel:9098097844" class="hover:text-white transition-colors">(909) 809-7844</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <x-icon name="clock" class="w-4 h-4 text-primary-400 shrink-0" />
                            <span>Mon – Fri, 7:00 AM – 5:00 PM</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach([
                            [route('about'),        'About Us'],
                            [route('programs'),     'Programs'],
                            [route('staff'),        'Our Staff'],
                            [route('location'),     'Location'],
                            [route('testimonials'), 'Testimonials'],
                            [route('contact'),      'Contact / Inquire'],
                        ] as [$href, $label])
                            <li><a href="{{ $href }}" class="hover:text-white transition-colors">{{ $label }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-slate-500">
                <p>© {{ date('Y') }} Roberts Family ChildCare. All rights reserved.</p>
                @if($loggedInStaff)
                    <a href="{{ route('portal.dashboard') }}" class="hover:text-slate-400 transition-colors">Admin Panel</a>
                @elseif($loggedInParent)
                    <a href="{{ route('parent.dashboard') }}" class="hover:text-slate-400 transition-colors">Parent Portal</a>
                @else
                    <button @click="loginOpen = true" class="hover:text-slate-400 transition-colors">Login</button>
                @endif
            </div>
        </div>
    </footer>

    {{-- Login modal — sibling of header so backdrop-filter on header can't trap it --}}
    <div x-show="loginOpen" x-cloak
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         @keydown.escape.window="loginOpen = false; loginError = null"
         @click.self="loginOpen = false; loginError = null">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden" @click.stop
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            {{-- Header --}}
            <div class="px-6 pt-6 pb-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-9 w-auto">
                    <h2 class="text-lg font-bold text-slate-800">Sign In</h2>
                </div>
                <button @click="loginOpen = false; loginError = null"
                        class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg hover:bg-slate-100">
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5">
                <div x-show="loginError" x-cloak
                     class="mb-4 flex items-center gap-2 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span x-text="loginError"></span>
                </div>

                <form method="POST" action="{{ route('unified.login') }}"
                      @submit="loading = true; loginError = null; if (!remember) username = ''">
                    @csrf

                    <div class="mb-4">
                        <label class="label" for="login-username">Username</label>
                        <input id="login-username" type="text" name="username"
                               x-model="username" required autofocus autocomplete="username"
                               class="input" placeholder="Email or username">
                    </div>

                    <div class="mb-4">
                        <label class="label" for="login-password">Password</label>
                        <input id="login-password" type="password" name="password"
                               required autocomplete="current-password"
                               class="input" placeholder="••••••••">
                    </div>

                    <div class="space-y-2 mb-6">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="remember-username" x-model="remember"
                                   class="rounded border-slate-300 text-primary-500 focus:ring-primary-400">
                            <label for="remember-username" class="text-sm text-slate-600 select-none cursor-pointer">
                                Remember my username
                            </label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="stay-logged-in" name="stay_logged_in" value="1"
                                   class="rounded border-slate-300 text-primary-500 focus:ring-primary-400">
                            <label for="stay-logged-in" class="text-sm text-slate-600 select-none cursor-pointer">
                                Stay logged in for a week
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full" :disabled="loading">
                        <span x-show="!loading">Sign In</span>
                        <span x-show="loading" x-cloak>Signing in…</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>{{-- /x-data wrapper --}}

<style>[x-cloak]{display:none!important}</style>
</body>
</html>

@extends('layouts.app')
@section('title', 'Staff Login — Roberts Family ChildCare')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 px-4">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <img src="{{ asset('logo.png') }}" alt="Roberts Family ChildCare" class="h-14 mx-auto mb-4">
            <h1 class="text-2xl font-bold text-slate-800">Staff Sign In</h1>
            <p class="text-slate-500 text-sm mt-1">Access the staff portal</p>
        </div>

        <div class="card p-6">
            <form method="POST" action="{{ route('staff.login') }}" x-data="{ loading: false }" @submit="loading = true">
                @csrf

                @if ($errors->any())
                    <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="mb-4">
                    <label class="label" for="email">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="input"
                        placeholder="staff@example.com"
                    >
                </div>

                <div class="mb-6">
                    <label class="label" for="password">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="input"
                        placeholder="••••••••"
                    >
                </div>

                <button type="submit" class="btn-primary w-full" :disabled="loading">
                    <span x-show="!loading">Sign In</span>
                    <span x-show="loading">Signing in…</span>
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-slate-400 mt-6">
            Parent?
            <a href="{{ route('parent.login') }}" class="text-primary-600 hover:underline">Sign in here</a>
        </p>
    </div>
</div>
@endsection

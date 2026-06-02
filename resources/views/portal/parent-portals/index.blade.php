@extends('layouts.portal')
@section('title', 'Parent Portals')

@section('portal-content')
<div class="w-full" x-data="{ createOpen: false, resetId: null, resetOpen: false }">
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2.5">
            <x-icon name="users" class="w-6 h-6 text-primary-500" />
            Parent Portals
        </h1>
        <button @click="createOpen = true" class="btn-primary gap-1.5">
            <x-icon name="plus" class="w-4 h-4" /> New Account
        </button>
    </div>
    <div class="h-0.5 bg-linear-to-r from-primary-400 to-transparent rounded-full mb-6"></div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($parents->isEmpty())
        <div class="card p-12 text-center text-slate-400">
            <x-icon name="users" class="w-10 h-10 mx-auto mb-3 text-slate-300" />
            <p class="font-medium">No parent accounts yet</p>
        </div>
    @else
        <div class="card divide-y divide-slate-100">
            @foreach($parents as $parent)
                <div class="flex items-center justify-between gap-4 p-4">
                    <div>
                        <div class="flex items-center gap-2 mb-0.5">
                            <span class="font-semibold text-slate-800">{{ $parent->username }}</span>
                            @if($parent->mustChangePassword)
                                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">Must Change PW</span>
                            @endif
                        </div>
                        @if($parent->contact)
                            <p class="text-sm text-slate-500">{{ $parent->contact->name }}</p>
                        @endif
                        <p class="text-xs text-slate-400">Created {{ $parent->createdAt->format('M j, Y') }}</p>
                    </div>
                    <button @click="resetId = '{{ $parent->id }}'; resetOpen = true"
                            class="btn-ghost text-xs text-amber-600 hover:text-amber-700">
                        Reset Password
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Create Account Modal --}}
<div x-show="createOpen" x-cloak
     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
     @click.self="createOpen = false">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md" @click.stop>
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Create Parent Account</h2>
        <form method="POST" action="{{ route('portal.parent-portals.store') }}">
            @csrf
            <div class="mb-3">
                <label class="label">Contact ID <span class="text-red-400">*</span></label>
                <input type="text" name="contactId" required class="input text-sm"
                       placeholder="Paste from Families page">
            </div>
            <div class="mb-3">
                <label class="label">Username <span class="text-red-400">*</span></label>
                <input type="text" name="username" required class="input text-sm">
            </div>
            <div class="mb-6">
                <label class="label">Temporary Password <span class="text-red-400">*</span></label>
                <input type="text" name="password" required class="input text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">Create</button>
                <button type="button" @click="createOpen = false" class="btn-ghost flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- Reset Password Modal --}}
<div x-show="resetOpen" x-cloak
     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
     @click.self="resetOpen = false">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm" @click.stop>
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Reset Password</h2>
        <form method="POST" :action="'/portal/parent-portals/' + resetId">
            @csrf
            @method('PATCH')
            <div class="mb-6">
                <label class="label">New Temporary Password</label>
                <input type="text" name="password" required class="input text-sm" placeholder="Min. 8 characters">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">Reset</button>
                <button type="button" @click="resetOpen = false" class="btn-ghost flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

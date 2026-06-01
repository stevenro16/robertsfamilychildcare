@extends('layouts.portal')
@section('title', 'Families')

@section('portal-content')
<div class="max-w-4xl" x-data="{ createOpen: false, search: '' }">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Families</h1>
        <button @click="createOpen = true" class="btn-primary gap-1.5">
            <x-icon name="plus" class="w-4 h-4" /> Add Contact
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <input type="text" x-model="search" placeholder="Search by name…" class="input text-sm max-w-xs">
    </div>

    @if($contacts->isEmpty())
        <div class="card p-12 text-center text-slate-400">
            <x-icon name="heart" class="w-10 h-10 mx-auto mb-3 text-slate-300" />
            <p class="font-medium">No contacts yet</p>
        </div>
    @else
        <div class="card divide-y divide-slate-100">
            @foreach($contacts as $contact)
                <a href="{{ route('portal.contacts.show', $contact->id) }}"
                   x-show="!search || '{{ strtolower($contact->name) }}'.includes(search.toLowerCase())"
                   class="flex items-center gap-4 p-4 hover:bg-slate-50 transition-colors">
                    <div class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-sm shrink-0">
                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-800">{{ $contact->name }}</p>
                        <p class="text-sm text-slate-500">
                            {{ $contact->email }}
                            @if($contact->email && $contact->phone) &middot; @endif
                            {{ $contact->phone }}
                        </p>
                    </div>
                    <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
                </a>
            @endforeach
        </div>
    @endif
</div>

{{-- Add Contact Modal --}}
<div x-show="createOpen" x-cloak
     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
     @click.self="createOpen = false">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md" @click.stop>
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Add Contact</h2>
        <form method="POST" action="{{ route('portal.contacts.store') }}">
            @csrf
            <div class="mb-3">
                <label class="label">Full Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" required class="input text-sm">
            </div>
            <div class="mb-3">
                <label class="label">Email</label>
                <input type="email" name="email" class="input text-sm">
            </div>
            <div class="mb-6">
                <label class="label">Phone</label>
                <input type="text" name="phone" class="input text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">Create</button>
                <button type="button" @click="createOpen = false" class="btn-ghost flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

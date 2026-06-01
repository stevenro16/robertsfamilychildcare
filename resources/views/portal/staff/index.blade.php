@extends('layouts.portal')
@section('title', 'Staff')

@section('portal-content')
<div class="max-w-4xl" x-data="{ createOpen: false }">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Staff</h1>
        <button @click="createOpen = true" class="btn-primary gap-1.5">
            <x-icon name="plus" class="w-4 h-4" /> Add Member
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($members->isEmpty())
        <div class="card p-12 text-center text-slate-400">
            <x-icon name="users" class="w-10 h-10 mx-auto mb-3 text-slate-300" />
            <p class="font-medium">No staff members yet</p>
        </div>
    @else
        <div class="card divide-y divide-slate-100">
            @foreach($members as $member)
                <a href="{{ route('portal.staff.show', $member->id) }}"
                   class="flex items-center gap-4 p-4 hover:bg-slate-50 transition-colors">
                    @if($member->photoUrl)
                        <img src="{{ $member->photoUrl }}" alt="{{ $member->name }}"
                             class="w-10 h-10 rounded-full object-cover shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-sm shrink-0">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1">
                        <p class="font-semibold text-slate-800">{{ $member->name }}</p>
                        <p class="text-sm text-slate-500">{{ $member->title }}</p>
                    </div>
                    <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
                </a>
            @endforeach
        </div>
    @endif
</div>

{{-- Add Member Modal --}}
<div x-show="createOpen" x-cloak
     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
     @click.self="createOpen = false">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md" @click.stop>
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Add Staff Member</h2>
        <form method="POST" action="{{ route('portal.staff.store') }}">
            @csrf
            <div class="mb-3">
                <label class="label">Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" required class="input text-sm" placeholder="Jane Smith">
            </div>
            <div class="mb-3">
                <label class="label">Title <span class="text-red-400">*</span></label>
                <input type="text" name="title" required class="input text-sm" placeholder="Lead Teacher">
            </div>
            <div class="mb-6">
                <label class="label">Bio <span class="text-slate-400 font-normal">(optional)</span></label>
                <textarea name="bio" rows="3" class="input text-sm resize-none"></textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">Add</button>
                <button type="button" @click="createOpen = false" class="btn-ghost flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

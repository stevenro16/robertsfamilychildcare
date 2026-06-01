@extends('layouts.parent')
@section('title', $child->firstName . ' — Contacts')

@section('parent-content')
<div class="max-w-2xl" x-data="{ addOpen: false }">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('parent.dashboard') }}" class="text-slate-400 hover:text-slate-600">
            <x-icon name="chevron-left" class="w-5 h-5" />
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800">{{ $child->firstName }}'s Emergency Contacts</h1>
        </div>
        <button @click="addOpen = true" class="btn-primary text-sm gap-1.5 ml-auto">
            <x-icon name="plus" class="w-4 h-4" /> Add
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($child->contacts->isEmpty())
        <div class="card p-8 text-center text-slate-400 text-sm">No contacts added yet.</div>
    @else
        <div class="card divide-y divide-slate-100">
            @foreach($child->contacts as $contact)
                <div class="p-4">
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="font-semibold text-slate-800">{{ $contact->name }}</span>
                        <span class="text-xs text-slate-500">{{ $contact->pivot->relationship }}</span>
                        @if($contact->pivot->isPrimary)
                            <span class="text-xs bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded">Primary</span>
                        @endif
                        @if($contact->pivot->addedByParent)
                            <span class="text-xs bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded">Added by you</span>
                        @endif
                    </div>
                    @if($contact->phone)
                        <p class="text-sm text-slate-500">{{ $contact->phone }}</p>
                    @endif
                    @if($contact->email)
                        <p class="text-sm text-slate-500">{{ $contact->email }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Add Contact Modal --}}
<div x-show="addOpen" x-cloak
     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
     @click.self="addOpen = false">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md" @click.stop>
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Add Emergency Contact</h2>
        <form method="POST" action="{{ route('parent.children.contacts.add', $child->id) }}">
            @csrf
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="label">First Name <span class="text-red-400">*</span></label>
                    <input type="text" name="firstName" required class="input text-sm">
                </div>
                <div>
                    <label class="label">Last Name <span class="text-red-400">*</span></label>
                    <input type="text" name="lastName" required class="input text-sm">
                </div>
            </div>
            <div class="mb-3">
                <label class="label">Phone</label>
                <input type="text" name="phone" class="input text-sm">
            </div>
            <div class="mb-3">
                <label class="label">Email</label>
                <input type="email" name="email" class="input text-sm">
            </div>
            <div class="mb-6">
                <label class="label">Relationship <span class="text-red-400">*</span></label>
                <select name="relationship" class="input text-sm">
                    <option>Parent</option>
                    <option>Guardian</option>
                    <option>Grandparent</option>
                    <option>Aunt/Uncle</option>
                    <option>Sibling</option>
                    <option>Friend</option>
                    <option>Other</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">Add Contact</button>
                <button type="button" @click="addOpen = false" class="btn-ghost flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

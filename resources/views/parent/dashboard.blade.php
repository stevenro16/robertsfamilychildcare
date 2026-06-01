@extends('layouts.parent')
@section('title', 'My Children')

@section('parent-content')
<div class="max-w-3xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">My Children</h1>
        <p class="text-slate-500 text-sm mt-1">
            Welcome, {{ $parent->contact ? $parent->contact->name : $parent->username }}
        </p>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($children->isEmpty())
        <div class="card p-12 text-center text-slate-400">
            <x-icon name="users" class="w-10 h-10 mx-auto mb-3 text-slate-300" />
            <p class="font-medium">No children linked to your account yet.</p>
            <p class="text-sm mt-1">Contact the daycare if you believe this is an error.</p>
        </div>
    @else
        <div class="grid gap-4">
            @foreach($children as $child)
                <div class="card p-5">
                    <div class="flex items-center gap-4 mb-4">
                        @if($child->photoUrl)
                            <img src="{{ $child->photoUrl }}" alt="{{ $child->firstName }}"
                                 class="w-14 h-14 rounded-full object-cover shrink-0">
                        @else
                            <div class="w-14 h-14 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-xl shrink-0">
                                {{ strtoupper(substr($child->firstName, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">{{ $child->firstName }} {{ $child->lastName }}</h2>
                            @if($child->dob)
                                <p class="text-sm text-slate-500">
                                    Age {{ \Carbon\Carbon::parse($child->dob)->age }}
                                    &middot; {{ \Carbon\Carbon::parse($child->dob)->format('M j, Y') }}
                                </p>
                            @endif
                            @if($child->checkedInAt)
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Checked In</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('parent.children.contacts', $child->id) }}"
                           class="btn-ghost text-sm flex-1 justify-center">Emergency Contacts</a>
                        <a href="{{ route('parent.children.documents', $child->id) }}"
                           class="btn-ghost text-sm flex-1 justify-center">Documents</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

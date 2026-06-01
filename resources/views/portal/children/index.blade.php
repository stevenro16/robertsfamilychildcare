@extends('layouts.portal')
@section('title', 'Children')

@section('portal-content')
<div class="max-w-5xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Children</h1>
        <a href="{{ route('portal.children.create') }}" class="btn-primary gap-1.5">
            <x-icon name="plus" class="w-4 h-4" /> Add Child
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($children->isEmpty())
        <div class="card p-12 text-center text-slate-400">
            <x-icon name="users" class="w-10 h-10 mx-auto mb-3 text-slate-300" />
            <p class="font-medium">No children enrolled yet</p>
            <a href="{{ route('portal.children.create') }}" class="btn-primary mt-4 inline-flex">Add First Child</a>
        </div>
    @else
        <div class="card divide-y divide-slate-100">
            @foreach($children as $child)
                <a href="{{ route('portal.children.show', $child->id) }}"
                   class="flex items-center gap-4 p-4 hover:bg-slate-50 transition-colors">
                    @if($child->photoUrl)
                        <img src="{{ $child->photoUrl }}" alt="{{ $child->firstName }}"
                             class="w-10 h-10 rounded-full object-cover shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-sm shrink-0">
                            {{ strtoupper(substr($child->firstName, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-800">{{ $child->firstName }} {{ $child->lastName }}</p>
                        <p class="text-sm text-slate-500">
                            @if($child->dob)
                                {{ \Carbon\Carbon::parse($child->dob)->age }} yrs
                                &middot; DOB {{ \Carbon\Carbon::parse($child->dob)->format('M j, Y') }}
                            @endif
                        </p>
                    </div>
                    @if($child->checkedInAt)
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">Checked In</span>
                    @endif
                    <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection

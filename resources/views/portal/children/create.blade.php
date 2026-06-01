@extends('layouts.portal')
@section('title', 'Add Child')

@section('portal-content')
<div class="max-w-xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('portal.children.index') }}" class="text-slate-400 hover:text-slate-600">
            <x-icon name="chevron-left" class="w-5 h-5" />
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Add Child</h1>
    </div>

    <div class="card p-6">
        <form method="POST" action="{{ route('portal.children.store') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="label">First Name <span class="text-red-400">*</span></label>
                    <input type="text" name="firstName" value="{{ old('firstName') }}" required class="input">
                    @error('firstName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Last Name <span class="text-red-400">*</span></label>
                    <input type="text" name="lastName" value="{{ old('lastName') }}" required class="input">
                    @error('lastName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="mb-6">
                <label class="label">Date of Birth <span class="text-red-400">*</span></label>
                <input type="date" name="dob" value="{{ old('dob') }}" required class="input">
                @error('dob') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">Create Child</button>
                <a href="{{ route('portal.children.index') }}" class="btn-ghost flex-1 text-center">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

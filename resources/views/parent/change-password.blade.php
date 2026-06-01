@extends('layouts.parent')
@section('title', 'Change Password')

@section('parent-content')
<div class="max-w-md">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Change Password</h1>
        <p class="text-slate-500 text-sm mt-1">Please set a new password for your account.</p>
    </div>

    <div class="card p-6">
        @if($errors->any())
            <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('parent.change-password.update') }}">
            @csrf
            <div class="mb-4">
                <label class="label">New Password <span class="text-red-400">*</span></label>
                <input type="password" name="password" required minlength="8" class="input">
            </div>
            <div class="mb-6">
                <label class="label">Confirm Password <span class="text-red-400">*</span></label>
                <input type="password" name="password_confirmation" required class="input">
            </div>
            <button type="submit" class="btn-primary w-full">Update Password</button>
        </form>
    </div>
</div>
@endsection

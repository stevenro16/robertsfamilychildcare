@extends('layouts.public')
@section('title', 'Leave a Review — Roberts Family ChildCare')

@section('content')
<div class="min-h-screen bg-slate-50 flex items-center justify-center py-16 px-4">
    <div class="w-full max-w-lg">
        <div class="text-center mb-8">
            <img src="{{ asset('logo.png') }}" alt="Roberts Family ChildCare" class="h-16 mx-auto mb-4">
            <h1 class="text-2xl font-bold text-slate-800">Leave a Review</h1>
        </div>

        @if($state === 'invalid')
            <div class="card p-8 text-center">
                <div class="text-4xl mb-4">🔒</div>
                <h2 class="text-xl font-bold text-slate-800 mb-2">Invalid Link</h2>
                <p class="text-slate-500">This review link is invalid or has expired. Please contact us for a new one.</p>
            </div>
        @elseif($state === 'used')
            <div class="card p-8 text-center">
                <div class="text-4xl mb-4">✅</div>
                <h2 class="text-xl font-bold text-slate-800 mb-2">Already Submitted</h2>
                <p class="text-slate-500">A review has already been submitted using this link. Thank you!</p>
            </div>
        @elseif($state === 'success')
            <div class="card p-8 text-center">
                <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center mx-auto mb-4">
                    <x-icon name="check" class="w-8 h-8 text-primary-600" />
                </div>
                <h2 class="text-xl font-bold text-slate-800 mb-2">Thank You!</h2>
                <p class="text-slate-500">Your review has been submitted and will appear after approval.</p>
            </div>
        @else
            {{-- Form --}}
            <div class="card p-8" x-data="{ rating: 0, hovering: 0 }">
                @if($errors->any())
                    <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                        Please correct the errors below.
                    </div>
                @endif

                <form method="POST" action="{{ route('testimonial.store', $token) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="label">Your Name <span class="text-red-400">*</span></label>
                        <input type="text" name="parentName" value="{{ old('parentName', $link->parentName ?? '') }}" required class="input">
                        @error('parentName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="label">Child's Age <span class="text-slate-400 font-normal">(optional)</span></label>
                        <input type="text" name="childAge" value="{{ old('childAge') }}" class="input" placeholder="e.g. 3 years old">
                    </div>

                    <div class="mb-4">
                        <label class="label">Rating <span class="text-red-400">*</span></label>
                        <div class="flex gap-2" @mouseleave="hovering = 0">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button"
                                    @click="rating = {{ $i }}"
                                    @mouseenter="hovering = {{ $i }}"
                                    :class="(hovering || rating) >= {{ $i }} ? 'text-amber-400' : 'text-slate-200'"
                                    class="text-3xl transition-colors focus:outline-none">
                                    ★
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" :value="rating">
                        @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="label">Your Review <span class="text-red-400">*</span></label>
                        <textarea name="content" rows="5" required class="input" placeholder="Share your experience with Roberts Family ChildCare…">{{ old('content') }}</textarea>
                        @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn-primary w-full">Submit Review</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

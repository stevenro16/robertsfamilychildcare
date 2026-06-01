@extends('layouts.portal')
@section('title', 'Testimonials')

@section('portal-content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Testimonials</h1>
        <a href="{{ route('portal.testimonials.links') }}" class="btn-ghost gap-1.5 text-sm">
            Review Links
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pending reviews --}}
    @if($pending->isNotEmpty())
        <div class="mb-8">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Pending Review</h2>
            <div class="space-y-3">
                @foreach($pending as $t)
                    <div class="card p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-slate-800">{{ $t->parentName }}</span>
                                    <span class="text-amber-400 text-sm">{{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}</span>
                                </div>
                                @if($t->childAge)
                                    <p class="text-xs text-slate-400 mb-2">Child age: {{ $t->childAge }}</p>
                                @endif
                                <p class="text-sm text-slate-700">{{ $t->content }}</p>
                                <p class="text-xs text-slate-400 mt-2">{{ $t->createdAt->format('M j, Y') }}</p>
                            </div>
                            <div class="flex flex-col gap-2 shrink-0">
                                <form method="POST" action="{{ route('portal.testimonials.update', $t->id) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="APPROVED">
                                    <button type="submit" class="btn-primary text-xs px-3 py-1.5 whitespace-nowrap">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('portal.testimonials.update', $t->id) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="REJECTED">
                                    <button type="submit" class="btn-ghost text-xs px-3 py-1.5 whitespace-nowrap text-red-500 hover:text-red-600">Reject</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Approved --}}
    <div class="mb-8">
        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Approved ({{ $approved->count() }})</h2>
        @if($approved->isEmpty())
            <p class="text-sm text-slate-400">None yet.</p>
        @else
            <div class="card divide-y divide-slate-100">
                @foreach($approved as $t)
                    <div class="flex items-start justify-between gap-4 p-4">
                        <div>
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="font-medium text-slate-800 text-sm">{{ $t->parentName }}</span>
                                <span class="text-amber-400 text-xs">{{ str_repeat('★', $t->rating) }}</span>
                            </div>
                            <p class="text-xs text-slate-500 line-clamp-2">{{ $t->content }}</p>
                        </div>
                        <form method="POST" action="{{ route('portal.testimonials.update', $t->id) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="REJECTED">
                            <button type="submit" class="text-xs text-slate-400 hover:text-red-500">Remove</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Rejected --}}
    @if($rejected->isNotEmpty())
        <div>
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Rejected ({{ $rejected->count() }})</h2>
            <div class="card divide-y divide-slate-100">
                @foreach($rejected as $t)
                    <div class="flex items-start justify-between gap-4 p-4">
                        <div>
                            <span class="font-medium text-slate-700 text-sm">{{ $t->parentName }}</span>
                            <p class="text-xs text-slate-400 line-clamp-1">{{ $t->content }}</p>
                        </div>
                        <form method="POST" action="{{ route('portal.testimonials.update', $t->id) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="APPROVED">
                            <button type="submit" class="text-xs text-primary-600 hover:underline">Approve</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

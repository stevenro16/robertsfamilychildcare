@extends('layouts.portal')
@section('title', 'Review Links')

@section('portal-content')
<div x-data="{ copied: null }">

    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2.5">
            <x-icon name="quote" class="w-6 h-6 text-primary-500" />
            Review Links
        </h1>
        <a href="{{ route('portal.testimonials.index') }}" class="btn-ghost text-sm gap-1.5">
            <x-icon name="arrow-left" class="w-4 h-4" /> Testimonials
        </a>
    </div>
    <div class="h-0.5 bg-linear-to-r from-primary-400 to-transparent rounded-full mb-6"></div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Create form --}}
    <div class="card p-6 mb-6">
        <h2 class="font-semibold text-slate-700 mb-1">Generate a Review Link</h2>
        <p class="text-xs text-slate-400 mb-4">Create a unique link to send to a parent. Copy it and share however you'd like — email, text, etc.</p>
        <form method="POST" action="{{ route('portal.testimonials.links.create') }}" class="flex items-end gap-3">
            @csrf
            <div class="flex-1">
                <label class="label">Parent Name <span class="text-red-400">*</span></label>
                <input type="text" name="parentName" required class="input text-sm"
                       placeholder="e.g. Jyll Roberts" value="{{ old('parentName') }}">
                @error('parentName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="btn-primary text-sm shrink-0">Generate Link</button>
        </form>
    </div>

    {{-- Links list --}}
    @if($links->isEmpty())
        <div class="card p-10 text-center">
            <x-icon name="link" class="w-8 h-8 text-slate-200 mx-auto mb-3" />
            <p class="text-sm font-medium text-slate-400">No review links yet</p>
            <p class="text-xs text-slate-300 mt-1">Generate one above to get started</p>
        </div>
    @else
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-semibold text-slate-700">All Links</h2>
                <span class="text-xs text-slate-400">{{ $links->count() }} {{ Str::plural('link', $links->count()) }}</span>
            </div>
            <ul class="divide-y divide-slate-50">
                @foreach($links as $link)
                    @php
                        $url = url('/testimonial/' . $link->token);
                        $used = $link->usedAt !== null;
                    @endphp
                    <li class="px-6 py-4">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                {{-- Name + badges --}}
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <span class="font-medium text-slate-800 text-sm">{{ $link->parentName }}</span>
                                    @if($used)
                                        @if($link->testimonial)
                                            @php $status = $link->testimonial->status; @endphp
                                            @if($status === 'APPROVED')
                                                <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-medium">Approved</span>
                                            @elseif($status === 'REJECTED')
                                                <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-medium">Rejected</span>
                                            @else
                                                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">Pending Review</span>
                                            @endif
                                        @else
                                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">Used</span>
                                        @endif
                                    @else
                                        <span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full font-medium">Pending</span>
                                    @endif
                                </div>

                                {{-- Meta --}}
                                <p class="text-xs text-slate-400 mb-2">
                                    Created {{ $link->createdAt->format('M j, Y') }}
                                    @if($link->createdBy) &middot; by {{ $link->createdBy->name }} @endif
                                    @if($used) &middot; Used {{ $link->usedAt->format('M j, Y') }} @endif
                                </p>

                                {{-- URL + copy --}}
                                <div class="flex items-center gap-2">
                                    <code class="text-xs bg-slate-50 border border-slate-200 px-2.5 py-1.5 rounded-lg text-slate-600 truncate max-w-sm font-mono">
                                        {{ $url }}
                                    </code>
                                    <button
                                        @click="
                                            navigator.clipboard.writeText('{{ $url }}');
                                            copied = '{{ $link->id }}';
                                            setTimeout(() => { if (copied === '{{ $link->id }}') copied = null; }, 2000);
                                        "
                                        :class="copied === '{{ $link->id }}'
                                            ? 'bg-emerald-50 border-emerald-200 text-emerald-700'
                                            : 'bg-white border-slate-200 text-slate-600 hover:border-primary-300 hover:text-primary-600'"
                                        class="shrink-0 flex items-center gap-1.5 border rounded-lg px-2.5 py-1.5 text-xs font-medium transition-colors">
                                        <template x-if="copied !== '{{ $link->id }}'">
                                            <span class="flex items-center gap-1.5">
                                                <x-icon name="copy" class="w-3.5 h-3.5" /> Copy
                                            </span>
                                        </template>
                                        <template x-if="copied === '{{ $link->id }}'">
                                            <span class="flex items-center gap-1.5">
                                                <x-icon name="check" class="w-3.5 h-3.5" /> Copied!
                                            </span>
                                        </template>
                                    </button>
                                </div>
                            </div>

                            {{-- If there's a pending testimonial, link to review it --}}
                            @if($used && $link->testimonial && $link->testimonial->status === 'PENDING')
                                <a href="{{ route('portal.testimonials.index') }}"
                                   class="shrink-0 btn-accent text-xs px-3 py-1.5">
                                    Review
                                </a>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

</div>
@endsection

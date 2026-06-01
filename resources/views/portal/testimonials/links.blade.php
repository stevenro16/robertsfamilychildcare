@extends('layouts.portal')
@section('title', 'Review Links')

@section('portal-content')
<div class="max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('portal.testimonials.index') }}" class="text-slate-400 hover:text-slate-600">
            <x-icon name="chevron-left" class="w-5 h-5" />
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Review Links</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Create form --}}
    <div class="card p-6 mb-6">
        <h2 class="font-semibold text-slate-700 mb-4">Send Review Request</h2>
        <form method="POST" action="{{ route('portal.testimonials.links.create') }}">
            @csrf
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="label">Parent Name <span class="text-red-400">*</span></label>
                    <input type="text" name="parentName" required class="input text-sm"
                           placeholder="Jane Smith">
                    @error('parentName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" required class="input text-sm"
                           placeholder="jane@example.com">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <button type="submit" class="btn-primary text-sm">Create Link</button>
        </form>
    </div>

    {{-- Links list --}}
    @if($links->isEmpty())
        <div class="card p-8 text-center text-slate-400 text-sm">No links created yet.</div>
    @else
        <div class="card divide-y divide-slate-100">
            @foreach($links as $link)
                <div class="p-4">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-medium text-slate-800 text-sm">{{ $link->parentName }}</span>
                        <span class="text-xs text-slate-400">{{ $link->createdAt->format('M j, Y') }}</span>
                    </div>
                    <p class="text-xs text-slate-500 mb-2">{{ $link->email }}</p>
                    @if($link->usedAt)
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Used {{ $link->usedAt->format('M j') }}</span>
                    @else
                        <div class="flex items-center gap-2">
                            <code class="text-xs bg-slate-100 px-2 py-1 rounded text-slate-600 truncate max-w-xs">
                                {{ url('/testimonial/' . $link->token) }}
                            </code>
                            <button onclick="navigator.clipboard.writeText('{{ url('/testimonial/' . $link->token) }}')"
                                    class="text-xs text-primary-600 hover:underline shrink-0">Copy</button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

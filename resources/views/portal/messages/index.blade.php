@extends('layouts.portal')
@section('title', 'Messages')

@section('portal-content')
<div class="w-full">
    <h1 class="text-2xl font-bold text-slate-800 mb-2 flex items-center gap-2.5">
        <x-icon name="message-circle" class="w-6 h-6 text-primary-500" />
        Messages
    </h1>
    <div class="h-0.5 bg-linear-to-r from-primary-400 to-transparent rounded-full mb-6"></div>

    @if($conversations->isEmpty())
        <div class="card p-12 text-center text-slate-400">
            <x-icon name="message-circle" class="w-10 h-10 mx-auto mb-3 text-slate-300" />
            <p class="font-medium">No conversations yet</p>
        </div>
    @else
        <div class="card divide-y divide-slate-100">
            @foreach($conversations as $conv)
                <a href="{{ route('portal.messages.conversation', $conv->parent->id) }}"
                   class="flex items-start gap-4 p-4 hover:bg-slate-50 transition-colors">
                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-sm shrink-0">
                        {{ strtoupper(substr($conv->parent->contact?->name ?? $conv->parent->username, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <span class="font-semibold text-slate-800">
                                {{ $conv->parent->contact ? $conv->parent->contact->name : $conv->parent->username }}
                            </span>
                            @if($conv->unread > 0)
                                <span class="bg-primary-500 text-white text-xs rounded-full px-1.5 py-0.5 leading-none font-medium">
                                    {{ $conv->unread }}
                                </span>
                            @endif
                        </div>
                        @if($conv->latest)
                            <p class="text-sm text-slate-500 truncate">
                                {{ $conv->latest->senderRole === 'STAFF' ? 'You: ' : '' }}{{ $conv->latest->content }}
                            </p>
                        @endif
                    </div>
                    <div class="text-xs text-slate-400 shrink-0 mt-0.5">
                        {{ $conv->latest?->createdAt->diffForHumans() }}
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection

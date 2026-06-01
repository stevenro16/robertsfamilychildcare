@extends('layouts.portal')
@section('title', 'Conversation')

@section('portal-content')
<div class="max-w-3xl flex flex-col h-full">
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-4">
        <a href="{{ route('portal.messages.index') }}" class="text-slate-400 hover:text-slate-600">
            <x-icon name="chevron-left" class="w-5 h-5" />
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800">
                {{ $parent->contact ? $parent->contact->name : $parent->username }}
            </h1>
            <p class="text-xs text-slate-400">@{{ $parent->username }}</p>
        </div>
    </div>

    {{-- Messages --}}
    <div class="card flex-1 flex flex-col overflow-hidden">
        <div class="flex-1 overflow-y-auto p-4 space-y-3" id="messages-container">
            @forelse($messages as $msg)
                <div class="flex {{ $msg->senderRole === 'STAFF' ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md xl:max-w-lg px-4 py-2.5 rounded-2xl text-sm
                                {{ $msg->senderRole === 'STAFF'
                                    ? 'bg-primary-500 text-white rounded-br-sm'
                                    : 'bg-slate-100 text-slate-800 rounded-bl-sm' }}">
                        <p class="whitespace-pre-wrap">{{ $msg->content }}</p>
                        <p class="text-xs mt-1 {{ $msg->senderRole === 'STAFF' ? 'text-primary-200' : 'text-slate-400' }}">
                            {{ $msg->createdAt->format('M j, g:i A') }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-center text-slate-400 text-sm py-8">No messages yet. Start the conversation below.</p>
            @endforelse
        </div>

        {{-- Send form --}}
        <div class="border-t border-slate-100 p-4">
            <form method="POST" action="{{ route('portal.messages.send', $parent->id) }}" class="flex gap-2">
                @csrf
                <textarea name="content" rows="2" required
                    class="input flex-1 resize-none text-sm"
                    placeholder="Type a message…"
                    @keydown.enter.prevent="if(!$event.shiftKey) $el.closest('form').submit()"></textarea>
                <button type="submit" class="btn-primary self-end gap-1.5">
                    <x-icon name="send" class="w-4 h-4" /> Send
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('messages-container');
    if (container) container.scrollTop = container.scrollHeight;
});
</script>
@endsection

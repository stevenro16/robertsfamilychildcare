@extends('layouts.parent')
@section('title', 'Messages')

@section('parent-content')
<div class="max-w-2xl flex flex-col">
    <h1 class="text-2xl font-bold text-slate-800 mb-4">Messages</h1>

    <div class="card flex flex-col overflow-hidden" style="min-height: 400px;">
        <div class="flex-1 overflow-y-auto p-4 space-y-3" id="messages-container">
            @forelse($messages as $msg)
                <div class="flex {{ $msg->senderRole === 'PARENT' ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md px-4 py-2.5 rounded-2xl text-sm
                                {{ $msg->senderRole === 'PARENT'
                                    ? 'bg-primary-500 text-white rounded-br-sm'
                                    : 'bg-slate-100 text-slate-800 rounded-bl-sm' }}">
                        <p class="whitespace-pre-wrap">{{ $msg->content }}</p>
                        <p class="text-xs mt-1 {{ $msg->senderRole === 'PARENT' ? 'text-primary-200' : 'text-slate-400' }}">
                            {{ $msg->createdAt->format('M j, g:i A') }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-center text-slate-400 text-sm py-8">No messages yet. Send one below!</p>
            @endforelse
        </div>

        <div class="border-t border-slate-100 p-4">
            <form method="POST" action="{{ route('parent.messages.send') }}" class="flex gap-2">
                @csrf
                <textarea name="content" rows="2" required
                    class="input flex-1 resize-none text-sm"
                    placeholder="Type a message…"></textarea>
                <button type="submit" class="btn-primary self-end gap-1.5">
                    <x-icon name="send" class="w-4 h-4" /> Send
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const c = document.getElementById('messages-container');
    if (c) c.scrollTop = c.scrollHeight;
});
</script>
@endsection

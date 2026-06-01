@extends('layouts.portal')
@section('title', $inquiry->name . ' — Inquiry')

@section('portal-content')
<div class="max-w-4xl" x-data="{ snoozeOpen: false, editOpen: false }">

    {{-- Back + header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('portal.inquiries.index') }}" class="text-slate-400 hover:text-slate-600">
            <x-icon name="chevron-left" class="w-5 h-5" />
        </a>
        <h1 class="text-2xl font-bold text-slate-800">{{ $inquiry->name }}</h1>
        <span class="text-xs px-2 py-0.5 rounded-full font-medium
            {{ match($inquiry->status) {
                'NEW'                       => 'bg-blue-100 text-blue-700',
                'LEFT_VOICEMAIL'            => 'bg-yellow-100 text-yellow-700',
                'LEFT_VOICEMAIL_2'          => 'bg-orange-100 text-orange-700',
                'LEFT_VOICEMAIL_FINAL'      => 'bg-red-100 text-red-700',
                'PROVIDED_PRICING_WAITING'  => 'bg-purple-100 text-purple-700',
                'FOLLOW_UP_WHEN_ROOM'       => 'bg-slate-100 text-slate-600',
                'COMPLETE'                  => 'bg-green-100 text-green-700',
                default                     => 'bg-slate-100 text-slate-600',
            } }}">
            {{ str_replace('_', ' ', $inquiry->status) }}
        </span>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main column --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Contact info card --}}
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-slate-700">Contact Information</h2>
                    <button @click="editOpen = true" class="btn-ghost text-xs gap-1.5">
                        <x-icon name="edit" class="w-3.5 h-3.5" /> Edit
                    </button>
                </div>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                    <div>
                        <dt class="text-slate-400 mb-0.5">Name</dt>
                        <dd class="font-medium text-slate-800">{{ $inquiry->name }}</dd>
                    </div>
                    @if($inquiry->email)
                    <div>
                        <dt class="text-slate-400 mb-0.5">Email</dt>
                        <dd><a href="mailto:{{ $inquiry->email }}" class="text-primary-600 hover:underline">{{ $inquiry->email }}</a></dd>
                    </div>
                    @endif
                    @if($inquiry->phone)
                    <div>
                        <dt class="text-slate-400 mb-0.5">Phone</dt>
                        <dd><a href="tel:{{ $inquiry->phone }}" class="text-primary-600 hover:underline">{{ $inquiry->phone }}</a></dd>
                    </div>
                    @endif
                    @if($inquiry->childDob)
                    <div>
                        <dt class="text-slate-400 mb-0.5">Child DOB</dt>
                        <dd class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($inquiry->childDob)->format('M j, Y') }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-slate-400 mb-0.5">Received</dt>
                        <dd class="text-slate-600">{{ $inquiry->createdAt->format('M j, Y g:i A') }}</dd>
                    </div>
                </dl>
                @if($inquiry->message)
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <p class="text-xs text-slate-400 mb-1">Message</p>
                        <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $inquiry->message }}</p>
                    </div>
                @endif
            </div>

            {{-- Notes --}}
            <div class="card p-6">
                <h2 class="font-semibold text-slate-700 mb-4">Notes</h2>

                @if($inquiry->notes->isEmpty())
                    <p class="text-sm text-slate-400 mb-4">No notes yet.</p>
                @else
                    <div class="space-y-3 mb-4">
                        @foreach($inquiry->notes as $note)
                            <div class="bg-slate-50 rounded-lg p-3">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-medium text-slate-600">{{ $note->employee?->name ?? 'Unknown' }}</span>
                                    <span class="text-xs text-slate-400">{{ $note->createdAt->format('M j, Y g:i A') }}</span>
                                </div>
                                <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $note->content }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('portal.inquiries.notes.add', $inquiry->id) }}">
                    @csrf
                    <textarea name="content" rows="3" required
                        class="input mb-2 resize-none"
                        placeholder="Add a note…"></textarea>
                    @error('content') <p class="text-red-500 text-xs mb-2">{{ $message }}</p> @enderror
                    <button type="submit" class="btn-primary text-sm">Add Note</button>
                </form>
            </div>

            {{-- Status history --}}
            @if($inquiry->statusHistory->isNotEmpty())
            <div class="card p-6">
                <h2 class="font-semibold text-slate-700 mb-4">Status History</h2>
                <div class="space-y-2">
                    @foreach($inquiry->statusHistory as $entry)
                        <div class="flex items-center gap-2 text-sm">
                            <span class="text-slate-400 text-xs w-36 shrink-0">{{ $entry->createdAt->format('M j, Y g:i A') }}</span>
                            <span class="font-medium text-slate-600">{{ str_replace('_', ' ', $entry->status) }}</span>
                            @if($entry->employee)
                                <span class="text-slate-400 text-xs">by {{ $entry->employee->name }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar actions --}}
        <div class="space-y-4">

            {{-- Update status --}}
            <div class="card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Update Status</h3>
                <form method="POST" action="{{ route('portal.inquiries.update', $inquiry->id) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="input mb-3 text-sm">
                        @foreach([
                            'NEW'                       => 'New',
                            'LEFT_VOICEMAIL'            => 'Left Voicemail',
                            'LEFT_VOICEMAIL_2'          => 'Left Voicemail 2',
                            'LEFT_VOICEMAIL_FINAL'      => 'Left Voicemail (Final)',
                            'PROVIDED_PRICING_WAITING'  => 'Provided Pricing — Waiting',
                            'FOLLOW_UP_WHEN_ROOM'       => 'Follow Up When Room',
                            'COMPLETE'                  => 'Complete',
                        ] as $val => $label)
                            <option value="{{ $val }}" {{ $inquiry->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-primary w-full text-sm">Update Status</button>
                </form>
            </div>

            {{-- Snooze --}}
            <div class="card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-1">Snooze</h3>
                @if($inquiry->isSnoozed && $inquiry->snoozeUntil?->isFuture())
                    <p class="text-xs text-indigo-600 mb-3">Snoozed until {{ $inquiry->snoozeUntil->format('M j, Y') }}</p>
                @endif
                <button @click="snoozeOpen = true" class="btn-ghost w-full text-sm">
                    Set Snooze Date
                </button>
            </div>

            {{-- Linked child --}}
            @if($inquiry->child)
            <div class="card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-2">Linked Child</h3>
                <a href="{{ route('portal.children.show', $inquiry->child->id) }}"
                   class="flex items-center gap-2 text-sm text-primary-600 hover:underline">
                    <x-icon name="users" class="w-4 h-4" />
                    {{ $inquiry->child->firstName }} {{ $inquiry->child->lastName }}
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Snooze modal --}}
<div x-show="snoozeOpen" x-cloak
     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
     @click.self="snoozeOpen = false">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm" @click.stop>
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Snooze Inquiry</h2>
        <form method="POST" action="{{ route('portal.inquiries.snooze', $inquiry->id) }}">
            @csrf
            <div class="mb-4">
                <label class="label">Snooze Until</label>
                <input type="date" name="snoozeUntil" required min="{{ now()->addDay()->format('Y-m-d') }}"
                       class="input" value="{{ $inquiry->snoozeUntil?->format('Y-m-d') }}">
                @error('snoozeUntil') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">Snooze</button>
                <button type="button" @click="snoozeOpen = false" class="btn-ghost flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit modal --}}
<div x-show="editOpen" x-cloak
     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
     @click.self="editOpen = false">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-lg" @click.stop>
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Edit Inquiry</h2>
        <form method="POST" action="{{ route('portal.inquiries.update', $inquiry->id) }}">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="label">Name</label>
                    <input type="text" name="name" value="{{ $inquiry->name }}" required class="input text-sm">
                </div>
                <div>
                    <label class="label">Phone</label>
                    <input type="text" name="phone" value="{{ $inquiry->phone }}" class="input text-sm">
                </div>
                <div>
                    <label class="label">Email</label>
                    <input type="email" name="email" value="{{ $inquiry->email }}" class="input text-sm">
                </div>
                <div>
                    <label class="label">Child DOB</label>
                    <input type="date" name="childDob" value="{{ $inquiry->childDob ? \Carbon\Carbon::parse($inquiry->childDob)->format('Y-m-d') : '' }}" class="input text-sm">
                </div>
            </div>
            <div class="mb-4">
                <label class="label">Message</label>
                <textarea name="message" rows="3" class="input text-sm resize-none">{{ $inquiry->message }}</textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">Save</button>
                <button type="button" @click="editOpen = false" class="btn-ghost flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

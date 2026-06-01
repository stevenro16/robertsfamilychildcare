@extends('layouts.portal')
@section('title', $contact->name)

@section('portal-content')
<div class="max-w-4xl" x-data="{ parentModal: false }">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('portal.contacts.index') }}" class="text-slate-400 hover:text-slate-600">
            <x-icon name="chevron-left" class="w-5 h-5" />
        </a>
        <h1 class="text-2xl font-bold text-slate-800">{{ $contact->name }}</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Edit contact --}}
            <div class="card p-6">
                <h2 class="font-semibold text-slate-700 mb-4">Contact Information</h2>
                <form method="POST" action="{{ route('portal.contacts.update', $contact->id) }}">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="label">Full Name</label>
                        <input type="text" name="name" value="{{ $contact->name }}" class="input text-sm">
                    </div>
                    <div class="mb-3">
                        <label class="label">Email</label>
                        <input type="email" name="email" value="{{ $contact->email }}" class="input text-sm">
                    </div>
                    <div class="mb-4">
                        <label class="label">Phone</label>
                        <input type="text" name="phone" value="{{ $contact->phone }}" class="input text-sm">
                    </div>
                    <button type="submit" class="btn-primary text-sm">Save</button>
                </form>
            </div>

            {{-- Notes --}}
            <div class="card p-6">
                <h2 class="font-semibold text-slate-700 mb-4">Notes</h2>
                @if($contact->notes->isEmpty())
                    <p class="text-sm text-slate-400 mb-4">No notes yet.</p>
                @else
                    <div class="space-y-3 mb-4">
                        @foreach($contact->notes->sortByDesc('createdAt') as $note)
                            <div class="bg-slate-50 rounded-lg p-3">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-medium text-slate-600">{{ $note->employee?->name ?? 'Unknown' }}</span>
                                    <span class="text-xs text-slate-400">{{ $note->createdAt->format('M j, Y') }}</span>
                                </div>
                                <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $note->content }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('portal.contacts.notes.add', $contact->id) }}">
                    @csrf
                    <textarea name="content" rows="3" required class="input mb-2 resize-none text-sm" placeholder="Add a note…"></textarea>
                    <button type="submit" class="btn-primary text-sm">Add Note</button>
                </form>
            </div>
        </div>

        <div class="space-y-4">
            {{-- Linked children --}}
            @if($contact->childContacts->isNotEmpty())
            <div class="card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Linked Children</h3>
                <div class="space-y-2">
                    @foreach($contact->childContacts as $cc)
                        <a href="{{ route('portal.children.show', $cc->child->id) }}"
                           class="flex items-center gap-2 text-sm text-primary-600 hover:underline">
                            <x-icon name="users" class="w-4 h-4" />
                            {{ $cc->child->firstName }} {{ $cc->child->lastName }}
                            <span class="text-xs text-slate-400">{{ $cc->relationship }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Parent account --}}
            <div class="card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Parent Account</h3>
                @if($contact->parentUser)
                    <div class="text-sm">
                        <p class="text-slate-600 mb-1">Username: <strong>{{ $contact->parentUser->username }}</strong></p>
                        <span class="text-xs {{ $contact->parentUser->mustChangePassword ? 'text-amber-600 bg-amber-50' : 'text-green-600 bg-green-50' }} px-2 py-0.5 rounded-full">
                            {{ $contact->parentUser->mustChangePassword ? 'Must change password' : 'Active' }}
                        </span>
                    </div>
                @else
                    <p class="text-sm text-slate-400 mb-3">No parent portal account.</p>
                    <button @click="parentModal = true" class="btn-ghost text-xs w-full">Create Account</button>
                @endif
            </div>

            {{-- Copy ID --}}
            <div class="card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-2">Contact ID</h3>
                <div class="flex items-center gap-2">
                    <code class="text-xs bg-slate-100 px-2 py-1 rounded text-slate-600 truncate flex-1">{{ $contact->id }}</code>
                    <button onclick="navigator.clipboard.writeText('{{ $contact->id }}')"
                            class="text-xs text-primary-600 hover:underline shrink-0">Copy</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Create Parent Account Modal --}}
<div x-show="parentModal" x-cloak
     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
     @click.self="parentModal = false">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm" @click.stop>
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Create Parent Account</h2>
        <form method="POST" action="{{ route('portal.contacts.parent-user.create', $contact->id) }}">
            @csrf
            <div class="mb-3">
                <label class="label">Username</label>
                <input type="text" name="username" required class="input text-sm"
                       placeholder="e.g. jsmith">
            </div>
            <div class="mb-6">
                <label class="label">Temporary Password</label>
                <input type="text" name="password" required class="input text-sm"
                       placeholder="Min. 8 characters">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">Create</button>
                <button type="button" @click="parentModal = false" class="btn-ghost flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

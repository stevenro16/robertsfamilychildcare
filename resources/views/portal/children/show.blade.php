@extends('layouts.portal')
@section('title', $child->firstName . ' ' . $child->lastName)

@section('portal-content')
<div class="max-w-4xl" x-data="{ tab: '{{ $tab }}', contactModal: false, noteModal: false }">

    {{-- Header --}}
    <div class="flex items-start gap-4 mb-6">
        <a href="{{ route('portal.children.index') }}" class="text-slate-400 hover:text-slate-600 mt-1">
            <x-icon name="chevron-left" class="w-5 h-5" />
        </a>
        <div class="flex items-center gap-4 flex-1">
            @if($child->photoUrl)
                <img src="{{ $child->photoUrl }}" alt="{{ $child->firstName }}"
                     class="w-14 h-14 rounded-full object-cover shrink-0">
            @else
                <div class="w-14 h-14 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-xl shrink-0">
                    {{ strtoupper(substr($child->firstName, 0, 1)) }}
                </div>
            @endif
            <div>
                <h1 class="text-2xl font-bold text-slate-800">{{ $child->firstName }} {{ $child->lastName }}</h1>
                @if($child->dob)
                    <p class="text-sm text-slate-500">
                        Age {{ \Carbon\Carbon::parse($child->dob)->age }} &middot;
                        Born {{ \Carbon\Carbon::parse($child->dob)->format('M j, Y') }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabs --}}
    <div class="flex gap-1 mb-6 bg-slate-100 p-1 rounded-lg w-fit">
        @foreach(['overview' => 'Overview', 'notes' => 'Notes', 'documents' => 'Documents', 'contacts' => 'Contacts'] as $key => $label)
            <button @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'bg-white shadow-sm text-slate-800' : 'text-slate-500 hover:text-slate-700'"
                    class="px-4 py-1.5 rounded-md text-sm font-medium transition-colors">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Overview tab --}}
    <div x-show="tab === 'overview'" x-cloak>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-slate-700">Basic Info</h2>
                </div>
                <form method="POST" action="{{ route('portal.children.update', $child->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="label">First Name</label>
                            <input type="text" name="firstName" value="{{ $child->firstName }}" class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Last Name</label>
                            <input type="text" name="lastName" value="{{ $child->lastName }}" class="input text-sm">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="label">Date of Birth</label>
                        <input type="date" name="dob" value="{{ $child->dob ? \Carbon\Carbon::parse($child->dob)->format('Y-m-d') : '' }}" class="input text-sm">
                    </div>
                    <button type="submit" class="btn-primary text-sm">Save Changes</button>
                </form>
            </div>

            <div class="card p-6">
                <h2 class="font-semibold text-slate-700 mb-4">Photo</h2>
                <form method="POST" action="{{ route('portal.children.photo', $child->id) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="photo" accept="image/*" class="input text-sm mb-3">
                    <button type="submit" class="btn-primary text-sm w-full">Upload Photo</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Notes tab --}}
    <div x-show="tab === 'notes'" x-cloak>
        <div class="card p-6">
            <h2 class="font-semibold text-slate-700 mb-4">Notes</h2>
            @if($child->notes->isEmpty())
                <p class="text-sm text-slate-400 mb-4">No notes yet.</p>
            @else
                <div class="space-y-3 mb-6">
                    @foreach($child->notes->sortByDesc('createdAt') as $note)
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
            <form method="POST" action="{{ route('portal.children.notes.add', $child->id) }}">
                @csrf
                <textarea name="content" rows="3" required class="input mb-2 resize-none" placeholder="Add a note…"></textarea>
                <button type="submit" class="btn-primary text-sm">Add Note</button>
            </form>
        </div>
    </div>

    {{-- Documents tab --}}
    <div x-show="tab === 'documents'" x-cloak>
        <div class="card p-6">
            <h2 class="font-semibold text-slate-700 mb-4">Documents</h2>
            @if($child->documents->isEmpty())
                <p class="text-sm text-slate-400 mb-4">No documents uploaded yet.</p>
            @else
                <div class="divide-y divide-slate-100 mb-6">
                    @foreach($child->documents as $doc)
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-2">
                                <x-icon name="file" class="w-4 h-4 text-slate-400" />
                                <a href="{{ $doc->fileUrl }}" target="_blank" class="text-sm text-primary-600 hover:underline">
                                    {{ $doc->name }}
                                </a>
                                @if($doc->uploadedByParent)
                                    <span class="text-xs bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded">Parent</span>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('portal.children.documents.delete', [$child->id, $doc->id]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 text-xs" onclick="return confirm('Delete this document?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
            <form method="POST" action="{{ route('portal.children.documents.upload', $child->id) }}" enctype="multipart/form-data">
                @csrf
                <label class="label">Upload Document</label>
                <input type="file" name="file" required class="input text-sm mb-2"
                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                @error('file') <p class="text-red-500 text-xs mb-2">{{ $message }}</p> @enderror
                <button type="submit" class="btn-primary text-sm">Upload</button>
            </form>
        </div>
    </div>

    {{-- Contacts tab --}}
    <div x-show="tab === 'contacts'" x-cloak>
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-slate-700">Emergency Contacts</h2>
                <button @click="contactModal = true" class="btn-ghost text-xs gap-1.5">
                    <x-icon name="plus" class="w-3.5 h-3.5" /> Add Contact
                </button>
            </div>

            @if($child->contacts->isEmpty())
                <p class="text-sm text-slate-400">No contacts added yet.</p>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($child->contacts as $contact)
                        <div class="flex items-start justify-between py-3">
                            <div>
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="font-medium text-slate-800 text-sm">
                                        {{ $contact->name }}
                                    </span>
                                    <span class="text-xs text-slate-500">{{ $contact->pivot->relationship }}</span>
                                    @if($contact->pivot->isPrimary)
                                        <span class="text-xs bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-medium">Primary</span>
                                    @endif
                                </div>
                                @if($contact->phone)
                                    <p class="text-xs text-slate-500">{{ $contact->phone }}</p>
                                @endif
                                @if($contact->email)
                                    <p class="text-xs text-slate-500">{{ $contact->email }}</p>
                                @endif
                            </div>
                            <a href="{{ route('portal.contacts.show', $contact->id) }}"
                               class="text-xs text-primary-600 hover:underline">View</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Add Contact Modal --}}
<div x-show="contactModal" x-cloak
     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
     @click.self="contactModal = false">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md" @click.stop>
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Add Emergency Contact</h2>
        <form method="POST" action="{{ route('portal.children.contacts.add', $child->id) }}">
            @csrf
            <div class="mb-4">
                <label class="label">Contact ID</label>
                <input type="text" name="contactId" required class="input text-sm"
                       placeholder="Paste contact ID from Families page">
            </div>
            <div class="mb-4">
                <label class="label">Relationship</label>
                <select name="relationship" class="input text-sm">
                    <option>Parent</option>
                    <option>Guardian</option>
                    <option>Grandparent</option>
                    <option>Aunt/Uncle</option>
                    <option>Sibling</option>
                    <option>Friend</option>
                    <option>Other</option>
                </select>
            </div>
            <div class="mb-4 flex items-center gap-2">
                <input type="checkbox" name="isPrimary" value="1" id="isPrimary" class="rounded">
                <label for="isPrimary" class="text-sm text-slate-700">Primary contact</label>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">Add</button>
                <button type="button" @click="contactModal = false" class="btn-ghost flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

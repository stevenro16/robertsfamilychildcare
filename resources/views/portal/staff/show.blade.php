@extends('layouts.portal')
@section('title', $member->name)

@section('portal-content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2.5">
            <x-icon name="star" class="w-6 h-6 text-primary-500" />
            Staff
        </h1>
    </div>
    <div class="h-0.5 bg-linear-to-r from-primary-400 to-transparent rounded-full mb-4"></div>

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('portal.staff.index') }}" class="text-slate-400 hover:text-slate-600">
            <x-icon name="chevron-left" class="w-5 h-5" />
        </a>
        <h2 class="text-xl font-semibold text-slate-800">{{ $member->name }}</h2>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Hidden photo upload form (file input lives here, submitted by Alpine) --}}
    <form id="photo-upload-form"
          method="POST"
          action="{{ route('portal.staff.photo', $member->id) }}"
          enctype="multipart/form-data"
          class="hidden">
        @csrf
        <input type="file" id="photo-file-input" name="photo" accept="image/*"
               @change.once="document.getElementById('photo-upload-form').submit()">
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">

                {{-- Photo + header --}}
                <div class="flex items-center gap-4 mb-6 pb-5 border-b border-slate-100"
                     x-data="{ menuOpen: false }">

                    {{-- Photo with hover overlay --}}
                    <div class="relative shrink-0 cursor-pointer group"
                         @click="menuOpen = !menuOpen"
                         @keydown.escape.window="menuOpen = false">

                        {{-- Photo or initial avatar --}}
                        @if($member->photoUrl)
                            <img src="{{ $member->photoUrl }}" alt="{{ $member->name }}"
                                 class="w-24 h-24 rounded-xl object-cover object-top">
                        @else
                            @php
                                $gradients = [
                                    'from-primary-100 to-primary-200 text-primary-700',
                                    'from-emerald-100 to-emerald-200 text-emerald-700',
                                    'from-violet-100 to-violet-200 text-violet-700',
                                    'from-amber-100 to-amber-200 text-amber-700',
                                    'from-rose-100 to-rose-200 text-rose-700',
                                ];
                                $gradient = $gradients[ord(strtolower($member->name[0] ?? 'a')) % count($gradients)];
                            @endphp
                            <div class="w-24 h-24 rounded-xl bg-linear-to-br {{ $gradient }} flex items-center justify-center">
                                <span class="text-2xl font-bold opacity-60">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif

                        {{-- Hover pencil overlay --}}
                        <div class="absolute inset-0 rounded-xl bg-black/45 opacity-0 group-hover:opacity-100 transition-opacity duration-150 flex items-center justify-center">
                            <x-icon name="pencil" class="w-6 h-6 text-white drop-shadow" />
                        </div>
                    </div>

                    {{-- Dropdown menu --}}
                    <div x-show="menuOpen"
                         x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         @click.outside="menuOpen = false"
                         class="absolute mt-1 z-30 bg-white border border-slate-200 rounded-xl shadow-lg w-48 py-1 overflow-hidden"
                         style="margin-top: 6.5rem; margin-left: 0;">

                        <button type="button"
                                @click="menuOpen = false; document.getElementById('photo-file-input').click()"
                                class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                            <x-icon name="camera" class="w-4 h-4 text-slate-400 shrink-0" />
                            {{ $member->photoUrl ? 'Change photo' : 'Upload photo' }}
                        </button>

                        @if($member->photoUrl)
                            <form method="POST" action="{{ route('portal.staff.photo.clear', $member->id) }}">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <x-icon name="trash" class="w-4 h-4 shrink-0" />
                                    Remove photo
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- Name + title + email action --}}
                    <div>
                        <p class="font-semibold text-slate-800 text-lg leading-tight">{{ $member->name }}</p>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $member->title }}</p>
                        @if($member->email)
                            <a href="mailto:{{ $member->email }}"
                               class="mt-1.5 inline-flex items-center gap-1.5 text-xs text-primary-600 hover:text-primary-700 font-medium">
                                <x-icon name="envelope" class="w-3.5 h-3.5" />
                                {{ $member->email }}
                            </a>
                        @else
                            <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                                <x-icon name="pencil" class="w-3 h-3" />
                                Click photo to change
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Profile form --}}
                <h2 class="font-semibold text-slate-700 mb-4">Profile</h2>

                @if($errors->any())
                    <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('portal.staff.update', $member->id) }}">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="label">Name</label>
                        <input type="text" name="name" value="{{ old('name', $member->name) }}" class="input text-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="label">Title</label>
                        <input type="text" name="title" value="{{ old('title', $member->title) }}" class="input text-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="label">Email</label>
                        <div class="flex gap-2">
                            <input type="email" name="email" value="{{ old('email', $member->email) }}"
                                   class="input text-sm flex-1" placeholder="staff@example.com">
                            @if($member->email)
                                <a href="mailto:{{ $member->email }}"
                                   class="btn-ghost text-sm flex items-center gap-1.5 shrink-0">
                                    <x-icon name="envelope" class="w-4 h-4" />
                                    Send Email
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="label">Start Date at Daycare</label>
                            <input type="date" name="startDate"
                                   value="{{ old('startDate', $member->startDate?->format('Y-m-d') ?? '') }}"
                                   class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Years of Experience</label>
                            <input type="number" name="yearsExperience" min="0" max="99"
                                   value="{{ old('yearsExperience', $member->yearsExperience) }}"
                                   class="input text-sm" placeholder="e.g. 8">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="label">Bio</label>
                        <textarea name="bio" rows="4" class="input text-sm resize-none">{{ old('bio', $member->bio) }}</textarea>
                    </div>
                    <div x-data="{ isActive: {{ old('isActive', $member->isActive) ? 'true' : 'false' }} }" class="mb-4">
                        <div class="flex items-center gap-2 mb-2">
                            <input type="hidden" name="isActive" value="0">
                            <input type="checkbox" name="isActive" value="1" id="isActive"
                                   x-model="isActive"
                                   {{ old('isActive', $member->isActive) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                            <label for="isActive" class="text-sm font-medium text-slate-700">Active staff member</label>
                        </div>
                        <div x-show="!isActive" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="rounded-lg bg-red-50 border border-red-200 p-4">
                                <p class="text-xs font-semibold text-red-700 uppercase tracking-wide mb-2">Reason for deactivating</p>
                                <textarea name="inactiveNote" rows="3"
                                          class="input text-sm resize-none border-red-200 focus:ring-red-400"
                                          placeholder="Describe why this staff member is being marked inactive (optional — will be saved as an internal note)…"></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary text-sm">Save Changes</button>
                </form>
            </div>

            <div class="card p-6">
                <h2 class="font-semibold text-slate-700 mb-4">Internal Notes</h2>
                @if($member->notes->isEmpty())
                    <p class="text-sm text-slate-400 mb-4">No notes yet.</p>
                @else
                    <div class="space-y-3 mb-4">
                        @foreach($member->notes->sortByDesc('createdAt') as $note)
                            <div class="bg-slate-50 rounded-lg p-3 border-l-2
                                {{ $note->sentiment === 'POSITIVE' ? 'border-green-400' :
                                   ($note->sentiment === 'NEGATIVE' ? 'border-red-400' : 'border-slate-200') }}">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-medium text-slate-600">{{ $note->employee?->name ?? 'Unknown' }}</span>
                                    <span class="text-xs text-slate-400">{{ $note->createdAt->format('M j, Y') }}</span>
                                    <span class="text-xs {{ $note->sentiment === 'POSITIVE' ? 'text-green-600' : ($note->sentiment === 'NEGATIVE' ? 'text-red-500' : 'text-slate-400') }}">
                                        {{ $note->sentiment }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $note->content }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('portal.staff.notes.add', $member->id) }}">
                    @csrf
                    <textarea name="content" rows="3" required class="input mb-2 resize-none text-sm" placeholder="Add a note…"></textarea>
                    <div class="flex items-center gap-2 mb-2">
                        <select name="sentiment" class="input text-sm w-auto">
                            <option value="NEUTRAL">Neutral</option>
                            <option value="POSITIVE">Positive</option>
                            <option value="NEGATIVE">Negative</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary text-sm">Add Note</button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    // Wire up the hidden file input — once a file is chosen, submit the upload form.
    document.getElementById('photo-file-input').addEventListener('change', function () {
        if (this.files.length) {
            document.getElementById('photo-upload-form').submit();
        }
    });
</script>
@endsection

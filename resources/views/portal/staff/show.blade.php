@extends('layouts.portal')
@section('title', $member->name)

@section('portal-content')
<div class="max-w-4xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('portal.staff.index') }}" class="text-slate-400 hover:text-slate-600">
            <x-icon name="chevron-left" class="w-5 h-5" />
        </a>
        <h1 class="text-2xl font-bold text-slate-800">{{ $member->name }}</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                <h2 class="font-semibold text-slate-700 mb-4">Profile</h2>
                <form method="POST" action="{{ route('portal.staff.update', $member->id) }}">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="label">Name</label>
                        <input type="text" name="name" value="{{ $member->name }}" class="input text-sm">
                    </div>
                    <div class="mb-3">
                        <label class="label">Title</label>
                        <input type="text" name="title" value="{{ $member->title }}" class="input text-sm">
                    </div>
                    <div class="mb-4">
                        <label class="label">Bio</label>
                        <textarea name="bio" rows="4" class="input text-sm resize-none">{{ $member->bio }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary text-sm">Save</button>
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

        <div class="space-y-4">
            <div class="card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Photo</h3>
                @if($member->photoUrl)
                    <img src="{{ $member->photoUrl }}" alt="{{ $member->name }}"
                         class="w-full aspect-square object-cover rounded-lg mb-3">
                @endif
                <form method="POST" action="{{ route('portal.staff.photo', $member->id) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="photo" accept="image/*" class="input text-xs mb-2">
                    <button type="submit" class="btn-primary text-sm w-full">Upload Photo</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.parent')
@section('title', $child->firstName . ' — Documents')

@section('parent-content')
<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('parent.dashboard') }}" class="text-slate-400 hover:text-slate-600">
            <x-icon name="chevron-left" class="w-5 h-5" />
        </a>
        <h1 class="text-xl font-bold text-slate-800">{{ $child->firstName }}'s Documents</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Upload form --}}
    <div class="card p-6 mb-6">
        <h2 class="font-semibold text-slate-700 mb-4">Upload Document</h2>
        <form method="POST" action="{{ route('parent.children.documents.upload', $child->id) }}" enctype="multipart/form-data">
            @csrf
            <p class="text-xs text-slate-400 mb-3">Accepted: PDF, JPG, PNG, DOC, DOCX — max 10 MB</p>
            <input type="file" name="file" required class="input text-sm mb-3"
                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
            <button type="submit" class="btn-primary text-sm">Upload</button>
        </form>
    </div>

    {{-- Documents list --}}
    @if($child->documents->isEmpty())
        <div class="card p-8 text-center text-slate-400 text-sm">No documents uploaded yet.</div>
    @else
        <div class="card divide-y divide-slate-100">
            @foreach($child->documents as $doc)
                <div class="flex items-center justify-between gap-3 p-4">
                    <div class="flex items-center gap-2">
                        <x-icon name="file" class="w-4 h-4 text-slate-400" />
                        <a href="{{ $doc->fileUrl }}" target="_blank"
                           class="text-sm text-primary-600 hover:underline">{{ $doc->name }}</a>
                    </div>
                    <span class="text-xs text-slate-400">
                        {{ $doc->uploadedByParent ? 'You' : 'Staff' }}
                    </span>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

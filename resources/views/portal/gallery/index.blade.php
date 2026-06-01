@extends('layouts.portal')
@section('title', 'Gallery')

@section('portal-content')
<div class="max-w-5xl" x-data="{ uploadOpen: false }">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Gallery</h1>
        <button @click="uploadOpen = true" class="btn-primary gap-1.5">
            <x-icon name="image-plus" class="w-4 h-4" /> Upload Image
        </button>
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

    @if($images->isEmpty())
        <div class="card p-12 text-center text-slate-400">
            <x-icon name="image-plus" class="w-10 h-10 mx-auto mb-3 text-slate-300" />
            <p class="font-medium">No images yet</p>
            <button @click="uploadOpen = true" class="btn-primary mt-4">Upload First Image</button>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($images as $image)
                <div class="group relative aspect-square rounded-xl overflow-hidden bg-slate-100">
                    <img src="{{ route('gallery.image', $image->id) }}" alt="{{ $image->caption }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition-colors flex flex-col items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                        @if($image->caption)
                            <p class="text-white text-xs px-2 text-center">{{ $image->caption }}</p>
                        @endif
                        <form method="POST" action="{{ route('portal.gallery.destroy', $image->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-red-600 transition-colors"
                                    onclick="return confirm('Delete this image?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Upload modal --}}
<div x-show="uploadOpen" x-cloak
     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
     @click.self="uploadOpen = false">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md" @click.stop>
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Upload Image</h2>
        <form method="POST" action="{{ route('portal.gallery.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="label">Image <span class="text-red-400">*</span></label>
                <input type="file" name="image" required accept="image/*" class="input text-sm">
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-6">
                <label class="label">Caption <span class="text-slate-400 font-normal">(optional)</span></label>
                <input type="text" name="caption" class="input text-sm" placeholder="e.g. Outdoor play time">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">Upload</button>
                <button type="button" @click="uploadOpen = false" class="btn-ghost flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

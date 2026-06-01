@extends('layouts.public')
@section('title', 'Gallery — Roberts Family ChildCare')

@section('content')
<section class="bg-gradient-to-br from-primary-500 to-primary-700 text-white">
    <div class="wide py-24 lg:py-32">
        <p class="text-primary-200 text-sm font-medium uppercase tracking-widest mb-4">Our Space</p>
        <h1 class="text-5xl lg:text-6xl font-bold mb-5">Gallery</h1>
        <p class="text-primary-100 text-xl max-w-xl">A glimpse into our warm, joyful environment.</p>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="wide">
        @if($images->isEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach(['from-primary-100 to-primary-200','from-accent-light to-amber-100','from-emerald-100 to-emerald-200','from-sky-100 to-sky-200','from-rose-100 to-rose-200','from-violet-100 to-violet-200'] as $g)
                    <div class="aspect-square rounded-2xl bg-gradient-to-br {{ $g }}"></div>
                @endforeach
            </div>
            <p class="text-center text-slate-400 text-sm mt-8">Gallery images coming soon — check back!</p>
        @else
            <div class="columns-2 sm:columns-3 lg:columns-4 gap-4 space-y-4">
                @foreach($images as $img)
                    <div class="break-inside-avoid relative group rounded-2xl overflow-hidden">
                        <img
                            src="{{ route('gallery.image', $img->id) }}"
                            alt="{{ $img->caption ?? 'Gallery image' }}"
                            class="w-full object-cover"
                            loading="lazy"
                        >
                        @if($img->caption)
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                <p class="text-white text-sm font-medium">{{ $img->caption }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection

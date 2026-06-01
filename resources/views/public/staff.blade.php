@extends('layouts.public')
@section('title', 'Our Staff — Roberts Family ChildCare')

@section('content')
<section class="bg-gradient-to-br from-primary-500 to-primary-700 text-white">
    <div class="wide py-24 lg:py-32">
        <p class="text-primary-200 text-sm font-medium uppercase tracking-widest mb-4">Meet the Team</p>
        <h1 class="text-5xl lg:text-6xl font-bold mb-5">Our Staff</h1>
        <p class="text-primary-100 text-xl max-w-xl">Dedicated caregivers passionate about every child's wellbeing.</p>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="wide">
        @if($staff->isEmpty())
            <p class="text-center text-slate-400">Staff profiles coming soon.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($staff as $member)
                    <div class="card overflow-hidden group hover:shadow-lg transition-shadow">
                        @if($member->photoUrl)
                            <div class="aspect-[4/3] overflow-hidden">
                                <img src="{{ asset($member->photoUrl) }}" alt="{{ $member->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        @else
                            <div class="aspect-[4/3] bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                                <span class="text-6xl font-bold text-primary-300">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-slate-800 mb-1">{{ $member->name }}</h3>
                            @if($member->title)
                                <p class="text-primary-600 font-medium text-sm mb-3">{{ $member->title }}</p>
                            @endif
                            @if($member->bio)
                                <p class="text-slate-500 text-sm leading-relaxed">{{ $member->bio }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection

@extends('layouts.public')
@section('title', 'Our Staff — Roberts Family ChildCare')

@section('content')
<section class="bg-linear-to-br from-primary-500 to-primary-700 text-white">
    <div class="wide py-8 lg:py-10">
        <p class="text-primary-200 text-sm font-medium uppercase tracking-widest mb-4">Meet the Team</p>
        <h1 class="text-5xl lg:text-6xl font-bold mb-5">Our Staff</h1>
        <p class="text-primary-100 text-xl max-w-xl">Dedicated caregivers passionate about every child's wellbeing.</p>
    </div>
</section>

<section class="py-24 bg-white" x-data="{ active: null }">
    <div class="wide">
        @if($staff->isEmpty())
            <p class="text-center text-slate-400">Staff profiles coming soon.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($staff as $member)
                    @php
                        $payload = [
                            'name'            => $member->name,
                            'title'           => $member->title,
                            'bio'             => $member->bio,
                            'photoUrl'        => $member->photoUrl ? asset($member->photoUrl) : null,
                            'startDate'       => $member->startDate ? $member->startDate->format('F j, Y') : null,
                            'yearsExperience' => $member->yearsExperience,
                            'initials'        => strtoupper(substr($member->name, 0, 1)),
                        ];
                    @endphp
                    <button type="button"
                            @click="active = {{ Js::from($payload) }}"
                            class="card overflow-hidden group hover:shadow-lg transition-shadow text-left cursor-pointer w-full">
                        {{-- Photo: object-top so faces are never cropped --}}
                        @if($member->photoUrl)
                            <div class="aspect-64/75 overflow-hidden">
                                <img src="{{ asset($member->photoUrl) }}" alt="{{ $member->name }}"
                                     class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                            </div>
                        @else
                            <div class="aspect-64/75 bg-linear-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                                <span class="text-6xl font-bold text-primary-300">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-slate-800 mb-1">{{ $member->name }}</h3>
                            @if($member->title)
                                <p class="text-primary-600 font-medium text-sm">{{ $member->title }}</p>
                            @endif
                        </div>
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Staff detail modal --}}
    <div x-show="active !== null" x-cloak
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
         @keydown.escape.window="active = null"
         @click.self="active = null">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[92vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             @click.stop>

            <div class="flex flex-col sm:flex-row min-h-112">

                {{-- Left: info --}}
                <div class="flex-1 p-10 lg:p-14">

                    {{-- Close --}}
                    <button type="button" @click="active = null"
                            class="float-right text-slate-300 hover:text-slate-500 transition-colors -mt-2 -mr-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>

                    <h2 class="text-4xl font-bold text-slate-800 leading-tight" x-text="active?.name"></h2>
                    <p class="text-primary-600 font-semibold text-lg mt-2" x-text="active?.title"></p>

                    {{-- Start date + experience --}}
                    <div class="flex flex-wrap gap-8 mt-6 mb-8">
                        <template x-if="active?.startDate">
                            <div class="flex flex-col">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1">With us since</span>
                                <span class="text-base font-medium text-slate-700" x-text="active?.startDate"></span>
                            </div>
                        </template>
                        <template x-if="active?.yearsExperience">
                            <div class="flex flex-col">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1">Years of experience</span>
                                <span class="text-base font-medium text-slate-700" x-text="active?.yearsExperience + ' yrs'"></span>
                            </div>
                        </template>
                    </div>

                    <template x-if="active?.bio">
                        <p class="text-slate-500 text-base leading-relaxed" x-text="active?.bio"></p>
                    </template>
                </div>

                {{-- Right: photo --}}
                <div class="sm:w-xl sm:shrink-0 bg-slate-100 flex items-start justify-center order-first sm:order-last">
                    <template x-if="active?.photoUrl">
                        <img :src="active.photoUrl" :alt="active.name"
                             class="w-full h-120 sm:h-full object-cover object-top">
                    </template>
                    <template x-if="!active?.photoUrl">
                        <div class="w-full h-120 sm:h-full flex items-center justify-center bg-linear-to-br from-primary-100 to-primary-200">
                            <span class="text-8xl font-bold text-primary-300" x-text="active?.initials"></span>
                        </div>
                    </template>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

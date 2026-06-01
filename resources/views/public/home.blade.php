@extends('layouts.public')
@section('title', 'Roberts Family ChildCare — Highland, CA')

@section('content')
{{-- Hero --}}
<section class="min-h-[calc(100vh-6rem)] flex flex-col lg:flex-row overflow-hidden">
    <div class="flex-1 bg-gradient-to-br from-amber-50 via-white to-orange-50 flex items-center">
        <div class="wide py-20 lg:py-0">
            <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 text-sm font-medium px-3 py-1.5 rounded-full mb-6">
                <x-icon name="map-pin" class="w-3.5 h-3.5" />
                Highland, CA 92346
            </div>

            <h1 class="text-5xl sm:text-6xl xl:text-7xl font-bold text-slate-800 leading-[1.1] mb-6">
                Where Every<br>
                Child <span class="text-primary-500">Thrives</span>
            </h1>

            <p class="text-xl text-slate-600 mb-8 leading-relaxed max-w-xl">
                Roberts Family ChildCare offers safe, joyful, and nurturing care for children
                6 months to 10 years old. We're more than a daycare — we're an extension of your family.
            </p>

            <ul class="space-y-2 mb-10">
                @foreach(['Licensed & insured home daycare','Ages 6 months to 10 years','Small group sizes','Nutritious meals & snacks included'] as $h)
                    <li class="flex items-center gap-2 text-slate-600 text-sm">
                        <x-icon name="check-circle" class="w-4 h-4 text-primary-400 shrink-0" />
                        {{ $h }}
                    </li>
                @endforeach
            </ul>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('contact') }}" class="btn-accent px-8 py-3.5 text-base">
                    Inquire About Availability
                </a>
            </div>

            <div class="mt-10 flex items-center gap-6 text-sm text-slate-500">
                <span class="flex items-center gap-1.5">
                    <x-icon name="clock" class="w-4 h-4 text-primary-400" /> Mon–Fri, 7am–5pm
                </span>
                <span class="flex items-center gap-1.5">
                    <x-icon name="phone" class="w-4 h-4 text-primary-400" />(909) 809-7844
                </span>
            </div>
        </div>
    </div>

    {{-- Hero right panel --}}
    <div class="hidden lg:flex lg:w-[42%] xl:w-[40%] bg-gradient-to-br from-primary-500 via-primary-600 to-primary-700 relative overflow-hidden items-center justify-center"
         x-data="{
            programs: [
                {
                    emoji: '🦁', label: 'Infants', range: '6 – 12 months',
                    accent: 'from-amber-400 to-orange-500',
                    description: 'Our infant program provides a calm, safe, and loving environment. We follow your baby\'s individual schedule for feeding, sleeping, and play — keeping you closely informed every step of the way.',
                    features: ['Individualized daily schedules','Tummy time & sensory play','Constant supervision','Daily updates to parents','Structured sleep routines']
                },
                {
                    emoji: '🐘', label: 'Toddlers', range: '1 – 2 years',
                    accent: 'from-yellow-400 to-amber-500',
                    description: 'Toddlers are natural explorers. Our program nurtures their growing independence with safe boundaries, social play, and language-rich activities that support key developmental milestones.',
                    features: ['Language & communication development','Structured & free play','Sensory activities','Potty training support','Social skills introduction']
                },
                {
                    emoji: '🦒', label: 'Preschool', range: '3 – 5 years',
                    accent: 'from-orange-400 to-amber-600',
                    description: 'Our preschool program blends structured learning with imaginative play. Children develop early literacy, numeracy, creativity, and the social-emotional skills needed for kindergarten success.',
                    features: ['Pre-reading & writing readiness','Early math concepts','Art, music & creative play','Outdoor exploration','Kindergarten preparation']
                },
                {
                    emoji: '🦓', label: 'School Age', range: '6 – 10 years',
                    accent: 'from-amber-500 to-orange-600',
                    description: 'Before and/or after school care for elementary-age children. We provide a safe, supportive environment where kids can decompress, get homework help, and enjoy supervised free time.',
                    features: ['Homework help','Structured after-school activities','Outdoor & active play','Healthy snacks','Drop-off & pick-up coordination']
                }
            ],
            selected: null,
            panelOpen: false,
            handleSelect(p) {
                if (this.panelOpen && this.selected && this.selected.label === p.label) {
                    this.panelOpen = false;
                    setTimeout(() => this.selected = null, 280);
                } else if (this.panelOpen) {
                    this.selected = p;
                } else {
                    this.selected = p;
                    setTimeout(() => this.panelOpen = true, 10);
                }
            }
         }"
         @keydown.escape.window="panelOpen = false; setTimeout(() => selected = null, 280)">

        {{-- Decorative circles --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-28 -left-28 w-[28rem] h-[28rem] rounded-full bg-white/10"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 rounded-full bg-white/5"></div>

        {{-- Content column --}}
        <div class="relative z-10 flex flex-col items-center gap-5 p-8 xl:p-10 w-full pb-16">

            {{-- Logo --}}
            <div class="bg-white rounded-3xl p-5 xl:p-6 shadow-2xl">
                <img src="{{ asset('logo.png') }}" alt="Roberts Family ChildCare" class="w-44 xl:w-52 h-auto">
            </div>

            <p class="text-white/60 text-[11px] uppercase tracking-[0.15em] font-medium -mb-1">Our Programs</p>

            {{-- 2×2 selector grid --}}
            <div class="grid grid-cols-2 gap-2.5 w-full max-w-[280px]">
                <template x-for="p in programs" :key="p.label">
                    <button type="button"
                        @click="handleSelect(p)"
                        :class="panelOpen && selected && selected.label === p.label
                            ? 'bg-white border-transparent shadow-xl scale-[1.05]'
                            : 'bg-white/10 border-white/20 hover:bg-white/20 hover:border-white/30 active:scale-95'"
                        class="rounded-2xl px-3 py-3 text-center transition-all duration-200 border group">
                        <div class="text-[1.6rem] mb-1 leading-none group-hover:scale-110 transition-transform duration-150" x-text="p.emoji"></div>
                        <div class="text-xs font-bold leading-tight"
                             :class="panelOpen && selected && selected.label === p.label ? 'text-primary-700' : 'text-white'"
                             x-text="p.label"></div>
                        <div class="text-[10px] mt-0.5"
                             :class="panelOpen && selected && selected.label === p.label ? 'text-primary-400' : 'text-primary-100/60'"
                             x-text="p.range"></div>
                    </button>
                </template>
            </div>

            {{-- Expanding detail card --}}
            <div class="w-full max-w-[280px] transition-all duration-300 ease-out overflow-hidden"
                 :class="panelOpen ? 'max-h-[420px] opacity-100' : 'max-h-0 opacity-0'">
                <template x-if="selected">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl overflow-hidden">
                        <div class="h-[3px]" :class="'bg-gradient-to-r ' + selected.accent"></div>
                        <div class="p-4">
                            <p class="text-white/90 text-[11px] leading-relaxed mb-3" x-text="selected.description"></p>
                            <p class="text-white/40 text-[9px] uppercase tracking-widest font-semibold mb-2">Includes</p>
                            <ul class="space-y-1.5 mb-4">
                                <template x-for="f in selected.features" :key="f">
                                    <li class="flex items-center gap-2 text-[11px] text-white/80">
                                        <x-icon name="check-circle" class="w-3 h-3 text-white/40 shrink-0" />
                                        <span x-text="f"></span>
                                    </li>
                                </template>
                            </ul>
                            <a href="{{ route('contact') }}"
                               class="block text-center bg-white text-primary-700 font-semibold py-2.5 rounded-xl text-xs hover:bg-primary-50 transition-colors">
                                Inquire About This Program
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Bottom strip --}}
        <div class="absolute bottom-5 inset-x-0 text-center z-10">
            <p class="text-primary-100/60 text-[11px] tracking-wide">🌿 Highland, CA · Mon–Fri 7am–5pm</p>
        </div>
    </div>
</section>

{{-- Animal strip --}}
<div class="bg-primary-50 border-y border-primary-100 py-4">
    <div class="flex items-center justify-around px-8 text-3xl gap-4 flex-wrap">
        @foreach(['🦁','🐘','🦒','🦓','🦏','🐆','🦅','🌿','🐊','🦋','🌺','🦜'] as $e)
            <span class="opacity-70 hover:opacity-100 transition-opacity">{{ $e }}</span>
        @endforeach
    </div>
</div>

{{-- Features --}}
<section class="py-24 bg-white">
    <div class="wide">
        <div class="mb-14">
            <h2 class="text-4xl font-bold text-slate-800 mb-3">Why Families Choose Us 🌿</h2>
            <p class="text-slate-500 text-lg">We provide more than childcare — we create a second home your child will love.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
            @foreach([
                ['icon' => 'heart',  'title' => 'Nurturing Environment',  'desc' => 'Every child is treated with warmth and individual attention in our family-style setting.',                         'color' => 'text-rose-500 bg-rose-50'],
                ['icon' => 'shield', 'title' => 'Safe & Secure',           'desc' => 'Our home is fully licensed, childproofed, and designed with your child\'s safety as the top priority.',           'color' => 'text-primary-500 bg-primary-50'],
                ['icon' => 'star',   'title' => 'Enriching Activities',    'desc' => 'Age-appropriate learning through play, art, music, and outdoor exploration every single day.',                    'color' => 'text-amber-500 bg-amber-50'],
                ['icon' => 'users',  'title' => 'Small Group Sizes',       'desc' => 'Low child-to-caregiver ratios ensure your child always gets the attention they deserve.',                         'color' => 'text-emerald-500 bg-emerald-50'],
            ] as $f)
                <div class="card p-8 hover:shadow-lg transition-shadow group">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-5 {{ $f['color'] }} group-hover:scale-110 transition-transform">
                        <x-icon name="{{ $f['icon'] }}" class="w-6 h-6" />
                    </div>
                    <h3 class="font-semibold text-slate-800 text-lg mb-2">{{ $f['title'] }}</h3>
                    <p class="text-slate-500 leading-relaxed">{{ $f['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Testimonials --}}
@if($testimonials->count())
<section class="py-24 bg-slate-50">
    <div class="wide">
        <div class="mb-14">
            <h2 class="text-4xl font-bold text-slate-800 mb-3">What Parents Say</h2>
            <p class="text-slate-500 text-lg">Hear from families who trust us with their most precious ones.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($testimonials as $t)
                <div class="card p-8 hover:shadow-md transition-shadow flex flex-col">
                    <x-icon name="quote" class="w-7 h-7 text-primary-200 mb-4" />
                    <p class="text-slate-600 leading-relaxed mb-6 flex-1">"{{ $t->content }}"</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-50">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-semibold">
                            {{ strtoupper(substr($t->parentName, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-slate-800">{{ $t->parentName }}</p>
                            @if($t->childAge)
                                <p class="text-xs text-slate-500">Child age: {{ $t->childAge }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-10">
            <a href="{{ route('testimonials') }}" class="btn-outline px-8 py-3">Read All Reviews</a>
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="bg-gradient-to-r from-primary-600 to-primary-700">
    <div class="wide py-24 flex flex-col lg:flex-row items-center justify-between gap-8">
        <div>
            <h2 class="text-4xl font-bold text-white mb-3">Ready to Learn More?</h2>
            <p class="text-primary-100 text-lg">Fill out our quick inquiry form and we'll get back to you within one business day.</p>
        </div>
        <a href="{{ route('contact') }}" class="shrink-0 inline-flex items-center gap-2 bg-white text-primary-700 font-semibold px-10 py-4 rounded-xl hover:bg-primary-50 transition-colors text-lg">
            Inquire About Availability
            <x-icon name="chevron-right" class="w-5 h-5" />
        </a>
    </div>
</section>
@endsection

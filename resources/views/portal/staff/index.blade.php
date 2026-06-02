@extends('layouts.portal')
@section('title', 'Staff')

@section('portal-content')
<div class="w-full" x-data="{
    createOpen: false,
    filter: 'active',
    saved: false,
    initSort() {
        const tryInit = () => {
            if (typeof Sortable === 'undefined') { setTimeout(tryInit, 50); return; }
            const grid = this.$refs.grid;
            if (!grid) return;
            Sortable.create(grid, {
                animation: 180,
                handle: '.drag-handle',
                ghostClass: 'opacity-30',
                onEnd: () => {
                    const ids = [...grid.querySelectorAll('[data-sid]')].map(el => el.dataset.sid);
                    fetch('{{ route('portal.staff.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        },
                        body: JSON.stringify({ ids }),
                    })
                    .then(r => r.json())
                    .then(() => { this.saved = true; setTimeout(() => this.saved = false, 3000); });
                }
            });
        };
        tryInit();
    }
}" x-init="initSort()">

    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2.5">
            <x-icon name="star" class="w-6 h-6 text-primary-500" />
            Staff
        </h1>
        <div class="flex items-center gap-1 bg-slate-100 rounded-lg p-1 text-sm">
            <button @click="filter = 'active'"
                    :class="filter === 'active' ? 'bg-white shadow text-slate-800 font-medium' : 'text-slate-500 hover:text-slate-700'"
                    class="px-3 py-1.5 rounded-md transition-all">Active</button>
            <button @click="filter = 'inactive'"
                    :class="filter === 'inactive' ? 'bg-white shadow text-slate-800 font-medium' : 'text-slate-500 hover:text-slate-700'"
                    class="px-3 py-1.5 rounded-md transition-all">Inactive</button>
            <button @click="filter = 'all'"
                    :class="filter === 'all' ? 'bg-white shadow text-slate-800 font-medium' : 'text-slate-500 hover:text-slate-700'"
                    class="px-3 py-1.5 rounded-md transition-all">All</button>
        </div>
    </div>
    <div class="h-0.5 bg-linear-to-r from-primary-400 to-transparent rounded-full mb-4"></div>
    <button @click="createOpen = true" class="btn-primary text-sm mb-4">
        <x-icon name="user-circle" class="w-4 h-4" /> Add Member
    </button>

    {{-- Order info banner --}}
    <div class="mb-6 flex items-center gap-3 px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-sm text-slate-500">
        <x-icon name="arrow-up-down" class="w-4 h-4 text-slate-400 shrink-0" />
        <span>Card order controls how staff appear on the public website — use the <strong class="text-slate-700">drag strip</strong> at the bottom of each card to reorder.</span>
        <span x-show="saved" x-cloak x-transition
              class="ml-auto flex items-center gap-1.5 text-primary-600 font-semibold shrink-0">
            <x-icon name="check-circle" class="w-4 h-4" /> Saved
        </span>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($members->isEmpty())
        <div class="card p-12 text-center text-slate-400">
            <x-icon name="users" class="w-10 h-10 mx-auto mb-3 text-slate-300" />
            <p class="font-medium">No staff members yet</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5" x-ref="grid">
            @foreach($members as $member)
                @php
                    $gradients = [
                        'from-primary-100 to-primary-200 text-primary-700',
                        'from-emerald-100 to-emerald-200 text-emerald-700',
                        'from-violet-100 to-violet-200 text-violet-700',
                        'from-amber-100 to-amber-200 text-amber-700',
                        'from-rose-100 to-rose-200 text-rose-700',
                        'from-sky-100 to-sky-200 text-sky-700',
                        'from-teal-100 to-teal-200 text-teal-700',
                        'from-orange-100 to-orange-200 text-orange-700',
                    ];
                    $gradient = $gradients[ord(strtolower($member->name[0] ?? 'a')) % count($gradients)];

                    $anniversary = null;
                    if ($member->startDate) {
                        $today = \Carbon\Carbon::today();
                        $start = \Carbon\Carbon::parse($member->startDate);
                        $years = $today->year - $start->year;
                        $nextAnniv = $start->copy()->setYear($today->year);
                        if ($nextAnniv->lt($today)) { $nextAnniv->addYear(); $years++; }
                        $daysUntil = $today->diffInDays($nextAnniv, false);
                        $suffix = match(true) {
                            $years % 100 >= 11 && $years % 100 <= 13 => 'th',
                            $years % 10 === 1 => 'st',
                            $years % 10 === 2 => 'nd',
                            $years % 10 === 3 => 'rd',
                            default => 'th',
                        };
                        $anniversary = [
                            'daysUntil' => (int) $daysUntil,
                            'year'      => $years,
                            'suffix'    => $suffix,
                            'date'      => $nextAnniv->format('M j'),
                        ];
                    }
                @endphp

                {{-- Card: photo fills entire card, label + drag strip are transparent overlays --}}
                <div data-sid="{{ $member->id }}"
                     x-show="filter === 'all' || filter === '{{ $member->isActive ? 'active' : 'inactive' }}'"
                     class="relative card overflow-hidden hover:shadow-md transition-all duration-200 group"
                     style="height: 560px;">

                    {{-- Photo fills the whole card --}}
                    @if($member->photoUrl)
                        <img src="{{ $member->photoUrl }}" alt="{{ $member->name }}"
                             class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="absolute inset-0 bg-linear-to-br {{ $gradient }} flex items-center justify-center">
                            <span class="text-6xl font-bold opacity-30">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif

                    {{-- Clickable link covers card except the drag strip (bottom 44px) --}}
                    <a href="{{ route('portal.staff.show', $member->id) }}"
                       class="absolute inset-0 z-10 flex flex-col justify-end"
                       style="bottom: 44px;">
                    </a>

                    {{-- Name / title / badge: overlaid at bottom of photo, 70% transparent --}}
                    <div class="absolute left-0 right-0 z-20 px-4 pt-4 pb-3 bg-white/70 backdrop-blur-sm"
                         style="bottom: 44px;">
                        <h3 class="font-semibold text-slate-800 leading-tight">
                            {{ $member->name }}
                        </h3>
                        @if($member->title)
                            <p class="text-xs text-slate-700 mt-0.5 mb-2">{{ $member->title }}</p>
                        @else
                            <div class="mb-2"></div>
                        @endif

                        {{-- Anniversary badge --}}
                        @if($anniversary)
                            @php
                                $d = $anniversary['daysUntil'];
                                if ($d === 0) {
                                    $badgeColor = 'bg-amber-50/80 border-amber-200 text-amber-700';
                                    $label = '🎉 Anniversary today!';
                                } elseif ($d <= 7) {
                                    $badgeColor = 'bg-amber-50/80 border-amber-200 text-amber-700';
                                    $label = "🎂 {$d}d · {$anniversary['year']}{$anniversary['suffix']} anniv.";
                                } elseif ($d <= 30) {
                                    $badgeColor = 'bg-primary-50/80 border-primary-100 text-primary-700';
                                    $label = "⭐ {$d}d · {$anniversary['year']}{$anniversary['suffix']} anniv.";
                                } else {
                                    $badgeColor = 'bg-white/40 border-white/50 text-slate-600';
                                    $label = "{$d}d until {$anniversary['year']}{$anniversary['suffix']} anniv.";
                                }
                            @endphp
                            <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full border {{ $badgeColor }} leading-none">
                                <x-icon name="calendar" class="w-3 h-3 shrink-0" />
                                {{ $label }}
                            </span>
                        @endif
                    </div>

                    {{-- Drag strip: absolute at very bottom, also over the photo --}}
                    <div class="drag-handle absolute bottom-0 left-0 right-0 z-30 py-3 flex items-center justify-center gap-2
                                bg-white/70 backdrop-blur-sm hover:bg-white/85 transition-colors
                                cursor-grab active:cursor-grabbing select-none group/drag"
                         style="height: 44px;">
                        <svg viewBox="0 0 22 8" class="w-5 h-2 text-slate-500 group-hover/drag:text-primary-600 transition-colors">
                            <circle cx="2"  cy="2" r="1.5" fill="currentColor"/>
                            <circle cx="8"  cy="2" r="1.5" fill="currentColor"/>
                            <circle cx="14" cy="2" r="1.5" fill="currentColor"/>
                            <circle cx="20" cy="2" r="1.5" fill="currentColor"/>
                            <circle cx="2"  cy="7" r="1.5" fill="currentColor"/>
                            <circle cx="8"  cy="7" r="1.5" fill="currentColor"/>
                            <circle cx="14" cy="7" r="1.5" fill="currentColor"/>
                            <circle cx="20" cy="7" r="1.5" fill="currentColor"/>
                        </svg>
                        <span class="text-xs font-medium text-slate-600 group-hover/drag:text-primary-700 transition-colors">
                            Drag to reorder
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Add Member Modal --}}
<div x-show="createOpen" x-cloak
     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
     @click.self="createOpen = false">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md" @click.stop>
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Add Staff Member</h2>
        <form method="POST" action="{{ route('portal.staff.store') }}">
            @csrf
            <div class="mb-3">
                <label class="label">Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" required class="input text-sm" placeholder="Jane Smith">
            </div>
            <div class="mb-3">
                <label class="label">Title <span class="text-red-400">*</span></label>
                <input type="text" name="title" required class="input text-sm" placeholder="Lead Teacher">
            </div>
            <div class="mb-6">
                <label class="label">Bio <span class="text-slate-400 font-normal">(optional)</span></label>
                <textarea name="bio" rows="3" class="input text-sm resize-none"></textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">Add</button>
                <button type="button" @click="createOpen = false" class="btn-ghost flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
@endpush
@endsection

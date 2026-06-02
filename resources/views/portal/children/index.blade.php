@extends('layouts.portal')
@section('title', 'Children')

@section('portal-content')
<div class="w-full" x-data="{ filter: 'active' }">
    <div class="flex items-center justify-between mb-2">

        {{-- Title --}}
        <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2.5">
            <x-icon name="users" class="w-6 h-6 text-primary-500" />
            Children
        </h1>

        {{-- Status filter pill toggle --}}
        <div class="flex items-center bg-slate-100 p-1 rounded-full gap-0.5">
            @foreach(['active' => 'Active', 'inactive' => 'Inactive', 'all' => 'All'] as $val => $lbl)
                <button type="button" @click="filter = '{{ $val }}'"
                        :class="filter === '{{ $val }}'
                            ? 'bg-white text-slate-800 shadow-sm'
                            : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-150 select-none">
                    {{ $lbl }}
                </button>
            @endforeach
        </div>

    </div>
    <div class="h-0.5 bg-linear-to-r from-primary-400 to-transparent rounded-full mb-4"></div>
    <a href="{{ route('portal.children.create') }}"
       class="btn-primary mb-6 inline-flex items-center gap-2 text-sm">
        <x-icon name="plus" class="w-4 h-4" />
        Enroll Child
    </a>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($children->isEmpty())
        <div class="card p-12 text-center text-slate-400">
            <x-icon name="users" class="w-10 h-10 mx-auto mb-3 text-slate-300" />
            <p class="font-medium">No children enrolled yet</p>
            <a href="{{ route('portal.children.create') }}" class="btn-primary mt-4 inline-flex">Add First Child</a>
        </div>
    @else
        <div class="flex flex-wrap gap-4">
            @foreach($children as $child)
                @php
                    // Age calculation
                    $dob = $child->dateOfBirth ? \Carbon\Carbon::parse($child->dateOfBirth) : null;
                    $months = $dob ? $dob->diffInMonths(now()) : null;
                    $ageStr = $dob
                        ? ($months < 24 ? $months . ' mo' : $dob->age . ' yr' . ($dob->age !== 1 ? 's' : ''))
                        : null;

                    // Primary contact
                    $primary = $child->contacts->first(fn($c) => (bool) $c->pivot->isPrimary)
                             ?? $child->contacts->first();

                    // Schedule
                    $schedule = $child->schedule ?? [];
                    $dayMap   = ['Mo' => 'monday', 'Tu' => 'tuesday', 'We' => 'wednesday', 'Th' => 'thursday', 'Fr' => 'friday'];

                    // Avatar gradient color (consistent per child name)
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
                    $gradient = $gradients[ord(strtolower($child->firstName[0] ?? 'a')) % count($gradients)];

                    // Weekly hours from schedule
                    $weeklyMinutes = 0;
                    foreach (['monday','tuesday','wednesday','thursday','friday'] as $_d) {
                        $entry = $schedule[$_d] ?? null;
                        if (is_array($entry) && !empty($entry['dropoff']) && !empty($entry['pickup'])) {
                            $toMins = fn($t) => intval(explode(':', $t)[0]) * 60 + intval(explode(':', $t)[1] ?? 0);
                            $weeklyMinutes += max(0, $toMins($entry['pickup']) - $toMins($entry['dropoff']));
                        }
                    }
                    $hoursStr = null;
                    if ($weeklyMinutes > 0) {
                        $h = intdiv($weeklyMinutes, 60);
                        $m = $weeklyMinutes % 60;
                        $hoursStr = $m > 0 ? "{$h}h {$m}m/wk" : "{$h}h/wk";
                    }
                    $docCount = $child->documents->count();
                @endphp

                <a href="{{ route('portal.children.show', $child->id) }}"
                   x-show="filter === 'all' || filter === '{{ strtolower($child->status ?? 'active') }}'"
                   class="relative card overflow-hidden hover:shadow-md transition-all duration-200 cursor-pointer group shrink-0"
                   style="width: 448px; height: 520px;">

                    {{-- Photo fills entire card --}}
                    @if($child->photoUrl)
                        <img src="{{ $child->photoUrl }}" alt="{{ $child->firstName }}"
                             class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="absolute inset-0 bg-linear-to-br {{ $gradient }} flex items-center justify-center">
                            <span class="text-7xl font-bold opacity-30">
                                {{ strtoupper(substr($child->firstName, 0, 1)) }}
                            </span>
                        </div>
                    @endif

                    {{-- Checked-in badge --}}
                    @if($child->checkedInAt)
                        <span class="absolute top-3 right-3 z-20 inline-flex items-center gap-1 text-xs bg-green-500 text-white px-2 py-0.5 rounded-full font-medium shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                            In
                        </span>
                    @endif

                    {{-- Status badge --}}
                    @if(($child->status ?? 'ACTIVE') !== 'ACTIVE')
                        <span class="absolute top-3 left-3 z-20 text-xs bg-slate-600/80 text-white px-2 py-0.5 rounded-full font-medium">
                            {{ ucfirst(strtolower($child->status ?? '')) }}
                        </span>
                    @endif

                    {{-- Bottom banner — 70% opaque, same as staff cards --}}
                    <div class="absolute bottom-0 left-0 right-0 z-10 bg-white/70 backdrop-blur-sm px-4 pt-3 pb-3">

                        {{-- Name --}}
                        <h3 class="font-semibold text-slate-800 text-base leading-tight group-hover:text-primary-600 transition-colors">
                            {{ $child->firstName }} {{ $child->lastName }}
                        </h3>

                        {{-- Age & DOB --}}
                        @if($dob)
                            <p class="text-xs text-slate-500 mt-0.5 mb-2">
                                {{ $ageStr }} &middot; {{ $dob->format('M j, Y') }}
                            </p>
                        @else
                            <p class="text-xs text-slate-400 mt-0.5 mb-2">No DOB on file</p>
                        @endif

                        {{-- Primary contact --}}
                        <div class="flex items-center gap-1.5 mb-2">
                            <x-icon name="user" class="w-3.5 h-3.5 shrink-0 {{ $primary ? 'text-slate-400' : 'text-slate-200' }}" />
                            @if($primary)
                                <span class="text-xs text-slate-600 leading-snug truncate">
                                    {{ $primary->name }}
                                    @if($primary->pivot->relationship)
                                        <span class="text-slate-400">&middot; {{ $primary->pivot->relationship }}</span>
                                    @endif
                                </span>
                            @else
                                <span class="text-xs text-slate-300">No contact on file</span>
                            @endif
                        </div>

                        {{-- Schedule grid --}}
                        <div class="mb-2">
                            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Schedule</p>
                            <div class="grid grid-cols-5 gap-1">
                                @foreach($dayMap as $abbr => $day)
                                    @php
                                        $val = $schedule[$day] ?? null;
                                        if (is_array($val)) {
                                            $val = implode(', ', array_filter($val, fn($v) => trim((string)$v) !== ''));
                                        } elseif ($val !== null) {
                                            $val = trim((string) $val);
                                        }
                                        $hasDay = !empty($val);
                                    @endphp
                                    <div class="flex flex-col items-center rounded-md py-1
                                                {{ $hasDay ? 'bg-primary-50/80' : 'bg-white/40' }}">
                                        <span class="text-xs font-semibold {{ $hasDay ? 'text-primary-600' : 'text-slate-300' }}">
                                            {{ $abbr }}
                                        </span>
                                        <span class="text-xs mt-0.5 leading-tight text-center px-0.5
                                                     {{ $hasDay ? 'text-primary-500 font-medium' : 'text-slate-200' }}">
                                            {{ $hasDay ? (strlen($val) > 5 ? substr($val, 0, 5) : $val) : '—' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Bottom strip: contact avatars + doc count + hours --}}
                        <div class="border-t border-slate-200/60 pt-2 flex items-center justify-between gap-2">

                            {{-- Contact avatar stack --}}
                            <div class="flex items-center -space-x-1.5">
                                @forelse($child->contacts->take(3) as $contact)
                                    @php
                                        $words    = array_filter(explode(' ', $contact->name));
                                        $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice($words, 0, 2)));
                                    @endphp
                                    <div class="w-6 h-6 rounded-full ring-2 ring-white bg-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                                        @if(!empty($contact->photoUrl))
                                            <img src="{{ $contact->photoUrl }}" alt="{{ $contact->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="font-bold text-slate-500 leading-none select-none" style="font-size:9px">{{ $initials }}</span>
                                        @endif
                                    </div>
                                @empty
                                    <span class="text-xs text-slate-300">—</span>
                                @endforelse
                                @if($child->contacts->count() > 3)
                                    <div class="w-6 h-6 rounded-full ring-2 ring-white bg-slate-100 flex items-center justify-center shrink-0">
                                        <span class="text-slate-400 leading-none" style="font-size:9px">+{{ $child->contacts->count() - 3 }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Doc count + weekly hours --}}
                            <div class="flex items-center gap-2.5">
                                @if($docCount > 0)
                                    <span class="inline-flex items-center gap-0.5 text-xs text-slate-500">
                                        <x-icon name="file" class="w-3.5 h-3.5 shrink-0" />
                                        {{ $docCount }}
                                    </span>
                                @endif
                                @if($hoursStr)
                                    <span class="inline-flex items-center gap-0.5 text-xs text-slate-500">
                                        <x-icon name="clock" class="w-3.5 h-3.5 shrink-0" />
                                        {{ $hoursStr }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <p class="mt-4 text-xs text-slate-400 text-right">
            {{ $children->count() }} {{ Str::plural('child', $children->count()) }}
        </p>
    @endif
</div>
@endsection

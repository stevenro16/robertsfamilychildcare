@extends('layouts.portal')
@section('title', $child->firstName . ' ' . $child->lastName)

@section('portal-content')
<div x-data="{
    tab: '{{ $tab }}',
    contactModal: false,
    noteModal: false,
    photoModal: false,
    editingContact: null,
    openEditContact(c) { this.editingContact = { ...c }; }
}">

    {{-- Section header --}}
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2.5">
            <x-icon name="users" class="w-6 h-6 text-primary-500" />
            Children
        </h1>
    </div>
    <div class="h-0.5 bg-linear-to-r from-primary-400 to-transparent rounded-full mb-4"></div>

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('portal.children.index') }}" class="text-slate-400 hover:text-slate-600 shrink-0">
            <x-icon name="chevron-left" class="w-5 h-5" />
        </a>

        {{-- Photo --}}
        <button type="button" @click="photoModal = true"
                class="relative group shrink-0 w-20 h-20 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2">
            @if($child->photoUrl)
                <img src="{{ $child->photoUrl }}" alt="{{ $child->firstName }}"
                     class="w-20 h-20 rounded-xl object-cover object-top">
            @else
                <div class="w-20 h-20 rounded-xl bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-3xl">
                    {{ strtoupper(substr($child->firstName, 0, 1)) }}
                </div>
            @endif
            <div class="absolute inset-0 rounded-xl bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <x-icon name="camera" class="w-5 h-5 text-white drop-shadow" />
            </div>
        </button>

        <div>
            <h2 class="text-xl font-semibold text-slate-800">{{ $child->firstName }} {{ $child->lastName }}</h2>
            @if($child->dateOfBirth)
                @php $headerDob = \Carbon\Carbon::parse($child->dateOfBirth); @endphp
                <p class="text-sm text-slate-500">
                    Age {{ $headerDob->age }} &middot; Born {{ $headerDob->format('M j, Y') }}
                </p>
            @endif
            <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                <x-icon name="camera" class="w-3 h-3" /> Click photo to change
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabs --}}
    <div class="flex gap-1 mb-6 bg-slate-100 p-1 rounded-lg w-fit">
        @foreach(['stats' => 'Stats', 'overview' => 'Overview', 'notes' => 'Notes', 'documents' => 'Documents'] as $key => $label)
            <button @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'bg-white shadow-sm text-slate-800' : 'text-slate-500 hover:text-slate-700'"
                    class="px-4 py-1.5 rounded-md text-sm font-medium transition-colors">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Stats tab --}}
    <div x-show="tab === 'stats'" x-cloak>
        @php
            $statsSchedule  = $child->schedule ?? [];
            $chartStart     = 420;   // 7:00 AM in minutes
            $chartEnd       = 1080;  // 6:00 PM in minutes
            $chartRange     = $chartEnd - $chartStart; // 660 min

            $statsDayKeys = [
                'monday'    => 'Mon',
                'tuesday'   => 'Tue',
                'wednesday' => 'Wed',
                'thursday'  => 'Thu',
                'friday'    => 'Fri',
            ];
            $schedRows      = [];
            $statsWeekHours = 0;

            foreach ($statsDayKeys as $key => $short) {
                $val = $statsSchedule[$key] ?? null;
                if (is_array($val)) {
                    $doff = $val['dropoff'] ?? '07:00';
                    $pup  = $val['pickup']  ?? '17:00';
                    [$dH, $dM] = array_map('intval', explode(':', $doff));
                    [$pH, $pM] = array_map('intval', explode(':', $pup));
                    $dMin = $dH * 60 + $dM;
                    $pMin = $pH * 60 + $pM;
                    $hrs  = round(($pMin - $dMin) / 60, 1);
                    $statsWeekHours += $hrs;
                    $leftPct  = max(0, min(100, ($dMin - $chartStart) / $chartRange * 100));
                    $widthPct = max(0, min(100 - $leftPct, ($pMin - $dMin) / $chartRange * 100));
                    $dAp = $dH >= 12 ? 'PM' : 'AM'; $dH12 = $dH % 12 ?: 12;
                    $pAp = $pH >= 12 ? 'PM' : 'AM'; $pH12 = $pH % 12 ?: 12;
                    $schedRows[] = [
                        'key'      => $key,
                        'short'    => $short,
                        'active'   => true,
                        'doffFmt'  => $dH12 . ':' . str_pad($dM, 2, '0', STR_PAD_LEFT) . ' ' . $dAp,
                        'pupFmt'   => $pH12 . ':' . str_pad($pM, 2, '0', STR_PAD_LEFT) . ' ' . $pAp,
                        'hrs'      => $hrs,
                        'leftPct'  => $leftPct,
                        'widthPct' => $widthPct,
                    ];
                } elseif (!empty($val)) {
                    $statsWeekHours += 10;
                    $schedRows[] = [
                        'key'      => $key,
                        'short'    => $short,
                        'active'   => true,
                        'doffFmt'  => '7:00 AM',
                        'pupFmt'   => '5:00 PM',
                        'hrs'      => 10,
                        'leftPct'  => 0,
                        'widthPct' => round(600 / 660 * 100, 2),
                    ];
                } else {
                    $schedRows[] = ['key' => $key, 'short' => $short, 'active' => false];
                }
            }

            $activeDayCount  = count(array_filter($schedRows, fn($r) => $r['active']));
            $activeDayShorts = implode(', ', array_column(array_filter($schedRows, fn($r) => $r['active']), 'short'));

            $childAgeYrs = $child->dateOfBirth ? (int) \Carbon\Carbon::parse($child->dateOfBirth)->age : null;

            $birthdayNext = null;
            $birthdayDays = null;
            if ($child->dateOfBirth) {
                $birthdayNext = \Carbon\Carbon::parse($child->dateOfBirth)->setYear(now()->year);
                if ($birthdayNext->isPast() && !$birthdayNext->isToday()) {
                    $birthdayNext->addYear();
                }
                $birthdayDays = (int) now()->startOfDay()->diffInDays($birthdayNext->startOfDay());
            }

            $enrolledDate   = $child->expectedStart ? \Carbon\Carbon::parse($child->expectedStart) : null;
            $enrolledMonths = $enrolledDate ? (int) $enrolledDate->diffInMonths(now()) : null;
            if ($enrolledMonths !== null) {
                $eYrs = intdiv($enrolledMonths, 12);
                $eMos = $enrolledMonths % 12;
                $enrolledText = $eYrs > 0
                    ? $eYrs . ' yr' . ($eMos > 0 ? ' ' . $eMos . ' mo' : '')
                    : $eMos . ' ' . \Illuminate\Support\Str::plural('month', max(1, $eMos));
            } else {
                $enrolledText = '—';
            }
        @endphp

        {{-- Metric cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="card p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Age</p>
                <p class="text-3xl font-bold text-slate-800">{{ $childAgeYrs ?? '—' }}</p>
                @if($child->dateOfBirth)
                    <p class="text-xs text-slate-400 mt-1">Born {{ \Carbon\Carbon::parse($child->dateOfBirth)->format('M j, Y') }}</p>
                @else
                    <p class="text-xs text-slate-300 mt-1">No birthdate on file</p>
                @endif
            </div>
            <div class="card p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Time Enrolled</p>
                <p class="text-3xl font-bold text-slate-800">{{ $enrolledText }}</p>
                @if($enrolledDate)
                    <p class="text-xs text-slate-400 mt-1">Since {{ $enrolledDate->format('M j, Y') }}</p>
                @else
                    <p class="text-xs text-slate-300 mt-1">No start date on file</p>
                @endif
            </div>
            <div class="card p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Days / Week</p>
                <p class="text-3xl font-bold text-slate-800">
                    {{ $activeDayCount }}
                    <span class="text-sm font-normal text-slate-400 ml-0.5">days</span>
                </p>
                <p class="text-xs text-slate-400 mt-1">
                    {{ $activeDayCount > 0 ? $activeDayShorts : 'No schedule set' }}
                </p>
            </div>
            <div class="card p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Hours / Week</p>
                <p class="text-3xl font-bold text-slate-800">
                    {{ $statsWeekHours > 0 ? number_format($statsWeekHours, 1) : '—' }}
                    @if($statsWeekHours > 0)
                        <span class="text-sm font-normal text-slate-400 ml-0.5">hrs</span>
                    @endif
                </p>
                @if($statsWeekHours > 0)
                    <p class="text-xs text-slate-400 mt-1">~{{ number_format($statsWeekHours * 52, 0) }} hrs / year</p>
                @else
                    <p class="text-xs text-slate-300 mt-1">No schedule set</p>
                @endif
            </div>
        </div>

        {{-- Schedule chart --}}
        <div class="card p-6 mb-6">
            <div class="flex items-start justify-between mb-5">
                <div>
                    <h2 class="font-semibold text-slate-700">Weekly Schedule</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Drop-off → pick-up timeline, 7 am – 6 pm</p>
                </div>
                @if($activeDayCount > 0)
                    <span class="text-xs px-2.5 py-1 rounded-full bg-primary-50 text-primary-700 font-semibold shrink-0">
                        {{ number_format($statsWeekHours, 1) }} hrs / week
                    </span>
                @endif
            </div>

            @if($activeDayCount === 0)
                <div class="py-12 text-center rounded-xl border-2 border-dashed border-slate-100">
                    <x-icon name="calendar" class="w-8 h-8 text-slate-200 mx-auto mb-2" />
                    <p class="text-sm text-slate-400">No schedule has been set yet</p>
                    <p class="text-xs text-slate-300 mt-1">Add days in the Overview tab</p>
                </div>
            @else
                {{-- Time axis --}}
                <div class="flex items-end gap-3 mb-1 select-none">
                    <div class="w-12 shrink-0"></div>
                    <div class="flex-1 relative h-4">
                        <span class="absolute text-[10px] text-slate-300" style="left:0%">7 am</span>
                        <span class="absolute text-[10px] text-slate-300 -translate-x-1/2" style="left:{{ number_format(5/11*100, 2) }}%">12 pm</span>
                        <span class="absolute text-[10px] text-slate-300 -translate-x-1/2" style="left:{{ number_format(8/11*100, 2) }}%">3 pm</span>
                        <span class="absolute text-[10px] text-slate-300 -translate-x-full" style="left:100%">6 pm</span>
                    </div>
                    <div class="w-12 shrink-0"></div>
                </div>

                {{-- Day rows --}}
                <div class="space-y-2">
                    @foreach($schedRows as $row)
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-semibold text-slate-400 w-12 shrink-0 text-right">
                                {{ $row['short'] }}
                            </span>
                            <div class="flex-1 relative h-10 bg-slate-50 rounded-lg overflow-hidden border border-slate-100">
                                {{-- Hour grid lines --}}
                                @for($hr = 1; $hr <= 10; $hr++)
                                    <div class="absolute inset-y-0 w-px bg-slate-200/60"
                                         style="left:{{ number_format($hr / 11 * 100, 2) }}%"></div>
                                @endfor
                                @if($row['active'])
                                    <div class="absolute rounded-md bg-primary-400 flex items-center px-2.5 overflow-hidden"
                                         style="top:6px;bottom:6px;left:{{ number_format($row['leftPct'], 2) }}%;width:{{ number_format($row['widthPct'], 2) }}%">
                                        <span class="text-[10px] font-semibold text-white whitespace-nowrap">
                                            {{ $row['doffFmt'] }} – {{ $row['pupFmt'] }}
                                        </span>
                                    </div>
                                @else
                                    <div class="h-full flex items-center justify-center">
                                        <span class="text-xs text-slate-300 italic">not scheduled</span>
                                    </div>
                                @endif
                            </div>
                            <span class="text-xs font-semibold tabular-nums w-12 shrink-0 text-right
                                         {{ $row['active'] ? 'text-slate-500' : 'text-transparent' }}">
                                {{ $row['active'] ? $row['hrs'] . 'h' : '' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Quick-stat cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="card p-4 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-violet-100 flex items-center justify-center shrink-0">
                    <x-icon name="file-text" class="w-5 h-5 text-violet-500" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $notes->count() }}</p>
                    <p class="text-xs text-slate-400">Internal {{ \Illuminate\Support\Str::plural('Note', $notes->count()) }}</p>
                </div>
            </div>
            <div class="card p-4 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-sky-100 flex items-center justify-center shrink-0">
                    <x-icon name="folder" class="w-5 h-5 text-sky-500" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $child->documents->count() }}</p>
                    <p class="text-xs text-slate-400">{{ \Illuminate\Support\Str::plural('Document', $child->documents->count()) }}</p>
                </div>
            </div>
            <div class="card p-4 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                    <x-icon name="users" class="w-5 h-5 text-amber-500" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $child->contacts->count() }}</p>
                    <p class="text-xs text-slate-400">Emergency {{ \Illuminate\Support\Str::plural('Contact', $child->contacts->count()) }}</p>
                </div>
            </div>
        </div>

        {{-- Birthday alert --}}
        @if($birthdayDays !== null && $birthdayDays <= 30)
            <div class="mt-4 flex items-center gap-3 px-4 py-3.5 rounded-xl bg-amber-50 border border-amber-200">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0 text-xl">🎂</div>
                <div>
                    <p class="text-sm font-semibold text-amber-800">Birthday coming up!</p>
                    <p class="text-xs text-amber-600 mt-0.5">
                        {{ $child->firstName }}'s birthday is
                        {{ $birthdayDays === 0 ? 'today' : 'in ' . $birthdayDays . ' ' . \Illuminate\Support\Str::plural('day', $birthdayDays) }}
                        — {{ $birthdayNext->format('F j') }}.
                    </p>
                </div>
            </div>
        @endif
    </div>

    {{-- Overview tab --}}
    <div x-show="tab === 'overview'" x-cloak>
        @php
            $existingSchedule = $child->schedule ?? [];
            $scheduleDayKeys  = ['monday','tuesday','wednesday','thursday','friday'];
            $timeToSlot = function ($t) {
                if (!$t) return null;
                $parts = explode(':', $t);
                $mins  = intval($parts[0]) * 60 + intval($parts[1] ?? 0);
                return max(0, min(20, (int) round(($mins - 420) / 30)));
            };
            $scheduleInit = [];
            foreach ($scheduleDayKeys as $d) {
                $val = $existingSchedule[$d] ?? null;
                if (is_array($val)) {
                    $ds = $timeToSlot($val['dropoff'] ?? null) ?? 2;
                    $ps = $timeToSlot($val['pickup']  ?? null) ?? 16;
                    $scheduleInit[$d] = ['active' => true,  'dropoff' => $ds, 'pickup' => $ps];
                } elseif ($val !== null && $val !== '' && $val !== false) {
                    $scheduleInit[$d] = ['active' => true,  'dropoff' => 2,   'pickup' => 16];
                } else {
                    $scheduleInit[$d] = ['active' => false, 'dropoff' => 2,   'pickup' => 16];
                }
            }
        @endphp

        <form method="POST" action="{{ route('portal.children.update', $child->id) }}">
            @csrf
            @method('PATCH')

            {{-- Basic info + photo --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

                <div class="lg:col-span-2 card p-6">
                    <h2 class="font-semibold text-slate-700 mb-4">Child Information</h2>

                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="label">First Name</label>
                            <input type="text" name="firstName" value="{{ $child->firstName }}" required class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Last Name</label>
                            <input type="text" name="lastName" value="{{ $child->lastName }}" required class="input text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="label">Date of Birth</label>
                            <input type="date" name="dob"
                                   value="{{ $child->dateOfBirth ? \Carbon\Carbon::parse($child->dateOfBirth)->format('Y-m-d') : '' }}"
                                   class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Expected Start Date</label>
                            <input type="date" name="expectedStart"
                                   value="{{ $child->expectedStart ? \Carbon\Carbon::parse($child->expectedStart)->format('Y-m-d') : '' }}"
                                   class="input text-sm">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="label">Status</label>
                        <select name="status" class="input text-sm">
                            @foreach(['ACTIVE' => 'Active', 'INACTIVE' => 'Inactive', 'WAITLIST' => 'Waitlist', 'PENDING' => 'Pending'] as $val => $lbl)
                                <option value="{{ $val }}" @selected(($child->status ?? 'ACTIVE') === $val)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="label">Internal Notes <span class="text-slate-400 font-normal text-xs">(not visible to parents)</span></label>
                        <textarea name="notes" rows="3" class="input text-sm resize-none"
                                  placeholder="Allergies, special instructions, general notes…">{{ $child->notes }}</textarea>
                    </div>
                </div>

                {{-- Contacts management card --}}
                <div class="card p-6 flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-slate-700">Contacts</h2>
                        <button type="button" @click="contactModal = true"
                                class="btn-ghost text-xs gap-1">
                            <x-icon name="plus" class="w-3.5 h-3.5" /> Add
                        </button>
                    </div>

                    @if($child->contacts->isEmpty())
                        <div class="flex-1 flex flex-col items-center justify-center py-6 text-center">
                            <x-icon name="users" class="w-8 h-8 text-slate-200 mb-2" />
                            <p class="text-sm text-slate-400">No contacts yet</p>
                        </div>
                    @else
                        <div class="space-y-1">
                            @foreach($child->contacts->sortByDesc(fn($c) => $c->pivot->isPrimary) as $contact)
                                @php
                                    $cWords    = array_filter(explode(' ', $contact->name));
                                    $cInitials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice($cWords, 0, 2)));
                                    $cPayload  = [
                                        'id'           => $contact->pivot->id,
                                        'contactId'    => $contact->id,
                                        'name'         => $contact->name,
                                        'phone'        => $contact->phone ?? '',
                                        'email'        => $contact->email ?? '',
                                        'relationship' => $contact->pivot->relationship ?? '',
                                        'isPrimary'    => (bool) $contact->pivot->isPrimary,
                                    ];
                                @endphp
                                <button type="button"
                                        @click="openEditContact({{ Js::from($cPayload) }})"
                                        class="w-full flex items-center gap-3 px-2.5 py-2 rounded-lg hover:bg-slate-50 transition-colors text-left group">

                                    {{-- Avatar --}}
                                    <div class="shrink-0">
                                        @if($contact->photoUrl)
                                            <img src="{{ $contact->photoUrl }}" alt="{{ $contact->name }}"
                                                 class="w-9 h-9 rounded-full object-cover">
                                        @else
                                            <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs">
                                                {{ $cInitials }}
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-sm font-medium text-slate-800 truncate">{{ $contact->name }}</span>
                                            @if($contact->pivot->isPrimary)
                                                <span class="text-[10px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-medium leading-none shrink-0">Primary</span>
                                            @endif
                                        </div>
                                        @if($contact->pivot->relationship)
                                            <p class="text-xs text-slate-500">{{ $contact->pivot->relationship }}</p>
                                        @endif
                                        @if($contact->phone)
                                            <p class="text-xs text-slate-400">{{ $contact->phone }}</p>
                                        @endif
                                    </div>

                                    <x-icon name="edit" class="w-3.5 h-3.5 text-slate-200 group-hover:text-slate-400 shrink-0" />
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── Weekly schedule slider ───────────────────── --}}
            <div class="card p-6 mb-6"
                 x-data="scheduleWidget({{ Js::from($scheduleInit) }})"
                 @mousemove.window="onMouseMove($event)"
                 @mouseup.window="stopDrag()"
                 @touchmove.window.passive="onMouseMove($event)"
                 @touchend.window="stopDrag()">

                {{-- Always-present hidden inputs for all 5 days --}}
                @foreach(['monday','tuesday','wednesday','thursday','friday'] as $d)
                <input type="hidden" name="schedule_active[{{ $d }}]"      :value="days.{{ $d }}.active ? '1' : ''">
                <input type="hidden" name="schedule[{{ $d }}][dropoff]"    :value="days.{{ $d }}.active ? slotToValue(days.{{ $d }}.dropoff) : ''">
                <input type="hidden" name="schedule[{{ $d }}][pickup]"     :value="days.{{ $d }}.active ? slotToValue(days.{{ $d }}.pickup)  : ''">
                @endforeach

                <div class="mb-6">
                    <h2 class="font-semibold text-slate-700">Weekly Schedule</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Toggle days on, then drag the handles to set hours. Snaps to 30-minute increments.</p>
                </div>

                {{-- Day toggle buttons --}}
                <div class="flex flex-wrap gap-3 mb-8">
                    @foreach(['monday'=>'Mon','tuesday'=>'Tue','wednesday'=>'Wed','thursday'=>'Thu','friday'=>'Fri'] as $key => $label)
                    <button type="button"
                            @click="days.{{ $key }}.active = !days.{{ $key }}.active"
                            :class="days.{{ $key }}.active
                                ? 'bg-primary-500 text-white border-primary-500 shadow-sm'
                                : 'bg-white border-slate-200 text-slate-500 hover:border-primary-300 hover:text-primary-500'"
                            class="px-4 py-2 rounded-lg border-2 font-semibold text-sm transition-all duration-150 select-none">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>

                {{-- Per-day sliders --}}
                <div class="space-y-8">
                    @foreach(['monday'=>'Monday','tuesday'=>'Tuesday','wednesday'=>'Wednesday','thursday'=>'Thursday','friday'=>'Friday'] as $key => $label)
                    <div x-show="days.{{ $key }}.active"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2">

                        {{-- Row header --}}
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-semibold text-slate-700">{{ $label }}</span>
                            <div class="flex items-center gap-1 text-xs font-medium tabular-nums">
                                <span class="px-2 py-0.5 bg-primary-100 text-primary-700 rounded-full"
                                      x-text="slotToTime(days.{{ $key }}.dropoff)"></span>
                                <span class="text-slate-300">→</span>
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-full"
                                      x-text="slotToTime(days.{{ $key }}.pickup)"></span>
                            </div>
                        </div>

                        {{-- Slider track area --}}
                        <div class="relative select-none" style="padding: 2rem 0.5rem 1.5rem">

                            {{-- Clickable track --}}
                            <div class="relative h-3 bg-slate-100 rounded-full cursor-pointer"
                                 id="strack-{{ $key }}"
                                 @mousedown.prevent="handleTrackClick($event, '{{ $key }}')">

                                {{-- Filled range --}}
                                <div class="absolute inset-y-0 bg-primary-300 rounded-full pointer-events-none"
                                     :style="`left:${days.{{ $key }}.dropoff/20*100}%;right:${(20-days.{{ $key }}.pickup)/20*100}%`">
                                </div>

                                {{-- Drop-off handle --}}
                                <div class="absolute top-1/2 z-20"
                                     :style="`left:${days.{{ $key }}.dropoff/20*100}%;transform:translate(-50%,-50%)`">
                                    <div class="absolute whitespace-nowrap text-xs font-semibold text-white bg-primary-500 px-2.5 py-1 rounded-full shadow-md pointer-events-none"
                                         style="bottom: calc(100% + 10px); left: 50%; transform: translateX(-50%)">
                                        <span x-text="slotToTime(days.{{ $key }}.dropoff)"></span>
                                    </div>
                                    <div class="w-6 h-6 rounded-full bg-primary-500 ring-[3px] ring-white shadow-lg cursor-grab transition-transform"
                                         :class="dragging?.day==='{{ $key }}'&&dragging?.handle==='dropoff' ? 'scale-125 cursor-grabbing' : 'hover:scale-110'"
                                         @mousedown.stop.prevent="startDrag('{{ $key }}','dropoff')"
                                         @touchstart.stop.prevent="startDrag('{{ $key }}','dropoff')">
                                    </div>
                                </div>

                                {{-- Pick-up handle --}}
                                <div class="absolute top-1/2 z-20"
                                     :style="`left:${days.{{ $key }}.pickup/20*100}%;transform:translate(-50%,-50%)`">
                                    <div class="absolute whitespace-nowrap text-xs font-semibold text-white bg-slate-600 px-2.5 py-1 rounded-full shadow-md pointer-events-none"
                                         style="bottom: calc(100% + 10px); left: 50%; transform: translateX(-50%)">
                                        <span x-text="slotToTime(days.{{ $key }}.pickup)"></span>
                                    </div>
                                    <div class="w-6 h-6 rounded-full bg-slate-600 ring-[3px] ring-white shadow-lg cursor-grab transition-transform"
                                         :class="dragging?.day==='{{ $key }}'&&dragging?.handle==='pickup' ? 'scale-125 cursor-grabbing' : 'hover:scale-110'"
                                         @mousedown.stop.prevent="startDrag('{{ $key }}','pickup')"
                                         @touchstart.stop.prevent="startDrag('{{ $key }}','pickup')">
                                    </div>
                                </div>
                            </div>

                            {{-- Hour labels --}}
                            <div class="flex justify-between mt-2 pointer-events-none select-none px-0.5">
                                @foreach(['7am','8','9','10','11','12pm','1','2','3','4','5pm'] as $tick)
                                    <span class="text-[10px] text-slate-300" style="width:0;display:flex;justify-content:center">{{ $tick }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach

                    {{-- Empty state --}}
                    <div x-show="!Object.values(days).some(d => d.active)" x-cloak>
                        <div class="py-10 text-center rounded-xl border-2 border-dashed border-slate-100">
                            <p class="text-sm text-slate-400">Toggle a day above to set the schedule</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">Save Changes</button>
                <a href="{{ route('portal.children.index') }}" class="btn-ghost">Cancel</a>
            </div>
        </form>

    </div>

    {{-- Notes tab --}}
    <div x-show="tab === 'notes'" x-cloak>
        <div class="card p-6">
            <h2 class="font-semibold text-slate-700 mb-4">Notes</h2>
            @if($notes->isEmpty())
                <p class="text-sm text-slate-400 mb-4">No notes yet.</p>
            @else
                <div class="space-y-3 mb-6">
                    @foreach($notes->sortByDesc('createdAt') as $note)
                        <div class="bg-slate-50 rounded-lg p-3">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-medium text-slate-600">{{ $note->employee?->name ?? 'Unknown' }}</span>
                                <span class="text-xs text-slate-400">{{ $note->createdAt->format('M j, Y g:i A') }}</span>
                            </div>
                            <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $note->content }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
            <form method="POST" action="{{ route('portal.children.notes.add', $child->id) }}">
                @csrf
                <textarea name="content" rows="3" required class="input mb-2 resize-none" placeholder="Add a note…"></textarea>
                <button type="submit" class="btn-primary text-sm">Add Note</button>
            </form>
        </div>
    </div>

    {{-- Documents tab --}}
    <div x-show="tab === 'documents'" x-cloak
         x-data="{
             uploading: false,
             uploadProgress: 0,
             uploadDone: false,
             uploadError: '',
             fileName: '',
             filePreview: null,
             fileIsImage: false,

             onFileChange(e) {
                 const f = e.target.files[0];
                 if (!f) return;
                 this.fileName    = f.name;
                 this.fileIsImage = f.type.startsWith('image/');
                 this.uploadError = '';
                 if (this.fileIsImage) {
                     const reader = new FileReader();
                     reader.onload = ev => { this.filePreview = ev.target.result; };
                     reader.readAsDataURL(f);
                 } else {
                     this.filePreview = null;
                 }
             },

             doUpload(e) {
                 e.preventDefault();
                 if (!this.fileName) return;
                 const fd = new FormData(e.target);
                 this.uploading = true;
                 this.uploadProgress = 0;
                 this.uploadDone  = false;
                 this.uploadError = '';
                 const xhr = new XMLHttpRequest();
                 xhr.upload.onprogress = ev => {
                     if (ev.lengthComputable)
                         this.uploadProgress = Math.round(ev.loaded / ev.total * 100);
                 };
                 xhr.onload = () => {
                     this.uploadProgress = 100;
                     this.uploadDone = true;
                     setTimeout(() => window.location.href = window.location.pathname + '?tab=documents', 800);
                 };
                 xhr.onerror = () => {
                     this.uploadError = 'Upload failed. Please try again.';
                     this.uploading   = false;
                 };
                 xhr.open('POST', e.target.action);
                 xhr.send(fd);
             },

             reset() {
                 this.uploading = false; this.uploadProgress = 0;
                 this.uploadDone = false; this.uploadError = '';
                 this.fileName = ''; this.filePreview = null; this.fileIsImage = false;
             }
         }">

        {{-- Document list --}}
        <div class="card overflow-hidden mb-4">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-semibold text-slate-700">Documents</h2>
                <span class="text-xs text-slate-400">{{ $child->documents->count() }} {{ Str::plural('file', $child->documents->count()) }}</span>
            </div>

            @if($child->documents->isEmpty())
                <div class="px-6 py-12 text-center">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                        <x-icon name="folder" class="w-6 h-6 text-slate-300" />
                    </div>
                    <p class="text-sm font-medium text-slate-400">No documents yet</p>
                    <p class="text-xs text-slate-300 mt-1">Upload files below</p>
                </div>
            @else
                <ul class="divide-y divide-slate-50">
                    @foreach($child->documents as $doc)
                        @php
                            $ext      = strtolower(pathinfo($doc->name, PATHINFO_EXTENSION));
                            $isImage  = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                            $isPdf    = $ext === 'pdf';
                            $isWord   = in_array($ext, ['doc','docx']);
                            [$iconBg, $iconColor, $iconName] = match(true) {
                                $isImage => ['bg-emerald-50', 'text-emerald-500', 'image'],
                                $isPdf   => ['bg-red-50',     'text-red-500',     'file-text'],
                                $isWord  => ['bg-blue-50',    'text-blue-500',    'file-text'],
                                default  => ['bg-slate-100',  'text-slate-400',   'file'],
                            };
                        @endphp
                        <li class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors group">

                            {{-- Thumbnail / icon --}}
                            <div class="shrink-0 w-12 h-12 rounded-lg overflow-hidden {{ $isImage ? '' : $iconBg . ' flex items-center justify-center' }}">
                                @if($isImage)
                                    <img src="{{ $doc->fileUrl }}" alt="{{ $doc->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <x-icon name="{{ $iconName }}" class="w-5 h-5 {{ $iconColor }}" />
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <a href="{{ $doc->fileUrl }}" target="_blank"
                                   class="text-sm font-medium text-slate-700 hover:text-primary-600 truncate block leading-snug transition-colors">
                                    {{ $doc->name }}
                                </a>
                                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                    <span class="text-xs text-slate-400 uppercase tracking-wide">{{ strtoupper($ext) }}</span>
                                    @if($doc->uploadedByParent)
                                        <span class="text-xs bg-sky-100 text-sky-600 px-1.5 py-0.5 rounded font-medium">Parent upload</span>
                                    @else
                                        <span class="text-xs bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded font-medium">Staff upload</span>
                                    @endif
                                    @if(isset($doc->createdAt))
                                        <span class="text-xs text-slate-300">{{ $doc->createdAt->format('M j, Y') }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ $doc->fileUrl }}" target="_blank"
                                   class="p-1.5 rounded-lg text-slate-400 hover:text-primary-600 hover:bg-primary-50 transition-colors"
                                   title="Open">
                                    <x-icon name="external-link" class="w-4 h-4" />
                                </a>
                                <form method="POST"
                                      action="{{ route('portal.children.documents.delete', [$child->id, $doc->id]) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($doc->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-1.5 rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 transition-colors"
                                            title="Delete">
                                        <x-icon name="trash" class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Upload card --}}
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="font-semibold text-slate-700">Upload Document</h2>
                <p class="text-xs text-slate-400 mt-0.5">PDF, Word, or image — up to 10 MB</p>
            </div>

            <div class="p-6">
                <form @submit="doUpload" method="POST"
                      action="{{ route('portal.children.documents.upload', $child->id) }}"
                      enctype="multipart/form-data">
                    @csrf

                    {{-- File picker zone --}}
                    <div x-show="!uploading" class="mb-4">
                        <label class="block cursor-pointer rounded-xl border-2 border-dashed transition-colors"
                               :class="fileName
                                   ? 'border-primary-300 bg-primary-50'
                                   : 'border-slate-200 hover:border-primary-300 hover:bg-slate-50'">
                            <div class="p-6">
                                <template x-if="!fileName">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center mb-3">
                                            <x-icon name="upload" class="w-5 h-5 text-slate-400" />
                                        </div>
                                        <p class="text-sm font-medium text-slate-600 mb-1">Click to select a file</p>
                                        <p class="text-xs text-slate-400">PDF, JPG, PNG, DOC, DOCX</p>
                                    </div>
                                </template>
                                <template x-if="fileName && !fileIsImage">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center shrink-0">
                                            <x-icon name="file-text" class="w-6 h-6 text-primary-500" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-primary-700 truncate" x-text="fileName"></p>
                                            <p class="text-xs text-slate-400 mt-0.5">Click to change</p>
                                        </div>
                                        <div class="ml-auto shrink-0">
                                            <x-icon name="check-circle" class="w-5 h-5 text-primary-500" />
                                        </div>
                                    </div>
                                </template>
                                <template x-if="fileName && fileIsImage">
                                    <div class="flex items-center gap-4">
                                        <img :src="filePreview" class="w-16 h-16 rounded-lg object-cover shrink-0 shadow-sm">
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-primary-700 truncate" x-text="fileName"></p>
                                            <p class="text-xs text-slate-400 mt-0.5">Click to change</p>
                                        </div>
                                        <div class="ml-auto shrink-0">
                                            <x-icon name="check-circle" class="w-5 h-5 text-primary-500" />
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <input type="file" name="file" required class="hidden"
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                   @change="onFileChange($event)"
                                   :disabled="uploading">
                        </label>
                        @error('file') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Upload progress --}}
                    <div x-show="uploading" class="mb-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center shrink-0">
                                <x-icon name="file" class="w-5 h-5 text-primary-600" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-700 truncate" x-text="fileName"></p>
                                <p class="text-xs text-slate-400" x-text="uploadDone ? 'Upload complete!' : 'Uploading…'"></p>
                            </div>
                            <span class="text-sm font-semibold tabular-nums shrink-0"
                                  :class="uploadDone ? 'text-emerald-600' : 'text-slate-600'"
                                  x-text="uploadProgress + '%'"></span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-300 ease-out"
                                 :class="uploadDone ? 'bg-emerald-500' : 'bg-primary-500'"
                                 :style="'width:' + uploadProgress + '%'"></div>
                        </div>
                    </div>

                    {{-- Error --}}
                    <p x-show="uploadError" x-text="uploadError"
                       class="text-red-500 text-xs mb-3 -mt-1"></p>

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <button type="submit"
                                class="btn-primary flex-1 justify-center"
                                :disabled="uploading || !fileName">
                            <span x-show="!uploading" class="flex items-center gap-2 justify-center">
                                <x-icon name="upload" class="w-4 h-4" /> Upload
                            </span>
                            <span x-show="uploading" class="flex items-center gap-2 justify-center">
                                <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                                </svg>
                                Uploading…
                            </span>
                        </button>
                        <button type="button" x-show="fileName && !uploading"
                                @click="reset(); $el.closest('form').reset()"
                                class="btn-ghost">
                            Clear
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


{{-- Photo Modal --}}
<div x-show="photoModal" x-cloak
     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
     @keydown.escape.window="photoModal = false; $dispatch('photo-modal-closed')"
     @click.self="photoModal = false; $dispatch('photo-modal-closed')">

    <div x-data="childPhotoUpload()"
         x-init="$watch('$root._x_dataStack?.[0]?.photoModal ?? true', v => { if (v) reset(); })"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="bg-white rounded-xl shadow-xl w-full max-w-sm overflow-hidden"
         @click.stop>

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 pt-5 pb-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-800">Edit Photo</h2>
            <button type="button" @click="photoModal = false; reset()"
                    class="text-slate-400 hover:text-slate-600 transition-colors">
                <x-icon name="x" class="w-5 h-5" />
            </button>
        </div>

        <div class="p-5 space-y-4">

            {{-- Child info row --}}
            <div class="flex items-center gap-3">
                <div class="shrink-0 w-12 h-12 rounded-full overflow-hidden bg-primary-100 flex items-center justify-center">
                    @if($child->photoUrl)
                        <img src="{{ $child->photoUrl }}" alt="{{ $child->firstName }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-primary-700 font-bold text-lg">{{ strtoupper(substr($child->firstName, 0, 1)) }}</span>
                    @endif
                </div>
                <div>
                    <p class="font-medium text-slate-700 text-sm">{{ $child->firstName }} {{ $child->lastName }}</p>
                    <p class="text-xs text-slate-400">{{ $child->photoUrl ? 'Has a photo on file' : 'No photo on file' }}</p>
                </div>
            </div>

            {{-- Hidden file input --}}
            <input type="file" id="child-photo-file-{{ $child->id }}"
                   class="sr-only" accept="image/jpeg,image/png,image/webp"
                   @change="pickFile($el.files)">

            {{-- Drop zone --}}
            <div x-show="!file && !done">
                <div @click="document.getElementById('child-photo-file-{{ $child->id }}').click()"
                     @dragover.prevent="dragging = true"
                     @dragleave.prevent="dragging = false"
                     @drop.prevent="dragging = false; pickFile($event.dataTransfer.files)"
                     :class="dragging
                         ? 'border-primary-400 bg-primary-50'
                         : 'border-slate-200 hover:border-primary-300 hover:bg-slate-50'"
                     class="cursor-pointer border-2 border-dashed rounded-xl p-8 flex flex-col items-center gap-2 transition-colors text-center select-none">
                    <x-icon name="camera" class="w-9 h-9 text-slate-300" />
                    <p class="text-sm font-medium text-slate-600">Click or drag a photo here</p>
                    <p class="text-xs text-slate-400">JPG, PNG or WebP &middot; max 5 MB</p>
                </div>
                <p x-show="error" x-text="error" class="text-xs text-red-500 mt-1.5"></p>
            </div>

            {{-- Preview --}}
            <div x-show="file && !done" class="relative rounded-xl overflow-hidden bg-slate-100" style="height:200px">
                <img :src="preview" class="w-full h-full object-cover">
                <button type="button"
                        @click="document.getElementById('child-photo-file-{{ $child->id }}').click()"
                        class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm rounded-lg px-2.5 py-1 text-xs font-medium text-slate-700 hover:bg-white transition-colors shadow-sm">
                    Change
                </button>
            </div>

            {{-- Progress bar --}}
            <div x-show="uploading || (progress > 0 && !error)">
                <div class="flex justify-between text-xs text-slate-500 mb-1">
                    <span x-text="done ? 'Complete' : 'Uploading…'"></span>
                    <span x-text="progress + '%'"></span>
                </div>
                <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-300 ease-out"
                         :class="done ? 'bg-emerald-500' : 'bg-primary-500'"
                         :style="'width:' + progress + '%'"></div>
                </div>
            </div>

            {{-- Success state --}}
            <div x-show="done" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200">
                <x-icon name="check-circle" class="w-5 h-5 text-emerald-500 shrink-0" />
                <div>
                    <p class="text-sm font-semibold text-emerald-700">Photo updated!</p>
                    <p class="text-xs text-emerald-600">Reloading page…</p>
                </div>
            </div>

            {{-- Upload button --}}
            <button x-show="file && !uploading && !done"
                    @click="upload('{{ route('portal.children.photo', $child->id) }}')"
                    class="btn-primary w-full text-sm">
                <x-icon name="camera" class="w-4 h-4" />
                Upload Photo
            </button>

            @if($child->photoUrl)
                {{-- Divider --}}
                <div x-show="!uploading && !done" class="flex items-center gap-3">
                    <div class="flex-1 h-px bg-slate-100"></div>
                    <span class="text-xs text-slate-400">or</span>
                    <div class="flex-1 h-px bg-slate-100"></div>
                </div>

                {{-- Remove — shows inline confirm --}}
                <div x-show="!uploading && !done">
                    <button x-show="!confirmRemove"
                            @click="confirmRemove = true"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-red-200 text-red-500 text-sm font-medium hover:bg-red-50 transition-colors">
                        <x-icon name="trash" class="w-4 h-4" />
                        Remove Photo
                    </button>

                    <div x-show="confirmRemove"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="rounded-lg bg-red-50 border border-red-200 p-4">
                        <p class="text-sm font-semibold text-red-700 text-center mb-3">Remove this photo?</p>
                        <div class="flex gap-2">
                            <button @click="confirmRemove = false" class="flex-1 btn-ghost text-sm">Cancel</button>
                            <form method="POST" action="{{ route('portal.children.photo.clear', $child->id) }}" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-full px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-semibold transition-colors">
                                    Yes, Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- Add Contact Modal --}}
<div x-show="contactModal" x-cloak
     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
     @click.self="contactModal = false">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md" @click.stop>
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Add Emergency Contact</h2>
        <form method="POST" action="{{ route('portal.children.contacts.add', $child->id) }}">
            @csrf
            <div class="mb-4">
                <label class="label">Contact ID</label>
                <input type="text" name="contactId" required class="input text-sm"
                       placeholder="Paste contact ID from Families page">
            </div>
            <div class="mb-4">
                <label class="label">Relationship</label>
                <select name="relationship" class="input text-sm">
                    <option>Parent</option>
                    <option>Guardian</option>
                    <option>Grandparent</option>
                    <option>Aunt/Uncle</option>
                    <option>Sibling</option>
                    <option>Friend</option>
                    <option>Other</option>
                </select>
            </div>
            <div class="mb-4 flex items-center gap-2">
                <input type="checkbox" name="isPrimary" value="1" id="isPrimary" class="rounded">
                <label for="isPrimary" class="text-sm text-slate-700">Primary contact</label>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">Add</button>
                <button type="button" @click="contactModal = false" class="btn-ghost flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Contact Modal --}}
<div x-show="editingContact !== null" x-cloak
     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
     @keydown.escape.window="editingContact = null"
     @click.self="editingContact = null">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         @click.stop>

        <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-800" x-text="editingContact?.name ?? 'Edit Contact'"></h2>
            <button type="button" @click="editingContact = null"
                    class="text-slate-400 hover:text-slate-600 transition-colors">
                <x-icon name="x" class="w-5 h-5" />
            </button>
        </div>

        <form method="POST"
              :action="'/portal/children/{{ $child->id }}/contacts/' + (editingContact?.id ?? '')">
            @csrf
            @method('PATCH')

            <div class="p-6 space-y-3">

                <div>
                    <label class="label">Name</label>
                    <input type="text" name="name" x-model="editingContact.name" required class="input text-sm">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="label">Phone</label>
                        <input type="text" name="phone" x-model="editingContact.phone" class="input text-sm">
                    </div>
                    <div>
                        <label class="label">Email</label>
                        <input type="email" name="email" x-model="editingContact.email" class="input text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="label">Relationship</label>
                        <select name="relationship" x-model="editingContact.relationship" class="input text-sm">
                            @foreach(['Parent','Guardian','Grandparent','Aunt/Uncle','Sibling','Friend','Other'] as $rel)
                                <option value="{{ $rel }}">{{ $rel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="isPrimary" value="1"
                                   :checked="editingContact?.isPrimary" class="rounded">
                            <span class="text-sm text-slate-700">Primary</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-1">
                    <a :href="'/portal/contacts/' + (editingContact?.contactId ?? '')"
                       class="text-xs text-primary-600 hover:underline">
                        View full contact record →
                    </a>
                </div>

            </div>

            <div class="px-6 pb-6 flex gap-2">
                <button type="submit" class="btn-primary flex-1">Save Changes</button>
                <button type="button" @click="editingContact = null" class="btn-ghost flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>

</div>{{-- /x-data --}}
@endsection

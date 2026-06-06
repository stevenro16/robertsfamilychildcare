@extends('layouts.portal')
@section('title', $inquiry->parentName . ' — Inquiry')

@php
$lifecycleSteps = [
    [
        'value' => 'NEW',
        'label' => 'New',
        'sub'   => 'Inquiry received',
    ],
    [
        'value' => 'LEFT_VOICEMAIL',
        'label' => 'Left Voicemail',
        'sub'   => 'Awaiting callback',
    ],
    [
        'value' => 'LEFT_VOICEMAIL_FINAL',
        'label' => 'Final Voicemail',
        'sub'   => 'Last attempt made',
    ],
    [
        'value' => 'PROVIDED_PRICING_WAITING',
        'label' => 'Provided Pricing',
        'sub'   => 'Waiting on decision',
    ],
    [
        'value' => 'COMPLETE',
        'label' => 'Complete',
        'sub'   => 'Enrolled or closed',
    ],
];

$statusOrder   = array_column($lifecycleSteps, 'value');
// LEFT_VOICEMAIL_2 maps visually to LEFT_VOICEMAIL
$displayStatus = $inquiry->status === 'LEFT_VOICEMAIL_2' ? 'LEFT_VOICEMAIL' : $inquiry->status;
$currentIdx    = array_search($displayStatus, $statusOrder);
$currentIdx    = $currentIdx === false ? -1 : (int) $currentIdx;
$isWhenRoom    = $inquiry->status === 'FOLLOW_UP_WHEN_ROOM';
@endphp

@section('portal-content')
<div x-data="{
    snoozeOpen: false,
    editing: false,
    snoozeDate: '{{ $inquiry->snoozeUntil ? $inquiry->snoozeUntil->format('Y-m-d') : '' }}',
    presetDate(days) {
        const d = new Date();
        d.setDate(d.getDate() + days);
        return d.toISOString().split('T')[0];
    },
    nextMonthDate() {
        const d = new Date();
        d.setMonth(d.getMonth() + 1, 1);
        return d.toISOString().split('T')[0];
    }
}">

    {{-- Hidden form for lifecycle step clicks --}}
    <form method="POST"
          action="{{ route('portal.inquiries.update', $inquiry->id) }}"
          x-ref="statusForm" class="hidden">
        @csrf
        <input type="hidden" name="_method" value="PATCH">
        <input type="hidden" name="status" x-ref="statusInput">
    </form>

    {{-- Hidden form for When Room --}}
    <form method="POST"
          action="{{ route('portal.inquiries.update', $inquiry->id) }}"
          id="when-room-form" class="hidden">
        @csrf
        <input type="hidden" name="_method" value="PATCH">
        <input type="hidden" name="status" value="FOLLOW_UP_WHEN_ROOM">
    </form>

    {{-- Section header --}}
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2.5">
            <x-icon name="message-circle" class="w-6 h-6 text-primary-500" />
            Inquiries
        </h1>
    </div>
    <div class="h-0.5 bg-linear-to-r from-primary-400 to-transparent rounded-full mb-4"></div>

    {{-- Back + inquiry name --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('portal.inquiries.index') }}" class="text-slate-400 hover:text-slate-600">
            <x-icon name="chevron-left" class="w-5 h-5" />
        </a>
        <h2 class="text-xl font-semibold text-slate-800">{{ $inquiry->parentName }}</h2>
        @if($isWhenRoom)
            <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-slate-100 text-slate-600">
                When Room Available
            </span>
        @else
            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                {{ match($inquiry->status) {
                    'NEW'                       => 'bg-blue-100 text-blue-700',
                    'LEFT_VOICEMAIL',
                    'LEFT_VOICEMAIL_2'          => 'bg-yellow-100 text-yellow-700',
                    'LEFT_VOICEMAIL_FINAL'      => 'bg-red-100 text-red-700',
                    'PROVIDED_PRICING_WAITING'  => 'bg-purple-100 text-purple-700',
                    'COMPLETE'                  => 'bg-green-100 text-green-700',
                    default                     => 'bg-slate-100 text-slate-600',
                } }}">
                {{ $lifecycleSteps[$currentIdx]['label'] ?? str_replace('_', ' ', $inquiry->status) }}
            </span>
        @endif
        @if($inquiry->isSnoozed && $inquiry->snoozeUntil?->isFuture())
            <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-indigo-100 text-indigo-700">
                Snoozed until {{ $inquiry->snoozeUntil->format('M j') }}
            </span>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main column --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Contact info card --}}
            <div class="card p-6">
                <form method="POST" action="{{ route('portal.inquiries.update', $inquiry->id) }}">
                    @csrf
                    @method('PATCH')

                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-slate-700">Contact Information</h2>
                        <div class="flex items-center gap-2">
                            <button type="button" x-show="!editing" @click="editing = true"
                                    class="btn-ghost text-xs gap-1.5">
                                <x-icon name="edit" class="w-3.5 h-3.5" /> Edit
                            </button>
                            <button type="submit" x-show="editing" x-cloak
                                    class="btn-primary text-xs px-3 py-1.5">
                                Save
                            </button>
                            <button type="button" x-show="editing" x-cloak @click="editing = false"
                                    class="btn-ghost text-xs px-3 py-1.5">
                                Cancel
                            </button>
                        </div>
                    </div>

                    {{-- View mode --}}
                    <dl x-show="!editing" class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div>
                            <dt class="text-slate-400 mb-0.5">Parent Name</dt>
                            <dd class="font-medium text-slate-800">{{ $inquiry->parentName ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 mb-0.5">Child's Name</dt>
                            <dd class="font-medium text-slate-800">{{ $inquiry->childName ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 mb-0.5">Email</dt>
                            <dd>
                                @if($inquiry->parentEmail)
                                    <a href="mailto:{{ $inquiry->parentEmail }}" class="text-primary-600 hover:underline">{{ $inquiry->parentEmail }}</a>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 mb-0.5">Phone</dt>
                            <dd>
                                @if($inquiry->parentPhone)
                                    <a href="tel:{{ $inquiry->parentPhone }}" class="text-primary-600 hover:underline">{{ $inquiry->parentPhone }}</a>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 mb-0.5">Child DOB</dt>
                            <dd class="text-slate-800">
                                {{ $inquiry->childDob ? \Carbon\Carbon::parse($inquiry->childDob)->format('M j, Y') : '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 mb-0.5">Desired Start</dt>
                            <dd class="text-slate-800">
                                {{ $inquiry->desiredStart ? \Carbon\Carbon::parse($inquiry->desiredStart)->format('M j, Y') : '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 mb-0.5">Program Interest</dt>
                            <dd class="text-slate-800">{{ $inquiry->programInterest ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 mb-0.5">Heard About Us</dt>
                            <dd class="text-slate-800">{{ $inquiry->hearAbout ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 mb-0.5">Received</dt>
                            <dd class="text-slate-600">{{ $inquiry->createdAt->format('M j, Y g:i A') }}</dd>
                        </div>
                    </dl>
                    @if($inquiry->message)
                        <div x-show="!editing" class="mt-4 pt-4 border-t border-slate-100">
                            <p class="text-xs text-slate-400 mb-1">Message</p>
                            <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $inquiry->message }}</p>
                        </div>
                    @endif

                    {{-- Edit mode --}}
                    <div x-show="editing" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div>
                            <label class="label">Parent Name</label>
                            <input type="text" name="parentName" value="{{ $inquiry->parentName }}" required class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Child's Name</label>
                            <input type="text" name="childName" value="{{ $inquiry->childName }}" class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Email</label>
                            <input type="email" name="parentEmail" value="{{ $inquiry->parentEmail }}" class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Phone</label>
                            <input type="text" name="parentPhone" value="{{ $inquiry->parentPhone }}" class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Child DOB</label>
                            <input type="date" name="childDob"
                                   value="{{ $inquiry->childDob ? \Carbon\Carbon::parse($inquiry->childDob)->format('Y-m-d') : '' }}"
                                   class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Desired Start Date</label>
                            <input type="date" name="desiredStart"
                                   value="{{ $inquiry->desiredStart ? \Carbon\Carbon::parse($inquiry->desiredStart)->format('Y-m-d') : '' }}"
                                   class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Program Interest</label>
                            @php $piOptions = ['Infant (6–12 mo)','Young Toddler (12–18 mo)','Toddler (18–36 mo)','Preschool (3–5 yrs)','School Age (5–10 yrs)']; @endphp
                            <select name="programInterest" class="input text-sm">
                                <option value="">— none —</option>
                                @if($inquiry->programInterest && !in_array($inquiry->programInterest, $piOptions))
                                    <option value="{{ $inquiry->programInterest }}" selected>{{ $inquiry->programInterest }}</option>
                                @endif
                                @foreach($piOptions as $opt)
                                    <option value="{{ $opt }}" @selected($inquiry->programInterest === $opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="label">How They Heard About Us</label>
                            @php $haOptions = ['Google Search','Facebook','Instagram','Friend/Family Referral','Nextdoor','Driving By','Other']; @endphp
                            <select name="hearAbout" class="input text-sm">
                                <option value="">— none —</option>
                                @if($inquiry->hearAbout && !in_array($inquiry->hearAbout, $haOptions))
                                    <option value="{{ $inquiry->hearAbout }}" selected>{{ $inquiry->hearAbout }}</option>
                                @endif
                                @foreach($haOptions as $opt)
                                    <option value="{{ $opt }}" @selected($inquiry->hearAbout === $opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="label">Message</label>
                            <textarea name="message" rows="3" class="input text-sm resize-none">{{ $inquiry->message }}</textarea>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Notes --}}
            <div class="card p-6">
                <h2 class="font-semibold text-slate-700 mb-4">Notes</h2>

                @if($inquiry->notes->isEmpty())
                    <p class="text-sm text-slate-400 mb-4">No notes yet.</p>
                @else
                    <div class="space-y-3 mb-4">
                        @foreach($inquiry->notes as $note)
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

                <form method="POST" action="{{ route('portal.inquiries.notes.add', $inquiry->id) }}">
                    @csrf
                    <textarea name="content" rows="3" required
                        class="input mb-2 resize-none"
                        placeholder="Add a note…"></textarea>
                    @error('content') <p class="text-red-500 text-xs mb-2">{{ $message }}</p> @enderror
                    <button type="submit" class="btn-primary text-sm">Add Note</button>
                </form>
            </div>

            {{-- Audit trail --}}
            @if($inquiry->statusHistory->isNotEmpty())
            <div class="card p-6">
                <h2 class="font-semibold text-slate-700 mb-4">Audit Trail</h2>
                <div class="space-y-2">
                    @foreach($inquiry->statusHistory as $entry)
                        @if($entry->newStatus === 'NOTIFICATION_SENT')
                            <div class="flex items-center gap-2 text-sm">
                                <span class="text-slate-400 text-xs w-36 shrink-0">{{ $entry->createdAt->format('M j, Y g:i A') }}</span>
                                <x-icon name="envelope" class="w-3.5 h-3.5 text-sky-400 shrink-0" />
                                <span class="font-medium text-sky-600">Notification email sent</span>
                                <span class="text-slate-400 text-xs">
                                    by {{ $entry->employee?->name ?? 'system' }}
                                </span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-sm">
                                <span class="text-slate-400 text-xs w-36 shrink-0">{{ $entry->createdAt->format('M j, Y g:i A') }}</span>
                                @if($entry->oldStatus)
                                    <span class="text-slate-400 text-xs">{{ str_replace('_', ' ', $entry->oldStatus) }}</span>
                                    <x-icon name="arrow-right" class="w-3 h-3 text-slate-300 shrink-0" />
                                @endif
                                <span class="font-medium text-slate-600">{{ str_replace('_', ' ', $entry->newStatus) }}</span>
                                @if($entry->employee)
                                    <span class="text-slate-400 text-xs">by {{ $entry->employee->name }}</span>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">

            {{-- ── Lifecycle Tracker ─────────────────────────────── --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-700">Inquiry Lifecycle</h3>
                    @if($isWhenRoom)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 font-medium">
                            Parked
                        </span>
                    @endif
                </div>

                <div class="px-5 pt-5 pb-1">
                    @foreach($lifecycleSteps as $i => $step)
                        @php
                            $isPast    = $i < $currentIdx;
                            $isCurrent = $i === $currentIdx && !$isWhenRoom;
                            $isFuture  = ($i > $currentIdx) || ($isWhenRoom && $i >= 0);
                            $isLast    = $loop->last;
                        @endphp

                        <div class="flex gap-3">
                            {{-- Circle + connector --}}
                            <div class="flex flex-col items-center shrink-0">
                                @if($isCurrent)
                                    {{-- Current: not clickable --}}
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center
                                                bg-primary-600 ring-4 ring-primary-100 shrink-0">
                                        <div class="w-2.5 h-2.5 rounded-full bg-white"></div>
                                    </div>
                                @elseif($isPast)
                                    {{-- Past: clickable to go back --}}
                                    <button type="button"
                                            title="Move back to {{ $step['label'] }}"
                                            @click="$refs.statusInput.value = '{{ $step['value'] }}'; $refs.statusForm.submit()"
                                            class="w-8 h-8 rounded-full flex items-center justify-center
                                                   bg-green-500 hover:bg-green-600 transition-colors shrink-0">
                                        <x-icon name="check" class="w-3.5 h-3.5 text-white" />
                                    </button>
                                @else
                                    {{-- Future: clickable to advance --}}
                                    <button type="button"
                                            title="Advance to {{ $step['label'] }}"
                                            @click="$refs.statusInput.value = '{{ $step['value'] }}'; $refs.statusForm.submit()"
                                            class="w-8 h-8 rounded-full flex items-center justify-center border-2
                                                   border-slate-200 bg-white hover:border-primary-400 hover:bg-primary-50
                                                   transition-colors shrink-0">
                                        <div class="w-2 h-2 rounded-full bg-slate-200 group-hover:bg-primary-300"></div>
                                    </button>
                                @endif

                                {{-- Connector line --}}
                                @if(!$isLast)
                                    <div class="w-px flex-1 my-1 min-h-5
                                                {{ $isPast ? 'bg-green-300' : 'bg-slate-200' }}">
                                    </div>
                                @endif
                            </div>

                            {{-- Label --}}
                            <div class="{{ $isLast ? 'pb-1' : 'pb-5' }} pt-1 min-w-0">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <p class="text-sm leading-tight
                                              {{ $isCurrent ? 'font-semibold text-slate-800' : ($isPast ? 'text-slate-400' : 'text-slate-500') }}">
                                        {{ $step['label'] }}
                                    </p>
                                    @if($isCurrent)
                                        <span class="text-xs px-1.5 py-0.5 rounded bg-primary-100 text-primary-700 font-medium leading-tight">
                                            Current
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs mt-0.5 {{ $isCurrent ? 'text-slate-500' : 'text-slate-300' }}">
                                    {{ $step['sub'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- When Room Available indicator --}}
                @if($isWhenRoom)
                    <div class="mx-5 mb-4 px-3 py-2.5 rounded-lg bg-slate-50 border border-slate-200 flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-slate-400 shrink-0"></div>
                        <span class="text-xs text-slate-600 font-medium">Parked — waiting for room availability</span>
                    </div>
                @endif

                {{-- Action buttons --}}
                <div class="px-5 pb-5 pt-3 border-t border-slate-100 space-y-2">
                    {{-- Snooze --}}
                    <button @click="snoozeOpen = true"
                            class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg border border-indigo-200 bg-indigo-50 text-indigo-700 text-sm font-medium hover:bg-indigo-100 transition-colors">
                        <x-icon name="bell" class="w-4 h-4" />
                        @if($inquiry->isSnoozed && $inquiry->snoozeUntil?->isFuture())
                            Snoozed until {{ $inquiry->snoozeUntil->format('M j') }} — Edit
                        @else
                            Snooze Inquiry
                        @endif
                    </button>

                    {{-- When Room / Return to active --}}
                    @if($isWhenRoom)
                        <button type="button"
                                @click="$refs.statusInput.value = 'NEW'; $refs.statusForm.submit()"
                                class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg border border-slate-200 bg-white text-slate-600 text-sm font-medium hover:bg-primary-50 hover:border-primary-400 hover:text-primary-700 transition-colors">
                            <x-icon name="arrow-left" class="w-4 h-4" />
                            Return to Active Queue
                        </button>
                    @else
                        <button type="button"
                                onclick="document.getElementById('when-room-form').submit()"
                                class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg border border-slate-200 bg-white text-slate-600 text-sm font-medium hover:bg-slate-50 transition-colors">
                            <x-icon name="clock" class="w-4 h-4" />
                            Follow Up When Room
                        </button>
                    @endif
                </div>
            </div>

            {{-- Send notification email --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-700">Notification Email</h3>
                </div>
                <div class="px-5 py-4 space-y-3">
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Sends the full inquiry details to
                        <span class="font-medium text-slate-700">{{ env('NOTIFICATION_EMAIL', 'not configured') }}</span>.
                    </p>
                    <form method="POST" action="{{ route('portal.inquiries.notify', $inquiry->id) }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg border border-sky-200 bg-sky-50 text-sky-700 text-sm font-medium hover:bg-sky-100 transition-colors">
                            <x-icon name="envelope" class="w-4 h-4" />
                            Send Notification Email
                        </button>
                    </form>
                </div>
            </div>

            {{-- Convert to child account --}}
            @if($inquiry->child)
            <div class="card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-2">Linked Child</h3>
                <a href="{{ route('portal.children.show', $inquiry->child->id) }}"
                   class="flex items-center gap-2 text-sm text-primary-600 hover:underline">
                    <x-icon name="users" class="w-4 h-4" />
                    {{ $inquiry->child->firstName }} {{ $inquiry->child->lastName }}
                </a>
            </div>
            @else
            <div class="card overflow-hidden" x-data="{ autoComplete: true }">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-700">Convert to Child Account</h3>
                </div>
                <div class="px-5 py-4 space-y-3">
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Creates a child record pre-filled with the name and date of birth from this inquiry, so you can start adding contacts, documents, and schedule details right away.
                    </p>

                    <label class="flex items-start gap-2.5 cursor-pointer select-none">
                        <input type="checkbox" x-model="autoComplete"
                               class="mt-0.5 rounded border-slate-300 text-primary-500 focus:ring-primary-400 shrink-0">
                        <span class="text-xs text-slate-600 leading-snug">
                            <span class="font-medium text-slate-700">Auto-complete inquiry</span><br>
                            Moves this inquiry to <em>Complete</em> and logs a note explaining it was converted.
                        </span>
                    </label>

                    <form method="POST" action="{{ route('portal.inquiries.convert', $inquiry->id) }}">
                        @csrf
                        <input type="hidden" name="autoComplete" :value="autoComplete ? '1' : '0'">
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition-colors">
                            <x-icon name="user-plus" class="w-4 h-4" />
                            Convert to Child Account
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Snooze modal --}}
    <div x-show="snoozeOpen" x-cloak
         class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
         @click.self="snoozeOpen = false">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm" @click.stop>

            <div class="flex items-center justify-between mb-1">
                <h2 class="text-lg font-semibold text-slate-800">Snooze Inquiry</h2>
                <button type="button" @click="snoozeOpen = false"
                        class="text-slate-400 hover:text-slate-600 transition-colors">
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            </div>
            <p class="text-sm text-slate-500 mb-5">Hide from the active queue until a future date.</p>

            {{-- Quick presets --}}
            <div class="grid grid-cols-3 gap-2 mb-4">
                <button type="button" @click="snoozeDate = presetDate(7)"
                        :class="snoozeDate === presetDate(7)
                            ? 'border-indigo-400 bg-indigo-50 text-indigo-700 ring-2 ring-indigo-100'
                            : 'border-slate-200 text-slate-600 hover:border-indigo-300 hover:bg-slate-50'"
                        class="px-3 py-2.5 rounded-lg border text-sm font-medium transition-colors">
                    1 Week
                </button>
                <button type="button" @click="snoozeDate = presetDate(14)"
                        :class="snoozeDate === presetDate(14)
                            ? 'border-indigo-400 bg-indigo-50 text-indigo-700 ring-2 ring-indigo-100'
                            : 'border-slate-200 text-slate-600 hover:border-indigo-300 hover:bg-slate-50'"
                        class="px-3 py-2.5 rounded-lg border text-sm font-medium transition-colors">
                    2 Weeks
                </button>
                <button type="button" @click="snoozeDate = nextMonthDate()"
                        :class="snoozeDate === nextMonthDate()
                            ? 'border-indigo-400 bg-indigo-50 text-indigo-700 ring-2 ring-indigo-100'
                            : 'border-slate-200 text-slate-600 hover:border-indigo-300 hover:bg-slate-50'"
                        class="px-3 py-2.5 rounded-lg border text-sm font-medium transition-colors">
                    Next Month
                </button>
            </div>

            <div class="flex items-center gap-3 mb-4">
                <div class="flex-1 h-px bg-slate-200"></div>
                <span class="text-xs text-slate-400">or pick a date</span>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>

            <form method="POST" action="{{ route('portal.inquiries.snooze', $inquiry->id) }}">
                @csrf
                <div class="mb-5">
                    <label class="label">Custom Date</label>
                    <input type="date" name="snoozeUntil" required
                           x-model="snoozeDate"
                           min="{{ now()->addDay()->format('Y-m-d') }}"
                           class="input">
                    @error('snoozeUntil') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1" :disabled="!snoozeDate">
                        Snooze
                    </button>
                    <button type="button" @click="snoozeOpen = false" class="btn-ghost flex-1">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

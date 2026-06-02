@extends('layouts.littlelog')

@section('littlelog-content')
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
    $gradFn = fn($name) => $gradients[ord(strtolower($name[0] ?? 'a')) % count($gradients)];

    $fmtTime = function($t) {
        if (!$t || trim($t) === '') return null;
        [$h, $m] = array_pad(explode(':', $t), 2, '00');
        $h = (int)$h; $m = (int)$m;
        $ampm = $h >= 12 ? 'pm' : 'am';
        $h12 = $h === 0 ? 12 : ($h > 12 ? $h - 12 : $h);
        return $m > 0 ? "{$h12}:{$m}{$ampm}" : "{$h12}{$ampm}";
    };
@endphp

{{-- Action-button colour / text driven by data-action attribute --}}
<style>
    .ll-card[data-action="checkin"]  .ll-action-btn { background:#10b981; color:#fff; }
    .ll-card[data-action="checkout"] .ll-action-btn { background:#f59e0b; color:#fff; }
    .ll-card[data-action="checkin"]  .ll-co-text { display:none; }
    .ll-card[data-action="checkout"] .ll-ci-text { display:none; }
</style>

<div class="h-full flex"
     x-data="littleLog()"
     x-init="init()"
     @keydown.escape.window="cancelAction()">

    {{-- ══ LEFT COLUMN — Expected ══════════════════════════════════════════════ --}}
    <div class="flex flex-col w-1/2 border-r border-slate-200 bg-slate-100">

        <div class="shrink-0 flex items-center justify-between gap-3 px-5 py-3 bg-white border-b border-slate-200">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-100 text-amber-700 text-sm font-bold"
                      x-text="leftCount"></span>
                <h2 class="font-semibold text-slate-700">Expected Today</h2>
            </div>
            <button @click="showAll = !showAll"
                    :class="showAll ? 'bg-primary-100 text-primary-700 border-primary-200' : 'bg-white text-slate-500 border-slate-200'"
                    class="flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-full border transition-colors">
                <x-icon name="users" class="w-3.5 h-3.5" />
                <span x-text="showAll ? 'Showing All' : 'Show All'"></span>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4">
            <div id="ll-expected" class="flex flex-wrap gap-3 content-start min-h-32">

                {{-- Scheduled today --}}
                @foreach($expected as $child)
                    @php
                        $sched   = is_array($child->schedule) ? $child->schedule : [];
                        $entry   = $sched[$today] ?? null;
                        $dropoff = is_array($entry) ? $fmtTime($entry['dropoff'] ?? null) : null;
                        $pickup  = is_array($entry) ? $fmtTime($entry['pickup']  ?? null) : null;
                        $timeStr = ($dropoff && $pickup) ? "{$dropoff}–{$pickup}" : ($dropoff ?? $pickup ?? '');
                        $grad    = $gradFn($child->firstName);
                        $dob     = $child->dateOfBirth ? \Carbon\Carbon::parse($child->dateOfBirth) : null;
                        $months  = $dob ? $dob->diffInMonths(now()) : null;
                        $ageStr  = $dob ? ($months < 24 ? $months . ' mo' : $dob->age . ' yr' . ($dob->age !== 1 ? 's' : '')) : null;
                    @endphp
                    <div class="ll-card relative rounded-xl overflow-hidden shadow-sm border border-slate-200
                                cursor-pointer select-none group"
                         style="width: calc(50% - 6px); height: 520px;"
                         data-action="checkin"
                         data-child-id="{{ $child->id }}"
                         data-child-name="{{ $child->firstName }} {{ $child->lastName }}"
                         data-dropoff="{{ $dropoff }}"
                         data-pickup="{{ $pickup }}"
                         data-timestr="{{ $timeStr }}"
                         data-scheduled="1"
                         @click="startActionFromCard($el)">

                        @if($child->photoUrl)
                            <img src="{{ $child->photoUrl }}" alt="{{ $child->firstName }}"
                                 class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="absolute inset-0 bg-linear-to-br {{ $grad }} flex items-center justify-center">
                                <span class="text-8xl font-bold opacity-30">{{ strtoupper(substr($child->firstName, 0, 1)) }}</span>
                            </div>
                        @endif

                        {{-- Subtle hover darkening --}}
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-200 z-10 pointer-events-none"></div>

                        <span class="ll-pulse hidden absolute top-2 right-2 z-20 items-center gap-1 text-xs bg-emerald-500 text-white px-2 py-0.5 rounded-full font-medium shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                            In
                        </span>

                        <div class="absolute bottom-0 left-0 right-0 z-20 bg-white/70 backdrop-blur-sm">
                            <div class="px-3 pt-2.5 pb-2">
                                <p class="font-semibold text-slate-800 text-sm leading-tight truncate">{{ $child->firstName }} {{ $child->lastName }}</p>
                                @if($ageStr)
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $ageStr }}</p>
                                @endif
                                @if($timeStr)
                                    <p class="ll-timeinfo text-xs text-primary-600 font-medium mt-1">{{ $timeStr }}</p>
                                @else
                                    <p class="ll-timeinfo text-xs text-slate-400 mt-1"></p>
                                @endif
                            </div>
                            <div class="ll-action-btn w-full py-3 text-center text-sm font-bold">
                                <span class="ll-ci-text">→&nbsp; Check In</span>
                                <span class="ll-co-text">←&nbsp; Check Out</span>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Not scheduled today (shown via Show All) --}}
                @foreach($unscheduled as $child)
                    @php
                        $grad   = $gradFn($child->firstName);
                        $dob    = $child->dateOfBirth ? \Carbon\Carbon::parse($child->dateOfBirth) : null;
                        $months = $dob ? $dob->diffInMonths(now()) : null;
                        $ageStr = $dob ? ($months < 24 ? $months . ' mo' : $dob->age . ' yr' . ($dob->age !== 1 ? 's' : '')) : null;
                    @endphp
                    <div class="ll-card relative rounded-xl overflow-hidden shadow-sm border border-dashed border-slate-300
                                cursor-pointer select-none group opacity-70"
                         x-show="showAll"
                         x-cloak
                         style="width: calc(50% - 6px); height: 520px;"
                         data-action="checkin"
                         data-child-id="{{ $child->id }}"
                         data-child-name="{{ $child->firstName }} {{ $child->lastName }}"
                         data-dropoff=""
                         data-pickup=""
                         data-timestr=""
                         data-scheduled="0"
                         @click="startActionFromCard($el)">

                        @if($child->photoUrl)
                            <img src="{{ $child->photoUrl }}" alt="{{ $child->firstName }}"
                                 class="absolute inset-0 w-full h-full object-cover object-top">
                        @else
                            <div class="absolute inset-0 bg-linear-to-br {{ $grad }} flex items-center justify-center">
                                <span class="text-8xl font-bold opacity-30">{{ strtoupper(substr($child->firstName, 0, 1)) }}</span>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-200 z-10 pointer-events-none"></div>

                        <span class="ll-pulse hidden absolute top-2 right-2 z-20 items-center gap-1 text-xs bg-emerald-500 text-white px-2 py-0.5 rounded-full font-medium shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                            In
                        </span>

                        <div class="absolute bottom-0 left-0 right-0 z-20 bg-white/70 backdrop-blur-sm">
                            <div class="px-3 pt-2.5 pb-2">
                                <p class="font-semibold text-slate-800 text-sm leading-tight truncate">{{ $child->firstName }} {{ $child->lastName }}</p>
                                @if($ageStr)
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $ageStr }}</p>
                                @endif
                                <p class="ll-timeinfo text-xs text-slate-400 italic mt-1">Not scheduled today</p>
                            </div>
                            <div class="ll-action-btn w-full py-3 text-center text-sm font-bold">
                                <span class="ll-ci-text">→&nbsp; Check In</span>
                                <span class="ll-co-text">←&nbsp; Check Out</span>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <div x-show="leftCount === 0 && !showAll" x-cloak
                 class="flex flex-col items-center justify-center py-20 text-slate-400 pointer-events-none">
                <x-icon name="check-circle" class="w-10 h-10 mb-3 text-slate-300" />
                <p class="font-medium text-sm">Everyone is checked in!</p>
            </div>
        </div>
    </div>

    {{-- ══ RIGHT COLUMN — Checked In ══════════════════════════════════════════ --}}
    <div class="flex flex-col w-1/2 bg-emerald-50/60">

        <div class="shrink-0 flex items-center gap-3 px-5 py-3 bg-white border-b border-slate-200">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 text-sm font-bold"
                  x-text="rightCount"></span>
            <h2 class="font-semibold text-slate-700">Checked In</h2>
            <span class="ml-auto text-xs text-slate-400"
                  x-text="rightCount > 0 ? rightCount + ' ' + (rightCount === 1 ? 'child' : 'children') + ' on site' : 'No children on site'"></span>
        </div>

        <div class="flex-1 overflow-y-auto p-4">
            <div id="ll-checkedin" class="flex flex-wrap gap-3 content-start min-h-32">

                @foreach($checkedIn as $child)
                    @php
                        $grad        = $gradFn($child->firstName);
                        $dob         = $child->dateOfBirth ? \Carbon\Carbon::parse($child->dateOfBirth) : null;
                        $months      = $dob ? $dob->diffInMonths(now()) : null;
                        $ageStr      = $dob ? ($months < 24 ? $months . ' mo' : $dob->age . ' yr' . ($dob->age !== 1 ? 's' : '')) : null;
                        $sched       = is_array($child->schedule) ? $child->schedule : [];
                        $entry       = $sched[$today] ?? null;
                        $dropoff     = is_array($entry) ? $fmtTime($entry['dropoff'] ?? null) : null;
                        $pickup      = is_array($entry) ? $fmtTime($entry['pickup']  ?? null) : null;
                        $timeStr     = ($dropoff && $pickup) ? "{$dropoff}–{$pickup}" : ($dropoff ?? $pickup ?? '');
                        $isScheduled = $entry && (is_array($entry)
                            ? (!empty($entry['dropoff']) || !empty($entry['pickup']))
                            : trim((string)$entry) !== '');
                    @endphp
                    <div class="ll-card relative rounded-xl overflow-hidden shadow-sm border border-emerald-300
                                cursor-pointer select-none group"
                         style="width: calc(50% - 6px); height: 520px;"
                         data-action="checkout"
                         data-child-id="{{ $child->id }}"
                         data-child-name="{{ $child->firstName }} {{ $child->lastName }}"
                         data-dropoff="{{ $dropoff }}"
                         data-pickup="{{ $pickup }}"
                         data-timestr="{{ $timeStr }}"
                         data-scheduled="{{ $isScheduled ? '1' : '0' }}"
                         @click="startActionFromCard($el)">

                        @if($child->photoUrl)
                            <img src="{{ $child->photoUrl }}" alt="{{ $child->firstName }}"
                                 class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="absolute inset-0 bg-linear-to-br {{ $grad }} flex items-center justify-center">
                                <span class="text-8xl font-bold opacity-30">{{ strtoupper(substr($child->firstName, 0, 1)) }}</span>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-200 z-10 pointer-events-none"></div>

                        <span class="ll-pulse inline-flex absolute top-2 right-2 z-20 items-center gap-1 text-xs bg-emerald-500 text-white px-2 py-0.5 rounded-full font-medium shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                            In
                        </span>

                        <div class="absolute bottom-0 left-0 right-0 z-20 bg-white/70 backdrop-blur-sm">
                            <div class="px-3 pt-2.5 pb-2">
                                <p class="font-semibold text-slate-800 text-sm leading-tight truncate">{{ $child->firstName }} {{ $child->lastName }}</p>
                                @if($ageStr)
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $ageStr }}</p>
                                @endif
                                <p class="ll-timeinfo text-xs text-emerald-600 font-medium mt-1">
                                    In since {{ $child->checkedInAt?->format('g:i A') }}
                                </p>
                            </div>
                            <div class="ll-action-btn w-full py-3 text-center text-sm font-bold">
                                <span class="ll-ci-text">→&nbsp; Check In</span>
                                <span class="ll-co-text">←&nbsp; Check Out</span>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <div x-show="rightCount === 0" x-cloak
                 class="flex flex-col items-center justify-center py-20 text-slate-400 pointer-events-none">
                <x-icon name="users" class="w-10 h-10 mb-3 text-slate-300" />
                <p class="font-medium text-sm">No one checked in yet</p>
                <p class="text-xs mt-1">Click a child on the left to check them in</p>
            </div>
        </div>
    </div>

    {{-- ══ CONFIRMATION MODAL ═══════════════════════════════════════════════════ --}}
    <div x-show="modal.open"
         x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">

        <div x-show="modal.open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             @click.stop
             class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">

            <div class="flex items-center justify-center mb-4">
                <div :class="modal.action === 'checkin' ? 'bg-emerald-100' : 'bg-amber-100'"
                     class="w-14 h-14 rounded-full flex items-center justify-center">
                    <x-icon name="users" class="w-7 h-7"
                            ::class="modal.action === 'checkin' ? 'text-emerald-600' : 'text-amber-600'" />
                </div>
            </div>

            <h3 class="text-lg font-bold text-slate-800 text-center mb-1"
                x-text="modal.action === 'checkin' ? 'Check In' : 'Check Out'"></h3>

            <p class="text-center text-slate-600 mb-4">
                <span x-text="modal.action === 'checkin' ? 'Check in' : 'Check out'"></span>
                <strong x-text="modal.name"></strong>?
            </p>

            {{-- Time override (checkout only) --}}
            <div x-show="modal.action === 'checkout'" class="mb-5">
                <label class="flex items-center gap-2 cursor-pointer select-none mb-2">
                    <input type="checkbox" x-model="modal.useCustomTime"
                           class="rounded border-slate-300 text-amber-500 focus:ring-amber-400 w-4 h-4">
                    <span class="text-sm text-slate-600 font-medium">Override check-out time</span>
                </label>
                <div x-show="modal.useCustomTime" x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <input type="time" x-model="modal.customTimeValue" class="input text-sm w-full">
                    <p class="text-xs text-slate-400 mt-1.5">This time will be recorded in the audit log instead of now.</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button @click="cancelAction()" class="flex-1 btn-ghost text-sm">Cancel</button>
                <button @click="confirmAction()"
                        :class="modal.action === 'checkin'
                            ? 'bg-emerald-500 hover:bg-emerald-600 text-white'
                            : 'bg-amber-500 hover:bg-amber-600 text-white'"
                        class="flex-1 px-4 py-2 rounded-lg font-semibold text-sm transition-colors"
                        x-text="modal.action === 'checkin' ? 'Yes, Check In' : 'Yes, Check Out'">
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function littleLog() {
    return {
        showAll:    false,
        leftCount:  {{ $expected->count() }},
        rightCount: {{ $checkedIn->count() }},

        modal: {
            open: false, action: null, name: '', childId: null,
            useCustomTime: false, customTimeValue: '',
        },

        _pendingItem:      null,
        _pendingScheduled: false,

        init() { /* nothing needed without SortableJS */ },

        startActionFromCard(el) {
            // action is encoded directly on the card as data-action
            const action    = el.dataset.action;
            const now       = new Date();
            const hh        = String(now.getHours()).padStart(2, '0');
            const mm        = String(now.getMinutes()).padStart(2, '0');

            this._pendingItem      = el;
            this._pendingScheduled = el.dataset.scheduled === '1';

            this.modal = {
                open:            true,
                action,
                name:            el.dataset.childName,
                childId:         el.dataset.childId,
                useCustomTime:   false,
                customTimeValue: `${hh}:${mm}`,
            };
        },

        cancelAction() {
            this.modal.open = false;
            this._pendingItem = null;
        },

        async confirmAction() {
            const { action, childId, useCustomTime, customTimeValue } = this.modal;
            const item      = this._pendingItem;
            const scheduled = this._pendingScheduled;
            this.modal.open  = false;
            this._pendingItem = null;

            const url  = action === 'checkin'
                ? `/portal/littlelog/checkin/${childId}`
                : `/portal/littlelog/checkout/${childId}`;

            const body = {};
            if (action === 'checkout' && useCustomTime && customTimeValue) {
                body.customTime = customTimeValue;
            }

            try {
                const res  = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept':       'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(body),
                });
                const data = await res.json();
                if (!data.ok) throw new Error('Server error');

                // ── Update counters ─────────────────────────────────────────
                if (action === 'checkin') {
                    if (scheduled) this.leftCount = Math.max(0, this.leftCount - 1);
                    this.rightCount++;
                } else {
                    this.rightCount = Math.max(0, this.rightCount - 1);
                    if (scheduled) this.leftCount++;
                }

                // ── Animate out ─────────────────────────────────────────────
                item.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                item.style.opacity    = '0';
                item.style.transform  = 'scale(0.93)';
                await new Promise(r => setTimeout(r, 220));

                // ── Move card to the other list ─────────────────────────────
                const targetList = action === 'checkin'
                    ? document.getElementById('ll-checkedin')
                    : document.getElementById('ll-expected');
                targetList.prepend(item);

                // ── Flip the card's action for next click ───────────────────
                item.dataset.action = action === 'checkin' ? 'checkout' : 'checkin';

                // ── Update border & content ─────────────────────────────────
                const pulse    = item.querySelector('.ll-pulse');
                const timeInfo = item.querySelector('.ll-timeinfo');

                if (action === 'checkin') {
                    item.classList.remove('border-slate-200', 'border-dashed', 'border-slate-300', 'opacity-70');
                    item.classList.add('border-emerald-300');

                    if (pulse)    { pulse.classList.remove('hidden'); pulse.classList.add('inline-flex'); }
                    if (timeInfo) {
                        timeInfo.textContent = 'In since ' + (data.checkedInAt ?? '');
                        timeInfo.className   = 'll-timeinfo text-xs text-emerald-600 font-medium mt-1';
                    }
                } else {
                    item.classList.remove('border-emerald-300');
                    item.classList.add('border-slate-200');

                    if (pulse)    { pulse.classList.remove('inline-flex'); pulse.classList.add('hidden'); }
                    if (timeInfo) {
                        const ts = item.dataset.timestr;
                        timeInfo.textContent = ts || '';
                        timeInfo.className   = ts
                            ? 'll-timeinfo text-xs text-primary-600 font-medium mt-1'
                            : 'll-timeinfo text-xs text-slate-400 mt-1';
                    }
                }

                // ── Animate in ──────────────────────────────────────────────
                item.style.opacity   = '0';
                item.style.transform = 'scale(0.93)';
                item.offsetHeight;   // force reflow before transition
                item.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                item.style.opacity   = '1';
                item.style.transform = 'scale(1)';
                setTimeout(() => {
                    item.style.transition = '';
                    item.style.opacity    = '';
                    item.style.transform  = '';
                }, 260);

            } catch (e) {
                console.error('LittleLog action failed', e);
            }
        },
    };
}
</script>
@endpush

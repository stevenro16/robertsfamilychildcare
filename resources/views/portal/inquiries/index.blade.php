@extends('layouts.portal')
@section('title', 'Inquiries')

@section('portal-content')
<div class="w-full">
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2.5">
            <x-icon name="message-circle" class="w-6 h-6 text-primary-500" />
            Inquiries
        </h1>
    </div>
    <div class="h-0.5 bg-linear-to-r from-primary-400 to-transparent rounded-full mb-6"></div>

    {{-- Queue tabs --}}
    <div class="flex gap-1 mb-6 bg-slate-100 p-1 rounded-lg w-fit">
        @foreach([
            ['queue' => 'needs_attention', 'label' => 'Needs Attention',  'route' => 'portal.inquiries.index'],
            ['queue' => 'awaiting',        'label' => 'Awaiting Feedback', 'route' => 'portal.inquiries.awaiting'],
            ['queue' => 'room',            'label' => 'When Room',         'route' => 'portal.inquiries.room'],
            ['queue' => 'snoozed',         'label' => 'Snoozed',           'route' => 'portal.inquiries.snoozed'],
            ['queue' => 'all',             'label' => 'All',               'route' => 'portal.inquiries.all'],
        ] as $tab)
            @php $isActive = $queue === $tab['queue']; $n = $counts[$tab['queue']] ?? 0; @endphp
            <a href="{{ route($tab['route']) }}"
               class="flex items-center gap-1.5 px-4 py-1.5 rounded-md text-sm font-medium transition-colors
                      {{ $isActive ? 'bg-white shadow-sm text-slate-800' : 'text-slate-500 hover:text-slate-700' }}">
                {{ $tab['label'] }}
                @if($tab['queue'] !== 'all' || $n > 0)
                <span class="inline-flex items-center justify-center min-w-5 h-5 px-1 rounded-full text-xs font-semibold leading-none
                             {{ $isActive ? 'bg-primary-100 text-primary-700' : 'bg-slate-200 text-slate-500' }}">
                    {{ $n }}
                </span>
                @endif
            </a>
        @endforeach
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($inquiries->isEmpty())
        <div class="card p-12 text-center text-slate-400">
            <x-icon name="check-circle" class="w-10 h-10 mx-auto mb-3 text-slate-300" />
            <p class="font-medium">No inquiries in this queue</p>
        </div>
    @else
        <div class="card overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">
                        <th class="px-4 py-3">Family</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Contact</th>
                        @if($queue === 'snoozed')
                            <th class="px-4 py-3">Snoozed Until</th>
                        @elseif($queue === 'awaiting')
                            <th class="px-4 py-3">Last Contact</th>
                        @else
                            <th class="px-4 py-3">Submitted</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($inquiries as $inquiry)
                        @php
                            $age = null;
                            if ($inquiry->childDob) {
                                $dob = \Carbon\Carbon::parse($inquiry->childDob);
                                $months = $dob->diffInMonths(now());
                                $age = $months < 24
                                    ? $months . ' mo'
                                    : $dob->age . ' yr' . ($dob->age !== 1 ? 's' : '');
                            }
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors cursor-pointer"
                            onclick="window.location='{{ route('portal.inquiries.show', $inquiry->id) }}'">

                            {{-- Family --}}
                            <td class="px-4 py-3">
                                <p class="font-medium text-slate-800 leading-snug">{{ $inquiry->parentName }}</p>
                                @if($inquiry->childName)
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        Child: {{ $inquiry->childName }}{{ $age ? ', ' . $age : '' }}
                                    </p>
                                @elseif($age)
                                    <p class="text-xs text-slate-400 mt-0.5">Age: {{ $age }}</p>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    [$statusColor, $statusLabel] = match($inquiry->status) {
                                        'NEW'                      => ['bg-blue-100 text-blue-700',     'New'],
                                        'LEFT_VOICEMAIL'           => ['bg-amber-100 text-amber-700',   'Voicemail Left'],
                                        'LEFT_VOICEMAIL_2'         => ['bg-orange-100 text-orange-700', '2nd Voicemail'],
                                        'LEFT_VOICEMAIL_FINAL'     => ['bg-red-100 text-red-700',       'Final Voicemail'],
                                        'PROVIDED_PRICING_WAITING' => ['bg-purple-100 text-purple-700', 'Pricing Provided'],
                                        'FOLLOW_UP_WHEN_ROOM'      => ['bg-slate-100 text-slate-600',   'When Room'],
                                        'COMPLETE'                 => ['bg-green-100 text-green-700',   'Complete'],
                                        default                    => ['bg-slate-100 text-slate-600',   str_replace('_', ' ', $inquiry->status)],
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            {{-- Contact --}}
                            <td class="px-4 py-3">
                                <p class="text-slate-700 leading-snug">{{ $inquiry->parentPhone ?? '—' }}</p>
                                @if($inquiry->parentEmail)
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $inquiry->parentEmail }}</p>
                                @endif
                            </td>

                            {{-- Last column --}}
                            @if($queue === 'snoozed')
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                        {{ $inquiry->snoozeUntil?->format('M j, Y') ?? '—' }}
                                    </span>
                                </td>
                            @elseif($queue === 'awaiting')
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @php $daysSince = $inquiry->updatedAt->diffInDays(now()); @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $daysSince >= 7 ? 'bg-red-100 text-red-700' : ($daysSince >= 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}"
                                          title="{{ $inquiry->updatedAt->format('M j, Y g:i A') }}">
                                        {{ $daysSince === 0 ? 'Today' : $daysSince . 'd ago' }}
                                    </span>
                                </td>
                            @else
                                <td class="px-4 py-3 text-slate-500 whitespace-nowrap text-sm">
                                    <span title="{{ $inquiry->createdAt->format('M j, Y g:i A') }}">
                                        {{ $inquiry->createdAt->diffForHumans() }}
                                    </span>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="mt-3 text-xs text-slate-400 text-right">{{ $inquiries->count() }} {{ Str::plural('inquiry', $inquiries->count()) }}</p>
    @endif
</div>
@endsection

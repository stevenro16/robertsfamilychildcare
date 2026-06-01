@extends('layouts.portal')
@section('title', 'Inquiries')

@section('portal-content')
<div class="max-w-5xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Inquiries</h1>
    </div>

    {{-- Queue tabs --}}
    <div class="flex gap-1 mb-6 bg-slate-100 p-1 rounded-lg w-fit">
        @foreach([
            ['queue' => 'active',    'label' => 'Active',    'route' => 'portal.inquiries.index'],
            ['queue' => 'snoozed',   'label' => 'Snoozed',   'route' => 'portal.inquiries.snoozed'],
            ['queue' => 'room',      'label' => 'When Room',  'route' => 'portal.inquiries.room'],
            ['queue' => 'completed', 'label' => 'Completed',  'route' => 'portal.inquiries.completed'],
        ] as $tab)
            <a href="{{ route($tab['route']) }}"
               class="px-4 py-1.5 rounded-md text-sm font-medium transition-colors
                      {{ $queue === $tab['queue'] ? 'bg-white shadow-sm text-slate-800' : 'text-slate-500 hover:text-slate-700' }}">
                {{ $tab['label'] }}
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
        <div class="card divide-y divide-slate-100">
            @foreach($inquiries as $inquiry)
                <a href="{{ route('portal.inquiries.show', $inquiry->id) }}"
                   class="flex items-start gap-4 p-4 hover:bg-slate-50 transition-colors">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <span class="font-semibold text-slate-800">{{ $inquiry->name }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                {{ match($inquiry->status) {
                                    'NEW'                       => 'bg-blue-100 text-blue-700',
                                    'LEFT_VOICEMAIL'            => 'bg-yellow-100 text-yellow-700',
                                    'LEFT_VOICEMAIL_2'          => 'bg-orange-100 text-orange-700',
                                    'LEFT_VOICEMAIL_FINAL'      => 'bg-red-100 text-red-700',
                                    'PROVIDED_PRICING_WAITING'  => 'bg-purple-100 text-purple-700',
                                    'FOLLOW_UP_WHEN_ROOM'       => 'bg-slate-100 text-slate-600',
                                    'COMPLETE'                  => 'bg-green-100 text-green-700',
                                    default                     => 'bg-slate-100 text-slate-600',
                                } }}">
                                {{ str_replace('_', ' ', $inquiry->status) }}
                            </span>
                            @if($inquiry->isSnoozed && $inquiry->snoozeUntil?->isFuture())
                                <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 font-medium">
                                    Snoozed until {{ $inquiry->snoozeUntil->format('M j') }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-500">
                            @if($inquiry->email)
                                <span>{{ $inquiry->email }}</span>
                            @endif
                            @if($inquiry->phone)
                                <span>{{ $inquiry->phone }}</span>
                            @endif
                            @if($inquiry->childDob)
                                <span>DOB: {{ \Carbon\Carbon::parse($inquiry->childDob)->format('M j, Y') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-xs text-slate-400 shrink-0 mt-0.5">
                        {{ $inquiry->createdAt->diffForHumans() }}
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection

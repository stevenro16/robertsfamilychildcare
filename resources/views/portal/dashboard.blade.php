@extends('layouts.portal')
@section('title', 'Dashboard')

@section('portal-content')
<div class="w-full">
    <div class="mb-2">
        <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2.5">
            <x-icon name="home" class="w-6 h-6 text-primary-500" />
            Dashboard
        </h1>
        <p class="text-slate-500 text-sm mt-1">Welcome back, {{ auth()->user()->name }}</p>
    </div>
    <div class="h-0.5 bg-linear-to-r from-primary-400 to-transparent rounded-full mb-6"></div>

    {{-- LittleLog launch button --}}
    <a href="{{ route('portal.littlelog') }}"
       class="inline-flex items-center gap-2.5 mb-6 px-5 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold shadow-md hover:shadow-lg transition-all text-sm">
        <x-icon name="clipboard-list" class="w-5 h-5" />
        Open LittleLog
        <span class="text-primary-300 text-xs font-normal">— live check-in dashboard</span>
    </a>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="card p-5">
            <p class="text-sm text-slate-500 mb-1">Open Inquiries</p>
            <p class="text-3xl font-bold text-primary-600">{{ $stats['inquiries'] }}</p>
        </div>
        <div class="card p-5">
            <p class="text-sm text-slate-500 mb-1">Active Children</p>
            <p class="text-3xl font-bold text-primary-600">{{ $stats['children'] }}</p>
        </div>
        <div class="card p-5">
            <p class="text-sm text-slate-500 mb-1">Unread Messages</p>
            <p class="text-3xl font-bold text-primary-600">{{ $stats['unreadMessages'] }}</p>
        </div>
    </div>

    <div class="card p-5 mb-8 overflow-x-auto">
        <div class="flex items-center justify-between gap-4 mb-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Weekly Schedule</h2>
                <p class="text-sm text-slate-500 mt-1">Monday through Friday schedule for active children.</p>
            </div>
            <div class="text-sm text-slate-500">Showing expected hours for each child and total headcount per day.</div>
        </div>

        @if($children->isEmpty())
            <div class="py-10 text-center text-slate-500">No active children with schedules available.</div>
        @else
            <table class="w-full text-left border-separate border-spacing-y-1">
                <thead>
                    <tr>
                        <th class="pb-3 pr-6 text-sm font-semibold text-slate-600 w-40">Child</th>
                        @foreach($days as $day)
                            <th class="pb-3 px-4 text-sm font-semibold text-slate-600 text-center">{{ ucfirst($day) }}</th>
                        @endforeach
                        <th class="pb-3 pl-4 text-sm font-semibold text-slate-600 text-right whitespace-nowrap">Wkly Hrs</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($children as $child)
                        <tr class="bg-slate-50">
                            <td class="py-2.5 pr-6 pl-3 font-medium rounded-l-lg">
                                <a href="{{ route('portal.children.show', $child->id) }}"
                                   class="text-slate-800 hover:text-primary-600 transition-colors">
                                    {{ $child->firstName }} {{ $child->lastName }}
                                </a>
                            </td>
                            @foreach($days as $day)
                                @php $val = $child->scheduleDays[$day]; @endphp
                                <td class="py-2.5 px-4 text-sm text-center
                                           {{ $val ? 'text-slate-700 font-medium' : 'text-slate-300' }}">
                                    {{ $val ?: '—' }}
                                </td>
                            @endforeach
                            <td class="py-2.5 pl-4 pr-3 text-sm text-right font-semibold rounded-r-lg
                                       {{ $child->weeklyHours ? 'text-primary-600' : 'text-slate-300' }}">
                                {{ $child->weeklyHours ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    {{-- Headcount row --}}
                    <tr class="border-t border-slate-100">
                        <td class="pt-4 pr-6 pl-3 text-sm font-semibold text-slate-500">Daily headcount</td>
                        @foreach($days as $day)
                            <td class="pt-4 px-4 text-sm font-bold text-primary-600 text-center">
                                {{ $totals[$day] > 0 ? $totals[$day] . ' kids' : '—' }}
                            </td>
                        @endforeach
                        <td class="pt-4 pl-4 pr-3"></td>
                    </tr>

                    {{-- Total weekly hours row --}}
                    @if($totalWeeklyHours)
                    <tr>
                        <td class="pt-2 pr-6 pl-3 text-sm font-semibold text-slate-500">Total weekly hours</td>
                        @foreach($days as $day)
                            @php
                                $h = intdiv($dailyMins[$day], 60);
                                $m = $dailyMins[$day] % 60;
                                $dayTotal = $dailyMins[$day] > 0 ? ($m > 0 ? "{$h}h {$m}m" : "{$h}h") : null;
                            @endphp
                            <td class="pt-2 px-4 text-sm text-center {{ $dayTotal ? 'text-slate-500' : 'text-slate-200' }}">
                                {{ $dayTotal ?? '—' }}
                            </td>
                        @endforeach
                        <td class="pt-2 pl-4 pr-3 text-sm font-bold text-primary-700 text-right whitespace-nowrap">
                            {{ $totalWeeklyHours }}
                        </td>
                    </tr>
                    @endif

                    {{-- Trend row --}}
                    @if($trend)
                    @php
                        $trendWrap = match($trend['status']) {
                            'ahead'  => 'bg-emerald-50 border border-emerald-100',
                            'behind' => 'bg-red-50 border border-red-100',
                            default  => 'bg-slate-50 border border-slate-100',
                        };
                        $trendText = match($trend['status']) {
                            'ahead'  => 'text-emerald-700',
                            'behind' => 'text-red-600',
                            default  => 'text-slate-500',
                        };
                    @endphp
                    <tr>
                        <td colspan="7" class="pt-3 pl-3 pr-3 pb-1">
                            <div class="flex items-center gap-3 rounded-lg px-4 py-2.5 {{ $trendWrap }}">

                                <span class="text-sm font-semibold whitespace-nowrap {{ $trendText }}">
                                    {{ $trend['label'] }}
                                </span>

                                @if(isset($trend['delivered']))
                                    <span class="text-xs text-slate-400">
                                        {{ $trend['delivered'] }} delivered through
                                        {{ now()->format('l') }}
                                        vs {{ $trend['expected'] }} expected at this point
                                        ({{ $trend['elapsedDays'] }} of 5 days elapsed)
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endif
                </tfoot>
            </table>
        @endif
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
        @foreach([
            ['route' => 'portal.inquiries.index',  'label' => 'View Inquiries',  'icon' => 'message-circle'],
            ['route' => 'portal.children.index',   'label' => 'Children',         'icon' => 'users'],
            ['route' => 'portal.messages.index',   'label' => 'Messages',         'icon' => 'message-circle'],
            ['route' => 'portal.gallery.index',    'label' => 'Gallery',          'icon' => 'image-plus'],
            ['route' => 'portal.testimonials.index','label' => 'Testimonials',    'icon' => 'star'],
            ['route' => 'portal.settings.index',   'label' => 'Settings',         'icon' => 'shield'],
        ] as $link)
            <a href="{{ route($link['route']) }}" class="card p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
                <x-icon name="{{ $link['icon'] }}" class="w-5 h-5 text-primary-500" />
                <span class="text-sm font-medium text-slate-700">{{ $link['label'] }}</span>
            </a>
        @endforeach
    </div>
</div>
@endsection

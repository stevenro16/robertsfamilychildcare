@extends('layouts.portal')
@section('title', 'Settings')

@section('portal-content')
<div class="w-full" x-data="{ sqlResult: null }">
    <h1 class="text-2xl font-bold text-slate-800 mb-2 flex items-center gap-2.5">
        <x-icon name="shield" class="w-6 h-6 text-primary-500" />
        Settings
    </h1>
    <div class="h-0.5 bg-linear-to-r from-primary-400 to-transparent rounded-full mb-6"></div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Accounts link --}}
    <div class="card p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-slate-700">Staff Accounts</h2>
                <p class="text-sm text-slate-400 mt-0.5">Manage staff login accounts</p>
            </div>
            <a href="{{ route('portal.accounts.index') }}" class="btn-ghost text-sm">Manage Accounts</a>
        </div>
    </div>

    {{-- DB Backup --}}
    <div class="card p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-slate-700">Database Backup</h2>
                <p class="text-sm text-slate-400 mt-0.5">Download a full SQL dump of the database</p>
            </div>
            <a href="{{ route('portal.settings.backup') }}" class="btn-primary text-sm gap-1.5">
                <x-icon name="download" class="w-4 h-4" /> Download
            </a>
        </div>
    </div>

    {{-- SQL Console --}}
    <div class="card p-6">
        <h2 class="font-semibold text-slate-700 mb-1">SQL Console</h2>
        <p class="text-xs text-amber-600 bg-amber-50 px-3 py-2 rounded-lg mb-4">
            Danger zone: run raw SQL queries. SELECT queries show results; others execute directly.
        </p>

        @if(session('sqlResults'))
            <div class="mb-4 overflow-x-auto">
                <p class="text-xs text-slate-400 mb-2">Query: <code class="bg-slate-100 px-1 rounded">{{ session('sqlQuery') }}</code></p>
                @php $results = session('sqlResults'); @endphp
                @if(count($results) > 0)
                    <table class="text-xs border-collapse w-full">
                        <thead>
                            <tr class="bg-slate-50">
                                @foreach(array_keys((array)$results[0]) as $col)
                                    <th class="border border-slate-200 px-2 py-1 text-left font-medium text-slate-600">{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $row)
                                <tr class="hover:bg-slate-50">
                                    @foreach((array)$row as $val)
                                        <td class="border border-slate-200 px-2 py-1 text-slate-700 max-w-xs truncate">{{ $val }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-sm text-slate-400">No results returned.</p>
                @endif
            </div>
        @endif

        <form method="POST" action="{{ route('portal.settings.sql') }}">
            @csrf
            <textarea name="query" rows="5" class="input mb-3 resize-none text-sm font-mono"
                      placeholder="SELECT * FROM Employee LIMIT 10">{{ session('sqlQuery') }}</textarea>
            <button type="submit" class="btn-primary text-sm">Execute</button>
        </form>
    </div>
</div>
@endsection

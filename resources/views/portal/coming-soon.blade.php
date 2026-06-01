@extends('layouts.portal')
@section('title', $pageTitle ?? 'Coming Soon')

@section('portal-content')
<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-slate-800 mb-2">{{ $pageTitle ?? 'Page' }}</h1>
    <p class="text-slate-500 mb-6">This portal section is being migrated from Next.js — it will be available soon.</p>
    <div class="card p-6 text-slate-500 text-sm border-dashed">
        <p>Migration in progress: <strong>{{ $pageTitle ?? 'This page' }}</strong></p>
    </div>
</div>
@endsection

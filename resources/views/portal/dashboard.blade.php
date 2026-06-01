@extends('layouts.portal')
@section('title', 'Dashboard')

@section('portal-content')
<div class="max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
        <p class="text-slate-500 text-sm mt-1">Welcome back, {{ auth()->user()->name }}</p>
    </div>

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

@extends('layouts.public')
@section('title', 'About Us — Roberts Family ChildCare')

@section('content')
<section class="bg-gradient-to-br from-primary-500 to-primary-700 text-white">
    <div class="wide py-24 lg:py-32">
        <p class="text-primary-200 text-sm font-medium uppercase tracking-widest mb-4">Who We Are</p>
        <h1 class="text-5xl lg:text-6xl font-bold mb-5">About Roberts Family ChildCare</h1>
        <p class="text-primary-100 text-xl max-w-xl">A licensed home daycare built on love, safety, and a passion for children's growth.</p>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="wide">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
            <div>
                <h2 class="text-3xl font-bold text-slate-800 mb-6">Our Story</h2>
                <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed space-y-4">
                    {!! nl2br(e($aboutBlurb ?: 'Roberts Family ChildCare is a licensed, home-based daycare serving the Highland, CA community. We provide a warm, nurturing environment where children learn, grow, and thrive.')) !!}
                </div>
                <div class="mt-8">
                    <a href="{{ route('contact') }}" class="btn-primary">Inquire About Availability</a>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                @foreach([
                    ['icon' => 'heart',  'title' => 'Family-Centered',     'desc' => 'Every child is part of our family, treated with warmth and individual care.'],
                    ['icon' => 'star',   'title' => 'Child-Led Learning',  'desc' => 'We follow each child\'s curiosity and natural pace of development.'],
                    ['icon' => 'shield', 'title' => 'Safety First',        'desc' => 'Fully licensed, insured, and childproofed for your peace of mind.'],
                    ['icon' => 'users',  'title' => 'Community',           'desc' => 'We build lasting connections between families in our community.'],
                ] as $v)
                    <div class="card p-6">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center mb-3">
                            <x-icon name="{{ $v['icon'] }}" class="w-5 h-5 text-primary-500" />
                        </div>
                        <h3 class="font-semibold text-slate-800 mb-1">{{ $v['title'] }}</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">{{ $v['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="bg-gradient-to-r from-primary-600 to-primary-700">
    <div class="wide py-20 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Ready to Join Our Family?</h2>
        <p class="text-primary-100 mb-8">We'd love to meet you and your little one.</p>
        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-white text-primary-700 font-semibold px-8 py-3.5 rounded-xl hover:bg-primary-50 transition-colors">
            Inquire Now <x-icon name="chevron-right" class="w-4 h-4" />
        </a>
    </div>
</section>
@endsection

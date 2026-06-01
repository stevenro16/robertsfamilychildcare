@extends('layouts.public')
@section('title', 'Testimonials — Roberts Family ChildCare')

@section('content')
<section class="bg-gradient-to-br from-primary-500 to-primary-700 text-white">
    <div class="wide py-24 lg:py-32">
        <p class="text-primary-200 text-sm font-medium uppercase tracking-widest mb-4">Parent Reviews</p>
        <h1 class="text-5xl lg:text-6xl font-bold mb-5">What Parents Say</h1>
        <p class="text-primary-100 text-xl max-w-xl">Real stories from families in our care.</p>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="wide">
        @if($testimonials->isEmpty())
            <p class="text-center text-slate-400 py-16">No reviews yet — check back soon!</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($testimonials as $t)
                    <div class="card p-8 hover:shadow-md transition-shadow flex flex-col">
                        <div class="flex gap-1 mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <x-icon name="star" class="w-4 h-4 {{ $i <= $t->rating ? 'text-amber-400 fill-amber-400' : 'text-slate-200' }}" />
                            @endfor
                        </div>
                        <x-icon name="quote" class="w-7 h-7 text-primary-200 mb-4" />
                        <p class="text-slate-600 leading-relaxed mb-6 flex-1">"{{ $t->content }}"</p>
                        <div class="flex items-center gap-3 pt-4 border-t border-slate-50">
                            <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-semibold">
                                {{ strtoupper(substr($t->parentName, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-slate-800">{{ $t->parentName }}</p>
                                @if($t->childAge)
                                    <p class="text-xs text-slate-500">Child age: {{ $t->childAge }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection

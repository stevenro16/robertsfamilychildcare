@extends('layouts.public')
@section('title', 'Programs — Roberts Family ChildCare')

@section('content')
<section class="bg-gradient-to-br from-primary-500 to-primary-700 text-white">
    <div class="wide py-24 lg:py-32">
        <p class="text-primary-200 text-sm font-medium uppercase tracking-widest mb-4">What We Offer</p>
        <h1 class="text-5xl lg:text-6xl font-bold mb-5">Our Programs</h1>
        <p class="text-primary-100 text-xl max-w-xl">Age-appropriate care and learning for every stage of childhood.</p>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="wide">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach([
                ['emoji' => '👶', 'label' => 'Infants',        'age' => '6 – 12 months',  'desc' => 'Gentle, responsive care focused on bonding, sensory exploration, and early development milestones.'],
                ['emoji' => '🧒', 'label' => 'Young Toddlers', 'age' => '12 – 18 months', 'desc' => 'Encouraging independence and language development through play and routine.'],
                ['emoji' => '🌱', 'label' => 'Toddlers',       'age' => '18 – 36 months', 'desc' => 'Building social skills, curiosity, and creativity in a safe, structured environment.'],
                ['emoji' => '🎨', 'label' => 'Preschool',      'age' => '3 – 5 years',    'desc' => 'Preparing children for kindergarten with literacy, numeracy, and social-emotional learning.'],
                ['emoji' => '📚', 'label' => 'School Age',     'age' => '5 – 10 years',   'desc' => 'After-school and full-day care with homework support and enrichment activities.'],
            ] as $prog)
                <div class="card p-8 hover:shadow-lg transition-shadow">
                    <div class="text-4xl mb-4">{{ $prog['emoji'] }}</div>
                    <h3 class="text-xl font-bold text-slate-800 mb-1">{{ $prog['label'] }}</h3>
                    <p class="text-primary-600 font-medium text-sm mb-3">Ages {{ $prog['age'] }}</p>
                    <p class="text-slate-500 leading-relaxed">{{ $prog['desc'] }}</p>
                </div>
            @endforeach
            <div class="card p-8 bg-primary-50 border-primary-100 flex flex-col justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Questions about our programs?</h3>
                    <p class="text-slate-600 leading-relaxed mb-6">We'd love to find the right fit for your child. Reach out and we'll follow up within one business day.</p>
                </div>
                <a href="{{ route('contact') }}" class="btn-primary self-start">Inquire Now</a>
            </div>
        </div>
    </div>
</section>
@endsection

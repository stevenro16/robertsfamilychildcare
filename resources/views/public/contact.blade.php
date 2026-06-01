@extends('layouts.public')
@section('title', 'Contact & Inquire — Roberts Family ChildCare')

@section('content')
<section class="bg-gradient-to-br from-primary-500 to-primary-700 text-white">
    <div class="wide py-24 lg:py-32">
        <p class="text-primary-200 text-sm font-medium uppercase tracking-widest mb-4">Get in Touch</p>
        <h1 class="text-5xl lg:text-6xl font-bold mb-5">Inquire About Availability</h1>
        <p class="text-primary-100 text-xl max-w-xl">Fill out the form and we'll follow up within one business day with availability and pricing.</p>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="wide">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
            {{-- Contact info --}}
            <div class="space-y-5">
                <div class="card p-8">
                    <h3 class="font-bold text-slate-800 text-lg mb-6">Contact Information</h3>
                    <ul class="space-y-5">
                        <li class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center shrink-0 mt-0.5">
                                <x-icon name="map-pin" class="w-4 h-4 text-primary-500" />
                            </div>
                            <div class="text-sm">
                                <p class="font-medium text-slate-700 mb-0.5">Address</p>
                                <p class="text-slate-500">Highland, CA 92346</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center shrink-0 mt-0.5">
                                <x-icon name="phone" class="w-4 h-4 text-primary-500" />
                            </div>
                            <div class="text-sm">
                                <p class="font-medium text-slate-700 mb-0.5">Phone</p>
                                <a href="tel:9098097844" class="text-primary-500 hover:underline">(909) 809-7844</a>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center shrink-0 mt-0.5">
                                <x-icon name="clock" class="w-4 h-4 text-primary-500" />
                            </div>
                            <div class="text-sm">
                                <p class="font-medium text-slate-700 mb-0.5">Hours</p>
                                <p class="text-slate-500">Mon–Fri, 7:00 AM – 5:00 PM</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="rounded-2xl p-6 bg-primary-50 border border-primary-100">
                    <p class="text-sm text-primary-700 leading-relaxed">
                        <strong>Pricing is provided upon inquiry</strong> so we can tailor it to your child's specific program, schedule, and start date.
                    </p>
                </div>
            </div>

            {{-- Inquiry form --}}
            <div class="lg:col-span-2">
                @if(session('inquiry_success'))
                    <div class="card p-10 text-center">
                        <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center mx-auto mb-4">
                            <x-icon name="check" class="w-8 h-8 text-primary-600" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Inquiry Received!</h3>
                        <p class="text-slate-500">Thank you for reaching out. We'll be in touch within one business day.</p>
                    </div>
                @else
                    <div class="card p-8" x-data="{ loading: false }">
                        <h3 class="text-xl font-bold text-slate-800 mb-6">Inquiry Form</h3>

                        @if($errors->any())
                            <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                                Please correct the errors below.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contact.store') }}" @submit="loading = true">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="label">Parent Name <span class="text-red-400">*</span></label>
                                    <input type="text" name="parentName" value="{{ old('parentName') }}" required class="input @error('parentName') border-red-300 @enderror" placeholder="Jane Smith">
                                    @error('parentName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="label">Phone <span class="text-red-400">*</span></label>
                                    <input type="tel" name="parentPhone" value="{{ old('parentPhone') }}" required class="input @error('parentPhone') border-red-300 @enderror" placeholder="(555) 555-5555">
                                    @error('parentPhone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="label">Email <span class="text-red-400">*</span></label>
                                    <input type="email" name="parentEmail" value="{{ old('parentEmail') }}" required class="input @error('parentEmail') border-red-300 @enderror" placeholder="jane@example.com">
                                    @error('parentEmail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="label">Child's Name <span class="text-red-400">*</span></label>
                                    <input type="text" name="childName" value="{{ old('childName') }}" required class="input @error('childName') border-red-300 @enderror" placeholder="First name">
                                    @error('childName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="label">Child's Date of Birth</label>
                                    <input type="date" name="childDob" value="{{ old('childDob') }}" class="input">
                                </div>
                                <div>
                                    <label class="label">Desired Start Date</label>
                                    <input type="date" name="desiredStart" value="{{ old('desiredStart') }}" class="input">
                                </div>
                                <div>
                                    <label class="label">How did you hear about us?</label>
                                    <select name="hearAbout" class="input">
                                        <option value="">Select one…</option>
                                        @foreach(['Google Search','Facebook','Instagram','Friend/Family Referral','Nextdoor','Driving By','Other'] as $opt)
                                            <option value="{{ $opt }}" @selected(old('hearAbout') === $opt)>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="label">Program Interest</label>
                                    <select name="programInterest" class="input">
                                        <option value="">Select one…</option>
                                        @foreach(['Infant (6–12 mo)','Young Toddler (12–18 mo)','Toddler (18–36 mo)','Preschool (3–5 yrs)','School Age (5–10 yrs)'] as $opt)
                                            <option value="{{ $opt }}" @selected(old('programInterest') === $opt)>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-6">
                                <label class="label">Message <span class="text-slate-400 font-normal">(optional)</span></label>
                                <textarea name="message" rows="4" class="input" placeholder="Any questions or additional information…">{{ old('message') }}</textarea>
                            </div>
                            <button type="submit" class="btn-primary" :disabled="loading">
                                <span x-show="!loading">Submit Inquiry</span>
                                <span x-show="loading">Submitting…</span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

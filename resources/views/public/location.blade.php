@extends('layouts.public')
@section('title', 'Location — Roberts Family ChildCare')

@section('content')
<section class="bg-gradient-to-br from-primary-500 to-primary-700 text-white">
    <div class="wide py-24 lg:py-32">
        <p class="text-primary-200 text-sm font-medium uppercase tracking-widest mb-4">Find Us</p>
        <h1 class="text-5xl lg:text-6xl font-bold mb-5">Our Location</h1>
        <p class="text-primary-100 text-xl max-w-xl">Conveniently located in Highland, CA — come visit us!</p>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="wide">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <div class="card p-8">
                <h3 class="font-bold text-slate-800 text-lg mb-6">Contact & Hours</h3>
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
                            <p class="text-slate-500">Monday – Friday<br>7:00 AM – 5:00 PM</p>
                        </div>
                    </li>
                </ul>
                <div class="mt-8">
                    <a href="{{ route('contact') }}" class="btn-primary">Schedule a Visit</a>
                </div>
            </div>

            <div class="rounded-2xl overflow-hidden shadow-lg h-96">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d26453.827853937!2d-117.25!3d34.13!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c3508c5e8bc1e5%3A0xbe08d2c9b7d3ae9f!2sHighland%2C%20CA%2092346!5e0!3m2!1sen!2sus!4v1620000000000"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Roberts Family ChildCare location"
                ></iframe>
            </div>
        </div>
    </div>
</section>
@endsection

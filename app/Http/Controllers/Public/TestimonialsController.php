<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;

class TestimonialsController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::where('status', 'APPROVED')
            ->where('isActive', true)
            ->orderBy('createdAt', 'desc')
            ->get();
        return view('public.testimonials', compact('testimonials'));
    }
}
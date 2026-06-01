<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::where('status', 'APPROVED')
            ->where('isActive', true)
            ->orderBy('createdAt', 'desc')
            ->limit(3)
            ->get();

        return view('public.home', compact('testimonials'));
    }
}

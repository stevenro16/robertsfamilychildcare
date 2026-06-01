<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TestimonialLink;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialSubmitController extends Controller
{
    public function show(string $token)
    {
        $link = TestimonialLink::where('token', $token)->first();

        if (! $link) {
            return view('public.testimonial-submit', ['state' => 'invalid', 'token' => $token, 'link' => null]);
        }

        if ($link->usedAt || $link->testimonial) {
            return view('public.testimonial-submit', ['state' => 'used', 'token' => $token, 'link' => $link]);
        }

        return view('public.testimonial-submit', ['state' => 'form', 'token' => $token, 'link' => $link]);
    }

    public function store(Request $request, string $token)
    {
        $link = TestimonialLink::where('token', $token)->whereNull('usedAt')->firstOrFail();

        $data = $request->validate([
            'parentName' => 'required|string|max:255',
            'childAge'   => 'nullable|string|max:100',
            'rating'     => 'required|integer|min:1|max:5',
            'content'    => 'required|string|min:10|max:2000',
        ]);

        Testimonial::create([
            'linkId'     => $link->id,
            'parentName' => $data['parentName'],
            'childAge'   => $data['childAge'] ?? null,
            'rating'     => $data['rating'],
            'content'    => $data['content'],
            'status'     => 'PENDING',
            'isActive'   => true,
            'createdAt'  => now(),
        ]);

        $link->update(['usedAt' => now()]);

        return view('public.testimonial-submit', ['state' => 'success', 'token' => $token, 'link' => $link]);
    }
}
<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\TestimonialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TestimonialController extends Controller
{
    public function index()
    {
        $pending  = Testimonial::where('status', 'PENDING')->orderBy('createdAt', 'desc')->get();
        $approved = Testimonial::where('status', 'APPROVED')->orderBy('createdAt', 'desc')->get();
        $rejected = Testimonial::where('status', 'REJECTED')->orderBy('createdAt', 'desc')->get();

        return view('portal.testimonials.index', compact('pending', 'approved', 'rejected'));
    }

    public function update(Request $request, string $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $data = $request->validate(['status' => 'required|in:APPROVED,REJECTED,PENDING']);
        $testimonial->update(array_merge($data, ['reviewedAt' => now()]));
        return back()->with('success', 'Testimonial updated.');
    }

    public function links()
    {
        $links = TestimonialLink::with(['createdBy', 'testimonial'])
            ->orderBy('createdAt', 'desc')
            ->get();
        return view('portal.testimonials.links', compact('links'));
    }

    public function createLink(Request $request)
    {
        $data = $request->validate([
            'parentName' => 'required|string|max:255',
        ]);

        TestimonialLink::create([
            'token'       => Str::random(32),
            'parentName'  => $data['parentName'],
            'createdById' => Auth::id(),
        ]);

        return back()->with('success', 'Link created.');
    }
}
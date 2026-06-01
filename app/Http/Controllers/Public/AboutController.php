<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;

class AboutController extends Controller
{
    public function index()
    {
        $aboutBlurb = SiteContent::get('about_blurb');
        return view('public.about', compact('aboutBlurb'));
    }
}
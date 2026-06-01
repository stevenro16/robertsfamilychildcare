<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function index()
    {
        return view('public.location');
    }
}
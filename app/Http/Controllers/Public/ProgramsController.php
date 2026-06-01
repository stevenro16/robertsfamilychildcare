<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class ProgramsController extends Controller
{
    public function index()
    {
        return view('public.programs');
    }
}
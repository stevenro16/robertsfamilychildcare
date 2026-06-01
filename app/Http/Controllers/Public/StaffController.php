<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\StaffMember;

class StaffController extends Controller
{
    public function index()
    {
        $staff = StaffMember::orderBy('sortOrder')->get();
        return view('public.staff', compact('staff'));
    }
}
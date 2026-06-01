<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\ChildContact;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    public function index()
    {
        $parent = Auth::guard('parent')->user();

        // Find children this parent is a contact for
        $childIds = ChildContact::where('contactId', $parent->contactId)->pluck('childId');
        $children = Child::whereIn('id', $childIds)->orderBy('lastName')->get();

        return view('parent.dashboard', compact('children', 'parent'));
    }
}
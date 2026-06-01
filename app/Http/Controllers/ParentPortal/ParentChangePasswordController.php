<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ParentChangePasswordController extends Controller
{
    public function show()
    {
        return view('parent.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = Auth::guard('parent')->user();
        $user->password = Hash::make($request->password);
        $user->mustChangePassword = false;
        $user->save();

        return redirect()->route('parent.dashboard')->with('success', 'Password updated.');
    }
}
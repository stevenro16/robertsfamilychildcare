<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function show()
    {
        return view('portal.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->mustChangePassword = false;
        $user->save();

        return redirect()->route('portal.dashboard')->with('success', 'Password updated successfully.');
    }
}
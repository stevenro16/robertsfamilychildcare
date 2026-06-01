<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnifiedLoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // Try staff guard (email field)
        if (Auth::guard('web')->attempt(['email' => $username, 'password' => $password, 'isActive' => true])) {
            $request->session()->regenerate();
            return redirect()->route('portal.dashboard');
        }

        // Try parent guard (username field)
        if (Auth::guard('parent')->attempt(['username' => $username, 'password' => $password])) {
            $request->session()->regenerate();
            return redirect()->route('parent.dashboard');
        }

        return back()->with('login_error', 'Invalid username or password.');
    }
}
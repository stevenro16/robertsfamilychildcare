<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('parent')->check()) {
            return redirect()->route('parent.dashboard');
        }
        return view('auth.parent-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::guard('parent')->attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('parent.dashboard'));
        }

        return back()->withErrors([
            'username' => 'These credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::guard('parent')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('https://robertsfamilychildcare.com/');
    }
}

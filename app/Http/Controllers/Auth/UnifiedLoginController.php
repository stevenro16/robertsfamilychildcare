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

        $username     = $request->input('username');
        $password     = $request->input('password');
        $stayLoggedIn = $request->boolean('stay_logged_in');

        // Try staff guard (email field)
        if (Auth::guard('web')->attempt(['email' => $username, 'password' => $password, 'isActive' => true])) {
            if ($stayLoggedIn) {
                $this->extendSession();
            }
            $request->session()->regenerate();
            return redirect()->route('portal.dashboard');
        }

        // Try parent guard (username field)
        if (Auth::guard('parent')->attempt(['username' => $username, 'password' => $password])) {
            if ($stayLoggedIn) {
                $this->extendSession();
            }
            $request->session()->regenerate();
            return redirect()->route('parent.dashboard');
        }

        return back()->withInput()->with('login_error', 'Invalid username or password.');
    }

    private function extendSession(): void
    {
        // Patch the session config before returning — Laravel's StartSession middleware
        // writes the session cookie AFTER the controller returns, so it picks up these
        // values and issues a persistent 7-day cookie instead of a browser-session cookie.
        config([
            'session.lifetime'        => 60 * 24 * 7, // 10 080 minutes = 7 days
            'session.expire_on_close' => false,
        ]);
    }
}
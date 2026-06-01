<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequirePasswordChange
{
    public function handle(Request $request, Closure $next, string $guard = 'web')
    {
        $user = Auth::guard($guard)->user();

        if ($user && $user->mustChangePassword) {
            $route = $guard === 'parent'
                ? route('parent.change-password')
                : route('portal.change-password');

            $allowed = $guard === 'parent'
                ? ['parent.change-password', 'parent.change-password.update', 'parent.logout']
                : ['portal.change-password', 'portal.change-password.update', 'staff.logout'];

            if (! $request->routeIs(...$allowed)) {
                return redirect($route);
            }
        }

        return $next($request);
    }
}

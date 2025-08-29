<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!empty($roles) && !in_array((string)($user->role ?? ''), $roles, true)) {
            return redirect()->route($user->role === 'admin' ? 'admin.dashboard.index' : 'user.dashboard.index');
        }

        return $next($request);
    }
}



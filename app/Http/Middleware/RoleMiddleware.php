<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        //Validate user role against the permitted roles list
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

       // Redirect back if authorization fails
        return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
    }
}
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
        
        // පරීක්ෂා කරනවා යූසර්ගේ රෝල් එක අදාළ ලිස්ට් එකේ තියෙනවද කියලා
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // අවසර නැත්නම් ආපහු හරවා යවනවා
        return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
    }
}
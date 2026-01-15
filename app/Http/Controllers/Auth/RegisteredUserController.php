<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validate ONLY Name & Email (No password needed)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        ]);

        // 2. Create User with HARDCODED password 'password'
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'), // 🔑 Default Password
            'role' => 0,       // Default Student
            'points' => 0,     // Start with 0 points
            // Other fields (skills, bio) will be null initially
        ]);

        event(new Registered($user));

        //Auth::login($user);

        return redirect()->route('login')
            ->with('email', $user->email)
            ->with('status', 'Registration successful! Please login with your email and the default password: password');
    }
}
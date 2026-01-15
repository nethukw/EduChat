<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    // ==========================================
    // 🌐 GOOGLE LOGIN
    // ==========================================
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $this->registerOrLoginUser($googleUser, 'google_id');
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google Login Failed or Cancelled.');
        }
    }

    // ==========================================
    // 🐙 GITHUB LOGIN
    // ==========================================
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();
            $this->registerOrLoginUser($githubUser, 'github_id');
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'GitHub Login Failed or Cancelled.');
        }
    }

    // ==========================================
    // ⚙️ SHARED LOGIC (Register or Login)
    // ==========================================
    private function registerOrLoginUser($socialUser, $providerField)
    {
        // 1. Check if user already exists with this email
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            // 2. If NOT exists, Create new user
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(16)), // Random password since they use social login
                $providerField => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'role' => 0, // Default Student
                'points' => 0,
                'academic_level' => 'Beginner'
            ]);
        } else {
            // 3. If exists, Update their provider ID (Link account)
            if (empty($user->$providerField)) {
                $user->update([
                    $providerField => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar() // Optional: Update avatar
                ]);
            }
        }

        // 4. Log the user in
        Auth::login($user);
    }
}
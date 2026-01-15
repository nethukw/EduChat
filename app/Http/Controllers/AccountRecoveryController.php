<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;

class AccountRecoveryController extends Controller
{
    /**
     * Show account recovery form
     */
    public function show()
    {
        return view('auth.recover-account');
    }

    /**
     * Restore soft-deleted account and send reset password email
     */
    public function submit(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Find soft-deleted user
        $user = User::onlyTrashed()
            ->where('email', $request->email)
            ->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'No deleted account found with this email.',
            ]);
        }

        // Restore account
        $user->restore();

        // Invalidate sessions just in case
        DB::table('sessions')->where('user_id', $user->id)->delete();

        // Send password reset link
        Password::sendResetLink([
            'email' => $user->email,
        ]);

        return redirect()
            ->route('login')
            ->with('status', 'Your account has been restored. A password reset link has been sent to your email.');
    }
}

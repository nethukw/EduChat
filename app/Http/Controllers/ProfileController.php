<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * 🧾 Show profile edit page
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * 💾 Update profile information
     * Normalizes data so profile completion & dashboards work correctly
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone'          => ['nullable', 'string', 'max:20'],
            'location'       => ['nullable', 'string', 'max:255'],
            'bio'            => ['nullable', 'string', 'max:1000'],
            'academic_level' => ['nullable', 'string', 'max:100'],
            'skills'         => ['nullable', 'string'],
            'interests'      => ['nullable', 'string'],
        ]);

        /* =====================================================
         | NORMALIZE SKILLS & INTERESTS
         | Always save as ARRAY (never JSON string)
         ===================================================== */

        $validated['skills'] = !empty($validated['skills'])
            ? array_values(array_filter(array_map(
                'trim',
                explode(',', $validated['skills'])
            )))
            : [];

        $validated['interests'] = !empty($validated['interests'])
            ? array_values(array_filter(array_map(
                'trim',
                explode(',', $validated['interests'])
            )))
            : [];

        /* =====================================================
         | SAVE PROFILE DATA
         ===================================================== */

        $user->update($validated);

        return redirect()
            ->back()
            ->with('status', 'profile-updated');
    }

    /**
     * 🔐 Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', 'min:8'],
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * 🗑️ Delete user account
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete(); // soft delete

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'account-deleted');
    }
}

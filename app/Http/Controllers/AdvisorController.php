<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdvisorController extends Controller
{
    // 🏠 1. DASHBOARD
    public function dashboard()
    {
        // Stats for Dashboard
        $pendingVerifications = DB::table('answers')->where('verification_status', 'none')->count();
        $verifiedCount = DB::table('answers')->where('verification_status', 'verified')->count();
        $flaggedContent = 0; // Future feature

        return view('advisor.dashboard', compact('pendingVerifications', 'verifiedCount'));
    }

    // 🔍 2. LIST ANSWERS TO REVIEW
    public function reviewAnswers()
    {
        // Get answers that are NOT yet verified
        $answers = DB::table('answers')
            ->join('questions', 'answers.question_id', '=', 'questions.id')
            ->join('users', 'answers.user_id', '=', 'users.id')
            ->select(
                'answers.id as answer_id',
                'answers.body as answer_body',
                'answers.created_at',
                'questions.title as question_title',
                'questions.body as question_body',
                'users.name as student_name'
            )
            ->where('answers.verification_status', 'none')
            ->orderBy('answers.created_at', 'asc')
            ->paginate(10);

        return view('advisor.review-answers', compact('answers'));
    }

    // ✅ 3. MARK AS VERIFIED
    public function verifyAnswer($id)
    {
        DB::table('answers')->where('id', $id)->update([
            'verification_status' => 'verified',
            'verified_by' => Auth::id(),
            'updated_at' => now()
        ]);

        // Optional: Give points to the student who gave the correct answer
        // $answer = DB::table('answers')->find($id);
        // User::find($answer->user_id)->increment('points', 10);

        return back()->with('success', 'Answer marked as Verified! ✅');
    }

    // ❌ 4. MARK AS INCORRECT
    public function rejectAnswer($id)
    {
        DB::table('answers')->where('id', $id)->update([
            'verification_status' => 'incorrect',
            'verified_by' => Auth::id(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Answer marked as Incorrect. ❌');
    }
}
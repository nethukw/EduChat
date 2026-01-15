<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LearningSession; // Changed from Session to LearningSession
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Get data required for the Landing Page.
     * This method pulls statistics, sessions, and top-rated teachers.
     */
    public function index()
    {
        // 1. Get system statistics for the Hero section
        $stats = [
            'students'    => User::where('role', 0)->count(),
            'teachers'    => User::where('role', 1)->count(),
            'sessions'    => LearningSession::where('status', 'approved')->count(),
            'enrollments' => DB::table('enrollments')->count(),
        ];

        // 2. Fetch the 4 latest approved sessions (Trending Sessions)
        // We eager load 'teacher' to show their name/avatar on the card
        $trendingSessions = LearningSession::with('teacher')
            ->where('status', 'approved')
            ->latest()
            ->take(4)
            ->get();

        // 3. Fetch top 4 teachers based on their XP points
        // Role 1 represents verified Teachers
        $topTeachers = User::where('role', 1)
            ->orderBy('points', 'desc')
            ->take(4)
            ->get();

        // Pass all the data to the welcome view
        return view('welcome', compact('stats', 'trendingSessions', 'topTeachers'));
    }
}
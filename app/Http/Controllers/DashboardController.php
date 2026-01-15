<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LearningSession;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | 🔥 AUTO PROMOTION (STUDENT → TEACHER)
        |--------------------------------------------------------------------------
        */
        if ($user->role == 0 && $user->points >= 1000) {
            $user->role = 1;
            $user->save();

            session()->flash(
                'promotion_success',
                '🎉 Congratulations ' . $user->name . '! You have been promoted to an INSTRUCTOR!'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 📊 PROFILE COMPLETION (COMMON – EXCEPT ADMIN)
        |--------------------------------------------------------------------------
        */
        $profileChecklist = [];
        $profilePercentage = 100;

        if ($user->role !== 3) { // Admin excluded

            $skills    = is_string($user->skills) ? json_decode($user->skills, true) : $user->skills;
            $interests = is_string($user->interests) ? json_decode($user->interests, true) : $user->interests;

            $profileChecklist = [
                'Profile photo uploaded' => !empty($user->avatar),
                'Bio added'              => !empty($user->bio),
                'Phone number added'     => !empty($user->phone),
                'Skills added'           => is_array($skills) && count($skills) > 0,
                'Academic level set'     => !empty($user->academic_level),
            ];

            $completed = collect($profileChecklist)->filter()->count();
            $total     = count($profileChecklist);

            $profilePercentage = intval(($completed / $total) * 100);
        }

        /*
        |--------------------------------------------------------------------------
        | 👨‍🏫 TEACHER DASHBOARD
        |--------------------------------------------------------------------------
        */
        if ($user->role == 1) {

            $teacherId = $user->id;

            $activeSessions = LearningSession::where('teacher_id', $teacherId)
                ->where('status', 'approved')
                ->count();

            $totalStudents = DB::table('enrollments')
                ->join('learning_sessions', 'enrollments.session_id', '=', 'learning_sessions.id')
                ->where('learning_sessions.teacher_id', $teacherId)
                ->count();

            $totalEarnings = DB::table('enrollments')
                ->join('learning_sessions', 'enrollments.session_id', '=', 'learning_sessions.id')
                ->where('learning_sessions.teacher_id', $teacherId)
                ->sum('enrollments.paid_amount');

            $recentSessions = LearningSession::where('teacher_id', $teacherId)
                ->latest()
                ->take(5)
                ->get();

            $quizAttempts = DB::table('quiz_results')
                ->join('quizzes', 'quiz_results.quiz_id', '=', 'quizzes.id')
                ->join('learning_sessions', 'quizzes.session_id', '=', 'learning_sessions.id')
                ->where('learning_sessions.teacher_id', $teacherId)
                ->count();

            return view('teacher.dashboard', compact(
                'activeSessions',
                'totalStudents',
                'totalEarnings',
                'recentSessions',
                'quizAttempts',
                'profileChecklist',
                'profilePercentage'
            ));
        }

        /*
        |--------------------------------------------------------------------------
        | 🧠 ADVISOR DASHBOARD
        |--------------------------------------------------------------------------
        */
        if ($user->role == 2) {

            $pendingVerifications = DB::table('answers')
                ->where('verification_status', 'none')
                ->count();

            $verifiedCount = DB::table('answers')
                ->where('verification_status', 'verified')
                ->count();

            return view('advisor.dashboard', compact(
                'pendingVerifications',
                'verifiedCount',
                'profileChecklist',
                'profilePercentage'
            ));
        }

        /*
        |--------------------------------------------------------------------------
        | 🛡️ ADMIN DASHBOARD (NO PROFILE COMPLETION)
        |--------------------------------------------------------------------------
        */
        if ($user->role == 3) {
            //total users, teachers, advisors, sessions, revenue, recent users
            $totalUsers = User::count();
            $totalTeachers = User::where('role', 1)->count();
            $totalAdvisors = User::where('role', 2)->count();
            $totalSessions = LearningSession::count();
            $totalRevenue = DB::table('enrollments')->sum('paid_amount');
            $recentUsers = User::latest()->take(5)->get();

            //for the charts
            $studentCount = User::where('role', User::ROLE_STUDENT)->count();
            $teacherCount = User::where('role', User::ROLE_TEACHER)->count();
            $advisorCount = User::where('role', User::ROLE_ADVISOR)->count();
            $adminCount = User::where('role', User::ROLE_ADMIN)->count();

            //session status stats
            $approvedSessions = LearningSession::where('status', 'approved')->count();
            $pendingSessions = LearningSession::where('status', 'pending')->count();
            $rejectedSessions = LearningSession::where('status', 'rejected')->count();

            return view('admin.dashboard', compact(
                'totalUsers',
                'totalTeachers',
                'totalAdvisors',
                'totalSessions',
                'totalRevenue',
                'recentUsers',

                //charts
                'studentCount',
                'teacherCount',
                'advisorCount',
                'adminCount',

                //session stats
                'approvedSessions',
                'pendingSessions',
                'rejectedSessions'
            ));
        }

        /*
        |--------------------------------------------------------------------------
        | 🎓 STUDENT DASHBOARD (DEFAULT)
        |--------------------------------------------------------------------------
        */

        $userInterests = is_string($user->interests)
            ? json_decode($user->interests, true)
            : $user->interests;

        $recommendedSessions = collect();

        if (is_array($userInterests) && count($userInterests)) {
            $recommendedSessions = LearningSession::where('status', 'approved')
                ->where(function ($query) use ($userInterests) {
                    foreach ($userInterests as $interest) {
                        $query->orWhere('category', 'LIKE', '%' . trim($interest) . '%')
                              ->orWhere('title', 'LIKE', '%' . trim($interest) . '%');
                    }
                })
                ->with('teacher')
                ->latest()
                ->take(10)
                ->get();
        }

        if ($recommendedSessions->isEmpty()) {
            $recommendedSessions = LearningSession::where('status', 'approved')
                ->with('teacher')
                ->inRandomOrder()
                ->take(6)
                ->get();
        }

        $trendingSessions = LearningSession::where('status', 'approved')
            ->whereNotIn('id', $recommendedSessions->pluck('id'))
            ->with('teacher')
            ->inRandomOrder()
            ->take(4)
            ->get();

        $recommendedTeachers = User::where('role', 1)->take(4)->get();

        return view('student.dashboard', compact(
            'recommendedSessions',
            'trendingSessions',
            'recommendedTeachers',
            'profileChecklist',
            'profilePercentage'
        ));
    }
}

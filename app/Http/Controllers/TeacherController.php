<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LearningSession;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // ✅ මෙතනට අලුතින් එකතු කළා

class TeacherController extends Controller
{
    /**
     * 📊 TEACHER DASHBOARD
     */
    public function dashboard()
    {
        $user = Auth::user();
        $teacherId = $user->id;

        /*
        |--------------------------------------------------------------------------
        | PROFILE COMPLETION LOGIC
        |--------------------------------------------------------------------------
        */
        $profileChecklist = [
            'Profile photo uploaded' => !empty($user->avatar),
            'Bio added'              => !empty($user->bio),
            'Phone number added'     => !empty($user->phone),
            'Skills added'           => !empty($user->skills),
            'Academic level set'     => !empty($user->academic_level),
        ];

        $completedSteps   = collect($profileChecklist)->filter()->count();
        $totalSteps       = count($profileChecklist);
        $profilePercentage = intval(($completedSteps / $totalSteps) * 100);

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD STATISTICS
        |--------------------------------------------------------------------------
        */
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

        $topicRequestCount = DB::table('topic_requests')->where('status', 'open')->count();

        return view('teacher.dashboard', compact(
            'activeSessions',
            'totalStudents',
            'totalEarnings',
            'recentSessions',
            'quizAttempts',
            'profilePercentage',
            'profileChecklist',
            'topicRequestCount'
        ));
    }

    /**
     * 📚 MY SESSIONS
     */
    public function mySessions()
    {
        $sessions = LearningSession::where('teacher_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('teacher.my-sessions', compact('sessions'));
    }

    /**
     * ➕ CREATE SESSION
     */
    public function createSession(Request $request)
{
    $user = Auth::user();
    
    // URL එකෙන් එන 'topic' එක ගන්නවා
    $preFilledTopic = $request->query('topic'); 

    // Profile Completion Logic
    $profileChecklist = [
        'avatar'         => !empty($user->avatar),
        'bio'            => !empty($user->bio),
        'phone'          => !empty($user->phone),
        'skills'         => !empty($user->skills),
        'academic_level' => !empty($user->academic_level),
    ];

    $completedSteps = collect($profileChecklist)->filter()->count();
    $percentage = intval(($completedSteps / count($profileChecklist)) * 100);

    if ($percentage < 40) {
        return redirect()
            ->route('profile.edit')
            ->with('error', 'ඔබේ Profile එක අවම වශයෙන් 40% ක් සම්පූර්ණ කරන්න (දැනට: ' . $percentage . '%).');
    }

    $existingCategories = LearningSession::distinct()->pluck('category')->filter()->values(); //
    
    $topicRequests = DB::table('topic_requests')
        ->join('users', 'topic_requests.student_id', '=', 'users.id')
        ->select('topic_requests.*', 'users.name as student_name')
        ->where('topic_requests.status', 'open')
        ->latest('topic_requests.created_at')
        ->take(10)
        ->get();

    return view('teacher.create-session', compact('existingCategories', 'topicRequests', 'preFilledTopic'));
}

    /**
     * 💾 STORE SESSION
     */
    public function storeSession(Request $request)
    {

    $feeType = Auth::user()->role >= 1 ? ($request->fee_type ?? 'free') : 'free';
    $baseFee = Auth::user()->role >= 1 ? ($request->base_fee ?? 0) : 0;

        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'category'     => 'required|string|max:100',
            'session_date' => 'required|date',
            'session_time' => 'required',
            'duration'     => 'required|integer',
        ]);

        $fullStartTime = $request->session_date . ' ' . $request->session_time;

        $session = LearningSession::create([
        'teacher_id'        => Auth::id(),
        'title'             => $request->title,
        'description'       => $request->description,
        'meeting_link'      => $request->meeting_link,
        'category'          => ucwords(strtolower($request->category)),
        'start_time'        => $request->session_date . ' ' . $request->session_time,
        'duration_minutes' => $request->duration,
        'fee_type'          => $feeType, // ✅ මාරු කළා
        'base_fee'          => $baseFee, // ✅ මාරු කළා
        'status'            => 'pending',
    ]);

        if ($request->hasFile('material_file')) {
            $path = $request->file('material_file')->store('materials', 'public');

            DB::table('materials')->insert([
                'session_id' => $session->id,
                'title'      => $request->material_title ?? 'Lecture Notes',
                'file_path'  => Storage::url($path), // ✅ Standardized path handling
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()
            ->route('teacher.sessions.index')
            ->with('success', 'Session submitted successfully!');
    }

    /**
     * 🧠 QUIZZES
     */
    public function quizzes()
    {
        $user = Auth::user();
        $teacherId = $user->id;

        $profileChecklist = [
            'avatar'         => !empty($user->avatar),
            'bio'            => !empty($user->bio),
            'phone'          => !empty($user->phone),
            'skills'         => !empty($user->skills),
            'academic_level' => !empty($user->academic_level),
        ];

        $completedSteps = collect($profileChecklist)->filter()->count();
        $percentage = intval(($completedSteps / count($profileChecklist)) * 100);

        if ($percentage < 40) {
            return redirect()
                ->route('profile.edit')
                ->with('error', 'Please complete at least 40% of your profile to manage quizzes.');
        }

        $quizzes = DB::table('quizzes')
            ->join('learning_sessions', 'quizzes.session_id', '=', 'learning_sessions.id')
            ->leftJoin('quiz_results', 'quizzes.id', '=', 'quiz_results.quiz_id')
            ->where('learning_sessions.teacher_id', $teacherId)
            ->select(
                'quizzes.id',
                'quizzes.title',
                'quizzes.created_at',
                'learning_sessions.title as session_title',
                'learning_sessions.category',
                DB::raw('COUNT(quiz_results.id) as total_attempts')
            )
            ->groupBy(
                'quizzes.id',
                'quizzes.title',
                'quizzes.created_at',
                'learning_sessions.title',
                'learning_sessions.category'
            )
            ->get();

        $mySessions = LearningSession::where('teacher_id', $teacherId)->get();

        return view('teacher.quizzes', compact('quizzes', 'mySessions'));
    }

    /**
     * 🏁 STORE QUIZ
     */
    public function storeQuiz(Request $request)
    {
        $request->validate([
            'title'      => 'required',
            'session_id' => 'required',
        ]);

        DB::table('quizzes')->insert([
            'title'      => $request->title,
            'session_id' => $request->session_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::find(Auth::id())->increment('points', 20);

        return redirect()
            ->route('teacher.quizzes.index')
            ->with('success', 'Quiz Created! +20 XP points earned.');
    }

    /**
     * 📝 EDIT SESSION
     */
    public function editSession($id)
    {
        $session = LearningSession::where('id', $id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        return view('teacher.edit-session', compact('session'));
    }

    /**
     * 🆙 UPDATE SESSION
     */
    public function updateSession(Request $request, $id)
    {
        $session = LearningSession::where('id', $id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required',
            'duration_minutes' => 'required|integer',
        ]);

        $session->update($request->all());

        return back()->with('success', 'Session details updated successfully!');
    }

    /**
     * 📂 MATERIAL UPLOAD
     */
    public function uploadMaterial(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'file' => 'required|mimes:pdf,doc,docx,zip|max:5120',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('materials', 'public');

            DB::table('materials')->insert([
                'session_id' => $id,
                'title'      => $request->title,
                'file_path'  => Storage::url($path), // ✅ Correct import used here
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return back()->with('success', 'Material uploaded successfully!');
        }

        return back()->with('error', 'File upload failed.');
    }

    /**
     * 💡 TOPIC REQUESTS
     */
    public function topicRequests()
{
    $requests = DB::table('topic_requests')
        ->join('users', 'topic_requests.student_id', '=', 'users.id')
        ->select('topic_requests.*', 'users.name as student_name', 'users.avatar as student_avatar')
        ->where('topic_requests.status', 'open')
        ->orderBy('topic_requests.created_at', 'desc')
        ->paginate(12);

    return view('teacher.requests-index', compact('requests'));
}
}
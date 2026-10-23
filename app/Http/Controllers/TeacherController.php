<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LearningSession;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; 
use App\Services\ZoomService; // Service for automated meeting link generation
use App\Models\Quiz;

class TeacherController extends Controller
{
    /**
     * Display the Teacher Dashboard with real-time statistics and profile health.
     * Includes logic for profile completion percentage.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $teacherId = $user->id;

        // Calculate Average Rating from 'teacher_ratings' table
    $averageRating = DB::table('teacher_ratings')
    ->where('teacher_id', $teacherId)
    ->avg('rating'); // Implementation of the SQL AVG() aggregate function using Laravel's Query Builder

        /*
        |--------------------------------------------------------------------------
        | PROFILE COMPLETION LOGIC
        |--------------------------------------------------------------------------
        | Calculates how much of the teacher's profile is complete to ensure
        | quality and credibility on the platform.
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
        | DASHBOARD STATISTICS (Aggregated via Eloquent and DB Facade)
        |--------------------------------------------------------------------------
        */
        // Count only sessions that have been approved by an Advisor
        $activeSessions = LearningSession::where('teacher_id', $teacherId)
            ->where('status', 'approved')
            ->count();

        // Calculate total unique students enrolled across all teacher's sessions
        $totalStudents = DB::table('enrollments')
            ->join('learning_sessions', 'enrollments.session_id', '=', 'learning_sessions.id')
            ->where('learning_sessions.teacher_id', $teacherId)
            ->count();

        // Sum up total earnings from paid sessions
        $totalEarnings = DB::table('enrollments')
            ->join('learning_sessions', 'enrollments.session_id', '=', 'learning_sessions.id')
            ->where('learning_sessions.teacher_id', $teacherId)
            ->sum('enrollments.paid_amount');

        $recentSessions = LearningSession::where('teacher_id', $teacherId)
            ->latest()
            ->take(5)
            ->get();

        // Track how many students have attempted quizzes linked to teacher's sessions
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
     * Retrieve a paginated list of all sessions created by the authenticated teacher.
     */
    public function mySessions()
    {
        $sessions = LearningSession::where('teacher_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('teacher.my-sessions', compact('sessions'));
    }

    /**
     * Show the form for creating a new learning session.
     * Enforces a 40% profile completion rule as a quality gatekeeper.
     */
    public function createSession(Request $request)
    {
        $user = Auth::user();
        
        // Handle pre-filled topics coming from the Topic Request module
        $preFilledTopic = $request->query('topic'); 

        // Quality Gate: Check if profile is sufficient for teaching
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
                ->with('error', 'Your profile must be at least 40% complete (Current: ' . $percentage . '%).');
        }

        // Fetch existing categories to provide suggestions in the UI
        $existingCategories = LearningSession::distinct()->pluck('category')->filter()->values(); 
        
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
     * Store a newly created session in the database.
     * Now includes Automated Zoom Meeting generation.
     */
    public function storeSession(Request $request, ZoomService $zoomService)
    {
        // Business Logic: Only Roles >= 1 (Teacher/Advisor) can set fees.
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
        
        // 1. Auto generate meeting link
        $autoZoomLink = $zoomService->createMeeting(
            $request->title, 
            $fullStartTime, 
            $request->duration
        );

       //If the link was not created, return without saving data
        if (!$autoZoomLink) {
            return back()
                ->withInput()
                ->with('error', 'Zoom link generation failed.Add it manually.');
        }

        // 3. Persist session data 
        $session = LearningSession::create([
            'teacher_id'       => Auth::id(),
            'title'            => $request->title,
            'description'      => $request->description,
            'meeting_link'     => $autoZoomLink,
            'category'         => ucwords(strtolower($request->category)),
            'start_time'       => $fullStartTime,
            'duration_minutes' => $request->duration,
            'fee_type'         => $feeType, 
            'base_fee'         => $baseFee, 
            'status'           => 'pending',
        ]);

        //Send a notification to all students in the system

        $allStudents = User::where('role', 0)->get(); 
            foreach ($allStudents as $student) {
            $student->notify(new \App\Notifications\NewSessionNotification([
                'title' => 'New Class Available! 🎓',
                'message' => "A new class has been created: {$request->title}",
                'link' => route('student.sessions') 
    ]));
}
        
        // Handle Optional Material Upload 
        if ($request->hasFile('material_file')) {
            $path = $request->file('material_file')->store('materials', 'public');

            DB::table('materials')->insert([
                'session_id' => $session->id,
                'title'      => $request->material_title ?? 'Lecture Notes',
                'file_path'  => Storage::url($path),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()
            ->route('teacher.sessions.index')
            ->with('success', 'Session submitted successfully! Students notified.');
    }

    /**
     * Display quizzes created by the teacher and total student attempts.
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

        // Aggregate quiz attempt data using GroupBy
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
     * Store a new quiz and award Experience Points (XP) to the teacher.
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

        //Notify those who have registered for this class
        $session = LearningSession::find($request->session_id);
        $enrolledStudents = DB::table('enrollments')->where('session_id', $request->session_id)->pluck('student_id');

        foreach ($enrolledStudents as $studentId) {
            $student = User::find($studentId);
            if ($student) {
                $student->notify(new \App\Notifications\NewSessionNotification([
                    'title' => 'New Quiz Added! 📝',
                    'message' => "'{$session->title}' New Quiz added for the class.",
                    'link' => route('student.quizzes') 
                ]));
            }
        }

        User::find(Auth::id())->increment('points', 20);

        return redirect()->route('teacher.quizzes.index')->with('success', 'Quiz Created and students notified!');
    }

    /**
     * Show edit form for a specific session, ensuring ownership.
     */
    public function editSession($id)
    {
        $session = LearningSession::where('id', $id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        return view('teacher.edit-session', compact('session'));
    }

    /**
     * Update session details.
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

       // Ensure that meeting_link is not NULL
        $data = $request->all();
        unset($data['meeting_link']); // Disable manual link editing

        $session->update($data);

        return back()->with('success', 'Session details updated successfully!');
    }

    /**
     * Handle material uploads with specific file mime-type validation.
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
                'file_path'  => Storage::url($path), 
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            //Notify students
            $session = LearningSession::find($id);
            $enrolledStudents = DB::table('enrollments')->where('session_id', $id)->pluck('student_id');

            foreach ($enrolledStudents as $studentId) {
                $student = User::find($studentId);
                if ($student) {
                    $student->notify(new \App\Notifications\NewSessionNotification([
                        'title' => 'New Material Uploaded! 📚',
                        'message' => "'{$session->title}' A new lecture note has been added.",
                        'link' => route('student.sessions.show', $id)
                    ]));
                }
            }

            return back()->with('success', 'Material uploaded and students notified!');
        }

        return back()->with('error', 'File upload failed.');
    }

    /**
     * Retrieve and paginate student-generated topic requests.
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

    /**
     * Delete a specific quiz created by the teacher.
     */

        public function deleteQuiz($id)
        {
            $quiz = \App\Models\Quiz::findOrFail($id);

            // Check if this quiz belongs to this teacher (Security)
            if ($quiz->session->teacher_id !== auth()->id()) {
                abort(403);
            }

            $quiz->delete(); //Dependent quiz_questions will be deleted via cascade

            return back()->with('success', 'Quiz deleted successfully! 🗑️');
        }


    /**
     *  Show the question management page for a specific quiz.
     */
        public function manageQuestions($id)
        {
            
            $quiz = Quiz::with('session')->findOrFail($id);

            if ($quiz->session->teacher_id !== Auth::id()) {
                abort(403);
            }

            $questions = DB::table('quiz_questions')
                ->where('quiz_id', $id)
                ->get();

            return view('teacher.manage-questions', compact('quiz', 'questions'));
        }


    /**
     *  Save a new MCQ question to the database.
     */
    public function storeQuizQuestion(Request $request, $id)
    {
        $request->validate([
            'question'       => 'required|string',
            'option_a'       => 'required|string',
            'option_b'       => 'required|string',
            'option_c'       => 'required|string',
            'option_d'       => 'required|string',
            'correct_option' => 'required|in:A,B,C,D',
        ]);

        DB::table('quiz_questions')->insert([
            'quiz_id'        => $id,
            'question'       => $request->question,
            'option_a'       => $request->option_a,
            'option_b'       => $request->option_b,
            'option_c'       => $request->option_c,
            'option_d'       => $request->option_d,
            'correct_option' => $request->correct_option,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return back()->with('success', 'Question added successfully! 🎯');
    }

    /**
     *  View results of students who attempted a specific quiz.
     */
        public function viewQuizResults($id)
    {
        $quiz = \App\Models\Quiz::with('session')->findOrFail($id);

        if ($quiz->session->teacher_id !== auth()->id()) {
            abort(403);
        }

        $results = DB::table('quiz_results')
            ->join('users', 'quiz_results.user_id', '=', 'users.id') 
            ->select('quiz_results.*', 'users.name as student_name', 'users.avatar as student_avatar')
            ->where('quiz_results.quiz_id', $id)
            ->orderBy('quiz_results.score', 'desc')
            ->get();

        return view('teacher.quiz-results', compact('quiz', 'results'));
    }

    /**
     *  Display the list of students enrolled in the class.
     */
    public function viewEnrolledStudents($id)
    {
        // 1. Fetch the class
        $session = LearningSession::findOrFail($id);

        // Verify if this class belongs to the authenticated teacher
        if ($session->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Retrieve student data using a Join Query
        $students = DB::table('enrollments')
            ->join('users', 'enrollments.student_id', '=', 'users.id')
            ->where('enrollments.session_id', $id)
            ->select(
                'users.name', 
                'users.email', 
                'users.avatar', 
                'enrollments.created_at as enrolled_at', 
                'enrollments.status'
            )
            ->get();

        return view('teacher.enrolled-students', compact('session', 'students'));
    }

    /**
     *  Remove a class (Session) from the system.
     */
    public function deleteSession($id)
    {
    // 1. Find the class (Checking with Teacher ID for security)
        $session = LearningSession::where('id', $id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

    // 2. Delete the class (If using Soft Deletes, the 'deleted_at' column will be updated)
        $session->delete();

    // 3. Redirect back to the list
        return redirect()->route('teacher.sessions.index')
            ->with('success', 'Session deleted successfully! 🗑️');
    }
}
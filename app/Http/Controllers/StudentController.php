<?php

namespace App\Http\Controllers;

use App\Models\LearningSession;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer; // ✅ Answer Model එක එකතු කළා
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * 🔥 XP HANDLE & AUTO PROMOTION LOGIC (Centralized Logic)
     * XP Categories: 'General', 'Academic', 'Community'
     */
    private function addXpAndCheckPromotion($userId, $xpAmount, $category = 'General')
    {
        $user = User::find($userId);
        if (!$user) return;

        $user->increment('points', $xpAmount);
        $user->refresh(); // fetch latest data from DB

        // logging XP gain to the leaderboard
        DB::table('points_logs')->insert([
            'user_id' => $userId,
            'points' => $xpAmount,
            'category' => $category,
            'activity_type' => 'engagement',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 2. Check for promotion to teacher
        if ($user->role == 0 && $user->points >= 1000) {
            $user->role = 1; // making the student a teacher (Role 1)
            $user->save();

            // upon promotion, flash a special message
            session()->flash('promotion_success', '🎉 Congratulations ' . $user->name . '! You have reached ' . $user->points . ' XP points and have been promoted to an INSTRUCTOR! Your dashboard is now updated with teaching tools.');
        }
    }

    /**
     * 🏠 1. STUDENT DASHBOARD
     */
    public function dashboard()
    {
        $user = Auth::user();

        // ✅ COMMON PROFILE COMPLETION (Admin auto-skipped)
        $profile = $user->profileCompletion();

        $profilePercentage   = $profile['percentage'];
        $profileChecklist    = $profile['checks'];
        $missingProfileItems = $profile['missing'];

        $recommendedSessions = LearningSession::where('status', 'approved')
            ->with('teacher')
            ->latest()
            ->take(6)
            ->get();

        $trendingSessions = LearningSession::where('status', 'approved')
            ->with('teacher')
            ->inRandomOrder()
            ->take(4)
            ->get();

        $recommendedTeachers = User::where('role', 1)->take(4)->get();

        return view('student.dashboard', compact(
            'recommendedSessions',
            'trendingSessions',
            'recommendedTeachers',
            'profilePercentage',
            'profileChecklist',
            'missingProfileItems'
        ));
    }

    /**
     * 🎥 2. EXPLORE SESSIONS
     */
    public function sessions(Request $request)
    {
        $query = LearningSession::where('status', 'approved')->with('teacher');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category') && $request->category != 'All') {
            $query->where('category', $request->category);
        }

        $sessions = $query->latest()->paginate(9);
        $categories = LearningSession::distinct()->whereNotNull('category')->pluck('category')->filter()->values();

        return view('student.sessions', compact('sessions', 'categories'));
    }

    /**
     * 🗨️ 3. FORUM / DISCUSSIONS
     */
    public function discussions(Request $request)
{
    $subject = $request->get('subject');
    $search = $request->get('search');
    
    $query = Question::with(['user', 'answers.user'])->withCount('answers');

    // 🔍 Search Bar Logic (Swen's Requirement)
    if ($request->filled('search')) {
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->orWhere('body', 'like', '%' . $search . '%');
        });
    }

    // 📚 Subject Wise Separation (Swen's Requirement)
    if ($subject && $subject !== 'All') {
        $query->where('subject_tag', $subject);
    }

    $questions = $query->latest()->paginate(10);
    
    // දැනට පද්ධතියේ ඇති සියලුම Subjects ලබා ගැනීම
    $subjects = Question::distinct()->pluck('subject_tag')->filter();

    return view('student.discussions', compact('questions', 'subjects', 'subject', 'search'));
}

    /**
     * ✅ STORE DISCUSSION (Ask Question - Earn 5 XP)
     */
    public function storeDiscussion(Request $request)
    {
        $request->validate(['title' => 'required', 'body' => 'required']);
        $subject = $request->tag ?? 'General';

        Question::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'body' => $request->body,
            'subject_tag' => $subject,
        ]);

        // ලකුණු 5ක් ලබා දී ප්‍රවර්ධනය පරීක්ෂා කරයි
        $this->addXpAndCheckPromotion(Auth::id(), 5, $subject);

        return back()->with('success', 'Discussion posted! You earned 5 XP points.');
    }

    /**
     * 💬 REPLY LOGIC (Fix for missing method error - Earn 2 XP)
     */
    public function replyDiscussion(Request $request, $id)
    {
        $request->validate(['body' => 'required']);

        $question = Question::findOrFail($id);

        Answer::create([
            'question_id' => $id,
            'user_id' => Auth::id(),
            'body' => $request->body,
            'verification_status' => 'none'
        ]);

        // පිළිතුරක් ලබා දුන් විට ලකුණු 2ක් ලබා දීම
        $this->addXpAndCheckPromotion(Auth::id(), 2, $question->subject_tag ?? 'General');

        return back()->with('success', 'Reply posted successfully! +2 XP earned.');
    }

    /**
     * 👍 LIKE / UPVOTE LOGIC (Earn 1 XP)
     */
    public function likeDiscussion($id)
    {
        $userId = Auth::id();
        $exists = DB::table('post_likes')->where('post_id', $id)->where('user_id', $userId)->exists();

        if ($exists) {
            DB::table('post_likes')->where('post_id', $id)->where('user_id', $userId)->delete();
        } else {
            DB::table('post_likes')->insert([
                'post_id' => $id,
                'user_id' => $userId,
                'created_at' => now()
            ]);
            // for interaction each will gain 1 XP for keep activities on going
            $this->addXpAndCheckPromotion($userId, 1, 'Community');
        }

        return back();
    }

    /**
     * 🧠 4. QUIZZES & SUBMISSION
     */
    public function quizzes()
    {
        $quizzes = DB::table('quizzes')
            ->join('learning_sessions', 'quizzes.session_id', '=', 'learning_sessions.id')
            ->select('quizzes.*', 'learning_sessions.title as session_title', 'learning_sessions.category')
            ->get();
        return view('student.quizzes', compact('quizzes'));
    }

    public function takeQuiz($id)
    {
        $quiz = DB::table('quizzes')->where('id', $id)->first();
        $questions = DB::table('quiz_questions')->where('quiz_id', $id)->get();
        return view('student.take-quiz', compact('quiz', 'questions'));
    }

    public function submitQuiz(Request $request, $id)
    {
        $score = 50; // Mock Score
        $category = 'Academic';

        DB::table('quiz_results')->insert([
            'student_id' => Auth::id(),
            'quiz_id' => $id,
            'score' => $score,
            'total_questions' => 10,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // to add up XP and check for promotion
        $this->addXpAndCheckPromotion(Auth::id(), $score, $category);

        return redirect()->route('student.quizzes')->with('success', 'Quiz completed! You earned ' . $score . ' XP.');
    }

    /**
     * 💬 5. TOPIC REQUESTS
     */
    public function requests()
    {
        $myRequests = DB::table('topic_requests')->where('student_id', Auth::id())->latest()->get();
        $categories = LearningSession::distinct()->whereNotNull('category')->pluck('category')->filter()->values();
        return view('student.requests', compact('myRequests', 'categories'));
    }

    public function storeRequest(Request $request)
    {
        $request->validate(['subject' => 'required', 'topic' => 'required']);

        DB::table('topic_requests')->insert([
            'student_id' => Auth::id(),
            'subject' => $request->subject,
            'topic' => $request->topic,
            'description' => $request->description,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Topic request submitted.');
    }

    /**
     * 🎥 6. ENROLLMENT & MY LEARNING
     */
    public function sessionDetail($id)
    {
        $session = LearningSession::with('teacher')->findOrFail($id);
        $isEnrolled = DB::table('enrollments')->where('session_id', $id)->where('student_id', Auth::id())->exists();
        return view('student.session-detail', compact('session', 'isEnrolled'));
    }

    public function enrollSession($id)
    {
        $session = LearningSession::findOrFail($id);

        // Already enrolled?
        $exists = DB::table('enrollments')
            ->where('session_id', $id)
            ->where('student_id', Auth::id())
            ->exists();

        if ($exists) {
            return redirect()->route('student.learning')
                ->with('info', 'Already enrolled.');
        }

        // ✅ FREE SESSION → DIRECT ENROLL
        if ((float)$session->base_fee <= 0) {

            DB::table('enrollments')->insert([
                'session_id'  => $id,
                'student_id'  => Auth::id(),
                'paid_amount' => 0.00,
                'status'      => 'paid',
                'created_at'  => now(),
                'updated_at'  => now()
            ]); 

            return redirect()->route('student.learning')
                ->with('success', 'Enrolled successfully!');
        }

        // ❌ PAID SESSION ONLY
        return redirect()->route('payments.checkout', $id);
    }




    public function myLearning()
    {
        $mySessions = DB::table('enrollments')
            ->join('learning_sessions', 'enrollments.session_id', '=', 'learning_sessions.id')
            ->join('users', 'learning_sessions.teacher_id', '=', 'users.id')
            ->where('enrollments.student_id', Auth::id())
            ->select('learning_sessions.*', 'users.name as teacher_name')
            ->get();
        return view('student.my-learning', compact('mySessions'));
    }

    /**
     * 🏆 7. LEADERBOARD (XP Based)
     */
    public function leaderboard(Request $request)
    {
        $subject = $request->get('subject');
        $period = $request->get('period', 'all');

        $query = DB::table('users')
            ->join('points_logs', 'users.id', '=', 'points_logs.user_id')
            ->select(
                'users.id',
                'users.name',
                'users.avatar',
                'users.academic_level',
                'points_logs.category as subject',
                DB::raw('SUM(points_logs.points) as total_points')
            )
            ->where('users.role', 0);

        if ($subject && $subject !== 'All') {
            $query->where('points_logs.category', $subject);
        }

        if ($period == 'weekly') {
            $query->where('points_logs.created_at', '>=', Carbon::now()->startOfWeek());
        } elseif ($period == 'monthly') {
            $query->where('points_logs.created_at', '>=', Carbon::now()->startOfMonth());
        }

        $rankings = $query->groupBy('users.id', 'users.name', 'users.avatar', 'users.academic_level', 'points_logs.category')
            ->orderBy('total_points', 'desc')
            ->take(20)
            ->get();

        $subjects = LearningSession::distinct()->whereNotNull('category')->pluck('category')->filter()->values();

        return view('student.leaderboard', compact('rankings', 'subjects', 'subject', 'period'));
    }
}

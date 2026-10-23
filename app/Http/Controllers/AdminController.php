<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LearningSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewSessionNotification;
use Illuminate\Support\Facades\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalTeachers = User::where('role', 1)->count();
        $totalAdvisors = User::where('role', 2)->count();
        $totalSessions = LearningSession::count();
        $totalRevenue = DB::table('enrollments')->sum('paid_amount');

        $studentCount = User::where('role', 0)->count();
        $teacherCount = User::where('role', 1)->count();
        $advisorCount = User::where('role', 2)->count();
        $adminCount   = User::where('role', 3)->count();

        $approvedSessions = LearningSession::where('status', 'approved')->count();
        $pendingSessions  = LearningSession::where('status', 'pending')->count();
        $rejectedSessions = LearningSession::where('status', 'cancelled')->count();

        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTeachers',
            'totalAdvisors',
            'totalSessions',
            'totalRevenue',
            'recentUsers',
            'studentCount',
            'teacherCount',
            'advisorCount',
            'adminCount',
            'approvedSessions',
            'pendingSessions',
            'rejectedSessions'
        ));
    }

    //user managment functions
    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users', compact('users'));
    }


    public function promoteToAdvisor($id)
    {
        $user = User::findOrFail($id);
        $user->role = 2; // to promote to Advisor
        $user->save();

        return back()->with('success', $user->name . ' Promoted to Advisor successfully.');
    }

    // demote advisor and teacher to student
    public function demoteUser($id)
    {
        $user = User::findOrFail($id);
        $user->role = 0;
        $user->save();

        return back()->with('success', $user->name . ' Demoted to Student successfully.');
    }


    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // to make sure that the admin is not deleting their own account
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own admin account.');
        }

        $user->delete();
        return back()->with('success', 'User account deleted successfully.');
    }


    public function sessions()
    {
        $sessions = LearningSession::with('teacher')->latest()->paginate(10);


        $pendingCount = LearningSession::where('status', 'pending')->count();

        return view('admin.sessions', compact('sessions', 'pendingCount'));
    }


    public function approveSession($id)
    {
        $session = LearningSession::findOrFail($id);
        $session->status = 'approved';
        $session->save();

        // Send notification to all students
        $students = User::where('role', 0)->get();

        Notification::send($students, new NewSessionNotification([
            'title'   => 'New Class Available! 🎓',
            'message' => "A new class '{$session->title}' has been approved. You can now enroll.",
            'link'    => route('student.sessions'),
        ]));

        return back()->with(
            'success',
            '"' . $session->title . '" session approved and notifications sent to all students.'
        );
    }



    public function deleteSession($id)
    {
        $session = LearningSession::findOrFail($id);
        $session->delete();

        return back()->with('success', 'Session deleted successfully.');
    }



public function discussions()
{
    
    $questions = DB::table('questions')
        ->join('users', 'questions.user_id', '=', 'users.id')
        ->select('questions.*', 'users.name as user_name')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('admin.discussions', compact('questions')); 
}

    //delete discussion function
    public function deleteDiscussion($id)
    {
        DB::table('questions')->where('id', $id)->delete();
        return back()->with('success', 'Forum post deleted successfully.');
    }

    // function to recover user accounts from admin dashboard
    public function recover($request, User $user)
    {
        //ensure only the admins can recover the user account
        if (Auth::user()->role != 3) {
            abort(403, 'Unauthorized');
        }

        //restore the deleted account
        $user->restore();

        return redirect()->back()->with('success', 'User Account Has been Successfully recovered');
    }

    public function generateReport(Request $request)
{
    $fromDate = $request->input('from_date', now()->startOfMonth()->format('Y-m-d'));
    $toDate = $request->input('to_date', now()->format('Y-m-d'));

    $data = [
        'from' => $fromDate,
        'to'   => $toDate,
        'total_students' => User::where('role', 0)->whereBetween('created_at', [$fromDate, $toDate])->count(),
        
        
        'total_teachers' => User::where('role', 1)->whereBetween('created_at', [$fromDate, $toDate])->count(),
        
        'total_sessions' => DB::table('learning_sessions')->whereBetween('created_at', [$fromDate, $toDate])->count(),
        'total_revenue'  => DB::table('enrollments')->whereBetween('created_at', [$fromDate, $toDate])->sum('paid_amount'),
        
        'recent_enrollments' => DB::table('enrollments')
            ->join('users', 'enrollments.student_id', '=', 'users.id')
            ->join('learning_sessions', 'enrollments.session_id', '=', 'learning_sessions.id')
            ->select('users.name as student', 'learning_sessions.title as session', 'enrollments.paid_amount', 'enrollments.created_at')
            ->whereBetween('enrollments.created_at', [$fromDate, $toDate])
            ->latest('enrollments.created_at')
            ->get(),
        'generated_at' => now()->format('Y-m-d H:i')
    ];

    $pdf = Pdf::loadView('admin.reports.summary', $data);
    return $pdf->download("EduChat_Report_{$fromDate}_to_{$toDate}.pdf");
}
}


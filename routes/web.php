<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// සියලුම Controllers Import කිරීම
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdvisorController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\AccountRecoveryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\LandingPageController;

/*
|--------------------------------------------------------------------------
| 🌐 1. PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::get('/about', function () {
    return view('public.about');
})->name('about');

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| 🌍 SOCIAL LOGIN & ACCOUNT RECOVERY
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
    Route::get('auth/github', [SocialAuthController::class, 'redirectToGithub'])->name('auth.github');
    Route::get('auth/github/callback', [SocialAuthController::class, 'handleGithubCallback']);

    Route::get('/recover-account', [AccountRecoveryController::class, 'show'])->name('account.recover.form');
    Route::post('/recover-account', [AccountRecoveryController::class, 'submit'])->name('account.recover.submit');
});

/*
|--------------------------------------------------------------------------
| 🔐 2. AUTHENTICATED SHARED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 // 🤖 AI Chatbot Route (Accessible by everyone)
    Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])->name('chatbot.ask');
    
    // User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

    Route::get('/notifications/mark-read', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.read');

    /* --------------------------------------------------------------------
        🎓 SHARED SESSION CREATION (Role: 0 and 1)
        ශිෂ්‍යයන්ට (0) සහ ගුරුවරුන්ට (1) දෙදෙනාටම පන්ති හැදීමට මෙතැනින් අවසර ලැබේ.
    -------------------------------------------------------------------- */
    Route::middleware(['role:0,1'])->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/sessions/create', [TeacherController::class, 'createSession'])->name('sessions.create');
        Route::post('/sessions/store', [TeacherController::class, 'storeSession'])->name('sessions.store');
    });

    /* --------------------------------------------------------------------
        🧑‍🎓 STUDENT FEATURES (Role 0)
    -------------------------------------------------------------------- */
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/discussions', [StudentController::class, 'discussions'])->name('discussions');
        Route::post('/discussions', [StudentController::class, 'storeDiscussion'])->name('discussions.store');
        Route::post('/discussions/{id}/reply', [StudentController::class, 'replyDiscussion'])->name('discussions.reply');
        Route::post('/discussions/{id}/like', [StudentController::class, 'likeDiscussion'])->name('discussions.like');

        Route::get('/sessions', [StudentController::class, 'sessions'])->name('sessions');
        Route::get('/sessions/{id}', [StudentController::class, 'sessionDetail'])->name('sessions.show');
        Route::post('/sessions/{id}/enroll', [StudentController::class, 'enrollSession'])->name('sessions.enroll');
        Route::get('/my-learning', [StudentController::class, 'myLearning'])->name('learning');

        Route::get('/requests', [StudentController::class, 'requests'])->name('requests');
        Route::post('/requests', [StudentController::class, 'storeRequest'])->name('requests.store');
        Route::get('/quizzes', [StudentController::class, 'quizzes'])->name('quizzes');
        Route::get('/quizzes/{id}/take', [StudentController::class, 'takeQuiz'])->name('quizzes.take');
        Route::post('/quizzes/{id}/submit', [StudentController::class, 'submitQuiz'])->name('quizzes.submit');
        Route::get('/leaderboard', [StudentController::class, 'leaderboard'])->name('leaderboard');
    });

    /* --------------------------------------------------------------------
        💳 PAYMENT SYSTEM
    -------------------------------------------------------------------- */
    Route::get('/payments/session/{id}', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::post('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
});

/*
|--------------------------------------------------------------------------
| 👨‍🏫 3. TEACHER ONLY ROUTES (Role: 1)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:1'])->prefix('teacher')->name('teacher.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/sessions', [TeacherController::class, 'mySessions'])->name('sessions.index');
    Route::get('/sessions/{id}/edit', [TeacherController::class, 'editSession'])->name('sessions.edit');
    Route::post('/sessions/{id}/update', [TeacherController::class, 'updateSession'])->name('sessions.update');
    Route::delete('/sessions/{id}', [TeacherController::class, 'deleteSession'])->name('sessions.destroy');

    Route::get('/sessions/{id}/students', [TeacherController::class, 'viewEnrolledStudents'])->name('sessions.students');
    Route::post('/sessions/{id}/material', [TeacherController::class, 'uploadMaterial'])->name('sessions.material.upload');
    Route::get('/topic-requests', [TeacherController::class, 'topicRequests'])->name('requests.index');

    Route::get('/quizzes', [TeacherController::class, 'quizzes'])->name('quizzes.index');
    Route::post('/quizzes', [TeacherController::class, 'storeQuiz'])->name('quizzes.store');
    Route::get('/quizzes/{id}/manage', [TeacherController::class, 'manageQuestions'])->name('quizzes.manage');
    Route::post('/quizzes/{id}/questions', [TeacherController::class, 'storeQuizQuestion'])->name('quizzes.questions.store');
    Route::delete('/questions/{id}', [TeacherController::class, 'deleteQuizQuestion'])->name('quizzes.questions.delete');
    Route::get('/quizzes/{id}/results', [TeacherController::class, 'viewQuizResults'])->name('quizzes.results');
    
    Route::get('/feedback', [TeacherController::class, 'feedback'])->name('feedback.index');
});

/*
|--------------------------------------------------------------------------
| ⚖️ 4. ADVISOR ROUTES (Role: 2)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:2'])->prefix('advisor')->name('advisor.')->group(function () {
    Route::get('/dashboard', [AdvisorController::class, 'dashboard'])->name('dashboard');
    Route::get('/reviews', [AdvisorController::class, 'reviewAnswers'])->name('reviews');
    Route::post('/reviews/{id}/approve', [AdvisorController::class, 'verifyAnswer'])->name('verify.approve');
    Route::post('/reviews/{id}/reject', [AdvisorController::class, 'rejectAnswer'])->name('verify.reject');
});

/*
|--------------------------------------------------------------------------
| 👮‍♂️ 5. ADMIN ROUTES (Role: 3)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:3'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::match(['get', 'post'], '/users/{id}/promote', [AdminController::class, 'promoteToAdvisor'])->name('users.promote');
    Route::match(['get', 'post'], '/users/{id}/demote', [AdminController::class, 'demoteUser'])->name('users.demote');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/users/{id}/recover', [AdminController::class, 'recover'])->name('users.recover');

    Route::get('/sessions', [AdminController::class, 'sessions'])->name('sessions');
    Route::match(['get', 'post'], '/sessions/{id}/approve', [AdminController::class, 'approveSession'])->name('sessions.approve');
    Route::delete('/sessions/{id}', [AdminController::class, 'deleteSession'])->name('sessions.delete');

    Route::get('/discussions', [AdminController::class, 'discussions'])->name('discussions');
    Route::delete('/discussions/{id}', [AdminController::class, 'deleteDiscussion'])->name('discussions.delete');
});

/* --------------------------------------------------------------------
    💳 EXTERNAL SERVICES
-------------------------------------------------------------------- */
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);
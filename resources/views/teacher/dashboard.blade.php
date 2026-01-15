@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- 1. Header & Quick Actions -->
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Instructor Dashboard 👨‍🏫</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Welcome back! Manage your academic activities and track your growth.</p>
        </div>
        <div class="flex flex-wrap gap-3">
    <a href="{{ route('teacher.requests.index') }}" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-amber-100 dark:shadow-none transition transform hover:-translate-y-1">
        <i class="fas fa-lightbulb"></i> Student Requests
    </a>

    <a href="{{ route('teacher.quizzes.index') }}" class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-emerald-100 dark:shadow-none transition transform hover:-translate-y-1">
        <i class="fas fa-brain"></i> Manage Quizzes
    </a>

    <a href="{{ route('teacher.sessions.create') }}" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-indigo-100 dark:shadow-none transition transform hover:-translate-y-1">
        <i class="fas fa-plus"></i> New Session
    </a>
</div>
    </div>

    <!-- 🔒 PROFILE COMPLETION SOFT BLOCKER -->
    @if($profilePercentage < 80)
    <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 p-5 rounded-xl">
        <div class="flex items-start gap-3">
            <i class="fas fa-exclamation-triangle text-yellow-500 mt-1"></i>
            <div>
                <h4 class="font-bold text-yellow-800 dark:text-yellow-300">
                    Profile Incomplete
                </h4>
                <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-1">
                    Complete your profile to unlock all instructor features.
                    Some actions are limited until your profile reaches at least <strong>80%</strong>.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- 2. Reward & Progress Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <!-- Contribution Points -->
        <div class="lg:col-span-1 bg-gradient-to-br from-indigo-600 to-purple-700 p-8 rounded-3xl shadow-lg text-white flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex justify-between items-start">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center text-3xl">
                        <i class="fas fa-award text-yellow-300"></i>
                    </div>
                    <span class="bg-white/20 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">
                        Expert Level
                    </span>
                </div>

                <div class="mt-6">
                    <p class="text-indigo-100 text-sm font-bold uppercase tracking-widest">
                        Contribution Points
                    </p>
                    <h4 class="text-5xl font-black mt-1">
                        {{ Auth::user()->points ?? 0 }}
                    </h4>
                    <p class="text-xs text-indigo-200 mt-4 leading-relaxed">
                        Earn <strong>20 points</strong> for every quiz you create!
                        High points improve your visibility to students.
                    </p>
                </div>
            </div>

            <i class="fas fa-star absolute -bottom-4 -right-4 text-9xl opacity-10"></i>
        </div>

        <!-- Profile Completion Card -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col md:flex-row items-center gap-10">
            <div class="relative w-32 h-32 flex-shrink-0">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <path
                        class="text-gray-100 dark:text-slate-700"
                        stroke-width="3"
                        stroke="currentColor"
                        fill="transparent"
                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                    />
                    <path
                        class="text-indigo-600"
                        stroke-width="3"
                        stroke-dasharray="{{ $profilePercentage }}, 100"
                        stroke-linecap="round"
                        stroke="currentColor"
                        fill="transparent"
                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                    />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center font-black text-2xl dark:text-white">
                    {{ $profilePercentage }}%
                </div>
            </div>

            <div>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Profile Strength
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mt-2 leading-relaxed text-sm">
                    A complete profile with your bio, phone, and skills helps build trust with your students.
                    Complete it to unlock advanced teacher features.
                </p>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 mt-4 text-indigo-600 dark:text-indigo-400 font-bold hover:gap-3 transition-all">
                    Finish Setup <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- ✅ PROFILE COMPLETION CHECKLIST -->
    <div class="mb-10 bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 p-6">
        <h4 class="font-bold text-gray-800 dark:text-white mb-4">
            Profile Completion Checklist
        </h4>

        <ul class="space-y-3">
            @foreach($profileChecklist as $label => $completed)
            <li class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-5 h-5 flex items-center justify-center rounded-full
                        {{ $completed ? 'bg-green-500' : 'bg-gray-300 dark:bg-slate-600' }}">
                        <i class="fas {{ $completed ? 'fa-check' : 'fa-minus' }} text-white text-[10px]"></i>
                    </span>
                    <span class="text-sm {{ $completed ? 'text-gray-800 dark:text-white' : 'text-gray-400' }}">
                        {{ $label }}
                    </span>
                </div>

                @if(!$completed)
                <a href="{{ route('profile.edit') }}"
                   class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                    Complete
                </a>
                @endif
            </li>
            @endforeach
        </ul>
    </div>

    <!-- 3. Key Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border-l-4 border-green-500 transition hover:shadow-md">
            <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest">Total Earnings</p>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-1">
                LKR {{ number_format($totalEarnings) }}
            </h3>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border-l-4 border-blue-500 transition hover:shadow-md">
            <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest">Total Students</p>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-1">
                {{ $totalStudents }}
            </h3>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border-l-4 border-indigo-500 transition hover:shadow-md">
            <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest">Active Classes</p>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-1">
                {{ $activeSessions }}
            </h3>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border-l-4 border-purple-500 transition hover:shadow-md">
            <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest">Quiz Engagement</p>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-1">
                {{ $quizAttempts ?? 0 }}
            </h3>
        </div>
    </div>

    <!-- 4. Recent Classes Table -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-gray-50 dark:border-slate-700 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 dark:text-white text-lg flex items-center gap-2">
                <i class="fas fa-history text-gray-400"></i> Recent Classes
            </h3>
            <a href="{{ route('teacher.sessions.index') }}" class="text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                View All Sessions
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-slate-900/50 text-gray-500 dark:text-gray-400 text-[10px] uppercase font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-4">Session Info</th>
                        <th class="px-6 py-4">Schedule</th>
                        <th class="px-6 py-4 text-center">Type</th>
                        <th class="px-8 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                    @forelse($recentSessions as $session)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                        <td class="px-8 py-5">
                            <div class="font-bold text-gray-900 dark:text-white">{{ $session->title }}</div>
                            <div class="text-[10px] text-gray-400 uppercase font-bold">{{ $session->category }}</div>
                        </td>
                        <td class="px-6 py-5 text-sm">
                            <i class="far fa-calendar-alt mr-1 opacity-50"></i>
                            {{ \Carbon\Carbon::parse($session->start_time)->format('M d, H:i') }}
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                {{ $session->fee_type == 'free'
                                    ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                    : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                {{ $session->fee_type }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <a href="{{ route('teacher.sessions.edit', $session->id) }}"
                               class="inline-flex items-center gap-2 bg-gray-100 dark:bg-slate-700 hover:bg-indigo-600 hover:text-white text-gray-600 dark:text-gray-300 px-4 py-2 rounded-xl text-xs font-bold transition">
                                <i class="fas fa-edit"></i> Manage
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-16 text-center text-gray-400 italic">
                            <div class="mb-3 text-3xl opacity-20">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            No sessions found. Create your first one to get started!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

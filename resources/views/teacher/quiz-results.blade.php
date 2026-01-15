@extends('layouts.app')

@section('content')
<!-- Teacher Quiz Results Analytics -->
<div class="max-w-7xl mx-auto px-4 py-8 animate-fade-in bg-slate-50/50 min-h-screen rounded-[3rem]">
    
    <!-- 🔝 Header & Breadcrumbs -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-8 px-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase text-slate-400 tracking-widest mb-4">
                <a href="{{ route('teacher.quizzes.index') }}" class="hover:text-indigo-600 transition">Quiz Center</a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <span class="text-indigo-600">Performance Analytics</span>
            </nav>
            <h1 class="text-5xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">
                Results Analysis <span class="text-emerald-500">.</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium text-lg italic">
                Detailed performance breakdown for: <span class="text-indigo-600 font-bold not-italic">"{{ $quiz->title }}"</span>
            </p>
        </div>

        <a href="{{ route('teacher.quizzes.index') }}" class="bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-black px-8 py-4 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 hover:bg-slate-50 transition flex items-center gap-3 uppercase text-xs tracking-widest">
            <i class="fas fa-arrow-left"></i> Back to Center
        </a>
    </div>

    <!-- 📊 Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-4 mb-12">
        <div class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/40 dark:shadow-none border border-white dark:border-slate-700">
            <p class="text-[11px] uppercase font-black text-slate-400 tracking-widest mb-3">Total Attempts</p>
            <div class="flex items-end gap-3">
                <p class="text-4xl font-black text-slate-900 dark:text-white leading-none">{{ $results->count() }}</p>
                <p class="text-sm font-bold text-emerald-500 mb-1">Students Participated</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/40 dark:shadow-none border border-white dark:border-slate-700">
            <p class="text-[11px] uppercase font-black text-slate-400 tracking-widest mb-3">Average Accuracy</p>
            <div class="flex items-end gap-3">
                @php 
                    $avg = $results->avg('score') ?? 0;
                    $totalQ = $results->first()->total_questions ?? 10;
                    $percentage = ($avg / $totalQ) * 100;
                @endphp
                <p class="text-4xl font-black text-indigo-600 leading-none">{{ number_format($percentage, 0) }}%</p>
                <p class="text-sm font-bold text-slate-400 mb-1">Class Proficiency</p>
            </div>
        </div>

        <div class="bg-indigo-600 p-8 rounded-[2.5rem] shadow-2xl shadow-indigo-200 dark:shadow-none text-white relative overflow-hidden">
            <i class="fas fa-chart-line absolute -right-4 -bottom-4 text-8xl opacity-10 rotate-12"></i>
            <p class="text-[11px] uppercase font-black text-indigo-200 tracking-widest mb-3">Top Score</p>
            <div class="flex items-end gap-3">
                <p class="text-4xl font-black leading-none">{{ $results->max('score') ?? 0 }} <span class="text-lg opacity-60">/ {{ $totalQ }}</span></p>
                <p class="text-sm font-bold text-indigo-100 mb-1">Peak Performance</p>
            </div>
        </div>
    </div>

    <!-- 📋 Detailed Results Table -->
    <div class="px-4">
        <div class="bg-white dark:bg-slate-800 rounded-[3rem] shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-900/50">
                        <th class="px-10 py-8 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Student</th>
                        <th class="px-8 py-8 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Score Achieved</th>
                        <th class="px-8 py-8 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Percentage</th>
                        <th class="px-10 py-8 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Completion Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    @forelse($results as $res)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-all duration-300">
                        <td class="px-10 py-8">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-slate-700 flex items-center justify-center text-indigo-600 font-black text-lg">
                                    {{ substr($res->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 dark:text-white text-lg leading-tight">{{ $res->name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mt-1">Verified Learner</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-8">
                            <span class="text-2xl font-black text-slate-900 dark:text-white">{{ $res->score }}</span>
                            <span class="text-sm font-bold text-slate-300 uppercase ml-1">/ {{ $res->total_questions }} pts</span>
                        </td>
                        <td class="px-8 py-8">
                            @php $p = ($res->score / $res->total_questions) * 100; @endphp
                            <div class="flex items-center gap-4">
                                <div class="flex-1 h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden max-w-[100px]">
                                    <div class="h-full {{ $p >= 75 ? 'bg-emerald-500' : ($p >= 40 ? 'bg-indigo-500' : 'bg-rose-500') }}" style="width: {{ $p }}%"></div>
                                </div>
                                <span class="font-black {{ $p >= 75 ? 'text-emerald-600' : ($p >= 40 ? 'text-indigo-600' : 'text-rose-600') }}">{{ number_format($p, 0) }}%</span>
                            </div>
                        </td>
                        <td class="px-10 py-8 text-right">
                            <p class="font-bold text-slate-700 dark:text-slate-300 text-sm italic">
                                {{ \Carbon\Carbon::parse($res->created_at)->format('M d, Y') }}
                            </p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">
                                {{ \Carbon\Carbon::parse($res->created_at)->diffForHumans() }}
                            </p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-32 text-center">
                            <div class="w-24 h-24 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200 text-4xl">
                                <i class="fas fa-user-slash"></i>
                            </div>
                            <h3 class="text-xl font-black text-slate-400 uppercase tracking-tighter">No Attempts Recorded</h3>
                            <p class="text-slate-400 font-medium italic mt-2">Waiting for students to complete this assessment.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>
@endsection
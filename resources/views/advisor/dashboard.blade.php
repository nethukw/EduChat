@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Advisor Panel 🧠</h1>
            <p class="text-gray-500 dark:text-gray-400">Ensure content quality and verify answers.</p>
        </div>
        <a href="{{ route('advisor.reviews') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2 rounded-lg font-bold shadow-lg transition">
            Start Reviewing
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border-l-4 border-yellow-500 flex items-center justify-between">
            <div>
                <div class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wide">Pending Reviews</div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $pendingVerifications }}</div>
            </div>
            <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-xl">
                <i class="fas fa-clock"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border-l-4 border-green-500 flex items-center justify-between">
            <div>
                <div class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wide">Verified Answers</div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $verifiedCount }}</div>
            </div>
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xl">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border-l-4 border-blue-500 flex items-center justify-between">
            <div>
                <div class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wide">Your Reputation</div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">Level 5</div>
            </div>
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xl">
                <i class="fas fa-medal"></i>
            </div>
        </div>

    </div>

    <div class="bg-indigo-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-indigo-100 dark:border-slate-700">
        <h3 class="font-bold text-indigo-800 dark:text-indigo-400 mb-2">📝 Guidelines for Advisors</h3>
        <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
            <li>Verify answers only if you are 100% sure they are correct.</li>
            <li>Mark answers as "Incorrect" if they are misleading or factually wrong.</li>
            <li>Report spam or abusive content to Admin immediately.</li>
            <li>Your verifications help students trust the platform!</li>
        </ul>
    </div>

</div>
@endsection
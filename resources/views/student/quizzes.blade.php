@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Available Quizzes 🧠</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($quizzes as $quiz)
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 hover:shadow-md transition group">
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition">
                <i class="fas fa-question"></i>
            </div>
            
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $quiz->title }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Related to: {{ $quiz->session_title }}</p>
            
            <div class="mt-6 flex justify-between items-center">
                <span class="text-xs font-bold bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">
                    Ready to Start
                </span>
                
                <a href="{{ route('student.quizzes.take', $quiz->id) }}" class="bg-purple-600 text-white text-sm font-bold px-4 py-2 rounded-lg hover:bg-purple-700 transition shadow-md shadow-purple-200 dark:shadow-none">
                    Start Quiz
                </a>
            </div>
        </div>
        @endforeach

        @if($quizzes->isEmpty())
            <div class="col-span-3 text-center py-12 bg-white dark:bg-slate-800 rounded-xl border-2 border-dashed border-gray-200 dark:border-slate-700">
                <div class="w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 text-2xl">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <p class="text-gray-500 dark:text-gray-400 font-medium">No quizzes available at the moment.</p>
                <p class="text-sm text-gray-400 dark:text-gray-500">Enroll in sessions to unlock new quizzes!</p>
            </div>
        @endif
    </div>
</div>
@endsection
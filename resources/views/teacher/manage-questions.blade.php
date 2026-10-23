@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    {{-- Header Section --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Manage Questions 📝</h1>
            <p class="text-gray-500">Quiz: <span class="font-bold text-primary">{{ $quiz->title }}</span></p>
        </div>
        <a href="{{ route('teacher.quizzes.index') }}" class="text-gray-500 hover:text-gray-700 font-medium">
            <i class="fas fa-arrow-left mr-1"></i> Back to Quizzes
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left: Add Question Form --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 sticky top-6">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Add New MCQ</h2>
                
                <form action="{{ route('teacher.quizzes.questions.store', $quiz->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Question Text</label>
                            <textarea name="question" rows="3" required class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-900 text-sm" placeholder="e.g. What is the primary key in SQL?"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <label class="text-xs text-gray-500 font-bold uppercase">Option A</label>
                                <input type="text" name="option_a" required class="w-full rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-900 text-sm">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 font-bold uppercase">Option B</label>
                                <input type="text" name="option_b" required class="w-full rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-900 text-sm">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 font-bold uppercase">Option C</label>
                                <input type="text" name="option_c" required class="w-full rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-900 text-sm">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 font-bold uppercase">Option D</label>
                                <input type="text" name="option_d" required class="w-full rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-900 text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 text-primary">Correct Answer</label>
                            <select name="correct_option" required class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-900 text-sm font-bold">
                                <option value="A">Option A</option>
                                <option value="B">Option B</option>
                                <option value="C">Option C</option>
                                <option value="D">Option D</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/20">
                            Add Question
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: List of Questions --}}
        <div class="lg:col-span-2">
            <div class="space-y-4">
                @forelse($questions as $index => $q)
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-gray-100 dark:border-slate-700 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-1 h-full bg-primary opacity-50"></div>
                    
                    <div class="flex justify-between items-start mb-4">
                        <span class="bg-gray-100 dark:bg-slate-700 text-gray-500 text-xs font-bold px-3 py-1 rounded-full uppercase">Question {{ $index + 1 }}</span>
                        <form action="{{ route('teacher.quizzes.questions.delete', $q->id) }}" method="POST" onsubmit="return confirm('Delete this question?')">
                            @csrf @method('DELETE')
                            <button class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>

                    <h3 class="font-bold text-gray-800 dark:text-white mb-4">{{ $q->question }}</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 rounded-xl border {{ $q->correct_option == 'A' ? 'border-secondary bg-green-50 dark:bg-green-900/10' : 'border-gray-100 dark:border-slate-700' }}">
                            <span class="text-xs font-bold mr-2 {{ $q->correct_option == 'A' ? 'text-secondary' : 'text-gray-400' }}">A</span> {{ $q->option_a }}
                        </div>
                        <div class="p-3 rounded-xl border {{ $q->correct_option == 'B' ? 'border-secondary bg-green-50 dark:bg-green-900/10' : 'border-gray-100 dark:border-slate-700' }}">
                            <span class="text-xs font-bold mr-2 {{ $q->correct_option == 'B' ? 'text-secondary' : 'text-gray-400' }}">B</span> {{ $q->option_b }}
                        </div>
                        <div class="p-3 rounded-xl border {{ $q->correct_option == 'C' ? 'border-secondary bg-green-50 dark:bg-green-900/10' : 'border-gray-100 dark:border-slate-700' }}">
                            <span class="text-xs font-bold mr-2 {{ $q->correct_option == 'C' ? 'text-secondary' : 'text-gray-400' }}">C</span> {{ $q->option_c }}
                        </div>
                        <div class="p-3 rounded-xl border {{ $q->correct_option == 'D' ? 'border-secondary bg-green-50 dark:bg-green-900/10' : 'border-gray-100 dark:border-slate-700' }}">
                            <span class="text-xs font-bold mr-2 {{ $q->correct_option == 'D' ? 'text-secondary' : 'text-gray-400' }}">D</span> {{ $q->option_d }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-20 bg-white dark:bg-slate-800 rounded-2xl border-2 border-dashed border-gray-200 dark:border-slate-700">
                    <i class="fas fa-file-alt text-4xl text-gray-200 mb-4 block"></i>
                    <p class="text-gray-500">No questions added yet. Use the form to start.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
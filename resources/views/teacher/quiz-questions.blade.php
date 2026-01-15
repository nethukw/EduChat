@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Manage Questions 📝</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Quiz: <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $quiz->title }}</span>
            </p>
        </div>
        <a href="{{ route('teacher.quizzes.index') }}" class="flex items-center gap-2 text-gray-500 hover:text-indigo-600 transition font-medium">
            <i class="fas fa-arrow-left"></i> Back to Quizzes
        </a>
    </div>

    <!-- ➕ Add New Question Card -->
    <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 mb-10 transition-all hover:shadow-md">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                <i class="fas fa-plus"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Add New Question</h3>
        </div>

        <form action="{{ route('teacher.quizzes.questions.store', $quiz->id) }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Question Text</label>
                <textarea name="question" rows="3" class="w-full border-gray-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-sm" placeholder="Type your MCQ question here..." required></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                @foreach(['a', 'b', 'c', 'd'] as $opt)
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold uppercase">{{ $opt }}</span>
                    <input type="text" name="option_{{ $opt }}" placeholder="Option {{ strtoupper($opt) }}" 
                           class="w-full pl-10 border-gray-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-sm" required>
                </div>
                @endforeach
            </div>

            <div class="mb-8">
                <label class="block text-xs font-bold text-gray-400 uppercase mb-3 ml-1">Select Correct Answer</label>
                <div class="grid grid-cols-4 gap-3">
                    @foreach(['A', 'B', 'C', 'D'] as $choice)
                    <label class="relative">
                        <input type="radio" name="correct_option" value="{{ $choice }}" class="peer sr-only" {{ $choice == 'A' ? 'checked' : '' }}>
                        <div class="p-3 text-center border-2 border-gray-100 dark:border-slate-700 rounded-2xl cursor-pointer transition peer-checked:border-indigo-600 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 font-bold text-gray-400 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400">
                            {{ $choice }}
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-indigo-100 dark:shadow-none transition transform hover:-translate-y-0.5 active:scale-95">
                <i class="fas fa-save mr-2"></i> Save Question
            </button>
        </form>
    </div>

    <!-- 📋 Existing Questions List -->
    <div class="flex items-center justify-between mb-6 px-2">
        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Question Bank</h3>
        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $questions->count() }} Total Questions</span>
    </div>

    <div class="space-y-6 mb-10">
        @forelse($questions as $q)
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 relative group transition-all hover:border-indigo-200 dark:hover:border-indigo-900/50">
            <div class="flex justify-between items-start mb-5">
                <div class="flex items-center gap-4">
                    <span class="w-10 h-10 bg-gray-100 dark:bg-slate-700 rounded-xl flex items-center justify-center font-bold text-gray-500 text-sm">
                        {{ $loop->iteration }}
                    </span>
                    <p class="font-bold text-gray-900 dark:text-white text-lg leading-snug">{{ $q->question }}</p>
                </div>
                
                <form action="{{ route('teacher.quizzes.questions.delete', $q->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-gray-300 hover:text-red-500 transition p-2">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-14">
                @foreach(['a', 'b', 'c', 'd'] as $opt)
                @php 
                    $optField = 'option_'.$opt; 
                    $isCorrect = strtoupper($opt) == $q->correct_option;
                @endphp
                <div class="flex items-center gap-3 p-3 rounded-xl border {{ $isCorrect ? 'bg-green-50 border-green-200 text-green-700 dark:bg-green-900/20 dark:border-green-800 dark:text-green-400 font-bold' : 'border-gray-50 dark:border-slate-700/50 text-gray-600 dark:text-gray-400' }}">
                    <span class="w-6 h-6 flex items-center justify-center rounded-lg border border-current text-[10px] font-black">{{ strtoupper($opt) }}</span>
                    <span class="text-sm">{{ $q->$optField }}</span>
                    @if($isCorrect)
                        <i class="fas fa-check-circle ml-auto text-green-500"></i>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-slate-800 p-16 rounded-3xl border-2 border-dashed border-gray-200 dark:border-slate-700 text-center">
            <div class="w-20 h-20 bg-gray-50 dark:bg-slate-900/50 text-gray-200 dark:text-gray-600 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">
                <i class="fas fa-question-circle"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">No Questions Found</h3>
            <p class="text-gray-500 dark:text-gray-400 max-w-xs mx-auto">Start building your assessment by adding your first MCQ above!</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
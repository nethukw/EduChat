@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Pending Verifications 🔍</h1>
        <a href="{{ route('advisor.dashboard') }}" class="text-gray-500 hover:text-gray-700">Back to Dashboard</a>
    </div>

    <div class="space-y-6">
        @foreach($answers as $ans)
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            
            <div class="mb-4 pb-4 border-b border-gray-100 dark:border-slate-700">
                <span class="text-xs font-bold text-gray-400 uppercase">Question:</span>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-1">{{ $ans->question_title }}</h3>
                <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $ans->question_body }}</p>
            </div>

            <div class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-xl mb-4">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name={{ $ans->student_name }}" class="w-6 h-6 rounded-full">
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $ans->student_name }}'s Answer:</span>
                    </div>
                    <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($ans->created_at)->diffForHumans() }}</span>
                </div>
                <p class="text-gray-800 dark:text-gray-200 text-base leading-relaxed">
                    {{ $ans->answer_body }}
                </p>
            </div>

            <div class="flex justify-end gap-3">
                <form action="{{ route('advisor.verify.reject', $ans->answer_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg font-bold text-sm transition flex items-center gap-2">
                        <i class="fas fa-times"></i> Incorrect
                    </button>
                </form>

                <form action="{{ route('advisor.verify.approve', $ans->answer_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold text-sm shadow-md transition flex items-center gap-2">
                        <i class="fas fa-check"></i> Verify Answer
                    </button>
                </form>
            </div>

        </div>
        @endforeach

        @if($answers->isEmpty())
            <div class="text-center py-16 bg-white dark:bg-slate-800 rounded-2xl border-2 border-dashed border-gray-200 dark:border-slate-700">
                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fas fa-check-double"></i>
                </div>
                <h3 class="font-bold text-gray-700 dark:text-gray-300">All Caught Up!</h3>
                <p class="text-gray-500 text-sm">There are no answers waiting for verification.</p>
            </div>
        @endif
    </div>

    <div class="mt-6">
        {{ $answers->links() }}
    </div>
</div>
@endsection
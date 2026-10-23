@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Quiz Performance 🏆</h1>
            <p class="text-gray-500">Results for: <span class="font-bold text-primary">{{ $quiz->title }}</span></p>
        </div>
        <a href="{{ route('teacher.quizzes.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Back</a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-slate-700 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-6 py-4">Student</th>
                    <th class="px-6 py-4 text-center">Score</th>
                    <th class="px-6 py-4">Attempted At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @forelse($results as $result)
                <tr>
                    <td class="px-6 py-4 flex items-center gap-3">
                        <img src="{{ $result->student_avatar ?? 'https://ui-avatars.com/api/?name='.$result->student_name }}" class="w-8 h-8 rounded-full">
                        <span class="font-medium">{{ $result->student_name }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $result->score >= 50 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $result->score }}%
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($result->created_at)->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-10 text-center text-gray-400">No students have attempted this quiz yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
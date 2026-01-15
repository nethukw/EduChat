@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">My Learning 🎓</h1>

    @if($mySessions->isEmpty())
        <div class="text-center py-16 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-gray-300 dark:border-slate-700">
            <div class="w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 text-2xl">
                <i class="fas fa-book-open"></i>
            </div>
            <h3 class="font-bold text-gray-700 dark:text-gray-300">No Enrollments Yet</h3>
            <p class="text-gray-500 text-sm mb-4">You haven't joined any classes yet.</p>
            <a href="{{ route('student.sessions') }}" class="bg-primary text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 transition">Browse Classes</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($mySessions as $session)
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden hover:shadow-md transition">
                <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white">
                    <i class="fas fa-chalkboard-teacher text-5xl opacity-20"></i>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-1">{{ $session->title }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">by {{ $session->teacher_name }}</p>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded font-bold">Enrolled</span>
                        <a href="{{ route('student.sessions.show', $session->id) }}" class="text-primary font-bold text-sm hover:underline">Go to Class →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
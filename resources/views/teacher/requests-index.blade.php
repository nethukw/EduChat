@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800 dark:text-white uppercase tracking-tighter">Student Topic Requests 💡</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">See what students want to learn next and create sessions for them.</p>
        </div>
        <a href="{{ route('teacher.sessions.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition transform hover:-translate-y-1">
            <i class="fas fa-plus mr-2"></i> Create Session
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($requests as $request)
        <div class="bg-white dark:bg-slate-800 p-6 rounded-[2rem] shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition group">
            
            <div class="flex items-center gap-4 mb-4">
                <img src="{{ $request->student_avatar ?? 'https://ui-avatars.com/api/?name='.$request->student_name }}" class="w-10 h-10 rounded-full border-2 border-indigo-100">
                <div>
                    <h4 class="font-bold text-gray-800 dark:text-white leading-none">{{ $request->student_name }}</h4>
                    <span class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Student Request</span>
                </div>
            </div>

            <h3 class="text-lg font-black text-indigo-600 dark:text-indigo-400 mb-2 leading-tight">
                {{ $request->topic }}
            </h3>
            
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3 italic">
                "{{ $request->description }}"
            </p>

            <div class="pt-4 border-t border-gray-50 dark:border-slate-700 flex justify-between items-center">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}
                </span>
                
                {{--  use 'topic' in the route helper for pre-filling --}}
                <a href="{{ route('teacher.sessions.create', ['topic' => $request->topic]) }}" class="text-xs font-black text-indigo-600 dark:text-indigo-400 hover:underline">
                    Create Session <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <div class=\"w-20 h-20 bg-gray-50 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-200 text-4xl\">
                <i class="fas fa-lightbulb"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">No Requests Yet</h3>
            <p class="text-gray-500">Students haven't requested any specific topics yet.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $requests->links() }}
    </div>
</div>
@endsection
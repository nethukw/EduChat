@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('teacher.sessions.index') }}" class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to My Sessions
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl overflow-hidden border border-gray-100 dark:border-slate-700">
        
        {{-- Header Section --}}
        <div class="bg-indigo-600 p-8 text-white">
            <h1 class="text-2xl font-bold">Enrolled Students 🎓</h1>
            <p class="opacity-80 mt-1">Class: {{ $session->title }}</p>
            <div class="mt-4 inline-block bg-white/20 px-4 py-1 rounded-full text-xs font-bold uppercase">
                Total Students: {{ $students->count() }}
            </div>
        </div>

        <div class="p-6 overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-gray-100 dark:border-slate-700 text-gray-400 text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Contact Info</th>
                        <th class="px-6 py-4">Enrolled Date</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                    @forelse($students as $student)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $student->avatar ? asset('storage/'.$student->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($student->name) }}" 
                                     class="w-10 h-10 rounded-full border border-gray-200">
                                <div class="font-bold text-gray-800 dark:text-gray-200">{{ $student->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $student->email }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($student->enrolled_at)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded-full">
                                {{ $student->status ?? 'Paid' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center text-gray-400 italic">
                            No students have enrolled for this session yet. 🔦
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
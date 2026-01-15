@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Enrolled Students 👥</h1>
            <p class="text-gray-500">{{ $session->title }}</p>
        </div>
        <a href="{{ route('teacher.sessions.index') }}" class="text-gray-500 hover:text-gray-700">Back</a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-gray-100 dark:border-slate-700">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-slate-700 text-gray-500 dark:text-gray-300 text-xs uppercase">
                <tr>
                    <th class="px-6 py-4">Student Name</th>
                    <th class="px-6 py-4">Joined At</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                @foreach($students as $student)
                <tr>
                    <td class="px-6 py-4 flex items-center gap-3">
                        <img src="{{ $student->avatar ?? 'https://ui-avatars.com/api/?name='.$student->name }}" class="w-8 h-8 rounded-full">
                        <div>
                            <p class="font-bold">{{ $student->name }}</p>
                            <p class="text-xs text-gray-500">{{ $student->email }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($student->joined_at)->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded uppercase">{{ $student->status }}</span>
                    </td>
                </tr>
                @endforeach
                @if($students->isEmpty())
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">No students enrolled yet.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
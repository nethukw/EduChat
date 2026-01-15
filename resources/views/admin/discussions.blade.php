@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Manage Forum Discussions 💬</h1>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-gray-100 dark:border-slate-700">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-slate-700 text-xs uppercase text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-4">Topic</th>
                    <th class="px-6 py-4">Posted By</th>
                    <th class="px-6 py-4">Tag</th>
                    <th class="px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                @foreach($questions as $q)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold">{{ $q->title }}</div>
                        <div class="text-xs text-gray-500 truncate w-64">{{ $q->body }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $q->user_name }}</td>
                    <td class="px-6 py-4"><span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">{{ $q->subject_tag }}</span></td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.discussions.delete', $q->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-50 text-red-600 p-2 rounded hover:bg-red-100 text-xs font-bold">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">
            {{ $questions->links() }}
        </div>
    </div>
</div>
@endsection
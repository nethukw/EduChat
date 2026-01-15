@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Manage Users 👥</h1>
        <div class="bg-white dark:bg-slate-800 px-4 py-2 rounded-lg shadow-sm text-sm font-bold text-gray-600 dark:text-gray-300">
            Total: {{ $users->total() }}
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-gray-100 dark:border-slate-700">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-slate-700 text-xs uppercase text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Current Role</th>
                    <th class="px-6 py-4">Points</th>
                    <th class="px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.$user->name }}" class="w-10 h-10 rounded-full">
                            <div>
                                <div class="font-bold">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->role == 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Student</span>
                        @elseif($user->role == 1)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Teacher</span>
                        @elseif($user->role == 2)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Advisor</span>
                        @elseif($user->role == 3)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Admin</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-mono">{{ $user->points }}</td>
                    <td class="px-6 py-4 flex gap-2">
                        <!--
                        @if($user->id != Auth::id())
                        <form action="{{ route('admin.users.recover', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to recover this user?');">
                            @csrf
                            @method('POST')
                            <button type="submit" class="bg-green-50 text-green-600 p-2 rounded hover:bg-green-100 text-xs font-bold" title="Recover User">
                                <i class="fas fa-rotate-left"></i>
                            </button>
                        </form>
                        @endif
                        -->

                    
                        @if($user->id != Auth::id())
                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-50 text-red-600 p-2 rounded hover:bg-red-100 text-xs font-bold" title="Delete User">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
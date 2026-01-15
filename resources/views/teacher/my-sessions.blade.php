@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">My Sessions List 📚</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage your active classes and track student enrollments.</p>
        </div>
        
        <a href="{{ route('teacher.sessions.create') }}" class="bg-primary hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-500/30 transition transform hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fas fa-plus"></i> Create New
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-gray-100 dark:border-slate-700">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-slate-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Session Title</th>
                        <th class="px-6 py-4 font-semibold">Date & Time</th>
                        <th class="px-6 py-4 font-semibold">Fee Model</th>
                        <th class="px-6 py-4 font-semibold">Price</th>
                        <th class="px-6 py-4 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                    @forelse($sessions as $session)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition duration-200">
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/50 text-primary flex items-center justify-center">
                                    <i class="fas fa-chalkboard"></i>
                                </div>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $session->title }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-sm">
                            <div class="flex flex-col">
                                <span class="font-semibold">{{ \Carbon\Carbon::parse($session->start_time)->format('M d, Y') }}</span>
                                <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if($session->fee_type == 'shared')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">SHARED</span>
                            @elseif($session->fee_type == 'free')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">FREE</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">FIXED</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 font-medium">
                            @if($session->base_fee == 0)
                                <span class="text-green-600 font-bold">Free</span>
                            @else
                                LKR {{ number_format($session->base_fee) }}
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('teacher.sessions.students', $session->id) }}" 
                                   class="p-2 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 hover:scale-110 transition" 
                                   title="View Enrolled Students">
                                    <i class="fas fa-users"></i>
                                </a>

                                <a href="{{ route('teacher.sessions.edit', $session->id) }}" 
                                   class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:scale-110 transition" 
                                   title="Edit Session">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('teacher.sessions.destroy', $session->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this session?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:scale-110 transition" title="Delete Session">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center text-gray-400 text-2xl mb-4">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">No Sessions Found</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">You haven't created any classes yet.</p>
                                
                                <a href="{{ route('teacher.sessions.create') }}" class="text-primary font-bold hover:underline">Create your first session</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800">
            {{ $sessions->links() }}
        </div>
    </div>
</div>
@endsection
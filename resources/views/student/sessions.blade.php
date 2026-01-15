@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Explore Sessions 📚</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Find the perfect class or create your own study group.</p>
        </div>
        
        <a href="{{ route('teacher.sessions.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-500/30 transition transform hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fas fa-plus-circle"></i> Create Your Own
        </a>
    </div>

    <div class="flex flex-col md:flex-row gap-8">
        
        <!-- Sidebar: ✅ DYNAMIC FILTERING -->
        <div class="w-full md:w-64 flex-shrink-0">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 sticky top-24">
                <h3 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-filter text-indigo-600"></i> Filter by Subject
                </h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('student.sessions') }}" class="block px-3 py-2 rounded-lg {{ !request('category') ? 'bg-indigo-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700' }}">
                            All Subjects
                        </a>
                    </li>
                    @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('student.sessions', ['category' => $cat]) }}" class="block px-3 py-2 rounded-lg {{ request('category') == $cat ? 'bg-indigo-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700' }}">
                            {{ $cat }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="flex-1">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($sessions as $session)
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden hover:shadow-lg transition group flex flex-col h-full">
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-4">
                            <span class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">{{ $session->fee_type }}</span>
                            <span class="text-xs text-gray-400 font-medium border border-gray-200 dark:border-slate-600 px-2 py-1 rounded">{{ $session->category ?? 'General' }}</span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 transition mb-2 line-clamp-2">{{ $session->title }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 flex items-center gap-2">
                            <i class="fas fa-chalkboard-teacher text-gray-400"></i> {{ $session->teacher->name }}
                        </p>
                        
                        <div class="mt-auto pt-4 border-t border-gray-100 dark:border-slate-700 flex justify-between items-center">
                            <div>
                                @if($session->base_fee == 0)
                                    <span class="font-bold text-green-600 text-lg">FREE</span>
                                @else
                                    <span class="font-bold text-indigo-600 dark:text-indigo-400 text-lg">LKR {{ number_format($session->base_fee) }}</span>
                                @endif
                            </div>
                            
                            <a href="{{ route('student.sessions.show', $session->id) }}" class="bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 hover:bg-indigo-600 hover:text-white w-10 h-10 rounded-full flex items-center justify-center transition">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 text-2xl">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">No Sessions Found</h3>
                    <p class="text-gray-500 dark:text-gray-400">Try adjusting your search or create a new session yourself!</p>
                </div>
                @endforelse
            </div>
            
            <div class="mt-8">
                {{ $sessions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
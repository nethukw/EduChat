@extends('layouts.app')

@section('content')
<!-- Teacher Quiz Management Dashboard -->
<div class="max-w-7xl mx-auto px-4 py-8 animate-fade-in bg-slate-50/50 min-h-screen rounded-[3rem]">
    
    <!-- 🔝 Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-8 px-4">
        <div>
            <h1 class="text-5xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">
                Quiz Center <span class="text-indigo-600">.</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium text-lg italic">
                Empower your teaching with interactive assessments.
            </p>
        </div>
        
        <!-- Teacher XP Badge -->
        <div class="group flex items-center gap-5 bg-white dark:bg-slate-800 px-8 py-5 rounded-[2.5rem] shadow-2xl shadow-indigo-500/10 border border-white dark:border-slate-700 transition-all hover:scale-105">
            <div class="w-14 h-14 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center text-white shadow-xl rotate-3 group-hover:rotate-0 transition-transform">
                <i class="fas fa-award text-2xl"></i>
            </div>
            <div>
                <p class="text-[11px] uppercase font-black text-slate-400 tracking-[0.2em] leading-none mb-2">My Contribution</p>
                <p class="text-3xl font-black text-slate-900 dark:text-indigo-300 leading-none">
                    {{ Auth::user()->points ?? 0 }} <span class="text-sm font-bold text-indigo-500 uppercase tracking-widest ml-1">XP</span>
                </p>
            </div>
        </div>
    </div>

    <!-- 🔔 Notification Alerts -->
    @if(session('success'))
    <div class="mx-4 mb-8 bg-emerald-50 border-l-4 border-emerald-400 p-5 rounded-r-2xl shadow-sm animate-fade-in flex items-center gap-4">
        <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
            <i class="fas fa-check"></i>
        </div>
        <p class="text-emerald-800 font-bold text-sm uppercase tracking-wider">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 px-4">
        
        <!-- ➕ Sidebar: Create New Quiz -->
        <div class="lg:col-span-4">
            <div class="bg-white dark:bg-slate-800 p-10 rounded-[3rem] shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 sticky top-24 transition-all group overflow-hidden">
                <div class="absolute -top-10 -left-10 w-32 h-32 bg-indigo-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-plus text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Draft Quiz</h3>
                    </div>
                    
                    <form action="{{ route('teacher.quizzes.store') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase mb-3 ml-2 tracking-widest italic">Quiz Title</label>
                            <input type="text" name="title" placeholder="e.g. Logic & Algorithms Test" 
                                   class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white rounded-2xl p-5 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold placeholder:text-slate-300" required>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase mb-3 ml-2 tracking-widest italic">Related Session</label>
                            <div class="relative">
                                <select name="session_id" class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white rounded-2xl p-5 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold cursor-pointer appearance-none" required>
                                    <option value="" disabled selected>Select a class...</option>
                                    @foreach($mySessions as $session)
                                        <option value="{{ $session->id }}">{{ $session->title }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-indigo-50/50 dark:bg-indigo-900/10 p-6 rounded-3xl border border-indigo-100 dark:border-indigo-800/30">
                            <div class="flex gap-4">
                                <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg flex items-center justify-center shrink-0">
                                    <i class="fas fa-bolt text-indigo-600 text-xs"></i>
                                </div>
                                <p class="text-[11px] text-indigo-800 dark:text-indigo-400 font-bold uppercase tracking-tight leading-relaxed mt-1">
                                    Bonus: You earn <span class="text-indigo-600 dark:text-white font-black underline">20 XP</span> instantly for every quiz created!
                                </p>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-slate-900 dark:bg-indigo-600 hover:bg-indigo-700 text-white font-black py-5 rounded-2xl shadow-xl transition transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-4">
                            <span class="tracking-widest uppercase text-xs">Publish Assessment</span>
                            <i class="fas fa-paper-plane text-[10px]"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 📋 Active Quizzes List -->
        <div class="lg:col-span-8">
            <div class="flex items-center justify-between mb-10 px-4">
                <div class="flex items-center gap-4">
                    <div class="w-2 h-8 bg-indigo-600 rounded-full"></div>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Your Quizzes</h3>
                </div>
                <div class="px-5 py-2 bg-indigo-100 dark:bg-slate-800 rounded-full border border-indigo-200 dark:border-slate-700">
                    <span class="text-[11px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">{{ $quizzes->count() }} Total Items</span>
                </div>
            </div>
            
            <div class="grid gap-8">
                @forelse($quizzes as $quiz)
                <div class="bg-white dark:bg-slate-800 p-8 rounded-[3rem] shadow-xl shadow-slate-200/40 dark:shadow-none border border-slate-50 dark:border-slate-700 hover:shadow-indigo-500/10 transition-all duration-500 group relative overflow-hidden">
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-10">
                        
                        <div class="flex items-center gap-8">
                            <div class="w-24 h-24 rounded-[2rem] bg-slate-50 dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-5xl transition-all duration-500 group-hover:bg-indigo-600 group-hover:text-white group-hover:scale-110 group-hover:rotate-6 shadow-inner border border-slate-100 dark:border-slate-700">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-900 dark:text-white text-3xl group-hover:text-indigo-600 transition tracking-tighter leading-none mb-3">{{ $quiz->title }}</h4>
                                <div class="flex flex-wrap items-center gap-5">
                                    <div class="flex items-center gap-2 px-4 py-1.5 bg-indigo-50 dark:bg-slate-700 rounded-full border border-indigo-100 dark:border-slate-600">
                                        <i class="fas fa-link text-indigo-400 text-[10px]"></i>
                                        {{-- ✅ FIXED: session_name to session_title to match Controller --}}
                                        <span class="text-[11px] font-black text-indigo-700 dark:text-gray-300 uppercase">{{ $quiz->session_title }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-slate-400">
                                        <i class="far fa-calendar-alt text-xs"></i>
                                        <span class="text-[10px] font-black uppercase tracking-widest">
                                            {{-- ✅ FIXED: Added check for created_at to prevent crash --}}
                                            @if(isset($quiz->created_at))
                                                Created {{ \Carbon\Carbon::parse($quiz->created_at)->diffForHumans() }}
                                            @else
                                                Recently Added
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <!-- ✅ Manage Questions Link -->
                            <a href="{{ route('teacher.quizzes.manage', $quiz->id) }}" 
                               class="flex-1 md:flex-none text-center text-[12px] font-black text-indigo-600 bg-white dark:bg-slate-900 border-2 border-indigo-600 dark:border-indigo-500 hover:bg-indigo-600 hover:text-white px-10 py-4 rounded-2xl transition-all shadow-lg shadow-indigo-100 dark:shadow-none uppercase tracking-widest">
                                Manage Questions
                            </a>

                            <!-- ✅ Results Link -->
                            <a href="{{ route('teacher.quizzes.results', $quiz->id) }}" 
                               class="p-4 bg-emerald-600 text-white rounded-2xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100 dark:shadow-none group/btn" 
                               title="View Results">
                                <i class="fas fa-chart-pie text-xl group-hover/btn:scale-125 transition-transform"></i>
                            </a>

                            <!-- Delete Form -->
                            <form action="{{ route('teacher.quizzes.destroy', $quiz->id) }}" method="POST" onsubmit="return confirm('Permanently delete this quiz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-4 text-slate-300 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-2xl transition-all" title="Remove">
                                    <i class="fas fa-trash-alt text-xl"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <!-- 🏜️ Empty State -->
                <div class="text-center py-40 bg-white dark:bg-slate-800 rounded-[4rem] border-4 border-dashed border-slate-100 dark:border-slate-700">
                    <div class="w-40 h-40 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-10 text-slate-200 dark:text-slate-700 text-8xl animate-pulse">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">Inventory Empty</h3>
                    <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto font-medium text-xl leading-relaxed italic">
                        Draft your first educational module on the left panel to begin.
                    </p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>
@endsection
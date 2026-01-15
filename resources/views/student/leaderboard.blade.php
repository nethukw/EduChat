@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    
    <!-- 🔝 Header Section -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tight">🏆 Hall of Fame</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2 font-medium">Recognizing the top academic performers in our community.</p>
    </div>

    <!-- 🔍 Filter Bar Section -->
    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 mb-10 transition-all hover:shadow-md">
        <form action="{{ route('student.leaderboard') }}" method="GET" class="flex flex-col md:flex-row items-end gap-6">
            
            <!-- Subject Filter -->
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Filter by Subject</label>
                <select name="subject" class="w-full border-gray-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition shadow-sm font-bold">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $cat)
                        <option value="{{ $cat }}" {{ $subject == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Time Period Filter -->
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Timeframe</label>
                <div class="flex bg-gray-100 dark:bg-slate-900 p-1 rounded-2xl border border-gray-200 dark:border-slate-700">
                    <button type="submit" name="period" value="weekly" 
                        class="flex-1 py-3 px-4 rounded-xl text-xs font-black uppercase transition {{ $period == 'weekly' ? 'bg-white dark:bg-slate-800 shadow-sm text-indigo-600' : 'text-gray-500 hover:text-indigo-500' }}">
                        Weekly
                    </button>
                    <button type="submit" name="period" value="monthly" 
                        class="flex-1 py-3 px-4 rounded-xl text-xs font-black uppercase transition {{ $period == 'monthly' ? 'bg-white dark:bg-slate-800 shadow-sm text-indigo-600' : 'text-gray-500 hover:text-indigo-500' }}">
                        Monthly
                    </button>
                    <button type="submit" name="period" value="all" 
                        class="flex-1 py-3 px-4 rounded-xl text-xs font-black uppercase transition {{ $period == 'all' ? 'bg-white dark:bg-slate-800 shadow-sm text-indigo-600' : 'text-gray-500 hover:text-indigo-500' }}">
                        All Time
                    </button>
                </div>
            </div>

            <!-- Apply Filter Button -->
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-10 py-4 rounded-2xl shadow-lg shadow-indigo-100 dark:shadow-none transition transform hover:-translate-y-1">
                Filter
            </button>
        </form>
    </div>

    <!-- 🥇 Podium Section (Top 3) -->
    @if($rankings->count() > 0)
    <div class="bg-gradient-to-b from-indigo-600 to-purple-700 rounded-t-[3rem] p-10 text-white shadow-xl relative overflow-hidden">
        <div class="flex justify-center items-end gap-2 md:gap-8 relative z-10">
            
            <!-- 🥈 2nd Place -->
            @if(isset($rankings[1]))
            <div class="flex flex-col items-center animate-fade-in-up">
                <div class="relative">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-full border-4 border-slate-300 overflow-hidden shadow-lg bg-white">
                        <img src="{{ $rankings[1]->avatar ?? 'https://ui-avatars.com/api/?name='.$rankings[1]->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-slate-300 text-slate-800 font-black px-3 py-0.5 rounded-full text-[10px] uppercase">2nd</div>
                </div>
                <p class="mt-4 font-bold text-sm text-center line-clamp-1 w-24 md:w-32">{{ $rankings[1]->name }}</p>
                <p class="text-[10px] font-black text-slate-200 uppercase tracking-widest">{{ number_format($rankings[1]->total_points) }} pts</p>
                <div class="h-20 w-20 md:w-28 bg-white/10 rounded-t-2xl mt-4 backdrop-blur-md"></div>
            </div>
            @endif

            <!-- 🥇 1st Place -->
            @if(isset($rankings[0]))
            <div class="flex flex-col items-center z-20 animate-fade-in-up" style="animation-delay: 100ms;">
                <div class="text-4xl mb-2 animate-bounce">👑</div>
                <div class="relative">
                    <div class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-yellow-400 overflow-hidden shadow-2xl bg-white scale-110">
                        <img src="{{ $rankings[0]->avatar ?? 'https://ui-avatars.com/api/?name='.$rankings[0]->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-yellow-400 text-yellow-900 font-black px-5 py-1 rounded-full text-xs uppercase shadow-lg">1st Place</div>
                </div>
                <p class="mt-6 font-black text-lg md:text-xl text-center line-clamp-1 w-32 md:w-48">{{ $rankings[0]->name }}</p>
                <p class="text-xs font-black text-yellow-300 uppercase tracking-widest">{{ number_format($rankings[0]->total_points) }} points</p>
                <div class="h-32 w-28 md:w-40 bg-white/20 rounded-t-3xl mt-4 backdrop-blur-md border-x border-t border-white/20"></div>
            </div>
            @endif

            <!-- 🥉 3rd Place -->
            @if(isset($rankings[2]))
            <div class="flex flex-col items-center animate-fade-in-up" style="animation-delay: 200ms;">
                <div class="relative">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-full border-4 border-orange-400 overflow-hidden shadow-lg bg-white">
                        <img src="{{ $rankings[2]->avatar ?? 'https://ui-avatars.com/api/?name='.$rankings[2]->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-orange-400 text-white font-black px-3 py-0.5 rounded-full text-[10px] uppercase">3rd</div>
                </div>
                <p class="mt-4 font-bold text-sm text-center line-clamp-1 w-24 md:w-32">{{ $rankings[2]->name }}</p>
                <p class="text-[10px] font-black text-orange-200 uppercase tracking-widest">{{ number_format($rankings[2]->total_points) }} pts</p>
                <div class="h-16 w-20 md:w-28 bg-white/10 rounded-t-2xl mt-4 backdrop-blur-md"></div>
            </div>
            @endif
        </div>

        <!-- Decorative background elements -->
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
            <i class="fas fa-trophy absolute -left-10 top-10 text-[15rem] rotate-12"></i>
            <i class="fas fa-medal absolute -right-10 bottom-10 text-[12rem] -rotate-12"></i>
        </div>
    </div>
    @endif

    <!-- 📊 Ranking Table Section -->
    <div class="bg-white dark:bg-slate-800 rounded-b-[2rem] shadow-xl border-x border-b border-gray-100 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 dark:bg-slate-900/50 text-[10px] uppercase font-black text-gray-400 tracking-widest border-b dark:border-slate-700">
                <tr>
                    <th class="px-8 py-5 text-center w-24">Rank</th>
                    <th class="px-6 py-5">Learner Details</th>
                    <th class="px-6 py-5">Top Subject</th>
                    <th class="px-8 py-5 text-right">Points Earned</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @forelse($rankings as $index => $rank)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition group {{ $index < 3 ? 'bg-indigo-50/30 dark:bg-indigo-900/10' : '' }}">
                    <td class="px-8 py-6">
                        <div class="flex items-center justify-center">
                            @if($index == 0) <span class="text-2xl">🥇</span>
                            @elseif($index == 1) <span class="text-2xl">🥈</span>
                            @elseif($index == 2) <span class="text-2xl">🥉</span>
                            @else <span class="font-black text-gray-300 group-hover:text-indigo-400 transition">#{{ $index + 1 }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-100 dark:bg-slate-700 flex-shrink-0 overflow-hidden border-2 border-white dark:border-slate-600 shadow-sm">
                                <img src="{{ $rank->avatar ?? 'https://ui-avatars.com/api/?name='.$rank->name }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="font-black text-gray-800 dark:text-white group-hover:text-indigo-600 transition">{{ $rank->name }}</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Verified Student</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-6">
                        <span class="px-3 py-1 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-[10px] font-black uppercase text-gray-500 dark:text-gray-400 rounded-full shadow-sm">
                            {{ $rank->subject }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <span class="text-xl font-black text-indigo-600 dark:text-indigo-400 font-mono">{{ number_format($rank->total_points) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-24 text-center">
                        <div class="text-5xl opacity-20 mb-4">🏜️</div>
                        <p class="text-gray-400 italic font-medium">No performance data found for the selected criteria.</p>
                        <p class="text-xs text-gray-300 mt-1 uppercase font-bold">Be the first to score by taking a quiz!</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Table Footer Info -->
        <div class="bg-gray-50 dark:bg-slate-900/30 p-6 border-t dark:border-slate-700 text-center">
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest flex items-center justify-center gap-2">
                <i class="fas fa-info-circle"></i> Leaderboard updates automatically based on quiz scores
            </p>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fade-in-up 0.5s ease-out forwards;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
</style>
@endsection
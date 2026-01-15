@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8 animate-fade-in">
    
    <!-- 🔝 Header & Status Overview -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6 px-2">
        <div>
            <h1 class="text-4xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">
                Session Approvals <span class="text-indigo-600">.</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium italic">Review, approve, or remove learning sessions from the platform.</p>
        </div>
        
        <div class="flex items-center gap-4">
            {{-- Display Pending Count from Controller --}}
            <div class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-6 py-4 rounded-[1.5rem] border border-amber-200 dark:border-amber-800 flex items-center gap-3 shadow-sm shadow-amber-100 dark:shadow-none transition-all hover:scale-105">
                <span class="w-2.5 h-2.5 bg-amber-500 rounded-full animate-pulse"></span>
                <span class="font-black uppercase text-xs tracking-widest">{{ $pendingCount }} Pending Review</span>
            </div>
        </div>
    </div>

    <!-- 🔔 Flash Messages (Success/Error) -->
    @if(session('success'))
        <div class="mb-8 bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-400 p-5 rounded-r-2xl text-emerald-800 dark:text-emerald-300 font-bold text-sm uppercase tracking-wide flex items-center gap-3 shadow-md animate-bounce-subtle">
            <i class="fas fa-check-circle text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 bg-rose-50 dark:bg-rose-900/20 border-l-4 border-rose-400 p-5 rounded-r-2xl text-rose-800 dark:text-rose-300 font-bold text-sm uppercase tracking-wide flex items-center gap-3 shadow-md">
            <i class="fas fa-exclamation-triangle text-lg"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- 📋 Sessions Data Table -->
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 dark:shadow-none overflow-hidden border border-gray-100 dark:border-slate-700">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b dark:border-slate-700">
                        <th class="px-8 py-7 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Instructor & Title</th>
                        <th class="px-8 py-7 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Category & Pricing</th>
                        <th class="px-8 py-7 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-center">Status</th>
                        <th class="px-8 py-7 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Moderation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                    @forelse($sessions as $session)
                    <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition duration-300 group">
                        <td class="px-8 py-7">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black shadow-inner border border-indigo-50 dark:border-indigo-800">
                                    {{ substr($session->teacher->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-black text-slate-800 dark:text-white leading-tight mb-1 text-lg group-hover:text-indigo-600 transition">{{ $session->title }}</div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Instructor:</span>
                                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-tighter">{{ $session->teacher->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-7">
                            <span class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-[9px] font-black uppercase rounded-lg tracking-widest border border-slate-200 dark:border-slate-600">
                                {{ $session->category }}
                            </span>
                            <div class="mt-2 text-xs font-bold italic">
                                @if($session->fee_type == 'free')
                                    <span class="text-emerald-500 uppercase flex items-center gap-1">
                                        <i class="fas fa-gift text-[10px]"></i> Free Session
                                    </span>
                                @else
                                    <span class="text-indigo-600 dark:text-indigo-400 flex items-center gap-1">
                                        <i class="fas fa-coins text-[10px]"></i> LKR {{ number_format($session->base_fee) }} ({{ ucfirst($session->fee_type) }})
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-7 text-center">
                            <span class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border
                                {{ $session->status == 'pending' ? 'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-900/20' : '' }}
                                {{ $session->status == 'approved' ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/20' : '' }}
                                {{ $session->status == 'cancelled' ? 'bg-rose-50 text-rose-600 border-rose-200 dark:bg-rose-900/20' : '' }}">
                                {{ $session->status }}
                            </span>
                        </td>
                        <td class="px-8 py-7 text-right">
                            <div class="flex justify-end items-center gap-3">
                                @if($session->status == 'pending')
                                    {{-- Approve Action --}}
                                    <form action="{{ route('admin.sessions.approve', $session->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white w-10 h-10 rounded-xl shadow-lg shadow-emerald-200 dark:shadow-none transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center" title="Approve & Notify Students">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Delete/Reject Action --}}
                                <form action="{{ route('admin.sessions.delete', $session->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this session? This will remove all associated data.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-white dark:bg-slate-700 border border-gray-100 dark:border-slate-600 text-rose-500 w-10 h-10 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-all active:scale-95 flex items-center justify-center shadow-sm" title="Remove Session">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-32 text-center">
                            <div class="w-24 h-24 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200 dark:text-slate-700 text-5xl">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Queue Clear!</h3>
                            <p class="text-slate-400 font-medium italic mt-2">There are currently no sessions waiting for approval.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination Links --}}
        @if($sessions->hasPages())
        <div class="px-10 py-6 bg-slate-50/50 dark:bg-slate-900/30 border-t dark:border-slate-700">
            {{ $sessions->links() }}
        </div>
        @endif
    </div>

    <!-- 💡 Admin Tip Section -->
    <div class="mt-12 p-8 bg-gradient-to-br from-slate-900 to-indigo-950 rounded-[2.5rem] text-white relative overflow-hidden shadow-2xl">
        <i class="fas fa-shield-alt absolute -right-6 -bottom-6 text-9xl opacity-10 rotate-12"></i>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center gap-8">
            <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-3xl">
                <i class="fas fa-bell text-indigo-300"></i>
            </div>
            <div>
                <h4 class="text-xl font-black uppercase tracking-tight mb-1">Smart Notification System</h4>
                <p class="text-slate-300 font-medium leading-relaxed max-w-2xl text-sm italic">
                    When you click the <span class="text-emerald-400 font-black">Checkmark</span> button, the system will automatically approve the class AND send a real-time notification to all students currently registered as Learners.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    
    @keyframes bounce-subtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
    }
    .animate-bounce-subtle { animation: bounce-subtle 2s infinite ease-in-out; }
</style>
@endsection
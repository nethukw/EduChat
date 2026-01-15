@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Community Forum 💬</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Join the discussion, ask questions, and share knowledge.</p>
        </div>
        <button onclick="document.getElementById('postModal').classList.remove('hidden')" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 dark:shadow-none transition transform hover:-translate-y-1">
            <i class="fas fa-plus mr-2"></i> Ask Question
        </button>
    </div>

    <div class="mb-10 bg-white dark:bg-slate-800 p-6 rounded-[2rem] shadow-sm border border-gray-100 dark:border-slate-700">
        <form action="{{ route('student.discussions') }}" method="GET" class="space-y-4">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search discussions by keywords..." 
                        class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                </div>
                <div class="md:w-64">
                    <select name="subject" onchange="this.form.submit()" class="w-full py-3 bg-gray-50 dark:bg-slate-900 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 dark:text-white font-bold">
                        <option value="All">All Subjects</option>
                        {{-- $subjects variable එක controller එකෙන් එනවා --}}
                        @if(isset($subjects))
                            @foreach($subjects as $sub)
                                <option value="{{ $sub }}" {{ request('subject') == $sub ? 'selected' : '' }}>{{ $sub }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div class="space-y-6">
        @forelse($questions as $q)
        <div x-data="{ showReplies: false }" class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-sm border border-gray-50 dark:border-slate-700 transition hover:shadow-md group">
            
            <div class="flex justify-between items-start">
                <div class="flex items-center space-x-3">
                    <img src="{{ $q->user?->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($q->user?->name ?? 'U') }}" class="w-12 h-12 rounded-2xl border-2 border-indigo-50">
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white text-base">{{ $q->user?->name ?? 'Unknown User' }}</h4>
                        <p class="text-xs text-gray-400 font-medium">{{ $q->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <span class="bg-indigo-50 dark:bg-slate-700 text-indigo-600 dark:text-indigo-300 text-[10px] font-black uppercase px-3 py-1 rounded-full tracking-widest">{{ $q->subject_tag }}</span>
            </div>
            
            <h3 class="mt-4 text-xl font-black text-gray-900 dark:text-white leading-tight group-hover:text-indigo-600 transition">{{ $q->title }}</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ $q->body }}</p>

            <div class="mt-6 flex items-center space-x-6 text-gray-500 text-sm border-t border-gray-50 dark:border-slate-700 pt-6">
                {{-- Upvote Form --}}
                <form action="{{ route('student.discussions.like', $q->id) }}" method="POST">
                    @csrf
                    @php
                        $isLiked = \DB::table('post_likes')->where('post_id', $q->id)->where('user_id', Auth::id())->exists();
                        $likeCount = \DB::table('post_likes')->where('post_id', $q->id)->count();
                    @endphp
                    <button type="submit" class="flex items-center space-x-2 {{ $isLiked ? 'text-indigo-600' : 'hover:text-indigo-600' }} transition">
                        <i class="fas fa-arrow-up {{ $isLiked ? 'scale-110' : '' }}"></i> 
                        <span class="font-bold">{{ $likeCount }} Upvotes</span>
                    </button>
                </form>

                <button @click="showReplies = !showReplies" class="flex items-center space-x-2 hover:text-indigo-600 transition">
                    <i class="fas fa-comment-alt"></i> 
                    <span class="font-bold">{{ $q->answers_count }} Replies</span>
                </button>
            </div>

            {{-- ✅ Swen's Requirement: Replies Section with Textarea --}}
            <div x-show="showReplies" x-transition class="mt-6 pt-6 border-t border-gray-50 dark:border-slate-700 space-y-4">
                @foreach($q->answers as $answer)
                <div class="bg-gray-50 dark:bg-slate-900/50 rounded-2xl p-5 border border-gray-100 dark:border-slate-700">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-2">
                            <img src="{{ $answer->user?->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($answer->user?->name ?? 'U') }}" class="w-6 h-6 rounded-full">
                            <span class="font-bold text-xs text-gray-800 dark:text-gray-200">{{ $answer->user?->name ?? 'Unknown' }}</span>
                            @if($answer->verification_status == 'verified')
                                <span class="bg-emerald-100 text-emerald-700 text-[8px] px-2 py-0.5 rounded-full font-black uppercase tracking-tighter">
                                    <i class="fas fa-check-circle mr-0.5"></i> Verified
                                </span>
                            @endif
                        </div>
                        <span class="text-[10px] text-gray-400">{{ $answer->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 italic leading-relaxed">"{{ $answer->body }}"</p>
                </div>
                @endforeach

                {{-- Reply Box --}}
                <form action="{{ route('student.discussions.reply', $q->id) }}" method="POST" class="mt-6">
                    @csrf
                    <textarea name="body" rows="3" placeholder="Write your detailed answer here..." 
                        class="w-full border-gray-200 dark:border-slate-600 dark:bg-slate-800 dark:text-white rounded-3xl px-6 py-4 text-sm focus:ring-2 focus:ring-indigo-500 shadow-inner" 
                        required></textarea>
                    <div class="flex justify-end mt-3">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2.5 rounded-full font-bold shadow-lg transition transform active:scale-95 flex items-center gap-2">
                            <span>Post Reply</span>
                            <i class="fas fa-paper-plane text-xs"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-white dark:bg-slate-800 rounded-[3rem] border-2 border-dashed border-gray-100 dark:border-slate-700">
            <div class="w-20 h-20 bg-gray-50 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-200 text-4xl">
                <i class="fas fa-search"></i>
            </div>
            <p class="text-gray-400 font-bold text-lg">No discussions found matching your criteria.</p>
            <p class="text-gray-300 text-sm">Try a different subject or keyword!</p>
        </div>
        @endforelse
    </div>
    
    <div class="mt-12">
        {{ $questions->appends(request()->query())->links() }}
    </div>
</div>

{{-- Post Modal --}}
<div id="postModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-slate-800 w-full max-w-lg p-8 rounded-[2rem] shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Ask Community</h3>
            <button onclick="document.getElementById('postModal').classList.add('hidden')" class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-times text-xl"></i></button>
        </div>
        
        <form action="{{ route('student.discussions.store') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-xs font-black text-gray-400 uppercase mb-2 ml-1">Discussion Title</label>
                <input type="text" name="title" class="w-full border-gray-200 rounded-xl p-3 dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:ring-2 focus:ring-indigo-500" placeholder="e.g. How to use Eloquent Relationships?" required>
            </div>
            <div class="mb-5">
                <label class="block text-xs font-black text-gray-400 uppercase mb-2 ml-1">Details</label>
                <textarea name="body" rows="4" class="w-full border-gray-200 rounded-xl p-3 dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:ring-2 focus:ring-indigo-500" placeholder="Describe your question in detail..." required></textarea>
            </div>
            <div class="mb-6">
                <label class="block text-xs font-black text-gray-400 uppercase mb-2 ml-1">Category Tag</label>
                <select name="tag" class="w-full border-gray-200 rounded-xl p-3 dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    <option>General</option>
                    <option>Information Technology</option>
                    <option>Mathematics</option>
                    <option>Science</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white font-black py-4 rounded-xl hover:bg-indigo-700 shadow-xl transition transform active:scale-95 uppercase tracking-widest text-xs">
                Post Question
            </button>
        </form>
    </div>
</div>
@endsection
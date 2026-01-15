@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    
    <div class="lg:col-span-3 space-y-8">
        
        {{-- 🔥 PROFILE COMPLETION ALERT (NEW) --}}
        @if(isset($profilePercentage) && $profilePercentage < 100)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg shadow-sm flex items-center justify-between animate-fade-in-down">
            <div class="flex items-center">
                <div class="relative w-12 h-12 mr-4">
                    {{-- Circular Progress --}}
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                        <path class="text-yellow-200" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                        <path class="text-yellow-500" stroke-dasharray="{{ $profilePercentage }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center font-bold text-xs text-yellow-700">{{ $profilePercentage }}%</div>
                </div>
                <div>
                    <p class="font-bold text-yellow-800">Your profile is incomplete!</p>
                    <p class="text-sm text-yellow-700">Add your Bio, Phone, and Skills to get better recommendations.</p>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold py-2 px-4 rounded-lg shadow transition">
                Complete Now
            </a>
        </div>
        @endif
        {{-- 🔥 END ALERT --}}

        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white shadow-lg flex justify-between items-center relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                <p class="mt-2 opacity-90 text-indigo-100">Ready to learn something new today?</p>
                <div class="mt-6 flex space-x-3">
                    <a href="{{ route('student.discussions') }}" class="bg-white text-indigo-600 px-5 py-2.5 rounded-xl font-bold shadow hover:bg-gray-50 transition transform hover:-translate-y-0.5">Start Discussion</a>
                    <a href="{{ route('student.sessions') }}" class="bg-indigo-500/30 backdrop-blur-md text-white px-5 py-2.5 rounded-xl font-bold border border-white/20 hover:bg-indigo-500/50 transition">Find a Class</a>
                </div>
            </div>
            <div class="absolute right-0 bottom-0 text-9xl opacity-10 transform translate-y-4 translate-x-4">
                <i class="fas fa-rocket"></i>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('student.sessions') }}" class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm hover:shadow-md transition text-center border border-gray-100 dark:border-slate-700 group">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center mx-auto mb-3 text-xl group-hover:scale-110 transition"><i class="fas fa-video"></i></div>
                <h3 class="font-bold text-gray-800 dark:text-white">Sessions</h3>
            </a>
            <a href="{{ route('student.discussions') }}" class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm hover:shadow-md transition text-center border border-gray-100 dark:border-slate-700 group">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-xl flex items-center justify-center mx-auto mb-3 text-xl group-hover:scale-110 transition"><i class="fas fa-comments"></i></div>
                <h3 class="font-bold text-gray-800 dark:text-white">Forum</h3>
            </a>
            <a href="{{ route('student.quizzes') }}" class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm hover:shadow-md transition text-center border border-gray-100 dark:border-slate-700 group">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl flex items-center justify-center mx-auto mb-3 text-xl group-hover:scale-110 transition"><i class="fas fa-brain"></i></div>
                <h3 class="font-bold text-gray-800 dark:text-white">Quizzes</h3>
            </a>
            <a href="{{ route('student.requests') }}" class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm hover:shadow-md transition text-center border border-gray-100 dark:border-slate-700 group">
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 rounded-xl flex items-center justify-center mx-auto mb-3 text-xl group-hover:scale-110 transition"><i class="fas fa-lightbulb"></i></div>
                <h3 class="font-bold text-gray-800 dark:text-white">Requests</h3>
            </a>
        </div>

        {{-- Auto Scroll Recommendations --}}
        <div x-data="{ 
                init() {
                    this.startAutoScroll();
                },
                interval: null,
                startAutoScroll() {
                    this.interval = setInterval(() => {
                        const el = this.$refs.carousel;
                        if (el) {
                            if (el.scrollLeft + el.clientWidth >= el.scrollWidth) {
                                el.scrollTo({ left: 0, behavior: 'smooth' });
                            } else {
                                el.scrollBy({ left: 300, behavior: 'smooth' });
                            }
                        }
                    }, 3000); 
                },
                stopAutoScroll() {
                    clearInterval(this.interval);
                }
             }" 
             class="relative group">
            
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-star text-yellow-500"></i> Recommended for You
                </h2>
                
                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition duration-300">
                    <button @click="$refs.carousel.scrollBy({ left: -300, behavior: 'smooth' })" class="w-8 h-8 rounded-full bg-white dark:bg-slate-800 shadow hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center justify-center text-gray-600 dark:text-gray-300"><i class="fas fa-chevron-left"></i></button>
                    <button @click="$refs.carousel.scrollBy({ left: 300, behavior: 'smooth' })" class="w-8 h-8 rounded-full bg-white dark:bg-slate-800 shadow hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center justify-center text-gray-600 dark:text-gray-300"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>

            <div x-ref="carousel" 
                 @mouseenter="stopAutoScroll()" 
                 @mouseleave="startAutoScroll()"
                 class="flex gap-5 overflow-x-auto pb-4 no-scrollbar snap-x snap-mandatory scroll-smooth">
                
                @forelse($recommendedSessions as $session)
                <div class="min-w-[280px] bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden hover:shadow-md transition snap-center flex flex-col">
                    <div class="h-24 bg-gradient-to-r from-indigo-500 to-purple-500 p-4 text-white relative">
                        <span class="absolute top-3 right-3 bg-black/20 text-xs font-bold px-2 py-1 rounded">{{ $session->fee_type }}</span>
                        <p class="text-xs opacity-75 uppercase tracking-wider">{{ $session->category ?? 'General' }}</p>
                        <h3 class="font-bold text-lg leading-tight mt-1 line-clamp-2">{{ $session->title }}</h3>
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ $session->teacher->name }}" class="w-6 h-6 rounded-full">
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">{{ $session->teacher->name }}</p>
                        </div>
                        <p class="text-xs text-gray-400 mb-4 flex items-center gap-2">
                            <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($session->start_time)->format('M d, h:i A') }}
                        </p>
                        <div class="mt-auto flex justify-between items-center border-t border-gray-100 dark:border-slate-700 pt-3">
                            <span class="text-indigo-600 dark:text-indigo-400 font-bold">
                                @if($session->base_fee == 0) Free @else LKR {{ number_format($session->base_fee) }} @endif
                            </span>
                            <a href="{{ route('student.sessions.show', $session->id) }}" class="text-xs bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 px-3 py-1.5 rounded-lg font-bold hover:bg-indigo-100 transition">View</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="w-full text-center py-8 text-gray-500 dark:text-gray-400">No recommendations found.</div>
                @endforelse
            </div>
        </div>

        <div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Trending Now 🔥</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($trendingSessions as $session)
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-5 hover:shadow-md transition flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <span class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-bold px-2 py-1 rounded">{{ $session->fee_type }}</span>
                        <span class="text-xs text-gray-400">{{ $session->category ?? 'General' }}</span>
                    </div>
                    
                    <h4 class="font-bold text-lg text-gray-900 dark:text-white mb-1">{{ $session->title }}</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2 mb-4">
                        <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($session->start_time)->format('M d, H:i A') }}
                    </p>
                    
                    <div class="mt-auto flex justify-between items-center pt-4 border-t border-gray-100 dark:border-slate-700">
                        <span class="text-indigo-600 dark:text-indigo-400 font-bold">
                            @if($session->base_fee == 0) Free @else LKR {{ number_format($session->base_fee) }} @endif
                        </span>
                        <a href="{{ route('student.sessions.show', $session->id) }}" class="text-sm font-bold text-primary hover:underline">View Details →</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- RIGHT SIDEBAR (Profile & Level) --}}
    <div class="space-y-6">
        
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6 text-center border border-gray-100 dark:border-slate-700">
            <div class="relative w-24 h-24 mx-auto mb-3">
                <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" class="w-full h-full rounded-full border-4 border-indigo-100 dark:border-slate-600 object-cover">
            </div>
            
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->academic_level }}</p>
            
            <div class="mt-6 bg-gray-50 dark:bg-slate-900 rounded-xl p-4 border border-gray-100 dark:border-slate-700">
                <div class="flex justify-between text-sm mb-2">
                    <span class="font-bold text-gray-700 dark:text-gray-300">Level Progress</span>
                    <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ Auth::user()->points }} / 1000</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-2.5 mb-2">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2.5 rounded-full transition-all duration-1000" style="width: {{ min((Auth::user()->points / 1000) * 100, 100) }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-1">Earn {{ 1000 - Auth::user()->points }} more XP to become a Teacher!</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-chalkboard-teacher text-indigo-500"></i> Top Teachers
            </h3>
            <div class="space-y-4">
                @foreach($recommendedTeachers as $teacher)
                <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 dark:hover:bg-slate-700 rounded-lg transition cursor-pointer">
                    <img src="https://ui-avatars.com/api/?name={{ $teacher->name }}" class="w-10 h-10 rounded-full border border-gray-200 dark:border-slate-600">
                    <div>
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">{{ $teacher->name }}</h4>
                        <div class="flex items-center text-xs text-yellow-500">
                            <i class="fas fa-star"></i> <span class="ml-1 text-gray-500 dark:text-gray-400">4.8 Rating</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
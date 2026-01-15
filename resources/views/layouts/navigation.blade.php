<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- 👨‍🏫 TEACHER LINKS (Role 1) --}}
                    @if(Auth::user()->role == 1)
                        <x-nav-link :href="route('teacher.sessions.index')" :active="request()->routeIs('teacher.sessions.*')">
                            {{ __('My Sessions') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('teacher.quizzes.index')" :active="request()->routeIs('teacher.quizzes.*')">
                            {{ __('Manage Quizzes') }}
                        </x-nav-link>
                    @endif

                    {{-- 🎓 STUDENT LINKS (Role 0) --}}
                    @if(Auth::user()->role == 0)
                        <x-nav-link :href="route('student.sessions')" :active="request()->routeIs('student.sessions')">
                            {{ __('Explore Classes') }}
                        </x-nav-link>
                        <x-nav-link :href="route('student.quizzes')" :active="request()->routeIs('student.quizzes')">
                            {{ __('Take Quizzes') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings & Notifications -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                
                {{-- ⭐ XP & ROLE BADGE: Changes based on Role --}}
                <div class="flex flex-col items-end mr-2">
                    <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-full tracking-widest border
                        {{ Auth::user()->role == 1 ? 'bg-indigo-100 text-indigo-700 border-indigo-200' : 'bg-gray-100 text-gray-600 border-gray-200' }}">
                        {{ Auth::user()->role == 1 ? 'Instructor' : 'Learner' }}
                    </span>
                    <span class="text-[9px] font-bold text-indigo-500 mt-0.5 tracking-tighter">{{ Auth::user()->points }} XP Earned</span>
                </div>

                <!-- Notification Dropdown -->
                <div class="relative" x-data="{ notifOpen: false }">
                    <button @click="notifOpen = !notifOpen" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none relative transition duration-150 ease-in-out">
                        <i class="fas fa-bell text-xl"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] rounded-full px-1.5 py-0.5 font-bold animate-pulse">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <div x-show="notifOpen" @click.away="notifOpen = false" x-transition class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-xl z-50 overflow-hidden" style="display: none;">
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 text-sm font-bold text-gray-700 dark:text-gray-200 border-b dark:border-gray-600">Notifications</div>
                        <div class="max-h-64 overflow-y-auto">
                            @forelse(auth()->user()->unreadNotifications as $notification)
                                <div class="px-4 py-3 border-b dark:border-gray-700 hover:bg-gray-50 bg-blue-50 transition">
                                    <p class="text-xs text-gray-800">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                    <span class="text-[10px] text-gray-400 mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            @empty
                                <div class="p-6 text-center text-gray-400 text-sm italic">All caught up!</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- User Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center gap-3">
                                <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" class="w-9 h-9 rounded-full border border-gray-100 object-cover shadow-sm">
                                <div class="text-left">
                                    <div class="font-bold text-gray-800 dark:text-white leading-none mb-1">{{ Auth::user()->name }}</div>
                                    {{-- 🔥 DYNAMIC ROLE TEXT: Shows Student (0) or Teacher (1) --}}
                                    <div class="text-[10px] font-black uppercase tracking-tighter 
                                        {{ Auth::user()->role == 1 ? 'text-indigo-600' : 'text-gray-400' }}">
                                        {{ Auth::user()->role == 1 ? 'Teacher (1)' : 'Student (0)' }}
                                    </div>
                                </div>
                            </div>
                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile Settings') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
            @if(Auth::user()->role == 1)
                <x-responsive-nav-link :href="route('teacher.sessions.index')">My Sessions</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('teacher.quizzes.index')">Manage Quizzes</x-responsive-nav-link>
            @endif
            @if(Auth::user()->role == 0)
                <x-responsive-nav-link :href="route('student.sessions')">Explore Classes</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.quizzes')">Take Quizzes</x-responsive-nav-link>
            @endif
        </div>
    </div>
</nav>

{{-- 🎉 PROMOTION CELEBRATION MODAL --}}
{{-- This triggers when the 'promotion_success' flash message is sent from the StudentController --}}
@if(session('promotion_success'))
<div x-data="{ show: true }" x-show="show" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md p-4 overflow-y-auto">
    <div x-show="show" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" 
         class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-[3rem] p-12 text-center shadow-2xl relative border border-indigo-100 dark:border-indigo-900/50">
        
        <!-- Confetti Icon -->
        <div class="w-24 h-24 bg-indigo-100 dark:bg-indigo-900/50 rounded-full flex items-center justify-center mx-auto mb-8 text-indigo-600 text-5xl animate-bounce">
            <i class="fas fa-graduation-cap"></i>
        </div>

        <h2 class="text-4xl font-black text-slate-900 dark:text-white uppercase tracking-tighter mb-4">Instructor Unlocked!</h2>
        <p class="text-slate-500 dark:text-slate-400 font-medium text-lg leading-relaxed mb-10 italic">
            {{ session('promotion_success') }}
        </p>

        <button @click="show = false" class="group w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-indigo-200 dark:shadow-none transition transform active:scale-95 flex items-center justify-center gap-3">
            <span class="tracking-widest uppercase text-xs">Enter Instructor Hub</span>
            <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
        </button>

        <!-- Background Decorative Elements -->
        <div class="absolute -top-6 -left-6 w-20 h-20 bg-indigo-500/10 rounded-full blur-xl"></div>
        <div class="absolute -bottom-6 -right-6 w-20 h-20 bg-purple-500/10 rounded-full blur-xl"></div>
    </div>
</div>
@endif
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EduChat') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5',
                        secondary: '#10B981',
                        dark: '#0f172a',
                        darker: '#020617',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-scroll::-webkit-scrollbar { width: 6px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #475569; border-radius: 3px; }
    </style>
</head>

<body class="bg-gray-50 dark:bg-dark text-gray-800 dark:text-gray-200 font-sans antialiased transition-colors duration-300"
      x-data="{ 
          sidebarOpen: false, 
          darkMode: localStorage.getItem('theme') === 'dark',
          toggleTheme() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
              if (this.darkMode) document.documentElement.classList.add('dark');
              else document.documentElement.classList.remove('dark');
          }
      }"
      x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')); if(darkMode) document.documentElement.classList.add('dark');">

    <div class="flex h-screen overflow-hidden">
        
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 dark:bg-darker text-white transition-transform duration-300 ease-in-out md:static md:translate-x-0 shadow-2xl flex flex-col">
            
            <div class="flex items-center justify-center h-20 bg-slate-950 dark:bg-black border-b border-slate-800 shadow-md">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary rounded flex items-center justify-center text-white"><i class="fas fa-graduation-cap"></i></div>
                    <span class="text-xl font-bold tracking-wider text-white">EduChat</span>
                </a>
            </div>

            <nav class="flex-1 px-4 space-y-2 mt-6 overflow-y-auto sidebar-scroll">
                
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-2">Menu</div>
                
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition group {{ request()->routeIs('dashboard') ? 'bg-primary text-white shadow-lg shadow-indigo-500/30' : '' }}">
                    <i class="fas fa-th-large w-6 group-hover:scale-110 transition"></i> <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('student.learning') }}" class="flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition group {{ request()->routeIs('student.learning') ? 'bg-slate-800 text-white border-l-4 border-primary' : '' }}">
                    <i class="fas fa-graduation-cap w-6 group-hover:scale-110 transition"></i> <span class="font-medium">My Learning</span>
                </a>

                <a href="{{ route('student.sessions') }}" class="flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition group {{ request()->routeIs('student.sessions*') ? 'bg-slate-800 text-white border-l-4 border-primary' : '' }}">
                    <i class="fas fa-book-reader w-6 group-hover:scale-110 transition"></i> <span class="font-medium">Browse Classes</span>
                </a>

                <a href="{{ route('student.discussions') }}" class="flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition group {{ request()->routeIs('student.discussions*') ? 'bg-slate-800 text-white border-l-4 border-primary' : '' }}">
                    <i class="fas fa-comments w-6 group-hover:scale-110 transition"></i> <span class="font-medium">Discussion Forum</span>
                </a>

                <a href="{{ route('student.quizzes') }}" class="flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition group {{ request()->routeIs('student.quizzes*') ? 'bg-slate-800 text-white border-l-4 border-primary' : '' }}">
                    <i class="fas fa-brain w-6 group-hover:scale-110 transition"></i> <span class="font-medium">Quizzes</span>
                </a>

                <a href="{{ route('student.leaderboard') }}" class="flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition group {{ request()->routeIs('student.leaderboard') ? 'bg-slate-800 text-white border-l-4 border-primary' : '' }}">
                    <i class="fas fa-trophy w-6 group-hover:scale-110 transition text-yellow-500"></i> <span class="font-medium">Leaderboard</span>
                </a>

                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Tools</div>

                <a href="{{ route('teacher.sessions.create') }}" class="flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition group {{ request()->routeIs('sessions.create') ? 'bg-slate-800 text-white border-l-4 border-primary' : '' }}">
                    <i class="fas fa-plus-circle w-6 group-hover:scale-110 transition"></i> 
                    <span class="font-medium">Create Session</span>
                </a>

                <a href="{{ route('student.requests') }}" class="flex items-center px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition group {{ request()->routeIs('student.requests*') ? 'bg-slate-800 text-white border-l-4 border-primary' : '' }}">
                    <i class="fas fa-lightbulb w-6 group-hover:scale-110 transition"></i> <span class="font-medium">Request Topic</span>
                </a>

                @if(Auth::user()->role >= 1)
                    <div class="text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-2 mt-6">Teacher Panel</div>
                    <a href="{{ route('teacher.dashboard') }}" class="flex items-center px-4 py-3 text-white bg-indigo-900/50 hover:bg-indigo-900 rounded-xl transition border border-indigo-700">
                        <i class="fas fa-chalkboard-teacher w-6 text-indigo-400"></i> <span class="font-medium">Instructor Mode</span>
                    </a>
                @endif

                @if(Auth::user()->role == 2)
                    <div class="text-xs font-semibold text-purple-400 uppercase tracking-wider mb-2 mt-6">Advisor Panel</div>
                    <a href="{{ route('advisor.dashboard') }}" class="flex items-center px-4 py-3 text-purple-100 hover:bg-slate-800 rounded-xl transition">
                        <i class="fas fa-brain w-6 text-purple-400"></i> <span class="font-medium">Overview</span>
                    </a>
                    <a href="{{ route('advisor.reviews') }}" class="flex items-center px-4 py-3 text-purple-100 hover:bg-slate-800 rounded-xl transition">
                        <i class="fas fa-check-double w-6 text-purple-400"></i> <span class="font-medium">Verify Answers</span>
                    </a>
                @endif
                
                @if(Auth::user()->role == 3)
                    <div class="text-xs font-semibold text-red-400 uppercase tracking-wider mb-2 mt-6">Admin Panel</div>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-red-100 hover:bg-slate-800 rounded-xl transition">
                        <i class="fas fa-tachometer-alt w-6"></i> <span class="font-medium">Overview</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="flex items-center px-4 py-3 text-red-100 hover:bg-slate-800 rounded-xl transition">
                        <i class="fas fa-users-cog w-6"></i> <span class="font-medium">Users</span>
                    </a>
                    <a href="{{ route('admin.sessions') }}" class="flex items-center px-4 py-3 text-red-100 hover:bg-slate-800 rounded-xl transition">
                        <i class="fas fa-chalkboard-teacher w-6"></i> <span class="font-medium">Sessions</span>
                    </a>
                    <a href="{{ route('admin.discussions') }}" class="flex items-center px-4 py-3 text-red-100 hover:bg-slate-800 rounded-xl transition">
                        <i class="fas fa-comments w-6"></i> <span class="font-medium">Forum</span>
                    </a>
                @endif
            </nav>

            <div class="p-4 bg-slate-950 dark:bg-black border-t border-slate-800">
                <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl p-4 shadow-lg">
                    <div class="flex justify-between items-center text-white mb-2">
                        <span class="text-xs font-bold uppercase tracking-wide">Next Level</span>
                        <i class="fas fa-trophy text-yellow-300"></i>
                    </div>
                    <div class="text-white font-bold text-sm mb-1">Teacher Status</div>
                    <div class="w-full bg-black/20 rounded-full h-2 mb-2">
                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ min((Auth::user()->points / 1000) * 100, 100) }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-indigo-100">
                        <span>{{ Auth::user()->points }} XP</span>
                        <span>1000 XP</span>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-gray-50 dark:bg-dark transition-colors duration-300">
            
            <header class="bg-white dark:bg-slate-800 shadow-sm z-10 h-16 flex items-center justify-between px-6 border-b border-gray-200 dark:border-slate-700 transition-colors duration-300">
                
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 dark:text-gray-300 focus:outline-none md:hidden p-2 rounded-md hover:bg-gray-100 dark:hover:bg-slate-700">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <div class="hidden md:block w-96 relative">
                    <form action="{{ route('student.sessions') }}" method="GET">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-full py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-primary text-gray-700 dark:text-gray-200 transition" 
                               placeholder="Search sessions (e.g. Laravel)...">
                    </form>
                </div>

                <div class="flex items-center space-x-2 md:space-x-4">
                    
                    <button @click="toggleTheme()" class="p-2 rounded-full text-gray-500 dark:text-yellow-400 hover:bg-gray-100 dark:hover:bg-slate-700 transition">
                        <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    </button>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="relative p-2 text-gray-400 dark:text-gray-300 hover:text-primary transition">
                            <i class="fas fa-bell fa-lg"></i>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full border-2 border-white dark:border-slate-800">
                                    {{ Auth::user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>
                        
                        <div x-show="open" class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-100 dark:border-slate-700 py-1 z-50 overflow-hidden" style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 flex justify-between items-center">
                                <span class="font-bold text-gray-800 dark:text-white text-sm">Notifications</span>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <a href="{{ route('notifications.read') }}" class="text-xs text-primary hover:underline">Mark all read</a>
                                @endif
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                @forelse(Auth::user()->unreadNotifications as $notification)
                                    <div class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-slate-700 transition border-b border-gray-50 dark:border-slate-700">
                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                                    </div>
                                @empty
                                    <div class="px-4 py-6 text-center text-gray-500 text-sm">No new notifications</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    
                    <div class="relative pl-2 md:pl-4 border-l border-gray-200 dark:border-slate-700" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-3 focus:outline-none">
                            <div class="text-right hidden sm:block">
                                <div class="text-sm font-bold text-gray-800 dark:text-white">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                                    @if(Auth::user()->role == 0) Student
                                    @elseif(Auth::user()->role == 1) Teacher
                                    @elseif(Auth::user()->role == 2) Advisor
                                    @elseif(Auth::user()->role == 3) Admin
                                    @endif
                                </div>
                            </div>
                            <img class="h-10 w-10 rounded-full object-cover border-2 border-primary shadow-sm" src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.Auth::user()->name.'&background=4F46E5&color=fff' }}" alt="Profile">
                        </button>

                        <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-100 dark:border-slate-700 py-1 z-50" style="display: none;">
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700"><i class="fas fa-user mr-3 w-4"></i> Profile</a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700"><i class="fas fa-cog mr-3 w-4"></i> Settings</a>
                            <div class="border-t border-gray-100 dark:border-slate-700 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-slate-700"><i class="fas fa-sign-out-alt mr-3 w-4"></i> Log Out</button>
                            </form>
                        </div>
                    </div>

                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 rounded-r shadow-sm flex items-center justify-between" role="alert">
                        <div class="flex items-center"><i class="fas fa-check-circle mr-2"></i><span class="block sm:inline font-medium">{{ session('success') }}</span></div>
                        <button onclick="this.parentElement.remove()" class="text-green-700 dark:text-green-300 hover:text-green-900"><i class="fas fa-times"></i></button>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    @include('components.chatbot-widget')
</body>
</html>
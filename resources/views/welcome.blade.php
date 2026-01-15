<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EduChat - Learn. Connect. Grow.</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5', /* Indigo Theme */
                        secondary: '#10B981', /* Green Accent */
                        dark: '#111827',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Poppins', sans-serif; }
        html { scroll-behavior: smooth; }
        
        /* Custom Animations for Background Blobs */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased" x-data="{ mobileMenuOpen: false }">

    <nav class="fixed w-full z-50 top-0 left-0 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            <a href="/" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center text-white text-xl shadow-lg">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <span class="text-2xl font-bold text-gray-900 tracking-tight">EduChat<span class="text-primary">.</span></span>
            </a>

            <div class="hidden md:flex space-x-8 items-center">
                <a href="#" class="text-gray-600 hover:text-primary font-medium transition">Home</a>
                <a href="#features" class="text-gray-600 hover:text-primary font-medium transition">Features</a>
                <a href="#trending" class="text-gray-600 hover:text-primary font-medium transition">Trending</a>
            </div>

            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-primary hover:bg-indigo-700 text-white font-bold px-6 py-2.5 rounded-full shadow-lg transition transform hover:-translate-y-0.5">
                        My Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary font-bold px-4 py-2 transition">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="bg-primary hover:bg-indigo-700 text-white font-bold px-6 py-2.5 rounded-full shadow-lg transition transform hover:-translate-y-0.5">
                        Get Started
                    </a>
                @endauth
            </div>

            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-primary focus:outline-none p-2">
                    <i class="fas fa-bars text-2xl" x-show="!mobileMenuOpen"></i>
                    <i class="fas fa-times text-2xl" x-show="mobileMenuOpen" style="display: none;"></i>
                </button>
            </div>
        </div>
    </div>

    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="md:hidden bg-white border-t border-gray-100 shadow-xl absolute w-full left-0" 
         style="display: none;">
        <div class="px-4 pt-4 pb-8 space-y-3">
            <a href="#" class="block px-4 py-3 text-gray-700 font-bold hover:bg-indigo-50 hover:text-primary rounded-xl transition">Home</a>
            <a href="#features" class="block px-4 py-3 text-gray-700 font-bold hover:bg-indigo-50 hover:text-primary rounded-xl transition">Features</a>
            <a href="#trending" class="block px-4 py-3 text-gray-700 font-bold hover:bg-indigo-50 hover:text-primary rounded-xl transition">Trending</a>
            
            <div class="pt-4 border-t border-gray-100 grid grid-cols-2 gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="col-span-2 text-center px-4 py-3 bg-primary text-white rounded-xl font-bold">My Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-center px-4 py-3 border border-gray-200 rounded-xl text-gray-700 font-bold hover:bg-gray-50 transition">Log in</a>
                    <a href="{{ route('register') }}" class="text-center px-4 py-3 bg-primary text-white rounded-xl font-bold shadow-md hover:bg-indigo-700 transition">Sign Up</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

    <section class="pt-32 pb-20 bg-gradient-to-br from-indigo-50 via-white to-purple-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <div class="text-center lg:text-left z-10">
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-primary text-sm font-semibold mb-6">
                        <span class="mr-2">🚀</span> Join the Sri Lankan Learning Revolution
                    </div>
                    <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 leading-tight mb-6 tracking-tighter">
                        Learn. Connect. <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-600 font-black">Grow Together.</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium">
                        A student-driven community where you can join study groups, get verified answers, and earn XP to become a teacher.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="bg-primary hover:bg-indigo-700 text-white text-lg font-bold px-8 py-3.5 rounded-full shadow-xl shadow-indigo-500/40 transition transform hover:-translate-y-1">
                            Start Learning Free
                        </a>
                        <a href="#trending" class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 text-lg font-bold px-8 py-3.5 rounded-full shadow-sm transition flex items-center justify-center gap-2">
                            Explore Classes
                        </a>
                    </div>
                    
                    <div class="mt-10 pt-8 border-t border-gray-200 flex justify-center lg:justify-start gap-8">
                        <div>
                            <p class="text-3xl font-black text-gray-900">{{ number_format(($stats['students'] ?? 0) + 1000) }}+</p>
                            <p class="text-xs uppercase font-black text-gray-400 tracking-widest">Students</p>
                        </div>
                        <div>
                            <p class="text-3xl font-black text-gray-900">{{ number_format(($stats['teachers'] ?? 0) + 50) }}+</p>
                            <p class="text-xs uppercase font-black text-gray-400 tracking-widest">Expert Teachers</p>
                        </div>
                        <div>
                            <p class="text-3xl font-black text-primary">{{ number_format($stats['sessions'] ?? 0) }}</p>
                            <p class="text-xs uppercase font-black text-gray-400 tracking-widest">Active Classes</p>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -top-10 -right-10 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
                    <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
                    
                    <div class="relative rounded-3xl p-4 bg-white shadow-2xl border border-gray-100 z-10 overflow-hidden transform hover:scale-[1.02] transition duration-500">
                        <img src="https://img.freepik.com/free-vector/online-learning-isometric-concept_1284-17947.jpg" 
                             alt="Learning Illustration" class="rounded-2xl w-full">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="trending" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
                <div>
                    <h2 class="text-xs font-black text-primary uppercase tracking-[0.3em] mb-3">Live Now</h2>
                    <h2 class="text-4xl font-black text-gray-900 tracking-tighter">Trending Learning Sessions <span class="text-primary">.</span></h2>
                    <p class="text-gray-500 mt-2 font-medium italic">Handpicked educational sessions for you.</p>
                </div>
                <a href="{{ route('login') }}" class="text-primary font-black uppercase text-xs tracking-widest flex items-center gap-2 hover:translate-x-1 transition">
                    Browse All <i class="fas fa-chevron-right text-[10px]"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($trendingSessions as $session)
                <div class="bg-gray-50 rounded-[2rem] p-6 hover:bg-white hover:shadow-2xl transition-all duration-300 border border-transparent hover:border-indigo-100 group">
                    <div class="flex justify-between items-start mb-6">
                        <span class="bg-indigo-100 text-primary text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-tighter">
                            {{ $session->category ?? 'Academic' }}
                        </span>
                        <span class="text-indigo-400 group-hover:text-primary transition"><i class="fas fa-star"></i> 5.0</span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight group-hover:text-primary transition">{{ $session->title }}</h3>
                    
                    <div class="flex items-center gap-3 mb-6">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($session->teacher->name ?? 'Instructor') }}&background=4f46e5&color=fff" 
                             class="w-8 h-8 rounded-full border-2 border-white shadow-sm">
                        <p class="text-xs font-bold text-gray-500">By <span class="text-gray-900">{{ $session->teacher->name ?? 'Expert' }}</span></p>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="text-primary font-black text-lg">
                            {{ $session->base_fee > 0 ? 'LKR '.number_format($session->base_fee) : 'FREE' }}
                        </div>
                        <a href="{{ route('login') }}" class="bg-gray-900 text-white text-[10px] font-black px-4 py-2 rounded-xl uppercase tracking-widest hover:bg-primary transition">Join</a>
                    </div>
                </div>
                @empty
                <p class="col-span-full text-center text-gray-400 italic">No approved sessions available at the moment.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="teachers" class="py-24 bg-indigo-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-xs font-black text-primary uppercase tracking-[0.3em] mb-3">Our Instructors</h2>
                <h2 class="text-4xl font-black text-gray-900 tracking-tighter">Verified Subject Experts</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($topTeachers as $teacher)
                <div class="bg-white rounded-[2.5rem] p-8 text-center shadow-lg border border-white hover:scale-105 transition-transform group">
                    <div class="relative w-24 h-24 mx-auto mb-6">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&size=128&background=4f46e5&color=fff" 
                             class="w-full h-full rounded-3xl object-cover shadow-xl group-hover:rotate-3 transition-transform">
                        <div class="absolute -bottom-2 -right-2 bg-secondary text-white w-8 h-8 rounded-xl flex items-center justify-center text-xs shadow-lg">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-1">{{ $teacher->name }}</h4>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">
                        {{ $teacher->academic_level ?? 'Academic Expert' }}
                    </p>
                    <div class="bg-indigo-50 rounded-2xl py-2 px-4 inline-flex items-center gap-2">
                        <span class="text-primary font-black text-sm">{{ number_format($teacher->points) }}</span>
                        <span class="text-[10px] font-black text-indigo-400 uppercase tracking-tighter">Points</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-gray-400 py-20 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-1 md:col-span-1">
                <span class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-graduation-cap text-primary"></i> EduChat.
                </span>
                <p class="mt-6 text-sm leading-relaxed font-medium">
                    The ultimate platform for collaborative learning in Sri Lanka. Start as a student, finish as a verified teacher.
                </p>
            </div>
            
            <div>
                <h3 class="text-white font-black uppercase text-xs tracking-widest mb-6">Platform</h3>
                <ul class="space-y-4 text-sm font-bold">
                    <li><a href="#" class="hover:text-primary transition">About Us</a></li>
                    <li><a href="#features" class="hover:text-primary transition">Features</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-primary transition">Sign Up</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-white font-black uppercase text-xs tracking-widest mb-6">Support</h3>
                <ul class="space-y-4 text-sm font-bold">
                    <li><a href="#" class="hover:text-primary transition">Help Center</a></li>
                    <li><a href="#" class="hover:text-primary transition">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-primary transition">Terms</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-white font-black uppercase text-xs tracking-widest mb-6">LNBTI</h3>
                <p class="text-sm mb-4 font-bold">Lanka Nippon BizTech Institute</p>
                <p class="text-sm italic">Colombo, Sri Lanka</p>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto border-t border-gray-800 mt-16 pt-8 text-center text-[10px] font-black uppercase tracking-[0.2em]">
            &copy; {{ date('Y') }} EduChat Platform | Group 05 | LNBTI.
        </div>
    </footer>

</body>
</html>
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl overflow-hidden border border-gray-100 dark:border-slate-700">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-10 text-white relative">
            <div class="absolute top-0 right-0 p-4 opacity-20">
                <i class="fas fa-chalkboard-teacher text-9xl"></i>
            </div>

            <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                {{ (float)$session->base_fee <= 0 ? 'Free' : 'Paid' }} Session
            </span>

            <h1 class="text-3xl md:text-4xl font-extrabold mt-4">
                {{ $session->title }}
            </h1>

            <p class="mt-3 opacity-90 flex items-center gap-2 text-lg">
                <i class="far fa-clock"></i>
                {{ \Carbon\Carbon::parse($session->start_time)->format('F d, Y h:i A') }}
                • {{ $session->duration_minutes }} mins
            </p>
        </div>

        <div class="p-8 grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- LEFT --}}
            <div class="lg:col-span-2 space-y-8">

                <div>
                    <h3 class="text-xl font-bold mb-3">About this Session</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ $session->description }}
                    </p>
                </div>

                {{-- TEACHER --}}
                <div class="bg-gray-50 dark:bg-slate-700/50 p-6 rounded-2xl">
                    <h3 class="font-bold mb-4">Meet your Teacher</h3>
                    <div class="flex items-center gap-4">
                        <img src="https://ui-avatars.com/api/?name={{ $session->teacher->name }}"
                             class="w-16 h-16 rounded-full">

                        <div>
                            <h4 class="font-bold">{{ $session->teacher->name }}</h4>
                            <p class="text-sm text-gray-500">EduChat Instructor</p>
                        </div>
                    </div>
                </div>

                {{-- ⭐ TEACHER RATING FORM (ONLY FOR ENROLLED STUDENTS) --}}
                @if($isEnrolled)
                <div class="bg-yellow-50 dark:bg-slate-900/50 p-6 rounded-2xl border border-yellow-100 dark:border-slate-700">
                    <h3 class="font-bold text-gray-800 dark:text-white mb-2">Rate your Teacher 👨‍🏫</h3>
                    <p class="text-xs text-gray-500 mb-4 italic">How was your learning experience with {{ $session->teacher->name }}?</p>
                    
                    <form action="{{ route('student.rate_teacher') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="teacher_id" value="{{ $session->teacher_id }}">
                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Stars</label>
                                <select name="rating" class="w-full rounded-xl border-gray-200 dark:bg-slate-800 dark:border-slate-600 dark:text-white focus:ring-yellow-400">
                                    <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                    <option value="4">⭐⭐⭐⭐ Very Good</option>
                                    <option value="3">⭐⭐⭐ Good</option>
                                    <option value="2">⭐⭐ Fair</option>
                                    <option value="1">⭐ Poor</option>
                                </select>
                            </div>
                            <div class="flex-[2]">
                                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Feedback (Optional)</label>
                                <textarea name="comment" rows="1" class="w-full rounded-xl border-gray-200 dark:bg-slate-800 dark:border-slate-600 dark:text-white" placeholder="Share your thoughts..."></textarea>
                            </div>
                        </div>

                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded-xl transition shadow-md shadow-yellow-200 dark:shadow-none">
                            Submit Rating
                        </button>
                    </form>
                </div>
                @endif

                {{-- MATERIALS --}}
                @if($isEnrolled)
                    @php
                        $materials = \Schema::hasTable('materials')
                            ? \DB::table('materials')->where('session_id', $session->id)->get()
                            : collect();
                    @endphp

                    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-2xl border border-blue-100 dark:border-blue-900/50">
                        <h3 class="font-bold mb-4">Class Materials</h3>

                        @forelse($materials as $file)
                            <a href="{{ $file->file_path }}" target="_blank"
                               class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 rounded-xl mb-2 hover:shadow-md transition">
                                <span class="font-medium">{{ $file->title }}</span>
                                <i class="fas fa-download text-blue-500"></i>
                            </a>
                        @empty
                            <p class="text-sm text-gray-500 italic text-center">No materials uploaded yet.</p>
                        @endforelse
                    </div>
                @endif
            </div>

            {{-- RIGHT --}}
            <div>
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-lg sticky top-24 border border-gray-100 dark:border-slate-700">

                    {{-- PRICE --}}
                    <p class="text-sm uppercase text-gray-500 font-bold tracking-wider">Total Price</p>
                    <h2 class="text-4xl font-extrabold text-indigo-600 mb-6">
                        {{ (float)$session->base_fee <= 0 ? 'FREE' : 'LKR ' . number_format($session->base_fee) }}
                    </h2>

                    {{-- ACTION --}}
                    @if($isEnrolled)

                        <div class="w-full bg-green-50 text-green-700 font-bold py-3 px-4 rounded-xl mb-4 flex items-center justify-center gap-2 border border-green-200">
                            <i class="fas fa-check-circle"></i> Enrolled
                        </div>

                        @if($session->meeting_link)
                            <a href="{{ $session->meeting_link }}" target="_blank"
                               class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition transform hover:scale-[1.02]">
                                Join Live Class
                            </a>
                        @else
                            <p class="text-center text-sm text-yellow-600 bg-yellow-50 p-3 rounded-xl border border-yellow-200">
                                <i class="fas fa-clock mr-1"></i> Class link coming soon
                            </p>
                        @endif

                    @elseif((float)$session->base_fee <= 0)

                        {{-- ✅ FREE SESSION --}}
                        <form method="POST" action="{{ route('student.sessions.enroll', $session->id) }}">
                            @csrf
                            <button
                                 class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl transition transform hover:scale-[1.02]">
                                Enroll for Free
                            </button>
                        </form>

                    @else

                        {{-- 💳 PAID SESSION --}}
                        <a href="{{ route('payments.checkout', $session->id) }}"
                           class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl transition transform hover:scale-[1.02]">
                            Pay & Enroll
                        </a>
                        <p class="text-[10px] text-center text-gray-400 mt-2 uppercase tracking-widest">
                            Secure payment via Stripe
                        </p>

                    @endif

                    <hr class="my-6 border-gray-100 dark:border-slate-700">

                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-4 font-medium">
                        <li class="flex items-center gap-3"><i class="fas fa-video text-indigo-500"></i> Live Online Class</li>
                        <li class="flex items-center gap-3"><i class="fas fa-infinity text-indigo-500"></i> Lifetime Access</li>
                        <li class="flex items-center gap-3"><i class="fas fa-mobile-alt text-indigo-500"></i> Mobile Friendly</li>
                    </ul>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
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

                {{-- MATERIALS --}}
                @if($isEnrolled)
                    @php
                        $materials = \Schema::hasTable('materials')
                            ? \DB::table('materials')->where('session_id', $session->id)->get()
                            : collect();
                    @endphp

                    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-2xl">
                        <h3 class="font-bold mb-4">Class Materials</h3>

                        @forelse($materials as $file)
                            <a href="{{ $file->file_path }}" target="_blank"
                               class="block p-3 bg-white dark:bg-slate-800 rounded-lg mb-2">
                                {{ $file->title }}
                            </a>
                        @empty
                            <p class="text-sm text-gray-500">No materials uploaded yet.</p>
                        @endforelse
                    </div>
                @endif
            </div>

            {{-- RIGHT --}}
            <div>
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-lg sticky top-24">

                    {{-- PRICE --}}
                    <p class="text-sm uppercase text-gray-500">Total Price</p>
                    <h2 class="text-4xl font-extrabold text-indigo-600 mb-6">
                        {{ (float)$session->base_fee <= 0 ? 'FREE' : 'LKR ' . number_format($session->base_fee) }}
                    </h2>

                    {{-- ACTION --}}
                    @if($isEnrolled)

                        <button disabled
                            class="w-full bg-green-100 text-green-700 font-bold py-3 rounded-xl mb-4">
                            <i class="fas fa-check-circle"></i> You are Enrolled
                        </button>

                        @if($session->meeting_link)
                            <a href="{{ $session->meeting_link }}" target="_blank"
                               class="block text-center bg-indigo-600 text-white font-bold py-3 rounded-xl">
                                Join Class
                            </a>
                        @else
                            <p class="text-center text-sm text-yellow-600">
                                Class link coming soon
                            </p>
                        @endif

                    @elseif((float)$session->base_fee <= 0)

                        {{-- ✅ FREE SESSION --}}
                        <form method="POST" action="{{ route('student.sessions.enroll', $session->id) }}">
                            @csrf
                            <button
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl">
                                Enroll for Free
                            </button>
                        </form>

                    @else

                        {{-- 💳 PAID SESSION --}}
                        <a href="{{ route('payments.checkout', $session->id) }}"
                           class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl">
                            Pay & Enroll
                        </a>
                        <p class="text-xs text-center text-gray-400 mt-2">
                            Secure payment • Stripe
                        </p>

                    @endif

                    <hr class="my-6">

                    <ul class="text-sm text-gray-600 space-y-3">
                        <li>📹 Live Online Class</li>
                        <li>♾ Lifetime Access</li>
                        <li>📱 Mobile Friendly</li>
                    </ul>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

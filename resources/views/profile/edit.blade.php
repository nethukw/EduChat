@extends('layouts.app')

@section('content')
@php
    $user = Auth::user();

    $skills = match (true) {
        is_array($user->skills) => $user->skills,
        is_string($user->skills) && str_starts_with($user->skills, '[')
            => json_decode($user->skills, true) ?? [],
        is_string($user->skills)
            => array_map('trim', explode(',', $user->skills)),
        default => [],
    };

    $interests = match (true) {
        is_array($user->interests) => $user->interests,
        is_string($user->interests) && str_starts_with($user->interests, '[')
            => json_decode($user->interests, true) ?? [],
        is_string($user->interests)
            => array_map('trim', explode(',', $user->interests)),
        default => [],
    };
@endphp

<div class="max-w-4xl mx-auto space-y-8">

    {{-- 🔒 PROFILE COMPLETION STATUS --}}
    @if(isset($profilePercentage) && isset($profileChecklist) && $profilePercentage < 100)
    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-xl p-4">
        <div class="flex justify-between items-center mb-3">
            <div>
                <p class="font-bold text-yellow-800 dark:text-yellow-300">
                    Profile {{ $profilePercentage }}% complete
                </p>
                <p class="text-sm text-yellow-700 dark:text-yellow-400">
                    Complete your contact details and bio to unlock all features.
                </p>
            </div>
            <span class="text-lg font-black text-yellow-800 dark:text-yellow-300">
                {{ $profilePercentage }}%
            </span>
        </div>

        <ul class="space-y-2 text-sm">
            @foreach($profileChecklist as $label => $completed)
            <li class="flex items-center gap-3">
                <span class="w-4 h-4 flex items-center justify-center rounded-full
                    {{ $completed ? 'bg-green-500' : 'bg-gray-300 dark:bg-slate-600' }}">
                    <i class="fas {{ $completed ? 'fa-check' : 'fa-minus' }} text-white text-[9px]"></i>
                </span>
                <span class="{{ $completed ? 'text-gray-800 dark:text-white' : 'text-gray-400' }}">
                    {{ $label }}
                </span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- PROFILE CARD --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-slate-700">
        <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600"></div>

        <div class="px-8 pb-8">
            <div class="relative flex justify-between items-end -mt-12 mb-6">
                <img
                    src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.$user->name }}"
                    class="w-32 h-32 rounded-full border-4 border-white dark:border-slate-800 shadow-md object-cover bg-white"
                >

                <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" class="absolute bottom-0 left-24">
                    @csrf
                    @method('PATCH')
                    <label class="bg-gray-900 text-white p-2 rounded-full cursor-pointer hover:bg-gray-700 transition shadow-lg">
                        <i class="fas fa-camera text-xs"></i>
                        <input type="file" name="avatar" class="hidden" onchange="this.form.submit()">
                    </label>
                </form>

                <span class="hidden sm:block bg-indigo-100 text-indigo-800 text-xs font-bold px-3 py-1 rounded-full uppercase">
                    {{ $user->academic_level ?? 'Student' }}
                </span>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
            <p class="text-gray-500 dark:text-gray-400">{{ $user->email }}</p>

            {{-- CONTACT DETAILS --}}
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                    <i class="fas fa-phone text-indigo-500"></i>
                    {{ $user->phone ?? 'No phone added' }}
                </div>
                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                    <i class="fas fa-map-marker-alt text-indigo-500"></i>
                    {{ $user->location ?? 'No location added' }}
                </div>
            </div>

            {{-- BIO --}}
            <div class="mt-6 bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl">
                <h3 class="font-bold text-gray-800 dark:text-white mb-2 flex items-center gap-2">
                    <i class="fas fa-user text-indigo-500"></i> About Me
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                    {{ $user->bio ?? 'No bio added yet.' }}
                </p>
            </div>

            {{-- SKILLS & INTERESTS --}}
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl">
                    <h3 class="font-bold text-gray-800 dark:text-white mb-2 flex items-center gap-2">
                        <i class="fas fa-code"></i> Skills
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse($skills as $skill)
                            <span class="bg-white dark:bg-slate-800 border px-3 py-1 rounded-full text-sm">
                                {{ $skill }}
                            </span>
                        @empty
                            <span class="text-sm italic text-gray-400">No skills added yet.</span>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl">
                    <h3 class="font-bold text-gray-800 dark:text-white mb-2 flex items-center gap-2">
                        <i class="fas fa-heart text-pink-500"></i> Interests
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse($interests as $interest)
                            <span class="bg-white dark:bg-slate-800 border px-3 py-1 rounded-full text-sm">
                                {{ $interest }}
                            </span>
                        @empty
                            <span class="text-sm italic text-gray-400">No interests added yet.</span>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ACCOUNT SETTINGS --}}
    <h2 class="text-xl font-bold text-gray-800 dark:text-white mt-10 pl-2 border-l-4 border-primary">
        Account Settings
    </h2>

    <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow rounded-lg border">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow rounded-lg border">
        @include('profile.partials.update-password-form')
    </div>

    <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow rounded-lg border">
        @include('profile.partials.delete-user-form')
    </div>

</div>
@endsection

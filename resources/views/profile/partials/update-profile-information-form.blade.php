<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and contact details.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- NAME --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full"
                :value="old('name', $user->name)"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- EMAIL --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button
                            form="send-verification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                        >
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- PHONE NUMBER (NEW) --}}
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input
                id="phone"
                name="phone"
                type="text"
                class="mt-1 block w-full"
                :value="old('phone', $user->phone)"
                placeholder="+94 7X XXX XXXX"
            />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        {{-- LOCATION (NEW) --}}
        <div>
            <x-input-label for="location" :value="__('Location')" />
            <x-text-input
                id="location"
                name="location"
                type="text"
                class="mt-1 block w-full"
                :value="old('location', $user->location)"
                placeholder="Colombo, Sri Lanka"
            />
            <x-input-error class="mt-2" :messages="$errors->get('location')" />
        </div>

        {{-- BIO (NEW) --}}
        <div>
            <x-input-label for="bio" :value="__('Bio / About Me')" />
            <textarea
                id="bio"
                name="bio"
                rows="4"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                placeholder="Tell students a bit about yourself..."
            >{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        {{-- ACADEMIC LEVEL --}}
        <div>
            <x-input-label for="academic_level" :value="__('Academic Level')" />
            <select
                id="academic_level"
                name="academic_level"
                class="mt-1 block w-full border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            >
                <option value="Beginner" {{ $user->academic_level == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                <option value="Intermediate" {{ $user->academic_level == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                <option value="Advanced" {{ $user->academic_level == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                <option value="Undergraduate" {{ $user->academic_level == 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('academic_level')" />
        </div>

        {{-- SKILLS --}}
        @php
            $skillsString = is_array($user->skills)
                ? implode(', ', $user->skills)
                : '';
        @endphp
        <div>
            <x-input-label for="skills" :value="__('My Skills (Comma separated)')" />
            <x-text-input
                id="skills"
                name="skills"
                type="text"
                class="mt-1 block w-full"
                :value="old('skills', $skillsString)"
                placeholder="Java, Python, Laravel"
            />
            <x-input-error class="mt-2" :messages="$errors->get('skills')" />
        </div>

        {{-- INTERESTS --}}
        @php
            $interestsString = is_array($user->interests)
                ? implode(', ', $user->interests)
                : '';
        @endphp
        <div>
            <x-input-label for="interests" :value="__('Study Interests (Comma separated)')" />
            <x-text-input
                id="interests"
                name="interests"
                type="text"
                class="mt-1 block w-full"
                :value="old('interests', $interestsString)"
                placeholder="AI, Data Science, Web Development"
            />
            <x-input-error class="mt-2" :messages="$errors->get('interests')" />
        </div>

        {{-- SAVE --}}
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>

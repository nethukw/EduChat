<x-guest-layout>
    <div class="max-w-md mx-auto p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">

        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-rotate-left"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                Recover Your Account
            </h1>
            <p class="text-sm text-gray-400 mt-2">
                Enter your email to restore your deleted account.
            </p>
        </div>

        @if(session('status'))
            <div class="mb-4 text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('account.recover.submit') }}">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                    Email Address
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 dark:text-gray-200"
                    placeholder="your@email.com"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <button
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow-md transition"
            >
                Recover Account
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            Remembered your password?
            <a href="{{ route('login') }}" class="text-indigo-500 font-semibold hover:underline">
                Login
            </a>
        </p>
    </div>
</x-guest-layout>

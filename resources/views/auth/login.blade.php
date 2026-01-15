<x-guest-layout>
    <div class="p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
        <div class="text-center mb-8">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 text-blue-500 dark:text-blue-400 rounded-xl flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-user-circle"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Welcome Back!</h1>
            <p class="text-sm text-gray-400 mt-2">Please sign in to continue learning.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                    Email Address
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', session('email')) }}"
                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 dark:text-gray-200"
                    placeholder="student@example.com"
                    required
                    autofocus
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                    Password
                </label>
                <input
                    type="password"
                    name="password"
                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 dark:text-gray-200"
                    placeholder="••••••••"
                    required
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mb-3">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input
                        id="remember_me"
                        type="checkbox"
                        class="rounded border-gray-300 dark:border-gray-700 text-blue-500 shadow-sm focus:ring-blue-300 dark:bg-gray-900"
                        name="remember"
                    >
                    <span class="ms-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">
                        Remember me
                    </span>
                </label>

                @if (Route::has('password.request'))
                    <a
                        class="text-sm text-blue-500 hover:text-blue-700 hover:underline font-medium"
                        href="{{ route('password.request') }}"
                    >
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- 🔁 ACCOUNT RECOVERY LINK -->
            <div class="mb-6 text-center">
                <p class="text-xs text-gray-400">
                    Deleted your account?
                    <a
                        href="{{ route('account.recover.form') }}"
                        class="text-blue-500 font-semibold hover:underline"
                    >
                        Recover it here
                    </a>
                </p>
            </div>

            <!-- Login Button -->
            <button
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-xl shadow-md shadow-blue-200 dark:shadow-none transition transform hover:-translate-y-0.5"
            >
                Log In
            </button>
        </form>

        <!-- Social Logins -->
        <div class="mt-8">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-100 dark:border-gray-700"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="px-3 bg-white dark:bg-gray-800 text-gray-400 font-medium tracking-wide">
                        Or continue with
                    </span>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-4">
                <a
                    href="{{ route('auth.google') }}"
                    class="flex items-center justify-center px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm bg-white dark:bg-gray-800 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition"
                >
                    <img class="h-5 w-5 mr-2" src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google">
                    Google
                </a>

                <a
                    href="{{ route('auth.github') }}"
                    class="flex items-center justify-center px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm bg-white dark:bg-gray-800 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition"
                >
                    <i class="fab fa-github text-lg mr-2 text-gray-800 dark:text-gray-200"></i>
                    GitHub
                </a>
            </div>
        </div>

        <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-8">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-blue-500 font-bold hover:text-blue-700 hover:underline">
                Sign up
            </a>
        </p>
    </div>
</x-guest-layout>

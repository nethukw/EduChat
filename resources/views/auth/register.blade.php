<x-guest-layout>
    <div class="p-8 bg-white rounded-2xl">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Create Account</h1>
            <p class="text-sm text-gray-400 mt-2">Enter your details to get started.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1">Full Name</label>
                <input type="text" name="name" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-300" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1">Email Address</label>
                <input type="email" name="email" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-300" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                <input type="password" name="password" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-300" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-600 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-300" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg transition">
                Register
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Already have an account? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Log in</a>
        </p>
    </div>
</x-guest-layout>
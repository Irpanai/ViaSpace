<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
            <input id="email" class="w-full border border-gray-200 rounded-xl shadow-sm bg-gray-50 focus:bg-white focus:ring-orange-500 focus:border-orange-500 p-3 transition-colors" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Masukkan email Anda" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-orange-600 hover:text-orange-500 transition-colors" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>
            <input id="password" class="w-full border border-gray-200 rounded-xl shadow-sm bg-gray-50 focus:bg-white focus:ring-orange-500 focus:border-orange-500 p-3 transition-colors" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500" name="remember">
            <label for="remember_me" class="ml-2 block text-sm text-gray-600">
                Ingat Saya
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-3.5 px-4 rounded-xl transition-all duration-300 shadow-lg shadow-orange-500/30 transform hover:-translate-y-0.5">
                Masuk ke Dashboard
            </button>
        </div>
    </form>
</x-guest-layout>

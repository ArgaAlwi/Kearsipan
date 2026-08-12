<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-wedel" />
            <x-text-input id="email" class="block mt-1 w-full focus:border-soga focus:ring-soga" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-wedel" />

            <x-text-input id="password" class="block mt-1 w-full focus:border-soga focus:ring-soga"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <!-- Warna checkbox diubah menjadi text-soga dan focus:ring-soga -->
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-soga shadow-sm focus:ring-soga" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <!-- Warna link diubah hover:text-wedel dan focus:ring-soga -->
                <a class="underline text-sm text-gray-600 hover:text-wedel rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-soga" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <!-- Tombol Log in diubah seluruhnya menggunakan tema Sogan -->
            <button type="submit" class="ms-3 inline-flex items-center px-4 py-2 bg-soga border border-transparent rounded-md font-semibold text-xs text-primisima uppercase tracking-widest hover:bg-wedel focus:bg-wedel active:bg-wedel focus:outline-none focus:ring-2 focus:ring-soga focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>
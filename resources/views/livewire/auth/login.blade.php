<x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
        <div class="flex w-full flex-col text-center">
            <h2 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">
                {{ __('Welcome Back') }}
            </h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-500 dark:text-zinc-400">
                {{ __('Enter your credentials to access your account') }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-passkey-verify />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <div>
                <flux:input
                    name="email"
                    :label="__('Email Address')"
                    :value="old('email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="name@example.com"
                    icon="envelope"
                />
            </div>

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Enter your password')"
                    icon="key"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-xs font-semibold end-0 text-indigo-600 dark:text-indigo-400 hover:underline" :href="route('password.request')" wire:navigate>
                        {{ __('Forgot password?') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="pt-1">
                <flux:checkbox name="remember" :label="__('Remember me for 30 days')" :checked="old('remember')" />
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" data-test="login-button"
                    class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 via-indigo-700 to-violet-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none hover:from-indigo-700 hover:to-violet-800 hover:shadow-xl transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    {{ __('Sign In') }}
                </button>
            </div>
        </form>

        <div class="pt-2 text-xs sm:text-sm text-center text-slate-500 dark:text-zinc-400 border-t border-slate-100 dark:border-zinc-800">
            <span>{{ __('Don\'t have an account?') }}</span>
            <a href="{{ route('register') }}" wire:navigate class="ml-1 font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                {{ __('Create an account') }}
            </a>
        </div>
    </div>
</x-layouts::auth>


<x-layouts::auth :title="__('Register')">
    <div class="flex flex-col gap-6">
        <div class="flex w-full flex-col text-center">
            <h2 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">
                {{ __('Create an Account') }}
            </h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-500 dark:text-zinc-400">
                {{ __('Join the platform to start managing your courses and learning') }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-4">
            @csrf

            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Full Name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('e.g. John Doe')"
                icon="user"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email Address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="name@example.com"
                icon="envelope"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Create a password')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                icon="key"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Repeat your password')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                icon="key"
                viewable
            />

            <!-- Submit Button -->
            <div class="pt-3">
                <button type="submit" data-test="register-user-button"
                    class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 via-indigo-700 to-violet-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none hover:from-indigo-700 hover:to-violet-800 hover:shadow-xl transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    {{ __('Create Account') }}
                </button>
            </div>
        </form>

        <div class="pt-2 text-xs sm:text-sm text-center text-slate-500 dark:text-zinc-400 border-t border-slate-100 dark:border-zinc-800">
            <span>{{ __('Already have an account?') }}</span>
            <a href="{{ route('login') }}" wire:navigate class="ml-1 font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                {{ __('Log in') }}
            </a>
        </div>
    </div>
</x-layouts::auth>


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    <link rel="stylesheet" href="{{ asset('css/filament-toasts.css') }}">
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    @if(app('impersonate')->isImpersonating())
        <div
            class="bg-amber-500 text-amber-950 px-4 py-2.5 text-sm font-semibold flex justify-between items-center z-50 shadow-md">
            <div class="flex items-center gap-2">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <span>You are impersonating: <strong class="underline">{{ auth()->user()->name }}</strong></span>
            </div>
            <a href="{{ route('impersonate.leave') }}"
                class="bg-amber-950 text-white px-3 py-1 rounded hover:bg-amber-900 transition text-xs">
                Leave Impersonation
            </a>
        </div>
    @endif

    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Platform')" class="grid">
                <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>
        @if(auth()->user()->isNormalUser())
            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Registration')" class="grid">
                    <flux:sidebar.item icon="user-plus" :href="route('student-registration')"
                        :current="request()->routeIs('student-registration')" wire:navigate>
                        {{ __('Student Registration') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="user-plus" :href="route('instructor-registration')"
                        :current="request()->routeIs('instructor-registration')" wire:navigate>
                        {{ __('Instructor Registration') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>
        @endif
        @if(auth()->user()->isStudent())
            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Advising')" class="grid">
                    <flux:sidebar.item icon="user-plus" :href="route('advising')" :current="request()->routeIs('advising')"
                        wire:navigate>
                        {{ __('Advising') }}
                    </flux:sidebar.item>

                </flux:sidebar.group>
            </flux:sidebar.nav>
        @endif
        @if(auth()->user()->isInstructor())
            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Advising')" class="grid">
                    <flux:sidebar.item icon="user-plus" :href="route('instructor-advising')"
                        :current="request()->routeIs('instructor-advising')" wire:navigate>
                        {{ __('Advising') }}
                        @php
                            $pendingAdvisingRequests = auth()->user()->instructor->advisingRequests()->where('status', 'pending')->count();
                        @endphp
                        @if ($pendingAdvisingRequests > 0)
                                    <span
                                        class="ml-20 px-1 py-1 text-xs font-medium bg-rose-500/20 dark:bg-rose-900/20 text-rose-500 dark:text-rose-400 rounded-full">{{
                            $pendingAdvisingRequests }}</span>
                        @endif
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>
        @endif

        <flux:spacer />

        <flux:sidebar.nav>
            <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit"
                target="_blank">
                {{ __('Repository') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire"
                target="_blank">
                {{ __('Documentation') }}
            </flux:sidebar.item>
        </flux:sidebar.nav>

        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Log out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @persist('toast')
    <flux:toast.group>
        <flux:toast />
    </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>
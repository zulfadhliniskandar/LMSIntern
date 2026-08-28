<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    <link rel="stylesheet" href="{{ asset('css/filament-toasts.css') }}">
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">


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
                    <flux:sidebar.item icon="user-plus" :href="route('advisor')" :current="request()->routeIs('advisor')"
                        wire:navigate>
                        {{ __('My Advisor') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
                <flux:sidebar.group :heading="__('Academic & AI Tools')" class="grid">
                    <flux:sidebar.item icon="book-open" :href="route('taking-course')"
                        :current="request()->routeIs('taking-course*', 'student-course-details*')" wire:navigate>
                        {{ __('My Courses') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="sparkles" :href="route('simplification-service')"
                        :current="request()->routeIs('simplification-service')" wire:navigate>
                        {{ __('Text Simplification') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="question-mark-circle" :href="route('ai-quiz-generator')"
                        :current="request()->routeIs('ai-quiz-generator*', 'materials.quiz-generator*', 'quizzes.show*')"
                        wire:navigate>
                        {{ __('AI Quiz Generator') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="speaker-wave" :href="route('audio-generator')"
                        :current="request()->routeIs('audio-generator*', 'audio-upload*')" wire:navigate>
                        {{ __('Audio Generator') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="sparkles" :href="route('notebooklm-audio')"
                        :current="request()->routeIs('notebooklm-audio*')" wire:navigate>
                        {{ __('NotebookLM Podcast') }}
                    </flux:sidebar.item>

                </flux:sidebar.group>
            </flux:sidebar.nav>
        @endif
        @if(auth()->user()->isInstructor())
            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Advising')" class="grid">
                    {{-- <flux:sidebar.item icon="user-plus" :href="route('instructor-advising')"
                        :current="request()->routeIs('instructor-advising')" wire:navigate>
                        {{ __('Advising') }}
                        @php
                        $pendingAdvisingRequests = auth()->user()->instructor?->advisingRequests()->where('status',
                        'pending')->count() ?? 0;
                        @endphp
                        @if ($pendingAdvisingRequests > 0)
                        <span
                            class="ml-auto px-2 py-0.5 text-xs font-semibold bg-rose-500/20 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-full inline-flex items-center justify-center min-w-[20px]">
                            {{ $pendingAdvisingRequests }}
                        </span>
                        @endif
                    </flux:sidebar.item> --}}
                    <flux:sidebar.item icon="user-plus" :href="route('instructor-advising-copy')"
                        :current="request()->routeIs('instructor-advising-copy')" wire:navigate>
                        {{ __('Advising') }}
                        @php
                            $pendingAdvisingRequests = auth()->user()->instructor?->advisingRequests()->where('status', 'pending')->count() ?? 0;
                        @endphp
                        @if ($pendingAdvisingRequests > 0)
                            <span
                                class="ml-auto px-2 py-0.5 text-xs font-semibold bg-rose-500/20 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-full inline-flex items-center justify-center min-w-[20px]">
                                {{ $pendingAdvisingRequests }}
                            </span>
                        @endif
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="book-open" :href="route('advisee-for-advisor')"
                        :current="request()->routeIs('advisee-for-advisor', 'student-profile*')" wire:navigate>
                        {{ __('My Students') }}
                        @php
                            $pendingAppointmentRequests = auth()->user()->instructor?->appointments()->where('status', 'pending')->count() ?? 0;
                        @endphp
                        @if ($pendingAppointmentRequests > 0)
                            <span
                                class="ml-auto px-2 py-0.5 text-xs font-semibold bg-rose-500/20 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-full inline-flex items-center justify-center min-w-[20px]">
                                {{ $pendingAppointmentRequests }}
                            </span>
                        @endif
                    </flux:sidebar.item>
                </flux:sidebar.group>
                <flux:sidebar.group :heading="__('Academic')" class="grid">
                    <flux:sidebar.item icon="book-open" :href="route('instructor-course')"
                        :current="request()->routeIs('instructor-course*', 'course-assignment-details*', 'student-submitted*', 'student-not-submitted*', 'submission-details*')"
                        wire:navigate>
                        {{ __('My Courses') }}
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
            <flux:profile
                :avatar="auth()->user()->passport_photo ? \Illuminate\Support\Facades\Storage::url(auth()->user()->passport_photo) : null"
                :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name"
                                :src="auth()->user()->passport_photo ? \Illuminate\Support\Facades\Storage::url(auth()->user()->passport_photo) : null"
                                :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    @if(app('impersonate')->isImpersonating())
                        <flux:menu.item href="{{ route('impersonate.leave') }}" icon="user-minus"
                            class="text-amber-600 dark:text-amber-400 font-bold">
                            {{ __('Leave Impersonation') }}
                        </flux:menu.item>
                        <flux:menu.separator />
                    @endif
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
    @filamentScripts
</body>

</html>
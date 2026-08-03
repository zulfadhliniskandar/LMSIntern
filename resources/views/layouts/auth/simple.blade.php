<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-slate-50 dark:bg-zinc-950 text-slate-900 dark:text-zinc-100 antialiased relative selection:bg-indigo-500 selection:text-white">
        <!-- Background decorative blobs -->
        <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <div class="absolute top-[-10%] left-[20%] w-[500px] h-[500px] bg-indigo-300/30 dark:bg-indigo-900/20 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[20%] w-[450px] h-[450px] bg-purple-300/30 dark:bg-violet-900/20 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
        </div>

        <div class="flex min-h-svh flex-col items-center justify-center p-6 md:p-10">
            <div class="flex w-full max-w-md flex-col gap-6">
                <!-- Branding Header -->
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 text-center group" wire:navigate>
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none group-hover:scale-105 transition-transform duration-200">
                        <x-app-logo-icon class="size-7 fill-current text-white" />
                    </div>
                    <span class="text-xl font-black tracking-tight text-slate-900 dark:text-white">
                        {{ config('app.name', 'Learning Management System') }}
                    </span>
                </a>

                <!-- Card Wrapper -->
                <div class="bg-white dark:bg-zinc-900/90 backdrop-blur-xl border border-slate-200/80 dark:border-zinc-800/80 rounded-3xl p-8 sm:p-10 shadow-2xl shadow-slate-200/60 dark:shadow-none transition-all">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>


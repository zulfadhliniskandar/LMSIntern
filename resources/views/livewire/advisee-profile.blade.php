<div class="max-w-7xl mx-auto space-y-6">
    @if(session()->has('success'))
        <div class="bg-green-500 dark:bg-green-900 text-white px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    {{-- Top Navigation & Back Link --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('advisee-for-advisor') }}" wire:navigate
            class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to My Advisees
        </a>

        <span
            class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            Active Advisee
        </span>
    </div>

    {{-- Student Hero Profile Card --}}
    <div
        class="relative overflow-hidden bg-white dark:bg-zinc-800 rounded-2xl border border-gray-200 dark:border-zinc-700 shadow-sm">
        {{-- Banner Background --}}
        <div class="h-28 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 opacity-90"></div>

        <div class="px-6 pb-6 pt-0 relative flex flex-col md:flex-row md:items-end md:justify-between gap-4 -mt-12">
            <div class="flex flex-col sm:flex-row items-center sm:items-end gap-4 text-center sm:text-left">
                {{-- Avatar --}}
                <div
                    class="w-24 h-24 rounded-2xl bg-indigo-600 dark:bg-indigo-500 text-white font-bold text-3xl flex items-center justify-center border-4 border-white dark:border-zinc-800 shadow-md uppercase tracking-wider">
                    {{ substr($advisee->name, 0, 2) }}
                </div>

                <div class="mt-2 sm:mt-0">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $advisee->name }}
                    </h1>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-1">
                        <span
                            class="inline-flex items-center gap-1 text-xs font-mono font-medium px-2.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                            ID: {{ $advisee->student?->student_id ?? 'N/A' }}
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $advisee->email }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center sm:justify-start gap-2">
                <span
                    class="px-3 py-1 text-xs font-medium bg-gray-100 dark:bg-zinc-700 text-gray-700 dark:text-gray-300 rounded-lg">
                    {{ $advisee->student?->programme?->name ?? 'No Programme Assigned' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Pending Appointment Card --}}
    @if($pendingAppointment)
        <div
            class="bg-amber-50/70 dark:bg-amber-950/20 rounded-2xl border border-amber-200 dark:border-amber-800/50 p-6 shadow-sm">
            <div
                class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-amber-200/60 dark:border-amber-800/40">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-amber-900 dark:text-amber-200">
                            Pending Appointment Request
                        </h3>
                        <p class="text-xs text-amber-700 dark:text-amber-400">
                            This advisee has requested an advising appointment with you.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button wire:confirm="Are you sure you want to accept this appointment?" wire:click="confirmAppointment"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-xl shadow-sm transition-all disabled:opacity-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Confirm Appointment
                    </button>

                    <button wire:click="openModalReject({{ $pendingAppointment->id }})" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-rose-700 dark:text-rose-300 bg-rose-100 dark:bg-rose-950/50 hover:bg-rose-200 dark:hover:bg-rose-900/60 rounded-xl border border-rose-200 dark:border-rose-800 transition-all disabled:opacity-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reject Appointment
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 text-sm">
                <div
                    class="bg-white/80 dark:bg-zinc-800/80 p-3.5 rounded-xl border border-amber-200/50 dark:border-amber-900/40">
                    <span class="text-xs text-gray-500 dark:text-gray-400 block font-medium">Requested Date</span>
                    <span class="font-semibold text-gray-900 dark:text-white mt-0.5 block">
                        {{ \Carbon\Carbon::parse($pendingAppointment->date_appointment)->format('F d, Y') }}
                    </span>
                </div>

                <div
                    class="bg-white/80 dark:bg-zinc-800/80 p-3.5 rounded-xl border border-amber-200/50 dark:border-amber-900/40">
                    <span class="text-xs text-gray-500 dark:text-gray-400 block font-medium">Requested Time</span>
                    <span class="font-semibold text-gray-900 dark:text-white mt-0.5 block">
                        {{ \Carbon\Carbon::parse($pendingAppointment->time_appointment)->format('h:i A') }}
                    </span>
                </div>

                <div
                    class="bg-white/80 dark:bg-zinc-800/80 p-3.5 rounded-xl border border-amber-200/50 dark:border-amber-900/40 md:col-span-1">
                    <span class="text-xs text-gray-500 dark:text-gray-400 block font-medium">Student Note / Purpose</span>
                    <span class="font-medium text-gray-800 dark:text-gray-200 mt-0.5 block italic">
                        "{{ $pendingAppointment->note ?? 'No note provided' }}"
                    </span>
                </div>
            </div>
        </div>
    @endif

    {{-- Academic Overview Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Academic Info Card --}}
        <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-gray-200 dark:border-zinc-700 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
                Academic Information
            </h2>

            <dl class="divide-y divide-gray-100 dark:divide-zinc-700/60 text-sm">
                <div class="py-3 flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Student ID</dt>
                    <dd class="font-mono font-semibold text-gray-900 dark:text-white">
                        {{ $advisee->student?->student_id ?? 'N/A' }}
                    </dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Programme</dt>
                    <dd class="font-medium text-gray-900 dark:text-white text-right">
                        {{ $advisee->student?->programme?->name ?? 'N/A' }}
                    </dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Programme Code</dt>
                    <dd class="font-mono font-medium text-gray-900 dark:text-white">
                        {{ $advisee->student?->programme?->code ?? 'N/A' }}
                    </dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Department</dt>
                    <dd class="font-medium text-gray-900 dark:text-white text-right">
                        {{ $advisee->student?->programme?->department?->name ?? 'N/A' }}
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Contact & Status Card --}}
        <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-gray-200 dark:border-zinc-700 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Account Details
            </h2>

            <dl class="divide-y divide-gray-100 dark:divide-zinc-700/60 text-sm">
                <div class="py-3 flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Full Name</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">{{ $advisee->name }}</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Email Address</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">{{ $advisee->email }}</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Registered Date</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">
                        {{ $advisee->created_at ? $advisee->created_at->format('M d, Y') : 'N/A' }}
                    </dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Advising Status</dt>
                    <dd class="font-medium text-emerald-600 dark:text-emerald-400">Assigned</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Subject History & Current Enrollment Section --}}
    <div
        class="bg-white dark:bg-zinc-800 rounded-2xl border border-gray-200 dark:border-zinc-700 shadow-sm overflow-hidden">
        {{-- Section Header & Navigation Tabs --}}
        <div
            class="p-6 pb-4 border-b border-gray-200 dark:border-zinc-700/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Academic Subjects Overview
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    View active course enrollments and completed subject history for this advisee.
                </p>
            </div>

            {{-- Segmented Tab Switcher --}}
            <div
                class="inline-flex p-1 bg-gray-100 dark:bg-zinc-900 rounded-xl border border-gray-200/80 dark:border-zinc-700/60">
                <button wire:click="setTab('current')"
                    class="inline-flex items-center gap-2 px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $activeTab === 'current' ? 'bg-white dark:bg-zinc-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Currently Taking
                    <span
                        class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $activeTab === 'current' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300' : 'bg-gray-200 text-gray-700 dark:bg-zinc-800 dark:text-gray-300' }}">
                        {{ $currentSubjectsCount }}
                    </span>
                </button>

                <button wire:click="setTab('completed')"
                    class="inline-flex items-center gap-2 px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $activeTab === 'completed' ? 'bg-white dark:bg-zinc-800 text-emerald-600 dark:text-emerald-400 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Completed
                    <span
                        class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $activeTab === 'completed' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-gray-200 text-gray-700 dark:bg-zinc-800 dark:text-gray-300' }}">
                        {{ $completedSubjectsCount }}
                    </span>
                </button>

                <button wire:click="setTab('all')"
                    class="inline-flex items-center gap-2 px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $activeTab === 'all' ? 'bg-white dark:bg-zinc-800 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                    View All
                </button>
            </div>
        </div>

        {{-- Tab Content Area --}}
        <div class="p-6 space-y-6">
            @if($showCurrentSubjects)
                <div>
                    @if($activeTab === 'all')
                        <h3
                            class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            Currently Taking Subjects ({{ $currentSubjectsCount }})
                        </h3>
                    @endif
                    <livewire:list-current-subjects :studentId="$advisee->id" />
                </div>
            @endif

            @if($showCompletedSubjects)
                <div>
                    @if($activeTab === 'all')
                        <h3
                            class="text-sm font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Completed Subjects ({{ $completedSubjectsCount }})
                        </h3>
                    @endif
                    <livewire:list-taken-subject :studentId="$advisee->id" />
                </div>
            @endif
        </div>
    </div>
    <!-- Reject Modal -->
    @if ($showRejectModal)
        <div x-data wire:keydown.escape.window="closeModalReject"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm transition-opacity animate-fade-in">

            <!-- Backdrop Click Area -->
            <div class="fixed inset-0" wire:click="closeModalReject"></div>

            <!-- Modal Window -->
            <div
                class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-7 shadow-2xl border border-gray-100 dark:border-gray-700 space-y-5 overflow-hidden z-10">
                <!-- Top Accent Line -->
                <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-rose-500 via-amber-500 to-rose-600"></div>

                <!-- Modal Header -->
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div
                            class="w-11 h-11 rounded-2xl bg-rose-100 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900/50 flex items-center justify-center text-rose-600 dark:text-rose-400 shrink-0 shadow-xs">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white leading-snug">Reject Appointment
                                Request</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Provide a reason for the student before
                                rejecting.</p>
                        </div>
                    </div>

                    <button wire:click="closeModalReject" type="button"
                        class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:text-slate-300 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Target Appointment Summary -->
                @if($selectedAppointment)
                    <div
                        class="p-3.5 bg-slate-50 dark:bg-gray-900/60 border border-slate-200/80 dark:border-gray-700/60 rounded-2xl space-y-1 text-xs">
                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-300">
                            <span class="font-semibold text-slate-500 dark:text-slate-400">Appointment Date:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">
                                {{ \Carbon\Carbon::parse($selectedAppointment->date_appointment)->format('F d, Y') }}
                                @if($selectedAppointment->time_appointment)
                                    at {{ \Carbon\Carbon::parse($selectedAppointment->time_appointment)->format('g:i A') }}
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-300">
                            <span class="font-semibold text-slate-500 dark:text-slate-400">Original Agenda:</span>
                            <span
                                class="font-medium text-slate-700 dark:text-slate-300 truncate max-w-[240px] italic">"{{ $selectedAppointment->note }}"</span>
                        </div>
                    </div>
                @endif

                <!-- Modal Form -->
                <form wire:submit.prevent="rejectAppointment" class="space-y-4">
                    <div class="space-y-1.5">
                        <label for="reject_note"
                            class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Reason for Rejection <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="reject_note" wire:model="reject_note" rows="3"
                            placeholder="Briefly state why you are declining this request..."
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-2xl text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all resize-none shadow-xs"></textarea>
                        @error('reject_note')
                            <p class="text-xs text-rose-500 font-medium mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" wire:click="closeModalReject"
                            class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-500/20 hover:shadow-rose-500/30 transition-all cursor-pointer disabled:opacity-50">
                            <span wire:loading.remove wire:target="rejectAppointment" class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Confirm Rejection
                            </span>
                            <span wire:loading wire:target="rejectAppointment" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
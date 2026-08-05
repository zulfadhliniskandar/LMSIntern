<div class="p-6 max-w-7xl mx-auto space-y-6">
    <!-- Breadcrumbs & Header -->
    <div class="space-y-1">
        <div class="text-[10px] sm:text-xs text-slate-400 dark:text-zinc-500 font-semibold uppercase tracking-wider">
            Academic &nbsp;&gt;&nbsp; My Advisor
        </div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Academic Advisor Portal</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">View your assigned advisor details and schedule
            consultation appointments.</p>
    </div>

    <!-- Flash Notifications -->
    @if (session()->has('message'))
        <div
            class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl flex items-center justify-between shadow-xs animate-fade-in">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-semibold">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div
            class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-2xl flex items-center justify-between shadow-xs animate-fade-in">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-semibold">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Advisor Profile Card -->
    @if($advisor)
        <div
            class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <div
                class="absolute top-0 right-0 -mt-8 -mr-8 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none">
            </div>

            <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div
                        class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 p-0.5 shadow-md shrink-0">
                        <div
                            class="w-full h-full bg-white dark:bg-gray-900 rounded-[14px] flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-xl sm:text-2xl uppercase">
                            {{ substr($advisor->user->name ?? 'A', 0, 2) }}
                        </div>
                    </div>

                    <div class="space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span
                                class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-950/60 dark:text-indigo-300">
                                Assigned Academic Advisor
                            </span>
                            @if($advisor->department)
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-gray-700 dark:text-slate-300">
                                    {{ $advisor->department->name }}
                                </span>
                            @endif
                        </div>

                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">
                            {{ $advisor->user->name ?? 'Advisor Not Assigned' }}
                        </h2>

                        <div class="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $advisor->user->email ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($advisor->user?->email)
                    <a href="mailto:{{ $advisor->user->email }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-slate-800 dark:text-white rounded-xl text-xs font-semibold transition-colors shrink-0">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Email Advisor
                    </a>
                @endif
            </div>
        </div>
    @else
        <div
            class="p-8 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 rounded-3xl text-center">
            <svg class="w-12 h-12 text-amber-500 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="text-base font-bold text-amber-900 dark:text-amber-200">No Academic Advisor Assigned</h3>
            <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">Please contact your department administrator to
                assign an academic advisor to your profile.</p>
        </div>
    @endif

    <!-- Two-Column Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Request Appointment Form (Left Column) -->
        <div
            class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 h-fit space-y-5">
            <div class="flex items-center gap-2 pb-3 border-b border-gray-100 dark:border-gray-700">
                <div class="p-2 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base">Request Appointment</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Schedule a 1-on-1 consultation session</p>
                </div>
            </div>

            @if($this->hasPendingAppointment())
                <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50 text-xs text-amber-800 dark:text-amber-300 flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>You already have a pending appointment request. Booking is disabled until your advisor responds.</span>
                </div>
            @endif

            <form wire:submit.prevent="sendRequest" class="space-y-4">
                <!-- Date & Time Picker Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label for="date"
                            class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Preferred Date <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="date" wire:model="date" min="{{ date('Y-m-d') }}"
                            @disabled($this->hasPendingAppointment())
                            class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-slate-100 dark:disabled:bg-slate-800" />
                        @error('date')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="time"
                            class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Preferred Time <span class="text-rose-500">*</span>
                        </label>
                        <input type="time" id="time" wire:model="time"
                            @disabled($this->hasPendingAppointment())
                            class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-slate-100 dark:disabled:bg-slate-800" />
                        @error('time')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Consultation Note -->
                <div class="space-y-1.5">
                    <label for="note"
                        class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                        Agenda / Consultation Note <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="note" wire:model="note" rows="4"
                        @disabled($this->hasPendingAppointment())
                        placeholder="Briefly state your academic concerns, course selection, or career advice needed..."
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-none disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-slate-100 dark:disabled:bg-slate-800"></textarea>
                    @error('note')
                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    @disabled($this->hasPendingAppointment())
                    wire:loading.attr="disabled"
                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs font-bold shadow-sm transition-all cursor-pointer">
                    <span wire:loading.remove wire:target="sendRequest" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Submit Request
                    </span>
                    <span wire:loading wire:target="sendRequest" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Sending...
                    </span>
                </button>
            </form>
        </div>

        <!-- Appointment Request History (Right Column) -->
        <div
            class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base">Appointment History</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Track your submitted consultation requests and
                        advisor responses</p>
                </div>
                <span
                    class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 dark:bg-gray-700 dark:text-slate-300">
                    {{ count($appointments) }} Total
                </span>
            </div>

            <div class="space-y-3 max-h-[500px] overflow-y-auto pr-1">
                @forelse($appointments as $item)
                    @php
                        $statusStr = strtolower($item->status);
                        $statusClasses = match ($statusStr) {
                            'approved' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300',
                            'rejected' => 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300',
                            default => 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300',
                        };
                    @endphp
                    <div
                        class="p-4 bg-slate-50 dark:bg-gray-900/50 rounded-2xl border border-slate-100 dark:border-gray-700/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1.5 flex-grow">
                            <div class="flex items-center gap-2">
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusClasses }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                                <span class="text-xs text-slate-400 dark:text-slate-500">
                                    Submitted {{ $item->created_at->format('M d, Y') }}
                                </span>
                            </div>

                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                {{ $item->note }}
                            </p>

                            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Requested Date & Time: <strong
                                        class="text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($item->date_appointment)->format('F d, Y (l)') }}
                                        @if($item->time_appointment) at
                                            {{ \Carbon\Carbon::parse($item->time_appointment)->format('g:i A') }}
                                        @endif</strong></span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center border-2 border-dashed border-slate-200 dark:border-gray-700 rounded-2xl">
                        <svg class="w-10 h-10 text-slate-300 dark:text-slate-600 mx-auto mb-3" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300">No Appointments Requested Yet</h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Use the form to schedule a consultation
                            with your academic advisor.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
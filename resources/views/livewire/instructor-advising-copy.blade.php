<div class="space-y-6">
    {{-- Header --}}
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Advising Portal</flux:heading>
            <flux:subheading>Review, verify, and approve academic course selection requests submitted by students.
            </flux:subheading>
        </div>
    </div>

    <!-- Session Feedback Alerts -->
    @if (session()->has('success'))
        <div
            class="p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in">
            <flux:icon name="check-circle" class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" />
            <div class="text-sm font-semibold">{{ session('success') }}</div>
        </div>
    @endif

    @if (session()->has('error'))
        <div
            class="p-4 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in">
            <flux:icon name="exclamation-triangle" class="w-5 h-5 text-rose-600 dark:text-rose-400 shrink-0" />
            <div class="text-sm font-semibold">{{ session('error') }}</div>
        </div>
    @endif

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Pending Card -->
        <flux:card
            class="flex items-center gap-4 p-5 bg-gradient-to-br from-white to-slate-50 dark:from-zinc-900 dark:to-zinc-900/60 border border-slate-100 dark:border-zinc-800 shadow-xs">
            <div class="p-3 bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 rounded-2xl">
                <flux:icon name="clock" class="w-6 h-6" />
            </div>
            <div>
                <div class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">Pending
                    Approval</div>
                <div class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-1 leading-none">
                    {{ $stats['pending'] ?? 0 }}
                </div>
            </div>
        </flux:card>

        <!-- Approved Card -->
        <flux:card
            class="flex items-center gap-4 p-5 bg-gradient-to-br from-white to-slate-50 dark:from-zinc-900 dark:to-zinc-900/60 border border-slate-100 dark:border-zinc-800 shadow-xs">
            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-2xl">
                <flux:icon name="check-circle" class="w-6 h-6" />
            </div>
            <div>
                <div class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">Approved
                    Requests</div>
                <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1 leading-none">
                    {{ $stats['approved'] ?? 0 }}
                </div>
            </div>
        </flux:card>

        <!-- Rejected Card -->
        <flux:card
            class="flex items-center gap-4 p-5 bg-gradient-to-br from-white to-slate-50 dark:from-zinc-900 dark:to-zinc-900/60 border border-slate-100 dark:border-zinc-800 shadow-xs">
            <div class="p-3 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 rounded-2xl">
                <flux:icon name="x-circle" class="w-6 h-6" />
            </div>
            <div>
                <div class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">Rejected
                    Requests</div>
                <div class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1 leading-none">
                    {{ $stats['rejected'] ?? 0 }}
                </div>
            </div>
        </flux:card>
    </div>

    {{-- Filament Table --}}
    <div
        class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 shadow-sm overflow-hidden p-1">
        {{ $this->table }}
    </div>

    <x-filament-actions::modals />
</div>
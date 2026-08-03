<div class="space-y-6">
    <!-- Breadcrumbs -->
    <div class="text-[10px] sm:text-xs text-slate-400 dark:text-zinc-500 font-semibold uppercase tracking-wider">
        Departments &nbsp;&gt;&nbsp; Create
    </div>

    <!-- Page Title -->
    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Create Department</h1>

    <form wire:submit="createDepartments" class="space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">
            <!-- Left Card: Basic Department Information (3 cols) -->
            <flux:card class="lg:col-span-3 p-6 space-y-6">
                <div class="flex gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-zinc-800 flex items-center justify-center shrink-0 border border-slate-100 dark:border-zinc-700">
                        <flux:icon name="academic-cap" variant="outline" class="w-5 h-5 text-slate-600 dark:text-zinc-400" />
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white leading-tight">Basic Department Information</h3>
                        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">General details and code for this academic faculty.</p>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-zinc-800" />

                <!-- Department Name -->
                <flux:field>
                    <flux:label class="font-bold text-xs text-slate-800 dark:text-zinc-200">Department Name <span class="text-rose-500">*</span></flux:label>
                    <flux:input wire:model="name" icon="academic-cap" placeholder="e.g. Faculty of Science" />
                    <flux:description class="text-[10px] text-slate-400 dark:text-zinc-500">Official name of the academic department.</flux:description>
                    <flux:error name="name" />
                </flux:field>

                <!-- Department Code -->
                <flux:field>
                    <flux:label class="font-bold text-xs text-slate-800 dark:text-zinc-200">Department Code <span class="text-rose-500">*</span></flux:label>
                    <flux:input wire:model="code" icon="hashtag" placeholder="e.g. FST" />
                    <flux:description class="text-[10px] text-slate-400 dark:text-zinc-500">Unique 3 to 5-character department code.</flux:description>
                    <flux:error name="code" />
                </flux:field>
            </flux:card>

            <!-- Right Card: Academic Guidelines (2 cols) -->
            <flux:card class="lg:col-span-2 p-6 space-y-6">
                <div class="flex gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-zinc-800 flex items-center justify-center shrink-0 border border-slate-100 dark:border-zinc-700">
                        <flux:icon name="building-office-2" variant="outline" class="w-5 h-5 text-slate-600 dark:text-zinc-400" />
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white leading-tight">Academic Guidelines</h3>
                        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Rules and standards for this department.</p>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-zinc-800" />

                <div class="space-y-4">
                    <div class="flex gap-3">
                        <flux:icon name="check-circle" class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" />
                        <div class="text-xs text-slate-600 dark:text-zinc-400">
                            <span class="font-bold text-slate-800 dark:text-zinc-200">Consistency:</span> Name should represent an official division or school.
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <flux:icon name="check-circle" class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" />
                        <div class="text-xs text-slate-600 dark:text-zinc-400">
                            <span class="font-bold text-slate-800 dark:text-zinc-200">Abbreviation:</span> Code must be unique, capitalized, and between 3 to 5 characters.
                        </div>
                    </div>
                </div>
            </flux:card>
        </div>

        <!-- Buttons row -->
        <div class="flex items-center gap-3">
            <button type="submit" class="bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-black font-bold rounded-lg px-4 py-2.5 text-xs transition-all shadow-sm flex items-center gap-2">
                Create
            </button>
            <a href="{{ route('listDepartments') }}" wire:navigate class="bg-white hover:bg-slate-50 border border-slate-200 dark:border-zinc-700 dark:bg-zinc-900 dark:hover:bg-zinc-800 text-slate-800 dark:text-zinc-200 font-bold rounded-lg px-4 py-2.5 text-xs transition-all shadow-sm">
                Cancel
            </a>
        </div>
    </form>
</div>
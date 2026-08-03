<div class="space-y-6">
    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-sm shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold">Success</h4>
                    <p class="text-xs">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <flux:heading size="xl" level="1">Academic Departments</flux:heading>
                <flux:badge size="sm">{{ count($departments) }}</flux:badge>
            </div>
            <flux:subheading>Manage university faculties, department codes, and academic divisions.</flux:subheading>
        </div>

        <flux:button href="{{ route('createDepartments') }}" variant="primary" icon="plus" wire:navigate>
            Add Department
        </flux:button>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Departments Card -->
        <flux:card class="flex items-center gap-4 p-5 bg-gradient-to-br from-white to-slate-50 dark:from-zinc-900 dark:to-zinc-900/60 border border-slate-100 dark:border-zinc-800">
            <div class="p-3 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V9a2 2 0 012-2h2a2 2 0 012 2v12" />
                </svg>
            </div>
            <div>
                <div class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">Total Departments</div>
                <div class="text-2xl font-black text-slate-900 dark:text-white mt-1 leading-none">{{ count($departments) }}</div>
            </div>
        </flux:card>

        <!-- Total Programmes Card -->
        <flux:card class="flex items-center gap-4 p-5 bg-gradient-to-br from-white to-slate-50 dark:from-zinc-900 dark:to-zinc-900/60 border border-slate-100 dark:border-zinc-800">
            <div class="p-3 bg-violet-50 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
            </div>
            <div>
                <div class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">Active Programmes</div>
                <div class="text-2xl font-black text-slate-900 dark:text-white mt-1 leading-none">{{ $departments->sum('programmes_count') }}</div>
            </div>
        </flux:card>

        <!-- Latest Department Card -->
        <flux:card class="flex items-center gap-4 p-5 bg-gradient-to-br from-white to-slate-50 dark:from-zinc-900 dark:to-zinc-900/60 border border-slate-100 dark:border-zinc-800">
            <div class="p-3 bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <div class="overflow-hidden w-full">
                <div class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">Latest Department</div>
                <div class="text-sm font-bold text-slate-900 dark:text-white mt-1 truncate" title="{{ $departments->first()?->name ?? 'None' }}">
                    {{ $departments->first()?->name ?? 'None' }}
                </div>
            </div>
        </flux:card>
    </div>

    <div class="flex items-center justify-between gap-4">
        <div class="w-full sm:w-80">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="Search by name or code..." icon="magnifying-glass" clearable />
        </div>
    </div>

    <flux:card class="overflow-hidden p-0">
        @if ($departments->count() > 0)
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Code</flux:table.column>
                    <flux:table.column>Department Name</flux:table.column>
                    <flux:table.column>Programmes</flux:table.column>
                    <flux:table.column>Created Date</flux:table.column>
                    <flux:table.column></flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($departments as $department)
                        <flux:table.row :key="$department->id">
                            <flux:table.cell>
                                <flux:badge size="sm" color="indigo" class="uppercase font-mono font-semibold">{{ $department->code ?? 'N/A' }}</flux:badge>
                            </flux:table.cell>
                            
                            <flux:table.cell class="font-semibold text-slate-900 dark:text-white">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-extrabold text-[10px] flex items-center justify-center border border-indigo-100 dark:border-indigo-900/60 shrink-0 uppercase tracking-wider">
                                        {{ substr($department->name, 0, 2) }}
                                    </div>
                                    <div>
                                        {{ $department->name }}
                                        <div class="text-[10px] text-slate-400 dark:text-zinc-500 font-normal">ID: #{{ $department->id }}</div>
                                    </div>
                                </div>
                            </flux:table.cell>

                            <flux:table.cell>
                                <div class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-zinc-400">
                                    <svg class="w-3.5 h-3.5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    </svg>
                                    {{ $department->programmes_count ?? 0 }} {{ Str::plural('Programme', $department->programmes_count ?? 0) }}
                                </div>
                            </flux:table.cell>

                            <flux:table.cell class="text-xs text-slate-500 dark:text-zinc-400">
                                {{ $department->created_at ? $department->created_at->format('M d, Y') : 'N/A' }}
                            </flux:table.cell>

                            <flux:table.cell class="text-right">
                                <flux:button variant="ghost" icon="trash" size="sm" wire:click="delete({{ $department->id }})" wire:confirm="Are you sure you want to delete '{{ $department->name }}'?" />
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-500 dark:text-indigo-400 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V9a2 2 0 012-2h2a2 2 0 012 2v12" />
                    </svg>
                </div>
                <flux:heading size="lg" level="3" class="mb-1">
                    @if ($search) No departments found matching "{{ $search }}" @else No departments registered yet @endif
                </flux:heading>
                <flux:subheading class="max-w-sm mx-auto mb-6">
                    @if ($search) Try checking for typos or clear the search filter. @else Get started by creating your first academic department. @endif
                </flux:subheading>
                @if (!$search)
                    <flux:button href="{{ route('createDepartments') }}" variant="primary" icon="plus" wire:navigate>
                        Add First Department
                    </flux:button>
                @endif
            </div>
        @endif
    </flux:card>
</div>
@if ($selectedRequest)
    @php
        $subjects = \App\Models\Subjects::whereIn('id', $selectedRequest->subject_ids ?? [])->get();
        $totalCredits = $subjects->sum('credit_hours');
    @endphp
    <div class="space-y-5 text-sm">
        {{-- Student Header --}}
        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-zinc-800/80 rounded-xl border border-gray-100 dark:border-zinc-700/60">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-bold text-sm flex items-center justify-center uppercase tracking-wider shrink-0 shadow-sm">
                {{ substr($selectedRequest->student?->user?->name ?? 'S', 0, 2) }}
            </div>
            <div>
                <h4 class="font-bold text-gray-900 dark:text-white leading-tight">
                    {{ $selectedRequest->student?->user?->name ?? 'N/A' }}
                </h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    ID: <span class="font-mono font-semibold">{{ $selectedRequest->student_id }}</span>
                </p>
            </div>
        </div>

        {{-- Meta Grid --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-xl border border-gray-100 dark:border-zinc-700/50">
                <span class="block text-xs font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-wider">Programme</span>
                <span class="font-bold text-gray-800 dark:text-gray-200 mt-0.5 block">
                    {{ $selectedRequest->student?->programme?->name ?? 'N/A' }}
                </span>
            </div>

            <div class="p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-xl border border-gray-100 dark:border-zinc-700/50">
                <span class="block text-xs font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-wider">Requested Date</span>
                <span class="font-semibold text-gray-800 dark:text-gray-200 mt-0.5 block">
                    {{ $selectedRequest->created_at ? $selectedRequest->created_at->format('M d, Y h:i A') : 'N/A' }}
                </span>
            </div>
        </div>

        {{-- Requested Subjects List --}}
        <div>
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-wider mb-2">
                Requested Subjects ({{ count($subjects) }})
            </span>

            <div class="space-y-2 bg-gray-50 dark:bg-zinc-800/50 border border-gray-200/80 dark:border-zinc-700 p-3.5 rounded-xl">
                @forelse($subjects as $sub)
                    <div class="flex justify-between items-center text-xs py-1">
                        <div class="flex items-center gap-2">
                            <span class="font-bold px-2 py-0.5 bg-gray-200 dark:bg-zinc-700 text-gray-800 dark:text-gray-200 rounded uppercase font-mono text-[10px]">
                                {{ $sub->code }}
                            </span>
                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $sub->name }}</span>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 shrink-0 font-medium">
                            {{ $sub->credit_hours }} Credits
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-2">No subjects selected</p>
                @endforelse

                <hr class="border-gray-200 dark:border-zinc-700 my-2" />

                <div class="flex justify-between text-xs font-bold text-gray-900 dark:text-white pt-1">
                    <span>Total Workload</span>
                    <span class="text-indigo-600 dark:text-indigo-400 font-mono">{{ $totalCredits }} Credits</span>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="space-y-6">
    <!-- Breadcrumbs -->
    <div class="text-[10px] sm:text-xs text-slate-400 dark:text-zinc-500 font-semibold uppercase tracking-wider">
        Instructor &nbsp;&gt;&nbsp; Advising Requests
    </div>

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
    @php
        $totalCount = count($advisingRequests);
        $pendingCount = count($advisingRequests->filter(fn($r) => strtolower($r->status) === 'pending'));
        $approvedCount = count($advisingRequests->filter(fn($r) => strtolower($r->status) === 'approved'));
        $rejectedCount = count($advisingRequests->filter(fn($r) => strtolower($r->status) === 'rejected'));
    @endphp
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
                    {{ $pendingCount }}
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
                    {{ $approvedCount }}
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
                <div class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1 leading-none">{{ $rejectedCount }}
                </div>
            </div>
        </flux:card>
    </div>

    <!-- Requests List Card -->
    <flux:card class="p-6">
        @if($totalCount > 0)
            <flux:table class="min-w-full">
                <flux:table.columns>
                    <flux:table.column>Student Details</flux:table.column>
                    {{-- <flux:table.column>Requested Courses</flux:table.column> --}}
                    <flux:table.column>Credit Workload</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column class="text-right">Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($advisingRequests as $request)
                        @php
                            $subjects = \App\Models\Subjects::whereIn('id', $request->subject_ids ?? [])->get();
                            $credits = $subjects->sum('credit_hours');
                        @endphp
                        <flux:table.row :key="$request->id" wire:click="showRequestDetails({{ $request->id }})"
                            class="cursor-pointer hover:bg-slate-50/50 dark:hover:bg-zinc-800/10 transition-colors">
                            <!-- Student Details -->
                            <flux:table.cell>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-xl bg-slate-50 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 flex items-center justify-center font-black text-xs border border-slate-150 dark:border-zinc-700 shrink-0 uppercase tracking-wider">
                                        {{ substr($request->student?->user?->name ?? 'S', 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900 dark:text-white leading-tight">
                                            {{ $request->student?->user?->name ?? 'N/A' }}
                                        </div>
                                        <div
                                            class="text-[10px] text-slate-400 dark:text-zinc-500 font-medium flex items-center gap-2 mt-0.5">
                                            <span>ID: {{ $request->student_id }}</span>
                                            <span class="text-slate-200 dark:text-zinc-800">•</span>
                                            <span
                                                class="max-w-[150px] truncate">{{ $request->student?->programme?->name ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </flux:table.cell>

                            <!-- Credit Workload -->
                            <flux:table.cell>
                                <div>
                                    <div class="text-xs text-slate-800 dark:text-zinc-200 font-bold">
                                        {{ $credits }} Credits
                                    </div>
                                    <div
                                        class="text-[9px] text-slate-400 dark:text-zinc-500 font-semibold uppercase mt-0.5 tracking-wider">
                                        {{ count($subjects) }} Subjects
                                    </div>
                                </div>
                            </flux:table.cell>

                            <!-- Status Badge -->
                            <flux:table.cell>
                                @php
                                    $status = strtolower($request->status);
                                    $color = $status === 'approved' ? 'emerald' : ($status === 'rejected' ? 'rose' : 'amber');
                                @endphp
                                <flux:badge size="sm" :color="$color" class="font-bold uppercase tracking-wider text-[9px]">
                                    {{ $request->status }}
                                </flux:badge>
                            </flux:table.cell>

                            <!-- Actions -->
                            <flux:table.cell class="text-right" wire:click.stop>
                                @if (strtolower($request->status) === 'pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <flux:button size="sm" variant="primary" icon="check"
                                            wire:confirm="Are you sure to approve this application"
                                            wire:click="approve({{ $request->id }})" class="font-semibold">
                                            Approve
                                        </flux:button>
                                        <flux:button size="sm" variant="danger" icon="x-mark"
                                            wire:confirm="Are you sure to reject this application"
                                            wire:click="reject({{ $request->id }})" class="font-semibold">
                                            Reject
                                        </flux:button>
                                    </div>
                                @else
                                    <flux:button size="sm" disabled class="font-semibold">{{ ucfirst($request->status) }}
                                    </flux:button>
                                @endif
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @else
            <div
                class="p-12 text-center bg-slate-50 dark:bg-zinc-900 border border-dashed border-slate-200 dark:border-zinc-800 rounded-3xl">
                <flux:icon name="inbox" class="w-10 h-10 text-slate-300 dark:text-zinc-600 mx-auto mb-3" />
                <h4 class="text-sm font-bold text-slate-900 dark:text-white">All Clear!</h4>
                <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">There are no pending advising requests to review.
                </p>
            </div>
        @endif
    </flux:card>

    <!-- Advising Request Details Modal -->
    <flux:modal name="advising-details-modal" wire:model="showDetailsModal" class="max-w-md">
        @if ($selectedRequest)
            <div class="space-y-6">
                <!-- Modal Header -->
                <div class="flex items-start justify-between">
                    <div>
                        <flux:heading size="lg">Advising Request Detail</flux:heading>
                        <flux:subheading>Submitted by {{ $selectedRequest->student?->user?->name }}</flux:subheading>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-zinc-800" />

                <!-- Details List -->
                <div class="space-y-4 text-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span
                                class="block text-xs font-semibold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">Student
                                ID</span>
                            <span
                                class="font-bold text-slate-800 dark:text-zinc-200">{{ $selectedRequest->student_id }}</span>
                        </div>
                        <div>
                            <span
                                class="block text-xs font-semibold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">Programme</span>
                            <span
                                class="font-bold text-slate-800 dark:text-zinc-200">{{ $selectedRequest->student?->programme?->name }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span
                                class="block text-xs font-semibold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">Status</span>
                            @php
                                $status = strtolower($selectedRequest->status);
                                $color = $status === 'approved' ? 'emerald' : ($status === 'rejected' ? 'rose' : 'amber');
                            @endphp
                            <flux:badge size="sm" :color="$color" class="font-bold uppercase text-[9px] mt-1">
                                {{ $selectedRequest->status }}
                            </flux:badge>
                        </div>
                        <div>
                            <span
                                class="block text-xs font-semibold text-slate-400 dark:text-zinc-500 uppercase tracking-wider">Requested
                                Date</span>
                            <span
                                class="font-semibold text-slate-800 dark:text-zinc-200">{{ $selectedRequest->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>

                    <!-- Selected Subjects -->
                    <div>
                        <span
                            class="block text-xs font-semibold text-slate-400 dark:text-zinc-500 uppercase tracking-wider mb-2">Requested
                            Subjects</span>
                        @php
                            $subjects = \App\Models\Subjects::whereIn('id', $selectedRequest->subject_ids ?? [])->get();
                        @endphp
                        <div
                            class="space-y-2 bg-slate-50 dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 p-3 rounded-xl">
                            @foreach($subjects as $sub)
                                <div class="flex justify-between items-center text-xs">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="font-bold px-1.5 py-0.5 bg-slate-200 dark:bg-zinc-800 text-slate-800 dark:text-zinc-300 rounded uppercase font-mono text-[9px]">
                                            {{ $sub->code }}
                                        </span>
                                        <span class="font-semibold text-slate-700 dark:text-zinc-300">{{ $sub->name }}</span>
                                    </div>
                                    <span
                                        class="text-[10px] text-slate-400 dark:text-zinc-500 shrink-0">{{ $sub->credit_hours }}
                                        Credits</span>
                                </div>
                            @endforeach
                            <hr class="border-slate-200 dark:border-zinc-800 my-1" />
                            <div class="flex justify-between text-xs font-bold text-slate-900 dark:text-white pt-1">
                                <span>Total Credits</span>
                                <span>{{ $subjects->sum('credit_hours') }} Credits</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-2 pt-4">

                    @if(strtolower($selectedRequest->status) === 'pending')
                        <div class="flex items-center justify-end gap-2">
                            <flux:button size="sm" variant="primary" icon="check"
                                wire:confirm="Are you sure to approve this application?" wire:click="approve({{ $selectedRequest->id }})"
                                class="font-semibold">
                                Approve
                            </flux:button>
                            <flux:button size="sm" variant="danger" icon="x-mark"
                                wire:confirm="Are you sure to reject this application?" wire:click="reject({{ $selectedRequest->id }})"
                                class="font-semibold">
                                Reject
                            </flux:button>
                        </div>
                    @else
                        <flux:modal.close>
                            <flux:button variant="filled">Close</flux:button>
                        </flux:modal.close>
                    @endif
                </div>
            </div>
        @endif
    </flux:modal>

</div>
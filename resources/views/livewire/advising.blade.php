<div class="space-y-6">
    <!-- Breadcrumbs -->
    <div class="text-[10px] sm:text-xs text-slate-400 dark:text-zinc-500 font-semibold uppercase tracking-wider">
        Academic &nbsp;&gt;&nbsp; Advising
    </div>

    <!-- Page Title -->
    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Academic Advising</h1>

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

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">
        <!-- Left Column: Student Details & Advising Summary (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Student Information Card -->
            <flux:card class="p-6 space-y-6">
                <div class="flex gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-zinc-800 flex items-center justify-center shrink-0 border border-slate-100 dark:border-zinc-700">
                        <flux:icon name="user" variant="outline" class="w-5 h-5 text-slate-600 dark:text-zinc-400" />
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white leading-tight">Student Profile</h3>
                        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Current academic enrollment details.
                        </p>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-zinc-800" />

                <!-- Profile details -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center font-bold text-slate-700 dark:text-zinc-300 uppercase text-xs">
                            {{ auth()->user()->initials() }}
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-slate-500 dark:text-zinc-400">Name</div>
                            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ auth()->user()->name }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs font-semibold text-slate-500 dark:text-zinc-400">Student ID</div>
                            <div class="text-sm font-bold text-slate-900 dark:text-white">
                                {{ auth()->user()->student?->student_id ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-slate-500 dark:text-zinc-400">Programme</div>
                            <div class="text-sm font-bold text-slate-900 dark:text-white">
                                {{ auth()->user()->student?->programme?->name ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-zinc-800" />

                <!-- Advisor details -->
                <div class="space-y-3">
                    <div class="text-xs font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">
                        Assigned Advisor</div>
                    @if($advisor)
                        <div
                            class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 rounded-xl">
                            <flux:icon name="academic-cap" class="w-5 h-5 text-indigo-500" />
                            <div>
                                <div class="text-xs font-bold text-slate-950 dark:text-white">{{ $advisor->user?->name }}
                                </div>
                                <div class="text-[10px] text-slate-400 dark:text-zinc-500">{{ $advisor->user?->email }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div
                            class="p-3 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900 text-amber-800 dark:text-amber-400 rounded-xl flex items-center gap-2">
                            <flux:icon name="exclamation-circle" class="w-4 h-4 shrink-0 mt-0.5" />
                            <div class="text-[11px] font-medium leading-normal">No advisor has been assigned yet. Please
                                contact faculty administration.</div>
                        </div>
                    @endif
                </div>
            </flux:card>

            <!-- Selection Summary Card (Computed dynamically in Blade) -->
            @php
                $selectedCollection = $subjectForProgrammeEnrolled->whereIn('id', $applySubject);
                $totalCredits = $selectedCollection->sum('credit_hours');
                $totalFee = $selectedCollection->sum('fee');
            @endphp
            @if(count($applySubject) > 0)
                <flux:card class="p-6 space-y-4 bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800">
                    <h4 class="text-xs font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider">Advising
                        Summary</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs text-slate-600 dark:text-zinc-400">
                            <span>Selected Subjects:</span>
                            <span class="font-bold text-slate-900 dark:text-white">{{ count($applySubject) }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-600 dark:text-zinc-400">
                            <span>Total Credit Hours:</span>
                            <span class="font-bold text-slate-900 dark:text-white">{{ $totalCredits }} Credits</span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-600 dark:text-zinc-400">
                            <span>Total Tuition Fee:</span>
                            <span class="font-bold text-slate-900 dark:text-white">RM
                                {{ number_format($totalFee, 2) }}</span>
                        </div>
                    </div>

                    @if($totalCredits < 3)
                        <div
                            class="p-2.5 bg-amber-50 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900 rounded-lg text-[10px] text-amber-700 dark:text-amber-400 leading-normal">
                            <strong>Note:</strong> Minimum recommended workload is 3 credit hours.
                        </div>
                    @elseif($totalCredits > 24)
                        <div
                            class="p-2.5 bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900 rounded-lg text-[10px] text-rose-700 dark:text-rose-400 leading-normal">
                            <strong>Warning:</strong> Exceeds maximum workload of 24 credit hours.
                        </div>
                    @else
                        <div
                            class="p-2.5 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900 rounded-lg text-[10px] text-emerald-700 dark:text-emerald-400 leading-normal">
                            Workload is within normal academic limits.
                        </div>
                    @endif
                </flux:card>
            @endif
        </div>

        <!-- Right Column: Available Subjects & Checklist (3 cols) -->
        <flux:card class="lg:col-span-3 p-6 space-y-6">
            <div class="flex justify-between items-center">
                <div class="flex gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-zinc-800 flex items-center justify-center shrink-0 border border-slate-100 dark:border-zinc-700">
                        <flux:icon name="book-open" variant="outline"
                            class="w-5 h-5 text-slate-600 dark:text-zinc-400" />
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white leading-tight">Subjects Checklist
                        </h3>
                        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Select the subjects you want to enroll
                            in for this advising session.</p>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100 dark:border-zinc-800" />

            <form wire:submit.prevent="submit" class="space-y-6">
                @php
                    $hasPending = App\Models\advising::where('student_id', auth()->user()->student->student_id)
                        ->where('status', 'Pending')
                        ->exists();
                @endphp
                @if($hasPending)
                    <div
                        class="p-2.5 bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900 rounded-lg text-[15px] text-rose-700 dark:text-rose-400 leading-normal">
                        Your advising request is pending. Button will be disabled temporarily
                    </div>
                @endif
                @if($subjectForProgrammeEnrolled->count() > 0)
                    <div class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @foreach($subjectForProgrammeEnrolled as $subject)
                            <div
                                class="py-4 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-zinc-800/20 px-2 rounded-xl transition-all duration-150">
                                @php
                                    $isEnrolled = $subjectAlreadyEnrolled->contains('subject_id', $subject->id);
                                @endphp
                                <div class="flex items-center gap-3">
                                    @if($isEnrolled)
                                        <flux:checkbox checked disabled value="{{ $subject->id }}" />
                                    @else
                                        <flux:checkbox wire:model.live="applySubject" :disabled="$hasPending" value="{{ $subject->id }}" />
                                    @endif
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span
                                                class="text-[10px] font-bold px-2 py-0.5 bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 rounded uppercase tracking-wider">
                                                {{ $subject->code }}
                                            </span>
                                            <span class="text-sm font-semibold text-slate-900 dark:text-white">
                                                {{ $subject->name }}
                                            </span>
                                        </div>
                                        <div class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1 flex items-center gap-1">
                                            <flux:icon name="user" class="w-3 h-3" />
                                            <span>Instructor: {{ $subject->instructor?->user?->name ?? 'TBA' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="text-xs font-bold text-slate-800 dark:text-zinc-200">
                                        {{ $subject->credit_hours }} Credits
                                    </div>
                                    <div class="text-[10px] text-slate-400 dark:text-zinc-500">
                                        RM {{ number_format($subject->fee, 2) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Validation Errors -->
                    @error('applySubject')
                        <div class="text-xs text-rose-500 font-semibold">{{ $message }}</div>
                    @enderror

                    <!-- Submit Button Row -->
                    <div class="flex justify-end pt-4">
                        @php
                            $hasPending = App\Models\advising::where('student_id', auth()->user()->student->student_id)
                                ->where('status', 'Pending')
                                ->exists();
                        @endphp
                        <button type="submit" @disabled($hasPending)
                            class="bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold rounded-lg px-6 py-2.5 text-xs transition-all shadow-sm flex items-center gap-2 ">
                            <span>Submit Advising Request</span>
                            <div wire:loading wire:target="submit"
                                class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin">
                            </div>
                        </button>
                    </div>
                @else
                    <div
                        class="p-8 text-center bg-slate-50 dark:bg-zinc-900 border border-dashed border-slate-200 dark:border-zinc-800 rounded-2xl">
                        <flux:icon name="book-open" class="w-8 h-8 text-slate-300 dark:text-zinc-600 mx-auto mb-3" />
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">No Subjects Available</h4>
                        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">There are no subjects listed under your
                            enrolled programme.</p>
                    </div>
                @endif
            </form>
        </flux:card>
    </div>
</div>
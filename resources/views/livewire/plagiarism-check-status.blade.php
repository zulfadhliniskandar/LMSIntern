<div class="space-y-6">
    <!-- Plagiarism Analysis Card -->
    <div class="bg-white dark:bg-gray-800/90 rounded-2xl border border-slate-200 dark:border-gray-700/80 p-5 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="p-2 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-xl shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">Plagiarism & Text Similarity</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Automated text comparison across student submissions</p>
                </div>
            </div>

            @if($check && $check->status === 'completed')
                @php
                    $percent = $check->similarity_percentage;
                    $badgeStyle = $percent < 15 
                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-400 dark:border-emerald-800' 
                        : ($percent < 40 
                            ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/50 dark:text-amber-400 dark:border-amber-800' 
                            : 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/50 dark:text-rose-400 dark:border-rose-800');
                @endphp
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl border {{ $badgeStyle }} text-xs font-bold shrink-0">
                    <span class="w-2 h-2 rounded-full {{ $percent < 15 ? 'bg-emerald-500' : ($percent < 40 ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                    <span>{{ $percent }}% Similarity Match</span>
                </div>
            @endif
        </div>

        @if(!$check || $check->status === 'pending' || $check->status === 'checking')
            <div class="p-4 bg-indigo-50/50 dark:bg-indigo-950/30 rounded-xl border border-indigo-100 dark:border-indigo-900/40 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <svg class="animate-spin h-5 w-5 text-indigo-600 dark:text-indigo-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <div class="text-xs">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block">Scanning files for similarity...</span>
                        <span class="text-slate-500 dark:text-slate-400">Extracting text and comparing with database</span>
                    </div>
                </div>
                <script>
                    setTimeout(() => @this.dispatch('refreshCheck'), 3000);
                </script>
            </div>

        @elseif($check->status === 'completed')
            @php
                $percent = $check->similarity_percentage;
                $barColor = $percent < 15 ? 'bg-emerald-500' : ($percent < 40 ? 'bg-amber-500' : 'bg-rose-500');
                $alertBg = $percent < 15 
                    ? 'bg-emerald-50/70 text-emerald-800 border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:border-emerald-900/50' 
                    : ($percent < 40 
                        ? 'bg-amber-50/70 text-amber-800 border-amber-200 dark:bg-amber-950/30 dark:text-amber-300 dark:border-amber-900/50' 
                        : 'bg-rose-50/70 text-rose-800 border-rose-200 dark:bg-rose-950/30 dark:text-rose-300 dark:border-rose-900/50');
            @endphp

            <!-- Visual Progress Bar -->
            <div class="space-y-1.5">
                <div class="w-full bg-slate-100 dark:bg-gray-700/60 rounded-full h-3 overflow-hidden p-0.5">
                    <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ min(100, max(4, $percent)) }}%"></div>
                </div>
                <div class="flex justify-between text-[11px] font-semibold text-slate-400 dark:text-slate-500 px-0.5">
                    <span>0% (Original)</span>
                    <span>15%</span>
                    <span>40%</span>
                    <span>100% (Duplicate)</span>
                </div>
            </div>

            <!-- Result Status Box -->
            <div class="p-3.5 rounded-xl border {{ $alertBg }} text-xs font-semibold flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    @if($percent < 15)
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>High Originality: Low similarity detected ({{ $percent }}%)</span>
                    @elseif($percent < 40)
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Moderate Similarity: {{ $percent }}% text matches existing submissions</span>
                    @else
                        <svg class="w-4 h-4 text-rose-600 dark:text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>High Similarity Warning: {{ $percent }}% match detected! Review suggested.</span>
                    @endif
                </div>
                <button type="button" wire:click="retryCheck" title="Re-scan document" class="text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 p-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>

            <!-- Matched Submissions Breakdown -->
            @if(!empty($check->matched_with) && count($check->matched_with) > 0)
                <div class="space-y-2 pt-1 border-t border-slate-100 dark:border-gray-700/80">
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">
                        Matched Submissions ({{ count($check->matched_with) }})
                    </span>

                    <div class="space-y-2">
                        @foreach($check->matched_with as $match)
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-gray-900/60 rounded-xl border border-slate-200/80 dark:border-gray-700/60 text-xs">
                                <div class="flex items-center gap-2.5 truncate pr-2">
                                    <div class="p-1.5 bg-slate-200 dark:bg-gray-700 text-slate-600 dark:text-slate-300 rounded-lg shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="truncate">
                                        <span class="font-bold text-slate-800 dark:text-white block truncate">{{ $match['student_name'] }}</span>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500">
                                            Submitted {{ !empty($match['submitted_at']) ? \Carbon\Carbon::parse($match['submitted_at'])->diffForHumans() : 'N/A' }}
                                        </span>
                                    </div>
                                </div>

                                <span class="px-2.5 py-1 rounded-lg text-xs font-extrabold shrink-0 {{ $match['similarity'] >= 30 ? 'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300' }}">
                                    {{ $match['similarity'] }}% Match
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        @elseif($check->status === 'failed')
            <div class="p-3.5 bg-rose-50/70 dark:bg-rose-950/30 rounded-xl border border-rose-200 dark:border-rose-900/50 text-xs text-rose-800 dark:text-rose-300 flex items-center justify-between gap-3">
                <div>
                    <span class="font-bold block">Plagiarism analysis could not be completed</span>
                    <span class="text-[11px] opacity-80 block">{{ $check->notes ?? 'File format may not contain readable text.' }}</span>
                </div>
                <button type="button" wire:click="retryCheck" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition-colors shrink-0">
                    Retry
                </button>
            </div>
        @endif
    </div>

    <!-- AI Content Detection Report Card -->
    <div class="bg-white dark:bg-gray-800/90 rounded-2xl border border-slate-200 dark:border-gray-700/80 p-5 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="p-2 bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 rounded-xl shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        AI Content Detection Report
                        @if($check && $check->ai_detector)
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300 uppercase">
                                {{ str_replace('_', ' ', $check->ai_detector) }}
                            </span>
                        @endif
                    </h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Deep learning NLP heuristics & LLM probability analysis</p>
                </div>
            </div>

            @if($check && $check->ai_status === 'completed')
                @php
                    $aiProb = floatval($check->ai_probability);
                    $aiBadgeStyle = $aiProb < 25 
                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-400 dark:border-emerald-800' 
                        : ($aiProb < 60 
                            ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/50 dark:text-amber-400 dark:border-amber-800' 
                            : 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/50 dark:text-rose-400 dark:border-rose-800');
                @endphp
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl border {{ $aiBadgeStyle }} text-xs font-bold shrink-0">
                    <span class="w-2 h-2 rounded-full {{ $aiProb < 25 ? 'bg-emerald-500' : ($aiProb < 60 ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                    <span>{{ $aiProb }}% AI Probability</span>
                </div>
            @endif
        </div>

        @if(!$check || $check->ai_status === 'pending' || $check->ai_status === 'checking')
            <div class="p-4 bg-purple-50/50 dark:bg-purple-950/30 rounded-xl border border-purple-100 dark:border-purple-900/40 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <svg class="animate-spin h-5 w-5 text-purple-600 dark:text-purple-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <div class="text-xs">
                        <span class="font-bold text-slate-800 dark:text-slate-200 block">Analyzing text structure for AI generation patterns...</span>
                        <span class="text-slate-500 dark:text-slate-400">Checking entropy, perplexity, burstiness, and LLM phrases</span>
                    </div>
                </div>
            </div>

        @elseif($check->ai_status === 'completed')
            @php
                $aiProb = floatval($check->ai_probability);
                $humanProb = round(100 - $aiProb, 2);
                $aiBarColor = $aiProb < 25 ? 'bg-emerald-500' : ($aiProb < 60 ? 'bg-amber-500' : 'bg-rose-500');
                $aiDetails = $check->ai_details ?? [];
                $aiModelDetection = $check->aiDetection;
                $detectedModels = $aiModelDetection?->detected_models ?? ($aiDetails['detected_models'] ?? []);
            @endphp

            <!-- AI vs Human Dual Progress Gauge -->
            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300">
                    <span class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Human Written: {{ $humanProb }}%
                    </span>
                    <span class="flex items-center gap-1.5 text-rose-600 dark:text-rose-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        AI Generated: {{ $aiProb }}%
                    </span>
                </div>

                <div class="w-full bg-slate-100 dark:bg-gray-700/60 rounded-full h-3.5 flex overflow-hidden p-0.5">
                    <div class="bg-emerald-500 h-2.5 rounded-l-full transition-all duration-500" style="width: {{ $humanProb }}%"></div>
                    <div class="{{ $aiBarColor }} h-2.5 rounded-r-full transition-all duration-500" style="width: {{ $aiProb }}%"></div>
                </div>
            </div>

            <!-- AI Assessment Banner -->
            <div class="p-3.5 rounded-xl border text-xs font-semibold flex items-center justify-between gap-2 {{ $aiProb < 25 ? 'bg-emerald-50/70 text-emerald-800 border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:border-emerald-900/50' : ($aiProb < 60 ? 'bg-amber-50/70 text-amber-800 border-amber-200 dark:bg-amber-950/30 dark:text-amber-300 dark:border-amber-900/50' : 'bg-rose-50/70 text-rose-800 border-rose-200 dark:bg-rose-950/30 dark:text-rose-300 dark:border-rose-900/50') }}">
                <div class="flex items-center gap-2">
                    @if($aiProb < 25)
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Human Authenticity Verified: Text appears to be written by a human ({{ $humanProb }}% Human score).</span>
                    @elseif($aiProb < 60)
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Mixed Content Flag: {{ $aiProb }}% probability of AI assistance or generated sentences.</span>
                    @else
                        <svg class="w-4 h-4 text-rose-600 dark:text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>High AI Content Alert: {{ $aiProb }}% probability of text generated by an AI model!</span>
                    @endif
                </div>

                <button type="button" wire:click="retryCheck" title="Re-analyze AI probability" class="text-slate-400 hover:text-purple-600 dark:hover:text-purple-400 p-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>

            <!-- Detected LLM Models & Heuristic Metrics Grid -->
            @if(!empty($detectedModels) || !empty($aiDetails))
                <div class="space-y-3 pt-2 border-t border-slate-100 dark:border-gray-700/80">
                    @if(!empty($detectedModels))
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                Potential Model Signatures:
                            </span>
                            @foreach($detectedModels as $model)
                                <span class="px-2.5 py-0.5 text-xs font-extrabold rounded-lg bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300 uppercase">
                                    {{ $model }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($aiDetails) && is_array($aiDetails))
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
                            <div class="p-2.5 bg-slate-50 dark:bg-gray-900/60 rounded-xl border border-slate-200/60 dark:border-gray-700/60">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Perplexity Score</span>
                                <span class="font-extrabold text-slate-800 dark:text-slate-200">
                                    {{ isset($aiDetails['perplexity']) ? round($aiDetails['perplexity'], 1) : 'N/A' }}
                                </span>
                            </div>
                            <div class="p-2.5 bg-slate-50 dark:bg-gray-900/60 rounded-xl border border-slate-200/60 dark:border-gray-700/60">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Burstiness Score</span>
                                <span class="font-extrabold text-slate-800 dark:text-slate-200">
                                    {{ isset($aiDetails['burstiness']) ? round($aiDetails['burstiness'], 1) : 'N/A' }}
                                </span>
                            </div>
                            <div class="p-2.5 bg-slate-50 dark:bg-gray-900/60 rounded-xl border border-slate-200/60 dark:border-gray-700/60">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Vocab Richness</span>
                                <span class="font-extrabold text-slate-800 dark:text-slate-200">
                                    {{ isset($aiDetails['vocab_richness']) ? round($aiDetails['vocab_richness'], 1) : 'N/A' }}
                                </span>
                            </div>
                            <div class="p-2.5 bg-slate-50 dark:bg-gray-900/60 rounded-xl border border-slate-200/60 dark:border-gray-700/60">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">AI Phrases Score</span>
                                <span class="font-extrabold text-slate-800 dark:text-slate-200">
                                    {{ isset($aiDetails['ai_phrases']) ? round($aiDetails['ai_phrases'], 1) : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

        @elseif($check->ai_status === 'not_checked' || $check->ai_status === 'failed')
            <div class="p-3.5 bg-slate-50 dark:bg-gray-900/40 rounded-xl border border-slate-200 dark:border-gray-700 text-xs flex items-center justify-between gap-3">
                <span class="text-slate-600 dark:text-slate-400">AI detection has not been calculated or completed yet.</span>
                <button type="button" wire:click="retryCheck" class="px-3.5 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition-colors shrink-0">
                    Run AI Detection
                </button>
            </div>
        @endif
    </div>
</div>
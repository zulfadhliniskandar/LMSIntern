<div class="p-6 max-w-4xl mx-auto space-y-6">
    <!-- Back Button & Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('instructor-course-details', $subject->id) }}" wire:navigate
            class="inline-flex items-center text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            <svg class="w-5 h-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to {{ $subject->name }}
        </a>
    </div>

    <!-- Form Container Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <!-- Header Banner -->
        <div
            class="p-6 bg-gradient-to-r from-indigo-50/50 via-purple-50/30 to-transparent dark:from-gray-800 dark:to-gray-800 border-b border-gray-100 dark:border-gray-700 flex items-start justify-between">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span
                        class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                        {{ $subject->code }}
                    </span>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">New Resource</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white">
                    Add Course Material
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Upload multiple files and add multiple resource links for <strong>{{ $subject->name }}</strong>.
                </p>
            </div>
        </div>

        <!-- Form Body -->
        <form wire:submit.prevent="addMaterial" class="p-6 space-y-6">
            <!-- Title -->
            <div class="space-y-1.5">
                <label for="title"
                    class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                    Material Title <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="title" wire:model="title" placeholder="e.g. Week 1: Lecture Notes & Resources"
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" />
                @error('title')
                    <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="space-y-1.5">
                <label for="description"
                    class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                    Description <span class="text-slate-400 font-normal text-xs">(Optional)</span>
                </label>
                <textarea id="description" wire:model="description" rows="2"
                    placeholder="Brief summary of what these materials cover..."
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-none"></textarea>
                @error('description')
                    <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Instructions -->
            <div class="space-y-1.5">
                <label for="instruction"
                    class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                    Instructions for Students <span class="text-slate-400 font-normal text-xs">(Optional)</span>
                </label>
                <textarea id="instruction" wire:model="instruction" rows="2"
                    placeholder="e.g. Download all files and complete reading assignment..."
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-none"></textarea>
                @error('instruction')
                    <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- File Upload Section (Multiple Files) -->
            <div class="space-y-3 pt-2">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                    Upload Files <span class="text-slate-400 font-normal text-xs">(Select one or multiple files)</span>
                </label>

                <div
                    class="relative border-2 border-dashed border-slate-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-400 rounded-2xl p-6 transition-colors bg-slate-50/50 dark:bg-gray-900/30 text-center">
                    <input type="file" wire:model="newFiles" multiple accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                    <div class="flex flex-col items-center justify-center space-y-2 pointer-events-none">
                        <div
                            class="p-3 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-full">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                            <span>Click to select files <span class="font-normal text-slate-500">or drag and drop</span></span>
                        </div>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500">PDF, DOCX, PPTX, ZIP up to 10MB each</p>
                    </div>
                </div>

                <!-- Livewire Loading State -->
                <div wire:loading wire:target="newFiles"
                    class="flex items-center gap-2 text-xs text-indigo-600 dark:text-indigo-400 font-medium">
                    <svg class="animate-spin h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Uploading selected files...
                </div>

                <!-- Selected Files List -->
                @if(!empty($files))
                    <div class="space-y-2 pt-1">
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Selected Files ({{ count($files) }})</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($files as $index => $file)
                                <div
                                    class="flex items-center justify-between p-2.5 bg-slate-50 dark:bg-gray-900/60 rounded-xl border border-slate-200 dark:border-gray-700/60 text-xs">
                                    <div class="flex items-center gap-2 truncate pr-2">
                                        <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span class="font-medium text-slate-700 dark:text-slate-300 truncate">{{ $file->getClientOriginalName() }}</span>
                                    </div>
                                    <button type="button" wire:click="removeFile({{ $index }})" class="text-slate-400 hover:text-rose-500 transition-colors p-1" title="Remove File">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @error('newFiles.*')
                    <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- External Links Section (Multiple Links) -->
            <div class="space-y-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                        External Links <span class="text-slate-400 font-normal text-xs">(Add website or video
                            links)</span>
                    </label>
                    <button type="button" wire:click="addLink"
                        class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Add Another Link</span>
                    </button>
                </div>

                <div class="space-y-3">
                    @foreach($links as $index => $linkItem)
                        <div
                            class="flex items-start gap-2 bg-slate-50/50 dark:bg-gray-900/30 p-3 rounded-xl border border-slate-200 dark:border-gray-700/60">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 flex-grow">
                                <div>
                                    <input type="text" wire:model="links.{{ $index }}.title"
                                        placeholder="Link Title (e.g. Reference Docs)"
                                        class="w-full px-3 py-2 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-lg text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                                </div>
                                <div>
                                    <input type="url" wire:model="links.{{ $index }}.url" placeholder="https://example.com"
                                        class="w-full px-3 py-2 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-lg text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                                </div>
                            </div>

                            @if(count($links) > 1)
                                <button type="button" wire:click="removeLink({{ $index }})"
                                    class="mt-1 p-1.5 text-slate-400 hover:text-rose-500 transition-colors" title="Remove Link">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>

                @error('links.*.url')
                    <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions Footer -->
            <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
                <a href="{{ route('instructor-course-details', $subject->id) }}" wire:navigate
                    class="px-5 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-gray-700 rounded-xl transition-colors">
                    Cancel
                </a>
                <button type="submit" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold rounded-xl shadow-xs transition-all cursor-pointer disabled:opacity-50">
                    <svg wire:loading.remove wire:target="addMaterial" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <svg wire:loading wire:target="addMaterial" class="animate-spin h-4 w-4 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span>Save Material</span>
                </button>
            </div>
        </form>
    </div>
</div>
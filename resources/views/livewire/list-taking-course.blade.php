<div class="p-6 max-w-7xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Subjects Taking</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Here are the subjects you are currently enrolled in.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($subjects as $taking)
            @php $subjectItem = $taking->subject; @endphp
            @if ($subjectItem)
                <a href="{{ route('student-course-details', $subjectItem->id) }}" wire:navigate
                    class="group relative flex flex-col bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 hover:-translate-y-1">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-900 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>

                    <div class="relative flex flex-col h-full p-6 z-10">
                        <div class="flex items-center justify-between mb-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                                {{ $subjectItem->code ?? 'Course' }}
                            </span>
                            @if($subjectItem->credit_hours)
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ $subjectItem->credit_hours }} Credits
                                </span>
                            @endif
                        </div>

                        <h3
                            class="text-xl font-bold text-gray-900 dark:text-white mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">
                            {{ $subjectItem->name }}
                        </h3>

                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-3 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Instructor: <strong
                                    class="text-slate-700 dark:text-slate-300 font-semibold">{{ $subjectItem->instructor->user->name ?? 'TBA' }}</strong></span>
                        </p>

                        @if($subjectItem->description)
                            <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-3 mb-4 flex-grow">
                                {{ $subjectItem->description }}
                            </p>
                        @else
                            <div class="flex-grow mb-4"></div>
                        @endif

                        <div
                            class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <div class="flex items-center text-sm text-indigo-600 dark:text-indigo-400 font-medium">
                                View Details
                                <svg class="ml-1 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="h-1.5 w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-500 absolute bottom-0 left-0">
                    </div>
                </a>
            @endif
        @empty
            <div
                class="col-span-full flex flex-col items-center justify-center p-12 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 shadow-sm">
                <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800/50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No courses enrolled</h3>
                <p class="mt-1 text-gray-500 dark:text-gray-400">You are not taking any courses at the moment.</p>
            </div>
        @endforelse
    </div>
</div>
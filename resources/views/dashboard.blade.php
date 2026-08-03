<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="relative flex flex-col h-[260px] p-5 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-sm transition-all duration-300 hover:shadow-md">
                @auth

                    <!-- Card Header -->
                    <div
                        class="flex items-center justify-between border-b border-neutral-100 dark:border-neutral-800 pb-3 mb-3">
                        <div class="flex items-center gap-2">
                            <div
                                class="p-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400">
                                <svg class="size-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-neutral-800 dark:text-neutral-200">Notifications</h3>
                        </div>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span
                                class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-semibold text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300">
                                {{ auth()->user()->unreadNotifications->count() }} new
                            </span>
                        @endif
                    </div>

                    <!-- Scrollable Notification List -->
                    <div class="flex-1 overflow-y-auto space-y-2.5 pr-1">
                        @forelse (auth()->user()->unreadNotifications as $notification)
                            @php
                                $isApproved = str_contains($notification->data['title'] ?? '', 'Approved');
                                $isRejected = str_contains($notification->data['title'] ?? '', 'Rejected');
                            @endphp
                            <div
                                class="flex gap-3 p-3 rounded-lg border border-neutral-100 dark:border-neutral-800 bg-neutral-50/50 dark:bg-neutral-800/40 hover:bg-neutral-50 dark:hover:bg-neutral-800/80 transition-colors">
                                <!-- Status Icon -->
                                <div class="flex-shrink-0 mt-0.5">
                                    @if($isApproved)
                                        <div
                                            class="p-1 rounded-full bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400">
                                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="3"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </div>
                                    @elseif($isRejected)
                                        <div class="p-1 rounded-full bg-rose-50 dark:bg-rose-950 text-rose-600 dark:text-rose-400">
                                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="3"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="p-1 rounded-full bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400">
                                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11.25 11.25l.041-.02a.75.75 0 111.085 1.085l-.04.02-.041.02a.75.75 0 00-.08.83l.042.02a.75.75 0 11-1.085-1.085l.04-.02.041-.02a.75.75 0 00.08-.83l-.042-.02zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Text Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-neutral-800 dark:text-neutral-200 truncate">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </p>
                                    <p
                                        class="text-[11px] text-neutral-500 dark:text-neutral-400 mt-0.5 line-clamp-2 leading-relaxed">
                                        {{ $notification->data['body'] ?? '' }}
                                    </p>
                                    <span class="text-[10px] text-neutral-400 dark:text-neutral-500 mt-1 block">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <!-- Empty State -->
                            <div class="flex flex-col items-center justify-center h-full py-6 text-center">
                                <svg class="size-8 text-neutral-300 dark:text-neutral-600 mb-2" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.143 17.082a24.248 24.248 0 003.844.148m-3.844-.148a23.856 23.856 0 01-5.455-1.31 8.961 8.961 0 002.3-5.541V9.75a9 9 0 00-2.33 5.962m10.029-1.312c1.73-.64 3.56-1.085 5.455-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022m13.312-6.022L15 9.75M8.25 15h7.5" />
                                </svg>
                                <p class="text-xs font-medium text-neutral-500 dark:text-neutral-400">All caught up!</p>
                                <p class="text-[10px] text-neutral-400 dark:text-neutral-500 mt-0.5">No unread notifications.
                                </p>
                            </div>
                        @endforelse
                    </div>
                @endauth
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts::app>
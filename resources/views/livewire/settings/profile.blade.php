<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profile settings') }}</flux:heading>

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your profile information and passport photo')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <!-- Passport Photo Section -->
            <div class="space-y-3">
                <label class="block text-sm font-semibold text-slate-900 dark:text-white">
                    {{ __('Passport Photo') }}
                </label>

                <div class="flex flex-wrap items-center gap-5">
                    <!-- Photo Preview Container -->
                    <div class="w-24 h-24 rounded-2xl bg-indigo-600 dark:bg-indigo-500 text-white font-bold text-2xl flex items-center justify-center border-4 border-white dark:border-zinc-800 shadow-md uppercase overflow-hidden shrink-0">
                        @if ($passport_photo)
                            <img src="{{ $passport_photo->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover" />
                        @elseif ($current_passport_photo)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($current_passport_photo) }}" alt="Passport Photo" class="w-full h-full object-cover" />
                        @else
                            {{ auth()->user()->initials() }}
                        @endif
                    </div>

                    <!-- Upload Controls -->
                    <div class="space-y-2">
                        <input type="file" wire:model="passport_photo" id="passport_photo_input" class="hidden" accept="image/*" />
                        
                        <div class="flex items-center gap-2">
                            <label for="passport_photo_input" class="inline-flex items-center gap-2 px-3.5 py-2 bg-indigo-50 dark:bg-indigo-950/60 hover:bg-indigo-100 dark:hover:bg-indigo-900/80 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 rounded-xl text-xs font-bold transition-all cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <span>{{ $current_passport_photo || $passport_photo ? __('Change Photo') : __('Upload Photo') }}</span>
                            </label>

                            @if ($current_passport_photo || $passport_photo)
                                <button type="button" wire:click="removePassportPhoto" wire:confirm="Are you sure you want to remove your passport photo?" class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 rounded-xl text-xs font-bold transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span>{{ __('Remove') }}</span>
                                </button>
                            @endif
                        </div>

                        <p class="text-[11px] text-slate-500 dark:text-slate-400">
                            {{ __('Supported formats: JPG, PNG. Maximum file size: 3MB.') }}
                        </p>
                        @error('passport_photo') <span class="text-xs text-rose-500 font-semibold block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:settings.delete-user-form />
        @endif
    </x-settings.layout>
</section>

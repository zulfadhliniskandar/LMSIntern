<div class="space-y-6">
    <!-- Breadcrumbs -->
    <div class="text-[10px] sm:text-xs text-slate-400 dark:text-zinc-500 font-semibold uppercase tracking-wider">
        Instructor Registration &nbsp;&gt;&nbsp; Apply
    </div>

    <!-- Page Title -->
    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Instructor Application</h1>

    @if (session()->has('success'))
        <div
            class="p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in">
            <flux:icon name="check-circle" class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" />
            <div class="text-sm font-semibold">{{ session('success') }}</div>
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">
            <!-- Left Card: Personal & Contact Info (3 cols) -->
            <flux:card class="lg:col-span-3 p-6 space-y-6">
                <div class="flex gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-zinc-800 flex items-center justify-center shrink-0 border border-slate-100 dark:border-zinc-700">
                        <flux:icon name="user" variant="outline" class="w-5 h-5 text-slate-600 dark:text-zinc-400" />
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white leading-tight">Personal & Contact Details</h3>
                        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">General personal details and contact information for the instructor applicant.</p>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-zinc-800" />

                <!-- Full Name -->
                <flux:field>
                    <flux:label class="font-bold text-xs text-slate-800 dark:text-zinc-200">Full Name <span
                            class="text-rose-500">*</span></flux:label>
                    <flux:input wire:model="full_name" icon="user" placeholder="e.g. Dr. Jane Smith" />
                    <flux:description class="text-[10px] text-slate-400 dark:text-zinc-500">Applicant's official full name.</flux:description>
                    <flux:error name="full_name" />
                </flux:field>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Email Address -->
                    <flux:field>
                        <flux:label class="font-bold text-xs text-slate-800 dark:text-zinc-200">Email Address <span
                                class="text-rose-500">*</span></flux:label>
                        <flux:input wire:model="email" icon="envelope" type="email" placeholder="jane.smith@example.com"
                            value="{{ $email }}" {{ $email ? 'disabled' : '' }} />
                        <flux:description class="text-[10px] text-slate-400 dark:text-zinc-500">Primary communication email address.</flux:description>
                        <flux:error name="email" />
                    </flux:field>

                    <!-- Phone Number -->
                    <flux:field>
                        <flux:label class="font-bold text-xs text-slate-800 dark:text-zinc-200">Phone Number <span
                                class="text-rose-500">*</span></flux:label>
                        <flux:input wire:model="phone" icon="phone" placeholder="e.g. 0123456789" />
                        <flux:description class="text-[10px] text-slate-400 dark:text-zinc-500">Active contact number.</flux:description>
                        <flux:error name="phone" />
                    </flux:field>
                </div>

                <!-- Address -->
                <flux:field>
                    <flux:label class="font-bold text-xs text-slate-800 dark:text-zinc-200">Address <span
                            class="text-rose-500">*</span></flux:label>
                    <flux:input wire:model="address" icon="map-pin" placeholder="e.g. No 45, Residency Heights" />
                    <flux:description class="text-[10px] text-slate-400 dark:text-zinc-500">Current residential address.</flux:description>
                    <flux:error name="address" />
                </flux:field>
            </flux:card>

            <!-- Right Card: Professional & Identity Profile (2 cols) -->
            <flux:card class="lg:col-span-2 p-6 space-y-6">
                <div class="flex gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-zinc-800 flex items-center justify-center shrink-0 border border-slate-100 dark:border-zinc-700">
                        <flux:icon name="academic-cap" variant="outline"
                            class="w-5 h-5 text-slate-600 dark:text-zinc-400" />
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white leading-tight">Professional & Identity</h3>
                        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Identity verification, department assignment, and CV upload.</p>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-zinc-800" />

                <!-- IC / Passport -->
                <flux:field>
                    <flux:label class="font-bold text-xs text-slate-800 dark:text-zinc-200">IC / Passport <span
                            class="text-rose-500">*</span></flux:label>
                    <flux:input wire:model="ic_passport" icon="identification" placeholder="IC or Passport No" />
                    <flux:description class="text-[10px] text-slate-400 dark:text-zinc-500">Identity verification number.</flux:description>
                    <flux:error name="ic_passport" />
                </flux:field>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Age -->
                    <flux:field>
                        <flux:label class="font-bold text-xs text-slate-800 dark:text-zinc-200">Age <span
                                class="text-rose-500">*</span></flux:label>
                        <flux:input wire:model="age" icon="hashtag" type="number" placeholder="Age" />
                        <flux:description class="text-[10px] text-slate-400 dark:text-zinc-500">Min 18 years old.</flux:description>
                        <flux:error name="age" />
                    </flux:field>

                    <!-- Gender -->
                    <flux:field>
                        <flux:label class="font-bold text-xs text-slate-800 dark:text-zinc-200">Gender <span
                                class="text-rose-500">*</span></flux:label>
                        <flux:select wire:model="gender">
                            <option value="" disabled selected>Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </flux:select>
                        <flux:description class="text-[10px] text-slate-400 dark:text-zinc-500">Applicant gender.</flux:description>
                        <flux:error name="gender" />
                    </flux:field>
                </div>

                <!-- Select Department -->
                <flux:field>
                    <flux:label class="font-bold text-xs text-slate-800 dark:text-zinc-200">Select Department <span
                            class="text-rose-500">*</span></flux:label>
                    <flux:select wire:model="department_id">
                        <option value="" disabled selected>Select Department</option>
                        @foreach($allDepartments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:description class="text-[10px] text-slate-400 dark:text-zinc-500">Associated academic department.</flux:description>
                    <flux:error name="department_id" />
                </flux:field>

                <!-- Document Upload -->
                <flux:field>
                    <flux:label class="font-bold text-xs text-slate-800 dark:text-zinc-200">Upload CV / Certificate
                        <span class="text-rose-500">*</span>
                    </flux:label>
                    <flux:input wire:model="document_path" type="file" />
                    <flux:description class="text-[10px] text-slate-400 dark:text-zinc-500">Proof of academic credentials (PDF, PNG, JPG - max 2MB).</flux:description>
                    <flux:error name="document_path" />

                    <div wire:loading wire:target="document_path"
                        class="mt-2 text-[10px] text-indigo-600 dark:text-indigo-400 font-semibold animate-pulse">
                        Uploading credentials...
                    </div>
                </flux:field>
            </flux:card>
        </div>

        <!-- Buttons row -->
        <div class="flex items-center gap-3">
            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold rounded-lg px-4 py-2.5 text-xs transition-all shadow-sm flex items-center gap-2">
                Submit Application
            </button>
            <a href="{{ route('dashboard') }}" wire:navigate
                class="bg-white hover:bg-slate-50 border border-slate-200 dark:border-zinc-700 dark:bg-zinc-900 dark:hover:bg-zinc-800 text-slate-800 dark:text-zinc-200 font-bold rounded-lg px-4 py-2.5 text-xs transition-all shadow-sm">
                Cancel
            </a>
        </div>
    </form>
</div>
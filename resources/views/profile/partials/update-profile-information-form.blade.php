<section class="space-y-6">
    <header class="flex items-start justify-between gap-4 pb-5 border-b border-[var(--border-light)]">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 border border-indigo-200/60 dark:border-indigo-800/60">
                <span class="material-symbols-outlined text-xl">person</span>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold tracking-tight text-[var(--text-dark)] m-0">
                    {{ __('Personal Information') }}
                </h2>
                <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                    {{ __("Update your account's profile name and email address.") }}
                </p>
            </div>
        </div>
        
        <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 shrink-0">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            Active Account
        </span>
    </header>

    @php
        $isSuperAdmin = auth()->user()->hasRole('Super Admin');
    @endphp

    {{-- Role & Office Meta Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        <div class="p-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50/70 dark:bg-slate-800/30 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-200/60 dark:border-blue-800/60">
                <span class="material-symbols-outlined text-lg">admin_panel_settings</span>
            </div>
            <div class="min-w-0">
                <div class="text-[11px] font-semibold uppercase tracking-wider text-[var(--text-muted)]">Assigned Role</div>
                <div class="text-xs sm:text-sm font-bold text-[var(--text-dark)] truncate">
                    {{ $user->roles->isNotEmpty() ? $user->roles->pluck('name')->implode(', ') : 'No Role Assigned' }}
                </div>
            </div>
        </div>

        <div class="p-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50/70 dark:bg-slate-800/30 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 border border-purple-200/60 dark:border-purple-800/60">
                <span class="material-symbols-outlined text-lg">location_on</span>
            </div>
            <div class="min-w-0">
                <div class="text-[11px] font-semibold uppercase tracking-wider text-[var(--text-muted)]">Regional Office</div>
                <div class="text-xs sm:text-sm font-bold text-[var(--text-dark)] truncate">
                    {{ $user->region ?? 'National Office / Central' }}
                </div>
            </div>
        </div>

        <div class="p-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50/70 dark:bg-slate-800/30 flex items-center gap-3 sm:col-span-2 lg:col-span-1">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-200/60 dark:border-emerald-800/60">
                <span class="material-symbols-outlined text-lg">calendar_today</span>
            </div>
            <div class="min-w-0">
                <div class="text-[11px] font-semibold uppercase tracking-wider text-[var(--text-muted)]">Member Since</div>
                <div class="text-xs sm:text-sm font-bold text-[var(--text-dark)] truncate">
                    {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Administrator Restriction Callout --}}
    @if(!$isSuperAdmin)
        <div class="p-4 rounded-xl bg-amber-50/80 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-800/50 flex items-start gap-3">
            <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-xl shrink-0 mt-0.5">lock</span>
            <div class="text-xs sm:text-sm text-amber-800 dark:text-amber-200">
                <span class="font-bold">Managed Account:</span> Name and email modifications are restricted to Super Administrators. Contact your IT Helpdesk administrator if you need to update these credentials.
            </div>
        </div>
    @endif

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        {{-- Full Name --}}
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="name" class="block text-xs sm:text-sm font-bold text-[var(--text-dark)]">
                    {{ __('Full Name') }} <span class="text-rose-500">*</span>
                </label>
                @if(!$isSuperAdmin)
                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-[var(--text-muted)]">
                        <span class="material-symbols-outlined text-xs">lock</span> Read-only
                    </span>
                @endif
            </div>

            <div class="relative flex items-center">
                <span class="absolute left-3.5 text-slate-400 material-symbols-outlined text-lg pointer-events-none">
                    badge
                </span>
                <input 
                    id="name" 
                    name="name" 
                    type="text" 
                    value="{{ old('name', $user->name) }}"
                    autocomplete="name" 
                    @readonly(!$isSuperAdmin)
                    class="w-full h-11 pl-10 pr-10 rounded-xl border border-[var(--border-light)] text-sm text-[var(--text-dark)] transition-all outline-none {{ !$isSuperAdmin ? 'bg-slate-100/80 dark:bg-slate-800/40 text-slate-500 dark:text-slate-400 cursor-not-allowed border-dashed' : 'bg-white dark:bg-slate-800/60 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500' }}"
                />
                @if(!$isSuperAdmin)
                    <span class="absolute right-3.5 text-slate-400 material-symbols-outlined text-lg pointer-events-none" title="Field is locked">
                        lock
                    </span>
                @endif
            </div>
            @if ($errors->get('name'))
                <p class="mt-1.5 text-xs text-rose-500 flex items-center gap-1 font-medium">
                    <span class="material-symbols-outlined text-sm">error</span>
                    {{ $errors->get('name')[0] }}
                </p>
            @endif
        </div>

        {{-- Email Address --}}
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="email" class="block text-xs sm:text-sm font-bold text-[var(--text-dark)]">
                    {{ __('Email Address') }} <span class="text-rose-500">*</span>
                </label>
                @if(!$isSuperAdmin)
                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-[var(--text-muted)]">
                        <span class="material-symbols-outlined text-xs">lock</span> Read-only
                    </span>
                @endif
            </div>

            <div class="relative flex items-center">
                <span class="absolute left-3.5 text-slate-400 material-symbols-outlined text-lg pointer-events-none">
                    mail
                </span>
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    value="{{ old('email', $user->email) }}" 
                    required 
                    autocomplete="username" 
                    @readonly(!$isSuperAdmin)
                    class="w-full h-11 pl-10 pr-10 rounded-xl border border-[var(--border-light)] text-sm text-[var(--text-dark)] transition-all outline-none {{ !$isSuperAdmin ? 'bg-slate-100/80 dark:bg-slate-800/40 text-slate-500 dark:text-slate-400 cursor-not-allowed border-dashed' : 'bg-white dark:bg-slate-800/60 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500' }}"
                />
                @if(!$isSuperAdmin)
                    <span class="absolute right-3.5 text-slate-400 material-symbols-outlined text-lg pointer-events-none" title="Field is locked">
                        lock
                    </span>
                @endif
            </div>
            @if ($errors->get('email'))
                <p class="mt-1.5 text-xs text-rose-500 flex items-center gap-1 font-medium">
                    <span class="material-symbols-outlined text-sm">error</span>
                    {{ $errors->get('email')[0] }}
                </p>
            @endif

            {{-- Unverified Email Alert --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 space-y-2">
                    <div class="flex items-center gap-2 text-xs sm:text-sm font-semibold text-amber-800 dark:text-amber-200">
                        <span class="material-symbols-outlined text-amber-600 text-lg">warning</span>
                        {{ __('Your email address is unverified.') }}
                    </div>

                    @if($isSuperAdmin)
                        <button form="send-verification" type="submit" class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline cursor-pointer">
                            <span class="material-symbols-outlined text-sm">send</span>
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    @endif

                    @if (session('status') === 'verification-link-sent')
                        <div class="p-2.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 text-xs flex items-center gap-2 border border-emerald-200 dark:border-emerald-800/60">
                            <span class="material-symbols-outlined text-emerald-600 text-sm">check_circle</span>
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Form Actions --}}
        <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            @can('edit_profile')
                @if($isSuperAdmin)
                    <button type="submit" class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-xl text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 active:scale-95 shadow-md shadow-indigo-600/20 transition-all cursor-pointer">
                        <span class="material-symbols-outlined text-lg">check_circle</span>
                        {{ __('Save Changes') }}
                    </button>
                @endif
            @endcan

            @if (session('status') === 'profile-updated')
                <div 
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60"
                >
                    <span class="material-symbols-outlined text-base text-emerald-500">check</span>
                    {{ __('Profile updated successfully.') }}
                </div>
            @endif
        </div>
    </form>
</section>
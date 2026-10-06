<section class="space-y-6" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
    <header class="flex items-start justify-between gap-4 pb-5 border-b border-[var(--border-light)]">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 border border-purple-200/60 dark:border-purple-800/60">
                <span class="material-symbols-outlined text-xl">lock_reset</span>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold tracking-tight text-[var(--text-dark)] m-0">
                    {{ __('Update Password') }}
                </h2>
                <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                    {{ __('Ensure your account is using a secure, long password to stay protected.') }}
                </p>
            </div>
        </div>

        <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60 shrink-0">
            <span class="material-symbols-outlined text-xs">shield</span>
            Security
        </span>
    </header>

    {{-- Password Guidelines Banner --}}
    <div class="p-4 rounded-xl bg-slate-50/80 dark:bg-slate-800/30 border border-[var(--border-light)] flex items-start gap-3">
        <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-xl shrink-0 mt-0.5">info</span>
        <div class="text-xs sm:text-sm text-[var(--text-muted)] leading-relaxed">
            <span class="font-bold text-[var(--text-dark)]">Password Recommendations:</span> Use at least 8 characters with a combination of uppercase letters, lowercase letters, numbers, and special symbols for maximum security.
        </div>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div>
            <label for="update_password_current_password" class="block text-xs sm:text-sm font-bold text-[var(--text-dark)] mb-1.5">
                {{ __('Current Password') }} <span class="text-rose-500">*</span>
            </label>
            <div class="relative flex items-center">
                <span class="absolute left-3.5 text-slate-400 material-symbols-outlined text-lg pointer-events-none">
                    key
                </span>
                <input 
                    id="update_password_current_password" 
                    name="current_password" 
                    :type="showCurrent ? 'text' : 'password'"
                    autocomplete="current-password" 
                    placeholder="Enter your current password"
                    class="w-full h-11 pl-10 pr-11 rounded-xl border border-[var(--border-light)] bg-white dark:bg-slate-800/60 text-sm text-[var(--text-dark)] placeholder-[var(--text-muted)] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none"
                />
                <button 
                    type="button" 
                    @click="showCurrent = !showCurrent" 
                    class="absolute right-2.5 w-7 h-7 rounded-lg text-slate-400 hover:text-[var(--text-dark)] hover:bg-slate-100 dark:hover:bg-slate-700/60 flex items-center justify-center transition-colors cursor-pointer"
                    :title="showCurrent ? 'Hide password' : 'Show password'"
                >
                    <span class="material-symbols-outlined text-lg" x-text="showCurrent ? 'visibility_off' : 'visibility'"></span>
                </button>
            </div>
            @if ($errors->updatePassword->get('current_password'))
                <p class="mt-1.5 text-xs text-rose-500 flex items-center gap-1 font-medium">
                    <span class="material-symbols-outlined text-sm">error</span>
                    {{ $errors->updatePassword->get('current_password')[0] }}
                </p>
            @endif
        </div>

        {{-- New Password --}}
        <div>
            <label for="update_password_password" class="block text-xs sm:text-sm font-bold text-[var(--text-dark)] mb-1.5">
                {{ __('New Password') }} <span class="text-rose-500">*</span>
            </label>
            <div class="relative flex items-center">
                <span class="absolute left-3.5 text-slate-400 material-symbols-outlined text-lg pointer-events-none">
                    lock
                </span>
                <input 
                    id="update_password_password" 
                    name="password" 
                    :type="showNew ? 'text' : 'password'"
                    autocomplete="new-password" 
                    placeholder="Create a strong new password"
                    class="w-full h-11 pl-10 pr-11 rounded-xl border border-[var(--border-light)] bg-white dark:bg-slate-800/60 text-sm text-[var(--text-dark)] placeholder-[var(--text-muted)] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none"
                />
                <button 
                    type="button" 
                    @click="showNew = !showNew" 
                    class="absolute right-2.5 w-7 h-7 rounded-lg text-slate-400 hover:text-[var(--text-dark)] hover:bg-slate-100 dark:hover:bg-slate-700/60 flex items-center justify-center transition-colors cursor-pointer"
                    :title="showNew ? 'Hide password' : 'Show password'"
                >
                    <span class="material-symbols-outlined text-lg" x-text="showNew ? 'visibility_off' : 'visibility'"></span>
                </button>
            </div>
            @if ($errors->updatePassword->get('password'))
                <p class="mt-1.5 text-xs text-rose-500 flex items-center gap-1 font-medium">
                    <span class="material-symbols-outlined text-sm">error</span>
                    {{ $errors->updatePassword->get('password')[0] }}
                </p>
            @endif
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="update_password_password_confirmation" class="block text-xs sm:text-sm font-bold text-[var(--text-dark)] mb-1.5">
                {{ __('Confirm New Password') }} <span class="text-rose-500">*</span>
            </label>
            <div class="relative flex items-center">
                <span class="absolute left-3.5 text-slate-400 material-symbols-outlined text-lg pointer-events-none">
                    verified_user
                </span>
                <input 
                    id="update_password_password_confirmation" 
                    name="password_confirmation" 
                    :type="showConfirm ? 'text' : 'password'"
                    autocomplete="new-password" 
                    placeholder="Re-type your new password"
                    class="w-full h-11 pl-10 pr-11 rounded-xl border border-[var(--border-light)] bg-white dark:bg-slate-800/60 text-sm text-[var(--text-dark)] placeholder-[var(--text-muted)] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none"
                />
                <button 
                    type="button" 
                    @click="showConfirm = !showConfirm" 
                    class="absolute right-2.5 w-7 h-7 rounded-lg text-slate-400 hover:text-[var(--text-dark)] hover:bg-slate-100 dark:hover:bg-slate-700/60 flex items-center justify-center transition-colors cursor-pointer"
                    :title="showConfirm ? 'Hide password' : 'Show password'"
                >
                    <span class="material-symbols-outlined text-lg" x-text="showConfirm ? 'visibility_off' : 'visibility'"></span>
                </button>
            </div>
            @if ($errors->updatePassword->get('password_confirmation'))
                <p class="mt-1.5 text-xs text-rose-500 flex items-center gap-1 font-medium">
                    <span class="material-symbols-outlined text-sm">error</span>
                    {{ $errors->updatePassword->get('password_confirmation')[0] }}
                </p>
            @endif
        </div>

        {{-- Form Actions --}}
        <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <button type="submit" class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-xl text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 active:scale-95 shadow-md shadow-indigo-600/20 transition-all cursor-pointer">
                <span class="material-symbols-outlined text-lg">lock</span>
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
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
                    {{ __('Password updated successfully.') }}
                </div>
            @endif
        </div>
    </form>
</section>
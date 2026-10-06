<section class="space-y-6">
    <header class="flex items-start justify-between gap-4 pb-5 border-b border-rose-200/80 dark:border-rose-900/50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-200/60 dark:border-rose-800/60">
                <span class="material-symbols-outlined text-xl">delete_forever</span>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold tracking-tight text-rose-600 dark:text-rose-400 m-0">
                    {{ __('Danger Zone: Delete Account') }}
                </h2>
                <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                    {{ __('Permanently remove your account, credentials, and associated user data.') }}
                </p>
            </div>
        </div>

        <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60 shrink-0">
            <span class="material-symbols-outlined text-xs">warning</span>
            Irreversible
        </span>
    </header>

    <div class="p-4 rounded-xl bg-rose-50/60 dark:bg-rose-950/30 border border-rose-200/70 dark:border-rose-900/50 flex items-start gap-3">
        <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-xl shrink-0 mt-0.5">report_problem</span>
        <div class="text-xs sm:text-sm text-rose-800 dark:text-rose-200 leading-relaxed">
            {{ __('Once your account is deleted, all associated resources, tickets, and user settings will be permanently erased. Before proceeding, please ensure you have saved or archived any critical data you need to keep.') }}
        </div>
    </div>

    <div>
        <button 
            type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-xl text-xs sm:text-sm font-semibold text-white bg-rose-600 hover:bg-rose-500 active:scale-95 shadow-md shadow-rose-600/20 transition-all cursor-pointer"
        >
            <span class="material-symbols-outlined text-lg">delete</span>
            {{ __('Delete Account') }}
        </button>
    </div>

    {{-- Confirmation Modal --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8 bg-[var(--card-bg)] text-[var(--text-dark)]" x-data="{ showModalPassword: false }">
            @csrf
            @method('delete')

            <div class="flex items-start gap-3.5 mb-5 pb-5 border-b border-[var(--border-light)]">
                <div class="w-11 h-11 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-200 dark:border-rose-800/60">
                    <span class="material-symbols-outlined text-2xl">warning</span>
                </div>
                <div>
                    <h3 class="text-lg sm:text-xl font-bold tracking-tight text-[var(--text-dark)] m-0">
                        {{ __('Are you sure you want to delete your account?') }}
                    </h3>
                    <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-1 font-medium leading-relaxed">
                        {{ __('This action cannot be undone. Please enter your password below to confirm that you wish to permanently delete your account.') }}
                    </p>
                </div>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-xs sm:text-sm font-bold text-[var(--text-dark)] mb-1.5">
                    {{ __('Confirm Your Password') }} <span class="text-rose-500">*</span>
                </label>

                <div class="relative flex items-center">
                    <span class="absolute left-3.5 text-slate-400 material-symbols-outlined text-lg pointer-events-none">
                        lock
                    </span>
                    <input
                        id="password"
                        name="password"
                        :type="showModalPassword ? 'text' : 'password'"
                        placeholder="{{ __('Enter your current password to confirm') }}"
                        class="w-full h-11 pl-10 pr-11 rounded-xl border border-[var(--border-light)] bg-white dark:bg-slate-800/60 text-sm text-[var(--text-dark)] placeholder-[var(--text-muted)] focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all outline-none"
                    />
                    <button 
                        type="button" 
                        @click="showModalPassword = !showModalPassword" 
                        class="absolute right-2.5 w-7 h-7 rounded-lg text-slate-400 hover:text-[var(--text-dark)] hover:bg-slate-100 dark:hover:bg-slate-700/60 flex items-center justify-center transition-colors cursor-pointer"
                        :title="showModalPassword ? 'Hide password' : 'Show password'"
                    >
                        <span class="material-symbols-outlined text-lg" x-text="showModalPassword ? 'visibility_off' : 'visibility'"></span>
                    </button>
                </div>

                @if ($errors->userDeletion->get('password'))
                    <p class="mt-1.5 text-xs text-rose-500 flex items-center gap-1 font-medium">
                        <span class="material-symbols-outlined text-sm">error</span>
                        {{ $errors->userDeletion->get('password')[0] }}
                    </p>
                @endif
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center sm:justify-end gap-3 pt-2">
                <button 
                    type="button" 
                    x-on:click="$dispatch('close')"
                    class="inline-flex items-center justify-center gap-1.5 h-11 px-5 rounded-xl text-xs sm:text-sm font-semibold border border-[var(--border-light)] text-[var(--text-muted)] hover:text-[var(--text-dark)] hover:bg-slate-100 dark:hover:bg-slate-800 transition-all cursor-pointer"
                >
                    {{ __('Cancel') }}
                </button>

                <button 
                    type="submit" 
                    class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-xl text-xs sm:text-sm font-semibold text-white bg-rose-600 hover:bg-rose-500 active:scale-95 shadow-md shadow-rose-600/20 transition-all cursor-pointer"
                >
                    <span class="material-symbols-outlined text-lg">delete_forever</span>
                    {{ __('Permanently Delete') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
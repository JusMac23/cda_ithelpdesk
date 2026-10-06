<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @php
        $user = Auth::user();
        $base64 = null;
        if (!empty($user->profile_image)) {
            $base64 = is_resource($user->profile_image) 
                ? base64_encode(stream_get_contents($user->profile_image)) 
                : $user->profile_image;
        }

        $initials = '';
        if (!empty($user->firstname) && !empty($user->lastname)) {
            $initials = strtoupper(substr($user->firstname, 0, 1) . substr($user->lastname, 0, 1));
        } else {
            $nameParts = explode(' ', trim($user->name ?? 'User'));
            $initials = strtoupper(substr($nameParts[0], 0, 1));
            if (count($nameParts) > 1) {
                $initials .= strtoupper(substr(end($nameParts), 0, 1));
            }
        }
    @endphp

    <div id="main-content" class="w-full max-w-6xl mx-auto space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-500 text-white flex items-center justify-center shadow-md shadow-indigo-500/20 shrink-0">
                    <span class="material-symbols-outlined text-2xl">manage_accounts</span>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                        Profile Settings
                    </h1>
                    <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                        Manage your account information, update security credentials, and view system permissions
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-auto">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/60">
                    <span class="material-symbols-outlined text-sm">verified_user</span>
                    {{ $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : 'Standard User' }}
                </span>
            </div>
        </div>

        {{-- Profile Hero Overview Card --}}
        <div class="bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-5 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                
                {{-- Avatar Container with Instant Photo Upload --}}
                <div class="relative group shrink-0">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl overflow-hidden border-2 border-indigo-500/30 shadow-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                        @if(!empty($base64))
                            <img src="data:image/jpeg;base64,{{ $base64 }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white font-extrabold text-2xl sm:text-3xl tracking-wider">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>

                    {{-- Floating Photo Upload Button --}}
                    <form method="POST" action="{{ route('profile.upload_image') }}" enctype="multipart/form-data" class="m-0">
                        @csrf
                        <label 
                            class="absolute -bottom-2 -right-2 w-9 h-9 rounded-xl bg-indigo-600 hover:bg-indigo-500 active:scale-95 text-white flex items-center justify-center shadow-md shadow-indigo-600/30 cursor-pointer transition-all"
                            title="Update profile picture"
                        >
                            <span class="material-symbols-outlined text-lg">photo_camera</span>
                            <input type="file" name="profile_image" class="hidden" accept="image/*" onchange="this.form.submit()">
                        </label>
                    </form>
                </div>

                {{-- User Highlights --}}
                <div class="flex-1 text-center sm:text-left min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 justify-center sm:justify-start">
                        <h2 class="text-xl sm:text-2xl font-extrabold text-[var(--text-dark)] m-0 truncate">
                            {{ $user->name }}
                        </h2>
                        
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60 self-center sm:self-auto">
                                <span class="material-symbols-outlined text-xs">error</span> Unverified
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 self-center sm:self-auto">
                                <span class="material-symbols-outlined text-xs">check_circle</span> Verified Email
                            </span>
                        @endif
                    </div>

                    <p class="text-sm text-[var(--text-muted)] font-medium m-0 mt-1 flex items-center gap-1.5 justify-center sm:justify-start">
                        <span class="material-symbols-outlined text-base text-slate-400">mail</span>
                        <span>{{ $user->email }}</span>
                    </p>

                    <div class="flex flex-wrap items-center gap-2 mt-4 justify-center sm:justify-start">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-[var(--text-dark)] border border-slate-200/80 dark:border-slate-700/80">
                            <span class="material-symbols-outlined text-sm text-indigo-500">location_on</span>
                            {{ $user->region ?? 'National Office' }}
                        </span>

                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-[var(--text-dark)] border border-slate-200/80 dark:border-slate-700/80">
                            <span class="material-symbols-outlined text-sm text-purple-500">calendar_month</span>
                            Joined {{ $user->created_at ? $user->created_at->format('M Y') : 'N/A' }}
                        </span>

                        @if($user->roles->isNotEmpty())
                            @foreach($user->roles as $role)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-semibold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60">
                                    <span class="material-symbols-outlined text-xs">shield_person</span>
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- Profile Forms Container --}}
        <div class="space-y-6">
            
            {{-- Update Profile Information Card --}}
            <div class="bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-5 sm:p-6 lg:p-8">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Update Password Card --}}
            <div class="bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-5 sm:p-6 lg:p-8">
                @include('profile.partials.update-password-form')
            </div>

            {{-- Danger Zone: Delete Account (Conditional) --}}
            @can('delete_profile')
                <div class="bg-[var(--card-bg)] border border-rose-200 dark:border-rose-900/60 rounded-2xl shadow-xs transition-colors duration-300 p-5 sm:p-6 lg:p-8">
                    @include('profile.partials.delete-user-form')
                </div>
            @endcan

        </div>
    </div>

    {{-- Hidden form for logging out securely via POST on password change --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    {{-- SweetAlert Notification Script with Dark Mode Theme Integration --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const getComputedColor = (cssVar) => getComputedStyle(document.body).getPropertyValue(cssVar).trim();

            @if (session('status') === 'password-updated')
                Swal.fire({
                    icon: 'success',
                    title: 'Password Updated Successfully',
                    text: 'For your security, you will now be logged out. Please log in again with your new password.',
                    confirmButtonColor: '#4f46e5',
                    background: getComputedColor('--card-bg'),
                    color: getComputedColor('--text-dark'),
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                });
            @elseif (session('status') === 'profile-updated')
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated',
                    text: 'Your profile information has been successfully updated.',
                    confirmButtonColor: '#4f46e5',
                    background: getComputedColor('--card-bg'),
                    color: getComputedColor('--text-dark'),
                    timer: 2500,
                    showConfirmButton: false
                });
            @elseif (session('status'))
                Swal.fire({
                    icon: 'info',
                    title: 'Notice',
                    text: "{!! addslashes(session('status')) !!}",
                    confirmButtonColor: '#4f46e5',
                    background: getComputedColor('--card-bg'),
                    color: getComputedColor('--text-dark')
                });
            @endif

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{!! addslashes(session('success')) !!}",
                    confirmButtonColor: '#4f46e5',
                    background: getComputedColor('--card-bg'),
                    color: getComputedColor('--text-dark'),
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{!! addslashes(session('error')) !!}",
                    confirmButtonColor: '#4f46e5',
                    background: getComputedColor('--card-bg'),
                    color: getComputedColor('--text-dark'),
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
</x-app-layout>
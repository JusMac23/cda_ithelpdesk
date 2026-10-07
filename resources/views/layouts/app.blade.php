<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CDA-ICT Helpdesk</title>
    <link rel="icon" href="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="/assets/js/sweetalert2.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>

    <script>
        (function() {
            const hasStored = 'darkMode' in localStorage;
            const isDark = hasStored 
                ? localStorage.getItem('darkMode') === 'true' 
                : window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            document.documentElement.classList.toggle('dark', isDark);

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!('darkMode' in localStorage)) {
                    document.documentElement.classList.toggle('dark', e.matches);
                    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: e.matches } }));
                }
            });
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data="{ 
        sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
        mobileSidebarOpen: false,
        darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebarOpen', this.sidebarOpen);
        },
        toggleTheme() {
            document.documentElement.classList.add('theme-transitioning');
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
            document.documentElement.classList.toggle('dark', this.darkMode);
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: this.darkMode } }));
            setTimeout(() => {
                document.documentElement.classList.remove('theme-transitioning');
            }, 300);
        }
    }" 
    x-init="
        document.documentElement.classList.toggle('dark', darkMode);
        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: darkMode } }));
    "
    :class="{ 'dark': darkMode }">
    
    <div class="app-wrapper">

        <div 
            class="mobile-overlay" 
            :class="{ 'mobile-open': mobileSidebarOpen }"
            @click="mobileSidebarOpen = false"
            x-transition.opacity
        ></div>

        @include('layouts.navigation')

        <div class="main-content">
            
            <header class="top-header">
                <div class="header-left">
                    <button @click="mobileSidebarOpen = true" class="icon-btn mobile-only" title="Open Navigation Menu">
                        <span class="material-symbols-outlined">menu</span>
                    </button>

                    <button @click="toggleSidebar()" class="icon-btn desktop-only" :title="sidebarOpen ? 'Collapse Sidebar' : 'Expand Sidebar'">
                        <span class="material-symbols-outlined" x-text="sidebarOpen ? 'menu_open' : 'menu'"></span>
                    </button>
                </div>

                <div class="header-right">
                    @php $user = Auth::user(); @endphp

                    <div class="clock-widget" x-data="{ 
                            time: '',
                            init() {
                                this.updateTime();
                                setInterval(() => this.updateTime(), 1000);
                            },
                            updateTime() {
                                const options = { timeZone: 'Asia/Manila', weekday: 'long', month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true };
                                this.time = new Date().toLocaleString('en-US', options);
                            }
                        }">
                        <span class="material-symbols-outlined">schedule</span>
                        <span x-text="time" class="clock-text"></span>
                    </div>

                    <button @click="toggleTheme()" 
                        type="button"
                        class="theme-toggle-btn" 
                        :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                        :aria-label="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
                        <div class="theme-icon-container">
                            <span class="material-symbols-outlined theme-icon-sun" :class="{ 'opacity-100 rotate-0 scale-100': !darkMode, 'opacity-0 -rotate-90 scale-50': darkMode }">
                                light_mode
                            </span>
                            <span class="material-symbols-outlined theme-icon-moon" :class="{ 'opacity-100 rotate-0 scale-100': darkMode, 'opacity-0 rotate-90 scale-50': !darkMode }">
                                dark_mode
                            </span>
                        </div>
                    </button>

                    <div class="profile-dropdown" x-data="{ open: false }" @click.away="open = false">
                        @php
                            $base64 = null;
                            if(!empty($user->profile_image)) {
                                $base64 = is_resource($user->profile_image) 
                                    ? base64_encode(stream_get_contents($user->profile_image)) 
                                    : $user->profile_image;
                            }

                            $initials = '';
                            if (!empty($user->firstname) && !empty($user->lastname)) {
                                $initials = strtoupper(substr($user->firstname, 0, 1) . substr($user->lastname, 0, 1));
                            } else {
                                $nameParts = explode(' ', trim($user->name ?? 'Client'));
                                $initials = strtoupper(substr($nameParts[0], 0, 1));
                                if (count($nameParts) > 1) {
                                    $initials .= strtoupper(substr(end($nameParts), 0, 1));
                                }
                            }
                        @endphp

                        <button @click="open = !open" class="avatar-btn" title="{{ $user->name ?? 'Client' }}">
                            @if(!empty($base64))
                                <img src="data:image/jpeg;base64,{{ $base64 }}" alt="Profile" class="avatar-img">
                            @else
                                <span class="avatar-initials">{{ $initials }}</span>
                            @endif
                        </button>

                        <div x-show="open" x-transition.opacity style="display: none;" class="dropdown-menu">
                            <div class="dropdown-header">
                                <!-- Enlarged Rounded Square Container -->
                                <div class="dropdown-avatar-container">
                                    @if(!empty($base64))
                                        <img src="data:image/jpeg;base64,{{ $base64 }}" alt="Profile Image" class="dropdown-profile-image">
                                    @else
                                        <span class="dropdown-avatar-initials">{{ $initials }}</span>
                                    @endif
                                </div>
                                
                                <!-- User Details aligned to the right -->
                                <div class="dropdown-user-details">
                                    <div class="dropdown-name">{{ $user->name }}</div>
                                    <div class="dropdown-email">{{ $user->email }}</div>
                                    <div class="dropdown-region">{{ $user->region }}</div>
                                    
                                    @if($user->roles->isNotEmpty())
                                        <span class="dropdown-role">
                                            {{ $user->roles->pluck('name')->implode(' | ') }}
                                        </span>
                                    @else
                                        <span class="dropdown-role" style="background: var(--dropdown-hover); color: var(--text-muted);">
                                            No Role
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <span class="material-symbols-outlined">account_circle</span> Account Settings
                            </a>

                            <form method="POST" action="{{ route('profile.upload_image') }}" enctype="multipart/form-data" style="margin: 0;">
                                @csrf
                                <label class="dropdown-item" style="cursor: pointer; margin: 0; width: 100%; box-sizing: border-box;">
                                    <span class="material-symbols-outlined">photo_camera</span> Update Profile Picture
                                    <input type="file" name="profile_image" style="display: none;" accept="image/*" onchange="this.form.submit()">
                                </label>
                            </form>

                            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item logout" style="width: 100%; text-align: left;">
                                    <span class="material-symbols-outlined">logout</span> Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="content-area">
                {{ $slot }}
            </main>

            <footer class="app-footer">
                <p>&copy; {{ date('Y') }} CDA-ICT Helpdesk. All rights reserved.</p>
            </footer>

        </div>
    </div>
</body>
</html>
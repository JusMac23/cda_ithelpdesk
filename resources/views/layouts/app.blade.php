<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CDA-ICT Helpdesk</title>
    <link rel="icon" href="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" type="image/png">

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script src="/assets/js/sweetalert2.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>

    <style>
        :root {
            --sidebar-bg: #133e5e;
            --sidebar-hover: rgba(255, 255, 255, 0.1);
            --sidebar-active: rgba(255, 255, 255, 0.15);
            --sidebar-text: #cbd5e1;
            --sidebar-width: 256px;
            --sidebar-collapsed-width: 80px;
            --sidebar-border: transparent;
            --body-bg: #f9fafb;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --danger: #ef4444;
            --danger-hover: #fee2e2;
            --header-bg: #ffffff;
            --dropdown-bg: #ffffff;
            --dropdown-hover: #f3f4f6;
            --avatar-outline: transparent;
        }

        body.dark {
            --sidebar-bg: #0f172a; 
            --sidebar-border: #334155; 
            --body-bg: #0f172a; 
            --text-main: #f3f4f6; 
            --text-muted: #9ca3af; 
            --border-color: #374151; 
            --danger-hover: #7f1d1d;
            --header-bg: #0f172a; 
            --dropdown-bg: #1f2937;
            --dropdown-hover: #374151;
            --avatar-outline: #3b82f6;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background-color: var(--body-bg); color: var(--text-main); overflow: hidden; transition: background-color 0.3s ease, color 0.3s ease; }
        a { text-decoration: none; }
        button { background: none; border: none; cursor: pointer; font-family: inherit; outline: none; }
        [x-cloak] { display: none !important; }
        
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }

        .app-wrapper { display: flex; height: 100vh; height: 100dvh; width: 100%; overflow: hidden; }
        .main-content { display: flex; flex-direction: column; flex: 1; min-width: 0; overflow: hidden; transition: margin 0.3s ease; }
        .content-area { flex: 1; padding: 24px; overflow-y: auto; }

        .sidebar { background-color: var(--sidebar-bg); border-right: 1px solid var(--sidebar-border); color: white; display: flex; flex-direction: column; transition: width 0.3s ease, transform 0.3s ease, background-color 0.3s ease, border-color 0.3s ease; z-index: 50; flex-shrink: 0; box-shadow: 4px 0 15px rgba(0,0,0,0.1); }
        .sidebar-header { height: 64px; display: flex; align-items: center; padding: 0 16px; border-bottom: 1px solid rgba(255,255,255,0.1); flex-shrink: 0; overflow: hidden; }
        .sidebar-logo { height: 32px; width: auto; background: rgba(255,255,255,0.1); padding: 4px; border-radius: 4px; object-fit: contain; }
        .sidebar-brand { display: flex; flex-direction: column; margin-left: 12px; white-space: nowrap; }
        .sidebar-brand-title { font-size: 18px; font-weight: bold; line-height: 1; color: white; }
        .sidebar-brand-sub { font-size: 10px; font-weight: 600; color: #7dd3fc; text-transform: uppercase; margin-top: 4px; letter-spacing: 0.5px; }
        
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 24px 12px; }
        .sidebar-nav::-webkit-scrollbar { width: 5px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
        .sidebar-nav::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.4); }

        .sidebar-footer { height: 64px; display: flex; align-items: center; justify-content: center; padding: 0 16px; border-top: 1px solid rgba(255,255,255,0.1); flex-shrink: 0; }
        .nav-label { font-size: 10px; font-weight: bold; color: #7dd3fc; text-transform: uppercase; letter-spacing: 1px; padding: 16px 12px 8px; margin-top: 8px; }

        .nav-link { width: 100%; display: flex; align-items: center; text-align: left; padding: 10px 12px; color: var(--sidebar-text); border-radius: 8px; font-weight: 500; transition: all 0.2s ease; margin-bottom: 4px; white-space: nowrap; }
        .nav-link:hover { background-color: var(--sidebar-hover); color: white; }
        .nav-link.active { background-color: var(--sidebar-active); color: white; border: 1px solid rgba(255,255,255,0.05); box-shadow: inset 0 2px 4px rgba(0,0,0,0.1); }
        .nav-link .material-symbols-outlined { font-size: 22px; margin-right: 12px; flex-shrink: 0; }
        .nav-link .nav-text { flex-grow: 1; }
        
        .nav-link.logout { color: #fb7185; }
        .nav-link.logout:hover { background-color: rgba(244, 63, 94, 0.1); color: #fda4af; }

        .submenu { list-style: none; padding-left: 0; margin-top: 4px; margin-bottom: 8px; }
        .submenu-link { display: flex; align-items: center; padding: 8px 12px 8px 44px; color: #94a3b8; font-size: 14px; border-radius: 8px; transition: 0.2s; text-decoration: none; }
        .submenu-link:hover { background-color: rgba(255,255,255,0.05); color: white; }
        .submenu-link.active { background-color: rgba(255,255,255,0.1); color: white; font-weight: 600; }
        .submenu-dot { width: 6px; height: 6px; border-radius: 50%; background-color: #64748b; margin-right: 12px; flex-shrink: 0; }
        .submenu-link.active .submenu-dot { background-color: #38bdf8; }
        
        .chevron { margin-left: auto; transition: transform 0.3s ease; width: 18px; height: 18px; color: inherit; }
        .chevron.open { transform: rotate(90deg); }

        .top-header { height: 64px; background-color: var(--header-bg); border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; padding: 0 24px; z-index: 30; flex-shrink: 0; transition: background-color 0.3s ease, border-color 0.3s ease; }
        .header-left, .header-right { display: flex; align-items: center; height: 100%; gap: 12px; }
        .icon-btn { padding: 8px; color: var(--text-muted); border-radius: 8px; transition: background 0.2s; display: flex; align-items: center; justify-content: center; }
        .icon-btn:hover { background-color: var(--dropdown-hover); color: var(--text-main); }
        
        .clock-widget { display: flex; align-items: center; color: var(--text-muted); font-size: 14px; font-weight: 500; white-space: nowrap; }
        .clock-widget .material-symbols-outlined { font-size: 18px; margin-right: 8px; }
        
        .theme-toggle { margin-right: 12px; }
        .profile-dropdown { position: relative; margin-left: 8px; }
        
        .avatar-btn { width: 40px; height: 40px; border-radius: 50%; background-color: var(--sidebar-bg); color: white; font-weight: bold; display: flex; align-items: center; justify-content: center; font-size: 14px; letter-spacing: 1px; transition: all 0.2s; flex-shrink: 0; border: 2px solid var(--avatar-outline); overflow: hidden; padding: 0; }
        .avatar-btn:hover { background-color: #1a537d; box-shadow: 0 0 0 2px var(--header-bg), 0 0 0 4px var(--sidebar-bg); }
        .avatar-img { width: 100%; height: 100%; object-fit: cover; }
        .avatar-initials { display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; }

        /* Dropdown & Side-by-Side Header CSS (Enlarged Rounded Square Profile Picture) */
        .dropdown-menu { position: absolute; right: 0; top: 100%; margin-top: 8px; width: 310px; background: var(--dropdown-bg); border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.12); border: 1px solid var(--border-color); z-index: 50; overflow: hidden; transition: background-color 0.3s ease, border-color 0.3s ease; }
        .dropdown-header { padding: 16px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 14px; }
        
        /* Enlarged Rounded-Square Image Container */
        .dropdown-avatar-container { width: 80px; height: 80px; border-radius: 12px; background-color: var(--sidebar-bg); color: white; flex-shrink: 0; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 2px solid var(--avatar-outline); box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .dropdown-profile-image { width: 100%; height: 100%; object-fit: cover; }
        .dropdown-avatar-initials { font-weight: bold; font-size: 22px; }

        /* Right Side User Information Alignment */
        .dropdown-user-details { flex: 1; min-width: 0; display: flex; flex-direction: column; justify-content: center; }
        .dropdown-name { font-weight: bold; color: var(--text-main); font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.2; }
        .dropdown-email { font-size: 12px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 3px; }
        .dropdown-region { font-size: 11px; font-weight: 700; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px; }
        .dropdown-role { display: inline-block; padding: 3px 8px; background: #e0f2fe; color: #133e5e; font-size: 10px; font-weight: bold; border-radius: 6px; margin-top: 6px; text-transform: uppercase; width: fit-content; }
        
        .dropdown-item { display: flex; align-items: center; width: 100%; padding: 10px 16px; color: var(--text-main); font-size: 14px; text-decoration: none; transition: 0.2s; background: transparent; text-align: left; border: none; cursor: pointer; font-family: inherit; }
        .dropdown-item:hover { background-color: var(--dropdown-hover); }
        .dropdown-item .material-symbols-outlined { font-size: 18px; margin-right: 10px; color: var(--text-muted); }
        .dropdown-item.logout { color: var(--danger); }
        .dropdown-item.logout:hover { background-color: var(--danger-hover); color: #b91c1c; }
        .dropdown-item.logout .material-symbols-outlined { color: inherit; }

        .app-footer { height: 64px; background: var(--header-bg); border-top: 1px solid var(--border-color); padding: 0 24px; display: flex; justify-content: center; align-items: center; gap: 24px; font-size: 14px; color: var(--text-muted); flex-shrink: 0; transition: background-color 0.3s ease, border-color 0.3s ease; }
        .app-footer a { color: var(--text-muted); transition: color 0.2s; }
        .app-footer a:hover { color: var(--sidebar-bg); }

        .mobile-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(17, 24, 39, 0.6); backdrop-filter: blur(2px); z-index: 40; display: none; }

        @media (min-width: 768px) {
            .mobile-only { display: none !important; }
            .sidebar { width: var(--sidebar-width); position: relative; }
            .sidebar.collapsed { width: var(--sidebar-collapsed-width); }
            .sidebar.collapsed .sidebar-brand { display: none; }
            .sidebar.collapsed .sidebar-header { justify-content: center; padding: 0; }
            .sidebar.collapsed .sidebar-header a { justify-content: center !important; }
            .sidebar.collapsed .nav-text { display: none; }
            .sidebar.collapsed .nav-label { display: none; }
            .sidebar.collapsed .submenu { display: none !important; } 
            .sidebar.collapsed .nav-link { justify-content: center; padding: 12px 0; }
            .sidebar.collapsed .nav-link .material-symbols-outlined { margin-right: 0; }
            .sidebar.collapsed .chevron { display: none; }
            .sidebar.collapsed hr { display: block !important; margin: 16px 12px; border: 0; border-top: 1px solid rgba(255,255,255,0.1); }
        }

        @media (max-width: 767px) {
            .desktop-only { display: none !important; }
            .sidebar { position: fixed; top: 0; bottom: 0; left: 0; width: var(--sidebar-width); transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
            .mobile-overlay.mobile-open { display: block; }
            .header-left .icon-btn { margin-right: 8px; }
            .top-header { padding: 0 16px; } 
            .clock-widget { font-size: 12px; margin-right: 4px;}
            .clock-widget .material-symbols-outlined { font-size: 16px; margin-right: 4px; }
            .theme-toggle { margin-right: 4px; }
            .app-footer { height: auto; min-height: 64px; padding: 16px 12px; padding-bottom: calc(16px + env(safe-area-inset-bottom)); flex-direction: column; gap: 8px; text-align: center; }
            .app-footer p, .app-footer a { margin: 0; font-size: 12px; word-wrap: break-word; }
            .sidebar-footer { height: auto; padding: 16px; padding-bottom: calc(16px + env(safe-area-inset-bottom)); }
        }
    </style>
</head>

<body x-data="{ 
        sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
        mobileSidebarOpen: false,
        darkMode: localStorage.getItem('darkMode') === 'true',
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebarOpen', this.sidebarOpen);
        },
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
        }
    }" 
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
                    <button @click="mobileSidebarOpen = true" class="icon-btn mobile-only">
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
                        <span x-text="time"></span>
                    </div>

                    <button @click="toggleTheme()" class="icon-btn theme-toggle" :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
                        <span class="material-symbols-outlined" x-text="darkMode ? 'light_mode' : 'dark_mode'"></span>
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
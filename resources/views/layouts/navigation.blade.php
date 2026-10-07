@php
    // Dynamically set the logo link based on role so non-admins don't get 403 errors
    $logoUrl = url('/');
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('Super Admin')) {
            $logoUrl = route('overview_tickets.index');
        } elseif ($user->hasRole('Client')) {
            $logoUrl = route('myrequested_tickets.index');
        } elseif ($user->hasAnyRole(['ICTD', 'ICTS', 'ICTS Admin'])) {
            $logoUrl = route('tickets.index');
        } elseif ($user->hasAnyRole(['DPO', 'DBRT'])) {
            $logoUrl = route('databreach.index');
        }
    }
@endphp

<aside class="sidebar" :class="{ 'collapsed': !sidebarOpen, 'mobile-open': mobileSidebarOpen }">
    
    <div class="sidebar-header">
        <a href="{{ $logoUrl }}" style="display: flex; align-items: center; color: white; width: 100%;">
            <img src="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" alt="CDA Logo" class="sidebar-logo">
            <div class="sidebar-brand">
                <span class="sidebar-brand-title">CDA-ICT Helpdesk</span>
            </div>
        </a>
        <button @click="mobileSidebarOpen = false" class="icon-btn mobile-only" style="margin-left: auto; color: white; padding: 4px;">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>

    <nav class="sidebar-nav">
        @if(auth()->user()->canAny(['view_all_tickets', 'view_overview_tickets', 'view_myrequested_tickets', 'view_assignedtome_tickets']))
        <div x-data="{ 
            open: {{ request()->routeIs('*tickets.index') ? 'true' : 'false' }},
            toggleMenu() {
                if (!sidebarOpen && !mobileSidebarOpen) toggleSidebar(); 
                this.open = !this.open;
            }
        }">

            <button @click="toggleMenu()" title="Ticket Management" class="nav-link {{ request()->routeIs('*tickets.index') ? 'active' : '' }}">
                <span class="material-symbols-outlined">confirmation_number</span>
                <span class="nav-text">Ticket Management</span>
                <svg class="chevron shrink-0" :class="open ? 'open' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <ul x-show="open && (sidebarOpen || mobileSidebarOpen)" x-collapse class="submenu" style="display: none;">
                @if(auth()->user()->can('view_overview_tickets'))
                <li>
                    <a href="{{ route('overview_tickets.index') }}" class="submenu-link {{ request()->routeIs('overview_tickets.index') ? 'active' : '' }}">
                        <span class="submenu-dot"></span> <span class="submenu-text">Tickets Overview</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('view_all_tickets'))
                <li>
                    <a href="{{ route('tickets.index') }}" class="submenu-link {{ request()->routeIs('tickets.index') ? 'active' : '' }}">
                        <span class="submenu-dot"></span> <span class="submenu-text">All Tickets</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('view_myrequested_tickets'))
                <li> 
                    <a href="{{ route('myrequested_tickets.index') }}" class="submenu-link {{ request()->routeIs('myrequested_tickets.index') ? 'active' : '' }}">
                        <span class="submenu-dot"></span> <span class="submenu-text">My Requested Tickets</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('view_assignedtome_tickets'))
                <li> 
                    <a href="{{ route('assignedtome_tickets.index') }}" class="submenu-link {{ request()->routeIs('assignedtome_tickets.index') ? 'active' : '' }}">
                        <span class="submenu-dot"></span> <span class="submenu-text">Tickets Assigned to Me</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('view_reassigned_tickets'))
                <li>
                    <a href="{{ route('reassigned_tickets.index') }}" class="submenu-link {{ request()->routeIs('reassigned_tickets.index') ? 'active' : '' }}">
                        <span class="submenu-dot"></span> <span class="submenu-text">Re-Assigned Tickets</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
        @endif

        @if(auth()->user()->canAny(['view_overview_databreach', 'view_all_databreach', 'view_dbrt']))
        <div x-data="{ 
            open: {{ (request()->routeIs('databreach.*') || request()->is('overview_databreach*')) ? 'true' : 'false' }},
            toggleMenu() {
                if (!sidebarOpen && !mobileSidebarOpen) toggleSidebar(); 
                this.open = !this.open;
            }
        }">

            <button @click="toggleMenu()" title="Incident Management" class="nav-link {{ (request()->routeIs('databreach.*') || request()->is('overview_databreach*')) ? 'active' : '' }}">
                <span class="material-symbols-outlined">shield_radar</span>
                <span class="nav-text">Incident Management</span>
                <svg class="chevron shrink-0" :class="open ? 'open' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <ul x-show="open && (sidebarOpen || mobileSidebarOpen)" x-collapse class="submenu" style="display: none;">
                @if(auth()->user()->can('view_overview_databreach'))
                <li>
                    <a href="{{ url('overview_databreach') }}" class="submenu-link {{ request()->is('overview_databreach*') ? 'active' : '' }}">
                        <span class="submenu-dot"></span> <span class="submenu-text">Incident Overview</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('view_all_databreach'))
                <li>
                    <a href="{{ route('databreach.index') }}" class="submenu-link {{ request()->routeIs('databreach.index') ? 'active' : '' }}">
                        <span class="submenu-dot"></span> <span class="submenu-text">All Incident Reports</span>
                    </a> 
                </li>
                @endif

                @if(auth()->user()->canAny(['view_dbrt', 'create_dbrt', 'edit_dbrt', 'delete_dbrt']))
                <li>
                    <a href="{{ route('databreach.team_databreach') }}" class="submenu-link {{ request()->routeIs('databreach.team_databreach') ? 'active' : '' }}">
                        <span class="submenu-dot"></span> <span class="submenu-text">DBRT</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
        @endif

        @if(auth()->user()->canAny(['view_technical_personnel', 'view_technical_services', 'view_tech_users', 'view_roles']))
            <div class="nav-label">System Setup</div>
            <hr style="display: none;"> 
        @endif

        @if(auth()->user()->canAny(['view_technical_personnel', 'create_technical_personnel', 'edit_technical_personnel', 'delete_technical_personnel', 'search_technical_personnel']))
        <a href="{{ route('tech_personnel.index') }}" 
           title="Technical Personnel"
           class="nav-link {{ request()->routeIs('tech_personnel.index') ? 'active' : '' }}">
            <span class="material-symbols-outlined">engineering</span>
            <span class="nav-text">Technical Personnel</span>
        </a>
        @endif

        @if(auth()->user()->canAny(['view_technical_services', 'create_technical_services', 'edit_technical_services', 'delete_technical_services', 'search_technical_services']))
        <a href="{{ route('tech_services.index') }}" 
           title="Technical Services"
           class="nav-link {{ request()->routeIs('tech_services.index') ? 'active' : '' }}">
            <span class="material-symbols-outlined">checklist</span>
            <span class="nav-text">Technical Services</span>
        </a>
        @endif

        @if(auth()->user()->canAny(['view_tech_users', 'create_tech_users', 'edit_tech_users', 'delete_tech_users', 'tech_users']))
        <a href="{{ route('users.index') }}" 
           title="Users"
           class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
            <span class="material-symbols-outlined">manage_accounts</span>
            <span class="nav-text">User Management</span>
        </a>
        @endif

        @if(auth()->user()->canAny(['view_roles', 'create_roles', 'edit_roles', 'delete_roles', 'search_roles']))
        <a href="{{ route('roles.index') }}" 
           title="Roles"
           class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
            <span class="material-symbols-outlined">add_moderator</span>
            <span class="nav-text">Access Roles</span>
        </a>
        @endif

    </nav>
</aside>
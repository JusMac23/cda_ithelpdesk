<aside id="sidebar" class="w-64 bg-white shadow-lg flex flex-col min-h-screen z-40 fixed top-0 left-0 h-full transition-all duration-300 ease-in-out">
    <div class="flex items-center justify-between px-4 py-3 bg-gray border-b border-gray-200">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-1 text-indigo-700 hover:text-indigo-800 transition overflow-hidden">
            <img src="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" alt="CDA Logo" class="h-10 w-auto logo">
            <span class="text-lg font-bold whitespace-nowrap sidebar-label">CDA-ITHelpdesk</span>
        </a>
        <button id="collapseSidebar" aria-label="Toggle Sidebar" class="ml-auto flex items-center justify-center w-8 h-8 rounded-full text-indigo-600 hover:text-indigo-800 hover:bg-gray-100">
            <span class="material-icons-outlined text-2xl">menu_open</span>
        </button>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

        <!-- Dashboard -->
        @if(auth()->user()->can('view_dashboard'))
        <a href="{{ route('dashboard') }}" class="menu-link flex items-center px-3 py-0 text-gray-800 rounded-full font-medium hover:bg-gray-100 hover:text-indigo-600 transition @if(request()->routeIs('dashboard')) bg-gray-100 text-indigo-600 @endif">
            <div class="icon-wrapper">
                <span class="material-icons-outlined text-xl">dashboard</span>
            </div>
            <span class="sidebar-label ml-2">Dashboard</span>
        </a>
        @endif

        <!-- Ticket Management with toggle -->
        <div x-data="{ open: false }">
            
            @if(auth()->user()->can('view_all_tickets') || auth()->user()->can('view_myrequested_tickets') || auth()->user()->can('view_assignedtome_tickets') || auth()->user()->can('view_reassigned_tickets'))
            <button @click="open = !open" type="button"
                    class=" menu-link w-full flex items-center px-3 py-0 text-gray-800 rounded-full font-medium hover:bg-gray-100 hover:text-indigo-600 transition focus:outline-none">
                <div class="icon-wrapper">
                    <span class="material-icons-outlined text-xl">confirmation_number</span>
                </div>
                <span class="sidebar-label ml-2">Ticket Management</span>
                <svg class="w-4 h-4 ml-2 transition-transform duration-200 transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            @endif

            <ul x-show="open" x-transition class="ml-10 mt-1 space-y-1 text-sm text-gray-700">

                @if(auth()->user()->can('view_all_tickets'))
                <li>
                    <a href="{{ route('tickets.index') }}"
                        class="sidebar-label menu-link flex w-full px-3 py-2 text-gray-800 rounded-full font-medium hover:bg-gray-100 hover:text-indigo-600 transition @if(request()->routeIs('tickets.index')) font-semibold text-indigo-600 @endif">
                        <span class="sidebar-label px-4 flex items-center">
                            <span class="w-2 h-2 bg-indigo-600 rounded-full mr-2"></span> <!-- Small Dot -->
                            All Tickets
                        </span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('view_myrequested_tickets'))
                <li>   
                    <a href="{{ route('myrequested_tickets.index') }}"
                        class="sidebar-label menu-link flex w-full px-3 py-2 text-gray-800 rounded-full font-medium hover:bg-gray-100 hover:text-indigo-600 transition @if(request()->routeIs('myrequested_tickets.index')) font-semibold text-indigo-600 @endif">
                        <span class="sidebar-label px-4 flex items-center">
                            <span class="w-2 h-2 bg-indigo-600 rounded-full mr-2"></span> 
                            My Requested Tickets
                        </span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('view_assignedtome_tickets'))
                <li>   
                    <a href="{{ route('assignedtome_tickets.index') }}"
                        class="sidebar-label menu-link flex w-full px-3 py-2 text-gray-800 rounded-full font-medium hover:bg-gray-100 hover:text-indigo-600 transition @if(request()->routeIs('assignedtome_tickets.index')) font-semibold text-indigo-600 @endif">
                        <span class="sidebar-label px-4 flex items-center">
                            <span class="w-2 h-2 bg-indigo-600 rounded-full mr-2"></span>
                            Tickets Assigned to Me
                        </span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('view_reassigned_tickets'))
                <li>
                    <a href="{{ route('reassigned_tickets.index') }}"
                        class="sidebar-label menu-link flex w-full px-3 py-2 text-gray-800 rounded-full font-medium hover:bg-gray-100 hover:text-indigo-600 transition @if(request()->routeIs('reassigned_tickets.index')) font-semibold text-indigo-600 @endif">
                        <span class="sidebar-label px-4 flex items-center">
                            <span class="w-2 h-2 bg-indigo-600 rounded-full mr-2"></span> 
                            Re-Assigned Tickets
                        </span>
                    </a>
                </li>
                @endif

            </ul>
        </div>

        <!--Databreach Management with toggle -->
        <div x-data="{ open: false }">
            @if(auth()->user()->can('view_all_databreach') || auth()->user()->can('view_overview_databreach') || auth()->user()->can('view_dbrt'))
            <button @click="open = !open" type="button"
                    class=" menu-link w-full flex items-center px-3 py-0 text-gray-800 rounded-full font-medium hover:bg-gray-100 hover:text-indigo-600 transition focus:outline-none">
                <div class="icon-wrapper">
                    <span class="material-icons-outlined text-xl">security</span>
                </div>
                <span class="sidebar-label ml-2">Data Breach Report</span>
                <svg class="w-4 h-4 ml-2 transition-transform duration-200 transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            @endif

            <ul x-show="open" x-transition class="ml-10 mt-1 space-y-1 text-sm text-gray-700">

                @if(auth()->user()->can('view_all_databreach'))
                <li>
                    <a href="{{ route('databreach.index') }}"
                        class="sidebar-label menu-link flex w-full px-3 py-2 text-gray-800 rounded-full font-medium hover:bg-gray-100 hover:text-indigo-600 transition @if(request()->routeIs('databreach.index')) font-semibold text-indigo-600 @endif">
                        <span class="sidebar-label px-4 flex items-center">
                            <span class="w-2 h-2 bg-indigo-600 rounded-full mr-2"></span> 
                            All Reports
                        </span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('view_overview_databreach'))
                <li>
                    <a href="{{ url('overview_databreach') }}"
                        class="sidebar-label menu-link flex w-full px-3 py-2 text-gray-800 rounded-full font-medium hover:bg-gray-100 hover:text-indigo-600 transition @if(request()->routeIs('overview_databreach')) font-semibold text-indigo-600 @endif">
                        <span class="sidebar-label px-4 flex items-center">
                            <span class="w-2 h-2 bg-indigo-600 rounded-full mr-2"></span> 
                            Data Breach Overview
                        </span>
                    </a>
                </li>
                @endif

                <!--
                <li>   
                    <a href="{{ route('databreach.per_region_databreach') }}"
                        class="sidebar-label menu-link flex w-full px-3 py-2 text-gray-800 rounded-full font-medium hover:bg-gray-100 hover:text-indigo-600 transition @if(request()->routeIs('databreach.team_databreach')) font-semibold text-indigo-600 @endif">
                        <span class="sidebar-label px-4 flex items-center">
                            <span class="w-2 h-2 bg-indigo-600 rounded-full mr-2"></span> 
                            Data Breach Notifications Regional Reports
                        </span>
                    </a>
                </li>
                -->

                @if(auth()->user()->can('view_dbrt') || auth()->user()->can('create_dbrt') || auth()->user()->can('edit_dbrt') || auth()->user()->can('delete_dbrt'))
                <li>   
                    <a href="{{ route('databreach.team_databreach') }}"
                        class="sidebar-label menu-link flex w-full px-3 py-2 text-gray-800 rounded-full font-medium hover:bg-gray-100 hover:text-indigo-600 transition @if(request()->routeIs('databreach.team_databreach')) font-semibold text-indigo-600 @endif">
                        <span class="sidebar-label px-4 flex items-center">
                            <span class="w-2 h-2 bg-indigo-600 rounded-full mr-2"></span> 
                            Data Breach Response Team
                        </span>
                    </a>
                </li>
                @endif
            </ul>
        </div>

        <!-- Technical Personnel -->
        @if(auth()->user()->can('view_technical_personnel') || auth()->user()->can('create_technical_personnel') || auth()->user()->can('edit_technical_personnel') || auth()->user()->can('delete_technical_personnel') || auth()->user()->can('search_technical_personnel'))
        <a href="{{ route('tech_personnel.index') }}" class="menu-link flex items-center px-3 py-0 text-gray-800 rounded-full font-medium hover:bg-gray-100 hover:text-indigo-600 transition">
            <div class="icon-wrapper">
                <span class="material-icons-outlined text-xl">engineering</span>
            </div>
            <span class="sidebar-label ml-2">Technical Personnel</span>
        </a>
        @endif

        <!--
        <a href="#" class="menu-link flex items-center px-3 py-0 text-gray-800 rounded-full font-medium hover:bg-gray-100 hover:text-indigo-600 transition">
            <div class="icon-wrapper">
                <span class="material-icons-outlined text-xl">folder_shared</span>
            </div>
            <span class="sidebar-label ml-2">Active Directory</span>
        </a>
        -->

        <!-- Technical Services -->
        @if(auth()->user()->can('view_technical_services') || auth()->user()->can('create_technical_services') || auth()->user()->can('edit_technical_services') || auth()->user()->can('delete_technical_services') || auth()->user()->can('search_technical_services'))
        <a href="{{ route('tech_services.index') }}" class="menu-link flex items-center px-3 py-0 text-gray-800 rounded-full font-medium hover:bg-gray-100 hover:text-indigo-600 transition">
            <div class="icon-wrapper">
                <span class="material-icons-outlined text-xl">checklist</span>
            </div>
            <span class="sidebar-label ml-2">Technical Services</span>
        </a>
        @endif

        <!-- Users -->
        @if(auth()->user()->can('view_tech_users') || auth()->user()->can('create_tech_users') || auth()->user()->can('edit_tech_users') || auth()->user()->can('delete_tech_users') || auth()->user()->can('tech_users'))
        <a href="{{ route('users.index') }}" class="menu-link flex items-center px-3 py-0 text-gray-800 rounded-full font-medium hover:bg-gray-100 hover:text-indigo-600 transition">
            <div class="icon-wrapper">
                <span class="material-icons-outlined text-xl">group</span>
            </div>
            <span class="sidebar-label ml-2">Users</span>
        </a>
        @endif

        <!-- Roles -->
        @if(auth()->user()->can('view_roles') || auth()->user()->can('create_roles') || auth()->user()->can('edit_roles') || auth()->user()->can('delete_roles') || auth()->user()->can('search_roles'))
        <a href="{{ route('roles.index') }}" class="menu-link flex items-center px-3 py-0 text-gray-800 rounded-full font-medium hover:bg-gray-100 hover:text-indigo-600 transition">
            <div class="icon-wrapper">
                <span class="material-icons-outlined text-xl">add_moderator</span>
            </div>
            <span class="sidebar-label ml-2">Roles</span>
        </a>
        @endif
    </nav>

    <div class="px-3 py-4 border-t border-gray-200">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="menu-link w-full flex items-center px-3 py-0 text-red-600 font-medium rounded-full hover:bg-red-100 transition">
                <div class="icon-wrapper">
                    <span class="material-icons-outlined text-xl">logout</span>
                </div>
                <span class="sidebar-label ml-2">Logout</span>
            </button>
        </form>
    </div>
</aside>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const toggleButton = document.getElementById("collapseSidebar");
        const sidebar = document.getElementById("sidebar");
        const mainContent = document.getElementById("main-content");
        const section = document.getElementById("section");
        const labels = document.querySelectorAll(".sidebar-label");
        const logo = document.querySelector(".logo");
        const toggleIcon = toggleButton.querySelector(".material-icons-outlined");

        const setSidebarState = (isCollapsed) => {
            if (isCollapsed) {
                sidebar.classList.remove("w-64");
                sidebar.classList.add("w-16", "sidebar-collapsed");
                document.body.classList.remove("sidebar-expanded");
                document.body.classList.add("sidebar-collapsed");
                labels.forEach(label => label.classList.add("hidden"));
                if (logo) logo.classList.add("hidden");
                toggleIcon.textContent = "menu";
                toggleButton.classList.add("mt-2");
            } else {
                sidebar.classList.remove("w-16", "sidebar-collapsed");
                sidebar.classList.add("w-64");
                document.body.classList.remove("sidebar-collapsed");
                document.body.classList.add("sidebar-expanded");
                labels.forEach(label => label.classList.remove("hidden"));
                if (logo) logo.classList.remove("hidden");
                toggleIcon.textContent = "menu_open";
                toggleButton.classList.remove("mb-2"); 
            }

            localStorage.setItem("sidebarCollapsed", isCollapsed);
        };

        const collapsed = localStorage.getItem("sidebarCollapsed") === "true";
        setSidebarState(collapsed);

        toggleButton.addEventListener("click", () => {
            const isCollapsed = sidebar.classList.contains("w-16");
            setSidebarState(!isCollapsed);
        });
    });
</script>

<style>
    #main-content,
    #section {
        transition: margin-left 0.3s ease-in-out;
        margin-left: 1rem; 

    }

    .sidebar-expanded #main-content,
    .sidebar-expanded #section {
        margin-left: 8rem;
    }

    .sidebar-collapsed #main-content,
    .sidebar-collapsed #section {
        margin-left: 2rem;
    }


    #sidebar {
        transition: width 0.3s ease-in-out;
    }

    .menu-link {
        display: flex;
        align-items: center;
        transition: all 0.3s ease-in-out;
    }

    .icon-wrapper {
        width: 2.5rem;
        height: 2.5rem;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .sidebar-label {
        transition: opacity 0.3s ease, margin 0.3s ease;
    }

    .sidebar-collapsed .menu-link {
        justify-content: center !important;
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
    }
    .sidebar-collapsed .icon-wrapper {
        margin: 0 !important;
    }
    .sidebar-collapsed .sidebar-label,
    .sidebar-collapsed .logo {
        display: none !important;
    }
</style>
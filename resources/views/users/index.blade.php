<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    
    <style>
        /* --- Theme Variables --- */
        :root {
            --card-bg: #ffffff;
            --bg-alt: #f8fafc;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border-light: #e2e8f0;
            --border-subtle: #f1f5f9;
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
            --input-text: #334155;

            /* Action Buttons (Gray) */
            --btn-gray-bg: #f1f5f9;
            --btn-gray-text: #475569;
            --btn-gray-border: #e2e8f0;
            --btn-gray-hover-bg: #e2e8f0;
            --btn-gray-hover-text: #0f172a;

            /* Error States */
            --error-bg: #fef2f2;
            --error-border: #fecaca;
            --error-text: #991b1b;
            --error-title: #7f1d1d;
            
            /* Readonly */
            --readonly-bg: #f8fafc;
            
            /* Badges */
            --badge-role-bg: #eef2ff;
            --badge-role-text: #4f46e5;
            --badge-role-border: #c7d2fe;
            --badge-none-text: #ef4444;
        }

        body.dark {
            --card-bg: #0f172a; 
            --bg-alt: #1e293b; 
            --text-dark: #f8fafc;
            --text-muted: #9ca3af;
            --border-light: #334155; 
            --border-subtle: #1e293b;
            --input-bg: #0f172a;
            --input-border: #4b5563;
            --input-text: #f1f5f9;

            /* Action Buttons (Gray) - Dark */
            --btn-gray-bg: #1e293b;
            --btn-gray-text: #9ca3af;
            --btn-gray-border: #334155;
            --btn-gray-hover-bg: #334155;
            --btn-gray-hover-text: #f8fafc;

            /* Error States - Dark */
            --error-bg: rgba(153, 27, 27, 0.2);
            --error-border: rgba(248, 113, 113, 0.4);
            --error-text: #fca5a5;
            --error-title: #f87171;
            
            /* Readonly */
            --readonly-bg: #1e293b;
            
            /* Badges - Dark */
            --badge-role-bg: rgba(79, 70, 229, 0.2);
            --badge-role-text: #a5b4fc;
            --badge-role-border: #4f46e5;
            --badge-none-text: #f87171;
        }

        /* Global Box Sizing & Font Fix */
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; transition: background-color 0.3s ease, color 0.3s ease; }

        /* Main Layout - Mobile First 100% Width & Dark Mode Outline */
        .panel { background-color: var(--card-bg); border-radius: 1rem; border: 1px solid var(--border-light); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); padding: 1.25rem; width: 100%; transition: background-color 0.3s ease, border-color 0.3s ease; }
        
        /* Typography */
        .header-flex { display: flex; flex-direction: column; align-items: flex-start; margin-bottom: 1.5rem; gap: 1rem; width: 100%; }
        .title { font-size: 1.75rem; font-weight: 800; color: var(--text-dark); margin: 0; letter-spacing: -0.025em; transition: color 0.3s ease; }
        
        /* --- Action Container & Search Toolbar - Mobile First --- */
        .action-container { display: flex; flex-direction: column; width: 100%; gap: 1rem; margin-bottom: 1.5rem; }
        
        .search-form { display: flex; align-items: stretch; width: 100%; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border-radius: 0.5rem; }
        .search-input { height: 44px; flex: 1; min-width: 0; padding: 0 1rem; font-size: 0.95rem; font-family: inherit; background-color: var(--input-bg); color: var(--input-text); border: 1px solid var(--input-border); border-right: none; border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem; outline: none; transition: all 0.2s; position: relative; z-index: 1; }
        .search-input:focus { border-color: #6366f1; box-shadow: inset 0 0 0 1px #6366f1, 0 0 0 3px rgba(99, 102, 241, 0.15); z-index: 10; }
        .search-input::placeholder { color: var(--text-muted); opacity: 0.7; }
        .search-btn { display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 1.25rem; border: none; border-top-right-radius: 0.5rem; border-bottom-right-radius: 0.5rem; background-color: #4f46e5; color: white; cursor: pointer; transition: background-color 0.2s; z-index: 2; width: auto; }
        .search-btn:hover { background-color: #4338ca; }

        /* --- Buttons - Uniform Heights --- */
        .btn { display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 1.5rem; border-radius: 0.5rem; font-size: 0.95rem; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s ease; width: 100%; text-decoration: none; font-family: inherit; }
        .btn i { margin-right: 0.5rem; font-size: 1rem; }
        
        /* Modern Green */
        .btn-green { background-color: #10b981; color: white; box-shadow: 0 1px 2px rgba(16, 185, 129, 0.2); }
        .btn-green:hover { background-color: #059669; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
        .btn-green:active { transform: translateY(0); box-shadow: 0 1px 2px rgba(16, 185, 129, 0.2); }

        /* Modern Indigo */
        .btn-indigo { background-color: #4f46e5; color: white; box-shadow: 0 1px 2px rgba(79, 70, 229, 0.2); }
        .btn-indigo:hover { background-color: #4338ca; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
        .btn-indigo:active { transform: translateY(0); box-shadow: 0 1px 2px rgba(79, 70, 229, 0.2); }

        /* Modern Gray */
        .btn-gray { background-color: var(--btn-gray-bg); color: var(--btn-gray-text); border: 1px solid var(--btn-gray-border); transition: all 0.3s ease; }
        .btn-gray:hover { background-color: var(--btn-gray-hover-bg); color: var(--btn-gray-hover-text); }

        /* Action Buttons inside Table */
        .action-cell { display: flex; flex-wrap: wrap; justify-content: flex-start; align-items: center; gap: 0.5rem; }
        .action-link { display: inline-flex; align-items: center; justify-content: center; height: 34px; padding: 0 0.85rem; border-radius: 0.375rem; font-size: 0.85rem; font-weight: 600; font-family: inherit; cursor: pointer; transition: all 0.2s; text-decoration: none; background: transparent; white-space: nowrap; box-sizing: border-box; }
        .action-link i { margin-right: 0.35rem; font-size: 0.9rem; }
        
        .link-blue { color: #3b82f6; border: 1px solid #bfdbfe; } 
        .link-blue:hover { background-color: #eff6ff; color: #1d4ed8; border-color: #93c5fd; }
        
        .link-red { color: #ef4444; border: 1px solid #fecaca; } 
        .link-red:hover { background-color: #fef2f2; color: #b91c1c; border-color: #fca5a5; }

        /* Dark Mode Action Link Overrides */
        body.dark .link-blue { color: #60a5fa; border-color: #1e3a8a; }
        body.dark .link-blue:hover { background-color: rgba(30, 58, 138, 0.4); color: #93c5fd; }
        body.dark .link-red { color: #f87171; border-color: #7f1d1d; }
        body.dark .link-red:hover { background-color: rgba(127, 29, 29, 0.4); color: #fca5a5; }

        /* Table & Badges */
        .table-container { overflow-x: auto; background-color: var(--card-bg); border-radius: 0.75rem; border: 1px solid var(--border-light); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); width: 100%; -webkit-overflow-scrolling: touch; margin-bottom: 1.5rem; transition: background-color 0.3s ease, border-color 0.3s ease; }
        .data-table { width: 100%; min-width: 900px; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
        .data-table th { padding: 1rem 1.5rem; background-color: var(--bg-alt); color: var(--text-muted); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid var(--border-light); white-space: nowrap; transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease; }
        .data-table td { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-subtle); color: var(--text-dark); vertical-align: middle; font-weight: 500; transition: color 0.3s ease, border-color 0.3s ease; }
        .data-table tbody tr { transition: background-color 0.15s; }
        .data-table tbody tr:hover { background-color: var(--bg-alt); }
        .text-center { text-align: center; }
        .font-bold-name { font-weight: 700; color: var(--text-dark); transition: color 0.3s ease; }

        /* Clean Role Badges */
        .role-badge { display: inline-flex; align-items: center; padding: 0.35rem 0.85rem; background-color: var(--badge-role-bg); color: var(--badge-role-text); border: 1px solid var(--badge-role-border); border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.025em; white-space: nowrap; margin-bottom: 0.25rem; transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease; }
        .role-none { font-size: 0.8rem; color: var(--badge-none-text); font-style: italic; font-weight: 600; transition: color 0.3s ease; }

        /* --- Modern UI Pagination (Laravel Structure Fix) --- */
        .pagination-wrapper { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-light); width: 100%; transition: border-color 0.3s ease; }
        .pagination-wrapper nav { display: flex; flex-direction: column; gap: 1.25rem; width: 100%; align-items: center; }
        
        /* Pagination Sub-Text */
        .pagination-wrapper p { margin: 0; font-size: 0.875rem; color: var(--text-muted); font-weight: 500; text-align: center; transition: color 0.3s ease; }
        .pagination-wrapper p span { font-weight: 700; color: var(--text-dark); transition: color 0.3s ease; }

        /* Container for links */
        .pagination-wrapper div > span.relative.z-0.inline-flex,
        .pagination-wrapper .flex.justify-between { display: flex; flex-wrap: wrap; gap: 0.5rem; box-shadow: none !important; justify-content: center; align-items: center; }

        /* Uniform Button Styling for Page Numbers & Arrows */
        .pagination-wrapper a, 
        .pagination-wrapper span[aria-current="page"] > span,
        .pagination-wrapper span[aria-disabled="true"] > span { display: inline-flex; align-items: center; justify-content: center; min-width: 2.25rem; height: 2.25rem; padding: 0 0.5rem; border-radius: 0.375rem !important; font-size: 0.875rem; font-weight: 600; font-family: 'Inter', sans-serif; transition: all 0.2s ease; border: 1px solid transparent; margin: 0 !important; text-decoration: none; line-height: 1; }

        /* Default Inactive Links */
        .pagination-wrapper a { background-color: var(--card-bg); color: var(--text-muted); border-color: var(--border-light); }
        .pagination-wrapper a:hover { background-color: var(--bg-alt); color: var(--text-dark); border-color: var(--input-border); transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

        /* Active Page Link */
        .pagination-wrapper span[aria-current="page"] > span { background-color: #4f46e5; color: #ffffff; border-color: #4f46e5; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.25); z-index: 2; position: relative; }

        /* Disabled Navigation Arrows */
        .pagination-wrapper span[aria-disabled="true"] > span { background-color: var(--bg-alt); color: var(--text-muted); border-color: var(--border-light); cursor: not-allowed; opacity: 0.7; }

        /* "..." Separator Fix */
        .pagination-wrapper span[aria-disabled="true"]:not([aria-label]) > span { background: transparent; border: none; opacity: 1; color: var(--text-muted); }

        /* Standardize Arrow SVGs */
        .pagination-wrapper svg { width: 1.25rem !important; height: 1.25rem !important; display: block; }

        /* Modals - Smooth Scaling Transitions */
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 50; display: flex; align-items: center; justify-content: center; padding: 1rem; opacity: 1; visibility: visible; transition: all 0.3s ease; }
        .modal-overlay.hidden { opacity: 0; visibility: hidden; pointer-events: none; }
        
        .modal-box { position: relative; background-color: var(--card-bg); border-radius: 1rem; border: 1px solid var(--border-light); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); width: 100%; max-width: 48rem; max-height: 90vh; overflow-y: auto; padding: 1.5rem; transform: scale(1); transition: transform 0.3s ease, background-color 0.3s ease, border-color 0.3s ease; }
        .modal-overlay.hidden .modal-box { transform: scale(0.95); }
        
        /* Fixed Modal Close Button */
        .close-btn { position: absolute; top: 1.25rem; right: 1.25rem; color: var(--text-muted); font-size: 2.25rem; background: none; border: none; cursor: pointer; transition: color 0.2s, background-color 0.2s; line-height: 1; border-radius: 0.25rem; padding: 0 0.5rem; }
        .close-btn:hover { color: var(--text-dark); }
        
        .modal-title { font-size: 1.5rem; font-weight: 800; color: var(--text-dark); margin-top: 0; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-light); padding-bottom: 1rem; padding-right: 2.5rem; transition: color 0.3s ease, border-color 0.3s ease; }
        
        /* Form Grid - Mobile First 100% Width */
        .form-grid { display: flex; flex-direction: column; gap: 1.25rem; width: 100%; }

        /* Form Controls - Unified Heights */
        .form-group { display: flex; flex-direction: column; width: 100%; }
        .form-label { font-size: 0.875rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.5rem; width: 100%; transition: color 0.3s ease; }
        .form-input, .form-select { height: 44px; padding: 0 1rem; font-size: 0.95rem; color: var(--input-text); border: 1px solid var(--input-border); border-radius: 0.5rem; background-color: var(--input-bg); outline: none; transition: all 0.2s; font-family: inherit; width: 100%; box-sizing: border-box; }
        .form-input:focus, .form-select:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }
        
        .form-input[readonly] { background-color: var(--readonly-bg) !important; color: var(--text-muted) !important; cursor: not-allowed; border-color: var(--border-light); }
        .form-input[readonly]:focus { box-shadow: none; border-color: var(--border-light); }

        /* Custom Radio Buttons */
        .radio-group { display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.5rem; }
        .radio-label { display: flex; align-items: center; gap: 0.75rem; font-size: 0.95rem; font-weight: 500; color: var(--input-text); cursor: pointer; transition: color 0.3s ease; }
        .radio-input { width: 1.15rem; height: 1.15rem; accent-color: #4f46e5; cursor: pointer; margin: 0; }
        
        .modal-footer { display: flex; flex-direction: column; padding-top: 1.5rem; border-top: 1px solid var(--border-light); margin-top: 1.5rem; gap: 0.75rem; width: 100%; transition: border-color 0.3s ease; }

        /* Error Box */
        .error-box { background-color: var(--error-bg); border: 1px solid var(--error-border); color: var(--error-text); padding: 1.25rem; border-radius: 0.5rem; margin-bottom: 1.5rem; transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease; }
        .error-title { margin: 0 0 0.5rem 0; font-weight: 700; font-size: 0.95rem; color: var(--error-title); transition: color 0.3s ease; }
        .error-list { margin: 0; padding-left: 1.5rem; font-size: 0.9rem; font-weight: 500; }

        /* Full-Screen Loading Overlay */
        .loading-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); display: none; flex-direction: column; align-items: center; justify-content: center; z-index: 9999; color: #ffffff; }
        .loading-overlay.active { display: flex; }
        .spinner { width: 44px; height: 44px; border: 4px solid rgba(255, 255, 255, 0.3); border-top-color: #ffffff; border-radius: 50%; animation: spin 0.8s linear infinite; margin-bottom: 1rem; }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* --------------------------------------------------- */
        /* Responsive Overrides                                */
        /* --------------------------------------------------- */
        
        /* Mobile Breakpoint for Pagination */
        @media (max-width: 639px) {
            .pagination-wrapper nav .hidden { display: none !important; }
            .pagination-wrapper nav .sm\:hidden { display: flex; width: 100%; justify-content: space-between; }
        }

        /* Desktop & Tablet Overrides */
        @media (min-width: 640px) {
            .panel { padding: 2rem; }
            .header-flex { flex-direction: row; justify-content: space-between; align-items: center; }
            
            /* Align Add button and Search inline */
            .action-container { flex-direction: row; justify-content: space-between; align-items: center; }
            .search-form { width: auto; min-width: 320px; }
            
            /* Un-stretch buttons on desktop */
            .btn { width: auto; }
            
            /* Restore Grid layout for Desktop */
            .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
            .col-span-2 { grid-column: span 2; }
            
            /* Modal formatting for Desktop */
            .modal-box { padding: 2.5rem; }
            .close-btn { top: 1.5rem; right: 2rem; }
            
            /* Modal Footer Buttons */
            .modal-footer { flex-direction: row; justify-content: flex-end; }

            /* Pagination Layout */
            .pagination-wrapper nav { flex-direction: row; justify-content: space-between; }
            .pagination-wrapper nav > div.sm\:hidden { display: none !important; }
            .pagination-wrapper nav > div.hidden.sm\:flex-1 { display: flex !important; width: 100%; justify-content: space-between; align-items: center; }
        }
    </style>

    <!-- Full-Screen Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="spinner"></div>
        <p style="font-size: 1rem; font-weight: 600; letter-spacing: 0.025em;">Processing User, please wait...</p>
    </div>

    <div id="main-content">
        <div class="panel">
            
            <div class="header-flex">
                <h3 class="title">All Users</h3>
            </div>

            <div class="action-container">
                @can('create_tech_users')
                    <button id="openModal" class="btn btn-green">
                        <span class="material-symbols-outlined" style="font-size: 1.25rem; margin-right: 0.2rem;">add</span> Add User
                    </button>
                @endcan

                <form action="{{ route('users.index') }}" method="GET" class="search-form">
                    <input type="text" name="search_query" value="{{ request('search_query') }}" placeholder="Search users..." class="search-input" autocomplete="off">
                    <button type="submit" class="search-btn">
                        <span class="material-symbols-outlined" style="font-size: 1.25rem; margin-right: 0.2rem;">search</span>
                    </button>
                </form>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>FullName</th>
                            <th>Email Address</th>
                            <th>Region</th>
                            <th>Contact Number</th>
                            <th>Role</th>
                            @can('delete_tech_users')<th class="text-center">Actions</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="font-bold-name">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->region }}</td>
                                <td>{{ $user->contact_number ?: 'N/A' }}</td>

                                <td>
                                    @if($user->roles->isNotEmpty())
                                        @foreach($user->roles as $role)
                                            <span class="role-badge">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="role-none">
                                            No Role Assigned
                                        </span>
                                    @endif
                                </td>

                                @if(auth()->user()->can('edit_tech_users') || auth()->user()->can('delete_tech_users'))
                                <td>
                                    <div class="action-cell" @if(auth()->user()->can('edit_tech_users') && auth()->user()->can('delete_tech_users')) style="justify-content: center;" @endif>
                                        
                                        {{-- Edit Button --}}
                                        @can('edit_tech_users')
                                            <button type="button" class="action-link link-blue editBtn"
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-region="{{ $user->region }}"
                                                data-contact-number="{{ $user->contact_number }}"
                                                data-role-ids="{{ $user->roles->pluck('id')->toJson() }}">
                                                <span class="material-symbols-outlined" style="font-size: 1.25rem; margin-right: 0.2rem;">edit</span> Edit
                                            </button>
                                        @endcan

                                        {{-- Delete Button --}}
                                        @can('delete_tech_users')
                                            <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="margin:0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="action-link link-red delete-btn" data-id="{{ $user->id }}">
                                                    <span class="material-symbols-outlined" style="font-size: 1.25rem; margin-right: 0.2rem;">delete</span> Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center" style="padding: 3rem; color: var(--text-muted); font-size: 1rem;">
                                    No Users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                {{ $users->links() }}
            </div>
            
        </div>
    </div>

    {{-- Modal: Add User --}}
    <div id="userModal" class="modal-overlay hidden">
        <div id="userModalContent" class="modal-box">
            <button id="closeModal" class="close-btn" aria-label="Close Modal">&times;</button>

            @if ($errors->any())
                <div class="error-box">
                    <h4 class="error-title"><span class="material-symbols-outlined" style="font-size: 1.25rem; margin-right: 0.2rem;">error</span> Please fix the following error(s):</h4>
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h2 class="modal-title">Add New User</h2>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group col-span-2">
                        <label for="name" class="form-label">Full Name<span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" id="name" required placeholder="e.g., Juan A. Dela Cruz" class="form-input" autocomplete="name" value="{{ old('name') }}">
                    </div>

                    <div class="form-group col-span-2">
                        <label for="email" class="form-label">Email Address<span style="color:#ef4444;">*</span></label>
                        <input type="email" name="email" id="email" required placeholder="e.g., j_delacruz@cda.gov.ph" class="form-input" autocomplete="email" value="{{ old('email') }}">
                    </div>

                    <div class="form-group col-span-2">
                        <label for="region" class="form-label">Region Assignment<span style="color:#ef4444;">*</span></label>
                        <select name="region" id="region" required class="form-select">
                            <option value="" disabled {{ old('region') ? '' : 'selected' }}>Select Region</option>
                            @foreach ($region as $area)
                                <option value="{{ $area }}" {{ old('region') == $area ? 'selected' : '' }}>{{ $area }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-span-2">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" id="contact_number" placeholder="e.g., 09123456789" class="form-input" autocomplete="tel" value="{{ old('contact_number') }}">
                    </div>

                    <!-- Password Options -->
                    <div class="form-group col-span-2">
                        <label class="form-label" for="auto_generate_password">
                            <input type="checkbox" id="auto_generate_password" class="form-checkbox">
                            <span>Generate Password Automatically</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password<span style="color:#ef4444;">*</span></label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <input type="password" name="password" id="password" required class="form-input" autocomplete="new-password" style="padding-right: 2.5rem;">
                            <button type="button" id="regenerateBtn" class="hidden" style="position: absolute; right: 0.5rem; background: none; border: none; cursor: pointer; color: #4f46e5;" title="Regenerate Password">
                                <span class="material-symbols-outlined" style="font-size: 1.25rem;">refresh</span>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password<span style="color:#ef4444;">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="form-input" autocomplete="new-password">
                    </div>

                    <div class="form-group col-span-2">
                        <span class="form-label">Select System Role(s)<span style="color:#ef4444;">*</span></span>
                        <div class="radio-group">
                            @foreach ($roles as $role)
                                <label class="radio-label">
                                    <input type="checkbox" name="roles[]" id="add_role_{{ $loop->index }}" value="{{ $role->id }}" class="radio-input" {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                    <span>{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('roles')
                            <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem; font-weight: 500;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group col-span-2">
                        <label for="created_at" class="form-label">Date Added</label>
                        <input type="text" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" readonly class="form-input">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-indigo">Register User</button>
                    <button type="button" class="btn btn-gray" id="cancelAddModal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Edit User --}}
    <div id="editModal" class="modal-overlay hidden">
        <div id="editModalContent" class="modal-box">
            <button id="closeEditModal" class="close-btn" aria-label="Close Modal">&times;</button>

            @if ($errors->any())
                <div class="error-box">
                    <h4 class="error-title"><span class="material-symbols-outlined" style="font-size: 1.25rem; margin-right: 0.2rem;">error</span> Please fix the following error(s):</h4>
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h2 class="modal-title">Edit User Details</h2>

            <form id="editForm" method="POST" action="#">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="form-group col-span-2">
                        <label for="edit_name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="edit_name" value="" required class="form-input" autocomplete="name">
                    </div>

                    <div class="form-group col-span-2">
                        <label for="edit_email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="edit_email" value="" required class="form-input" autocomplete="email">
                    </div>

                    <div class="form-group col-span-2">
                        <label for="edit_region" class="form-label">Region Assignment<span style="color:#ef4444;">*</span></label>
                        <select name="region" id="edit_region" required class="form-select">
                            <option value="" disabled selected>Select Region</option>
                            @foreach ($region as $area)
                                <option value="{{ $area }}">{{ $area }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-span-2">
                        <label for="edit_contact_number" class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" id="edit_contact_number" value="" class="form-input" autocomplete="tel">
                    </div>

                    <div class="form-group col-span-2">
                        <span class="form-label">Select System Role(s)<span style="color:#ef4444;">*</span></span>
                        <div class="radio-group">
                            @foreach ($roles as $role)
                                <label class="radio-label">
                                    <input type="checkbox" name="roles[]" id="edit_role_{{ $loop->index }}" value="{{ $role->id }}" class="radio-input">
                                    <span>{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('roles')
                            <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem; font-weight: 500;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group col-span-2">
                        <label class="form-label">Last Updated</label>
                        <input type="text" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" readonly class="form-input">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-indigo">Save Changes</button>
                    <button type="button" class="btn btn-gray" id="cancelEditModal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // --- LOADING OVERLAY HELPER UTILITIES ---
            function showLoading() {
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) overlay.classList.add('active');
            }

            function hideLoading() {
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) overlay.classList.remove('active');
            }

            // Helper to get CSS variable colors for SweetAlert Dark Mode
            const getComputedColor = (cssVar) => getComputedStyle(document.body).getPropertyValue(cssVar).trim();

            // --- SWEETALERT NOTIFICATIONS ---
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{!! addslashes(session("success")) !!}',
                    timer: 2500,
                    showConfirmButton: false,
                    background: getComputedColor('--card-bg'),
                    color: getComputedColor('--text-dark')
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Notice!',
                    text: '{!! addslashes(session("error")) !!}',
                    timer: 3000,
                    showConfirmButton: false,
                    background: getComputedColor('--card-bg'),
                    color: getComputedColor('--text-dark')
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    showConfirmButton: true,
                    confirmButtonColor: '#4f46e5',
                    background: getComputedColor('--card-bg'),
                    color: getComputedColor('--text-dark')
                });
            @endif

            // Modal Helpers
            const body = document.body;

            function openModal(modal) {
                if (!modal) return;
                modal.classList.remove('hidden');
                body.classList.add('overflow-hidden');
            }

            function closeModal(modal) {
                if (!modal) return;
                modal.classList.add('hidden');
                body.classList.remove('overflow-hidden');
            }

            // --- ADD USER MODAL TOGGLES ---
            const addModal = document.getElementById("userModal");
            const openAddBtn = document.getElementById("openModal");
            const closeAddBtn = document.getElementById("closeModal");
            const cancelAddBtn = document.getElementById("cancelAddModal");

            if (openAddBtn && addModal) {
                openAddBtn.addEventListener("click", () => openModal(addModal));
            }

            const closeAddModalFunc = () => closeModal(addModal);

            if (closeAddBtn) closeAddBtn.addEventListener("click", closeAddModalFunc);
            if (cancelAddBtn) cancelAddBtn.addEventListener("click", closeAddModalFunc);

            if (addModal) {
                addModal.addEventListener("click", (e) => {
                    if (e.target === addModal) closeAddModalFunc();
                });

                // Trigger loading spinner on Add User Form submission
                const addForm = addModal.querySelector('form');
                if (addForm) {
                    addForm.addEventListener('submit', function () {
                        showLoading();
                    });
                }
            }

            // --- PASSWORD AUTO-GENERATE & VISIBILITY LOGIC ---
            const autoGenCheckbox = document.getElementById('auto_generate_password');
            const passInput = document.getElementById('password');
            const passConfirmInput = document.getElementById('password_confirmation');
            const regenerateBtn = document.getElementById('regenerateBtn');

            function generateRandomPassword(length = 12) {
                const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
                let password = "";
                for (let i = 0; i < length; i++) {
                    password += charset.charAt(Math.floor(Math.random() * charset.length));
                }
                return password;
            }

            function applyRandomPassword() {
                const newPassword = generateRandomPassword(12);
                if (passInput) passInput.value = newPassword;
                if (passConfirmInput) passConfirmInput.value = newPassword;
            }

            if (autoGenCheckbox) {
                autoGenCheckbox.addEventListener('change', function () {
                    if (this.checked) {
                        if (passInput) {
                            passInput.type = "text";
                            passInput.readOnly = true;
                        }
                        if (passConfirmInput) {
                            passConfirmInput.type = "text";
                            passConfirmInput.readOnly = true;
                        }
                        if (regenerateBtn) regenerateBtn.classList.remove('hidden');

                        applyRandomPassword();
                    } else {
                        if (passInput) {
                            passInput.type = "password";
                            passInput.readOnly = false;
                            passInput.value = "";
                        }
                        if (passConfirmInput) {
                            passConfirmInput.type = "password";
                            passConfirmInput.readOnly = false;
                            passConfirmInput.value = "";
                        }
                        if (regenerateBtn) regenerateBtn.classList.add('hidden');
                    }
                });
            }

            if (regenerateBtn) {
                regenerateBtn.addEventListener('click', function () {
                    applyRandomPassword();
                });
            }

            // --- EDIT USER MODAL TOGGLES ---
            const editModal = document.getElementById("editModal");
            const closeEditBtn = document.getElementById("closeEditModal");
            const cancelEditBtn = document.getElementById("cancelEditModal");
            const editButtons = document.querySelectorAll(".editBtn");

            const editForm = document.getElementById("editForm");
            const editName = document.getElementById("edit_name");
            const editRegion = document.getElementById("edit_region");
            const editEmail = document.getElementById("edit_email");
            const editContactNumber = document.getElementById("edit_contact_number");

            editButtons.forEach(button => {
                button.addEventListener("click", (e) => {
                    e.preventDefault();

                    const id = button.dataset.id;
                    const name = button.dataset.name;
                    const email = button.dataset.email;
                    const region = button.dataset.region;
                    const contactNumber = button.dataset.contactNumber;

                    // Extract role IDs array from dataset
                    let existingRoleIds = [];
                    try {
                        existingRoleIds = JSON.parse(button.dataset.roleIds).map(String);
                    } catch (err) {
                        if (button.dataset.roleIds) {
                            existingRoleIds = String(button.dataset.roleIds).split(',').map(s => s.trim());
                        }
                    }

                    // Populate form fields
                    if (editName) editName.value = name || '';
                    if (editEmail) editEmail.value = email || '';
                    if (editRegion) editRegion.value = region || '';
                    if (editContactNumber) editContactNumber.value = contactNumber || '';

                    // Set dynamic action URL
                    if (editForm) editForm.action = `/users/${id}`;

                    // Check existing assigned roles in modal checkboxes
                    document.querySelectorAll('input[name="roles[]"][id^="edit_role_"]').forEach(checkbox => {
                        checkbox.checked = existingRoleIds.includes(String(checkbox.value));
                    });

                    openModal(editModal);
                });
            });

            const closeEditModalFunc = () => closeModal(editModal);

            if (closeEditBtn) closeEditBtn.addEventListener("click", closeEditModalFunc);
            if (cancelEditBtn) cancelEditBtn.addEventListener("click", closeEditModalFunc);

            if (editModal) {
                editModal.addEventListener("click", (e) => {
                    if (e.target === editModal) closeEditModalFunc();
                });
            }

            // Trigger loading spinner on Edit User Form submission
            if (editForm) {
                editForm.addEventListener('submit', function () {
                    showLoading();
                });
            }

            // --- DELETE USER CONFIRMATION & LOADING ---
            document.querySelectorAll('.delete-btn').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Delete this User?',
                        text: "This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel',
                        background: getComputedColor('--card-bg'),
                        color: getComputedColor('--text-dark')
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showLoading();
                            form.submit();
                        }
                    });
                });
            });

            // --- KEYBOARD ACCESSIBILITY (ESC KEY) ---
            document.addEventListener('keydown', function (event) {
                if (event.key === "Escape") {
                    closeAddModalFunc();
                    closeEditModalFunc();
                }
            });
        });
    </script>
</x-app-layout>
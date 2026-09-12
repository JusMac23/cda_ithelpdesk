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
        .search-btn { display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 1.25rem; border: none; border-top-right-radius: 0.5rem; border-bottom-right-radius: 0.5rem; background-color: #4f46e5; color: white; cursor: pointer; transition: background-color 0.2s; z-index: 2; }
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

        /* Table */
        .table-container { overflow-x: auto; background-color: var(--card-bg); border-radius: 0.75rem; border: 1px solid var(--border-light); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); width: 100%; -webkit-overflow-scrolling: touch; margin-bottom: 1.5rem; transition: background-color 0.3s ease, border-color 0.3s ease; }
        .data-table { width: 100%; min-width: 600px; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
        .data-table th { padding: 1rem 1.5rem; background-color: var(--bg-alt); color: var(--text-muted); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid var(--border-light); white-space: nowrap; transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease; }
        .data-table td { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-subtle); color: var(--text-dark); vertical-align: middle; font-weight: 500; transition: color 0.3s ease, border-color 0.3s ease; }
        .data-table tbody tr { transition: background-color 0.15s; }
        .data-table tbody tr:hover { background-color: var(--bg-alt); }
        .text-center { text-align: center; }

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
        <p style="font-size: 1rem; font-weight: 600; letter-spacing: 0.025em;">Processing Technical Services, please wait...</p>
    </div>

    <div id="main-content">
        <div class="panel">
            
            <div class="header-flex">
                <h3 class="title">All Technical Services</h3>
            </div>

            <span style="margin-bottom: 1rem; display: block;">The Resolution Times per Category and Level, as may be applicable are presented in the table below.</span>

            <div class="action-container">
                @can('create_technical_services')
                    <button id="openModal" class="btn btn-green">
                        <span class="material-symbols-outlined" style="font-size: 1.25rem; margin-right: 0.2rem;">add</span> Add Service
                    </button>
                @endcan

                @can('search_technical_services')
                <form action="{{ route('tech_services.index') }}" method="GET" class="search-form">
                    <input type="text" name="search_query" value="{{ request('search_query') }}" placeholder="Search services..." class="search-input" autocomplete="off">
                    <button type="submit" class="search-btn">
                        <span class="material-symbols-outlined" style="font-size: 1.25rem; margin-right: 0.2rem;">search</span>
                    </button>
                </form>
                @endcan
            </div>

            @php
                $canManage = auth()->user()->can('edit_technical_services') || auth()->user()->can('delete_technical_services');
            @endphp

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Technical Services Description</th>
                            <th>Low</th>
                            <th>Medium</th>
                            <th>High</th>
                            <th>Critical</th>
                            @if($canManage)
                                <th class="text-center">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($technical_services as $tech_services)
                            <tr>
                                <td>{{ $tech_services->technical_services }}</td>
                                <td>{{ $tech_services->low }}</td>
                                <td>{{ $tech_services->medium }}</td>
                                <td>{{ $tech_services->high }}</td>
                                <td>{{ $tech_services->critical }}</td>

                                @if($canManage)
                                <td>
                                    <div class="action-cell" style="display: flex; justify-content: center; gap: 0.5rem;">
                                        
                                        {{-- Edit Button --}}
                                        @can('edit_technical_services')
                                            <button type="button" class="action-link link-blue editBtn"
                                                data-id="{{ $tech_services->id }}"
                                                data-technical_services="{{ $tech_services->technical_services }}"
                                                data-low="{{ $tech_services->low }}"
                                                data-medium="{{ $tech_services->medium }}"
                                                data-high="{{ $tech_services->high }}"
                                                data-critical="{{ $tech_services->critical }}">
                                                <span class="material-symbols-outlined" style="font-size: 1.25rem; margin-right: 0.2rem;">edit</span> Edit
                                            </button>
                                        @endcan

                                        {{-- Delete Button --}}
                                        @can('delete_technical_services')
                                            <form id="delete-form-{{ $tech_services->id }}" action="{{ route('tech_services.destroy', $tech_services->id) }}" method="POST" style="margin:0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="action-link link-red delete-btn" data-id="{{ $tech_services->id }}">
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
                                <td colspan="{{ $canManage ? 6 : 5 }}" class="text-center" style="padding: 3rem; color: var(--text-muted); font-size: 1rem;">
                                    No Technical Services found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                {{ $technical_services->links() }}
            </div>
            
        </div>
    </div>

    {{-- Modal: Add Service --}}
    <div id="servicesModal" class="modal-overlay hidden">
        <div id="servicesModalContent" class="modal-box">
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

            <h2 class="modal-title">Add Technical Service</h2>

            <form action="{{ route('tech_services.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group col-span-2">
                        <label for="technical_services" class="form-label">Technical Services Description</label>
                        <input type="text" name="technical_services" id="technical_services" required class="form-input" autocomplete="off" placeholder="e.g. Cybersecurity Incident Management">
                    </div>

                    {{-- SLA Fields --}}
                    <div class="form-group">
                        <label for="low" class="form-label">Low Level SLA</label>
                        <input type="text" name="low" id="low" class="form-input" autocomplete="off" placeholder="e.g. 1 day 30 mins or N/A">
                    </div>

                    <div class="form-group">
                        <label for="medium" class="form-label">Medium Level SLA</label>
                        <input type="text" name="medium" id="medium" class="form-input" autocomplete="off" placeholder="e.g. 4 hours 30 mins">
                    </div>

                    <div class="form-group">
                        <label for="high" class="form-label">High Level SLA</label>
                        <input type="text" name="high" id="high" class="form-input" autocomplete="off" placeholder="e.g. 2 hours 30 mins">
                    </div>

                    <div class="form-group">
                        <label for="critical" class="form-label">Critical Level SLA</label>
                        <input type="text" name="critical" id="critical" class="form-input" autocomplete="off" placeholder="e.g. 1 hour 30 mins">
                    </div>

                    <div class="form-group col-span-2">
                        <label class="form-label">Date Added</label>
                        <input type="text" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" readonly class="form-input">
                        <input type="hidden" name="added_at" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s') }}">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-indigo">Submit Service</button>
                    <button type="button" class="btn btn-gray" id="cancelAddModal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Edit Service --}}
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

            <h2 class="modal-title">Edit Technical Service</h2>

            <form id="editForm" method="POST" action="#">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="form-group col-span-2">
                        <label for="edit_technical_services" class="form-label">Technical Services Description</label>
                        <input type="text" name="technical_services" id="edit_technical_services" required class="form-input" autocomplete="off">
                    </div>

                    {{-- SLA Fields --}}
                    <div class="form-group">
                        <label for="edit_low" class="form-label">Low Level SLA</label>
                        <input type="text" name="low" id="edit_low" class="form-input" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="edit_medium" class="form-label">Medium Level SLA</label>
                        <input type="text" name="medium" id="edit_medium" class="form-input" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="edit_high" class="form-label">High Level SLA</label>
                        <input type="text" name="high" id="edit_high" class="form-input" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="edit_critical" class="form-label">Critical Level SLA</label>
                        <input type="text" name="critical" id="edit_critical" class="form-input" autocomplete="off">
                    </div>

                    <div class="form-group col-span-2">
                        <label class="form-label">Date Updated</label>
                        <input type="text" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" readonly class="form-input">
                        <input type="hidden" name="updated_at" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s') }}">
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
                    text: @json(session('success')),
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
                    text: @json(session('error')),
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
                    html: @json($errors->all()).join('<br>'),
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

            // --- ADD SERVICES MODAL TOGGLES ---
            const addModal = document.getElementById("servicesModal");
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

                // Trigger loading spinner on Add Form submission
                const addForm = addModal.querySelector('form');
                if (addForm) {
                    addForm.addEventListener('submit', function () {
                        showLoading();
                    });
                }
            }

            // --- EDIT SERVICES MODAL TOGGLES ---
            const editModal = document.getElementById("editModal");
            const closeEditBtn = document.getElementById("closeEditModal");
            const cancelEditBtn = document.getElementById("cancelEditModal");
            const editButtons = document.querySelectorAll(".editBtn");
            const editForm = document.getElementById("editForm");

            // Input references for Edit Modal
            const editTechnicalServices = document.getElementById("edit_technical_services");
            const editLowRes = document.getElementById("edit_low");
            const editMediumRes = document.getElementById("edit_medium");
            const editHighRes = document.getElementById("edit_high");
            const editCriticalRes = document.getElementById("edit_critical");

            editButtons.forEach(button => {
                button.addEventListener("click", (e) => {
                    e.preventDefault();

                    const id = button.dataset.id;

                    // Populate modal input fields from button dataset attributes
                    if (editTechnicalServices) editTechnicalServices.value = button.dataset.technical_services || '';
                    if (editLowRes) editLowRes.value = button.dataset.low || '';
                    if (editMediumRes) editMediumRes.value = button.dataset.medium || '';
                    if (editHighRes) editHighRes.value = button.dataset.high || '';
                    if (editCriticalRes) editCriticalRes.value = button.dataset.critical || '';

                    // Dynamic route assignment for Technical Services endpoint
                    if (editForm) editForm.action = `/tech_services/${id}`;

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

            // Trigger loading spinner on Edit Form submission
            if (editForm) {
                editForm.addEventListener('submit', function () {
                    showLoading();
                });
            }

            // --- DELETE CONFIRMATION & LOADING ---
            document.querySelectorAll('.delete-btn').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Delete this Service?',
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
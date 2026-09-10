<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
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
            
           /* Enhanced Status Badges - Light */
            --badge-new-bg: #f3e8ff; --badge-new-text: #6b21a8;     /* New/Open */
            --badge-res-bg: #dcfce7; --badge-res-text: #166534;     /* Resolved */
            --badge-pen-bg: #fef9c3; --badge-pen-text: #854d0e;     /* Pending */
            --badge-rea-bg: #e0f2fe; --badge-rea-text: #0369a1;     /* Reassigned */
            --badge-clo-bg: #f1f5f9; --badge-clo-text: #475569;     /* Closed/Default */

            /* Enhanced Priority Badges - Light */
            --prio-low-bg: #ecfdf5; --prio-low-border: #34d399; --prio-low-text: #059669;
            --prio-med-bg: #fffbeb; --prio-med-border: #fbbf24; --prio-med-text: #d97706;
            --prio-high-bg: #fff1f2; --prio-high-border: #f43f5e; --prio-high-text: #e11d48;
            --prio-crit-bg: #ef4444; --prio-crit-border: #b91c1c; --prio-crit-text: #ffffff;
            --prio-def-bg: #f8fafc; --prio-def-border: #94a3b8; --prio-def-text: #64748b;
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

             /* Enhanced Status Badges - Dark */
            --badge-new-bg: rgba(147, 51, 234, 0.2); --badge-new-text: #d8b4fe;
            --badge-res-bg: rgba(22, 101, 52, 0.4);  --badge-res-text: #4ade80;
            --badge-pen-bg: rgba(133, 77, 14, 0.4);  --badge-pen-text: #facc15;
            --badge-rea-bg: rgba(3, 105, 161, 0.4);  --badge-rea-text: #38bdf8;
            --badge-clo-bg: rgba(71, 85, 105, 0.4);  --badge-clo-text: #94a3b8;

            /* Enhanced Priority Badges - Dark */
            --prio-low-bg: rgba(5, 150, 105, 0.15); --prio-low-border: #059669; --prio-low-text: #34d399;
            --prio-med-bg: rgba(217, 119, 6, 0.15); --prio-med-border: #d97706; --prio-med-text: #fbbf24;
            --prio-high-bg: rgba(225, 29, 72, 0.15);--prio-high-border: #e11d48; --prio-high-text: #fb7185;
            --prio-crit-bg: #dc2626; --prio-crit-border: #991b1b; --prio-crit-text: #ffffff;
            --prio-def-bg: rgba(71, 85, 105, 0.15); --prio-def-border: #475569; --prio-def-text: #94a3b8;
        }

        /* Global Box Sizing & Font Fix */
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; transition: background-color 0.3s ease, color 0.3s ease; }

        /* Main Container */
        .content-panel { background-color: var(--card-bg); border-radius: 1rem; border: 1px solid var(--border-light); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); padding: 1.25rem; width: 100%; transition: background-color 0.3s ease, border-color 0.3s ease; }
        
        .header-flex { display: flex; flex-direction: column; align-items: flex-start; margin-bottom: 1.5rem; gap: 1rem; width: 100%; }
        .title { font-size: 1.75rem; font-weight: 800; color: var(--text-dark); margin: 0; letter-spacing: -0.025em; transition: color 0.3s ease; }

        /* --- Action Container (Toolbar Layout) --- */
        .action-container { display: flex; flex-direction: column; width: 100%; gap: 1rem; margin-bottom: 1.5rem; }

        /* Auto Reload Toggle */
        .auto-reload-label { display: flex; align-items: center; font-size: 0.9rem; font-weight: 600; color: var(--text-muted); cursor: pointer; width: 100%; justify-content: flex-start; padding: 0.5rem 0; white-space: nowrap; transition: color 0.3s ease; }
        .auto-reload-checkbox { margin-right: 0.5rem; cursor: pointer; width: 1.15rem; height: 1.15rem; accent-color: #4f46e5; border-radius: 0.25rem; }

        /* --- Search Form Group --- */
        .search-form { display: flex; align-items: stretch; width: 100%; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border-radius: 0.5rem; }
        .search-input { height: 44px; flex: 1; min-width: 0; padding: 0 1rem; font-size: 0.95rem; font-family: inherit; color: var(--input-text); border: 1px solid var(--input-border); border-right: none; border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem; outline: none; transition: all 0.2s; position: relative; z-index: 1; background-color: var(--input-bg); }
        .search-input:focus { border-color: #6366f1; box-shadow: inset 0 0 0 1px #6366f1, 0 0 0 3px rgba(99, 102, 241, 0.15); z-index: 10; }
        .search-btn { display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 1.25rem; border: none; border-top-right-radius: 0.5rem; border-bottom-right-radius: 0.5rem; background-color: #4f46e5; color: white; cursor: pointer; transition: background-color 0.2s; z-index: 2; width: auto; }
        .search-btn:hover { background-color: #4338ca; }

        /* --- Buttons --- */
        .btn { display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 1.5rem; border-radius: 0.5rem; font-size: 0.95rem; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s ease; width: 100%; text-decoration: none; font-family: inherit; white-space: nowrap; box-sizing: border-box; }

        .btn-green { background-color: #10b981; color: white; box-shadow: 0 1px 2px rgba(16, 185, 129, 0.2); }
        .btn-green:hover { background-color: #059669; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
        .btn-green:active { transform: translateY(0); box-shadow: 0 1px 2px rgba(16, 185, 129, 0.2); }
        
        .btn-indigo { background-color: #4f46e5; color: white; box-shadow: 0 1px 2px rgba(79, 70, 229, 0.2); }
        .btn-indigo:hover { background-color: #4338ca; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }

        .btn-yellow { background-color: #eab308; color: white; box-shadow: 0 1px 2px rgba(234, 179, 8, 0.2); }
        .btn-yellow:hover { background-color: #ca8a04; transform: translateY(-1px); }

        .btn-gray { background-color: var(--btn-gray-bg); color: var(--btn-gray-text); border: 1px solid var(--btn-gray-border); }
        .btn-gray:hover { background-color: var(--btn-gray-hover-bg); color: var(--btn-gray-hover-text); }

        /* --- Data Table --- */
        .table-container { width: 100%; overflow-x: auto; background-color: var(--card-bg); border-radius: 0.75rem; border: 1px solid var(--border-light); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-top: 1.5rem; -webkit-overflow-scrolling: touch; display: block; transition: background-color 0.3s ease, border-color 0.3s ease; }
        .data-table { width: 100%; min-width: 1200px; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
        .data-table th { padding: 1rem 1.25rem; background-color: var(--bg-alt); color: var(--text-muted); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid var(--border-light); white-space: nowrap; transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease; }
        .data-table td { padding: 1.25rem; border-bottom: 1px solid var(--border-subtle); color: var(--text-dark); vertical-align: middle; font-weight: 500; transition: color 0.3s ease, border-color 0.3s ease; }
        .data-table tbody tr { transition: background-color 0.15s; }
        .data-table tbody tr:hover { background-color: var(--bg-alt); }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; color: var(--text-dark); transition: color 0.3s ease; }
        .text-truncate { max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; }
        
        /* Thumbnails */
        .thumb-img { width: 3rem; height: 3rem; object-fit: cover; border-radius: 0.5rem; border: 1px solid var(--border-light); transition: all 0.2s; cursor: pointer; }
        .thumb-img:hover { opacity: 0.8; border-color: var(--text-muted); }

        /* --- Enhanced Status Badges --- */
        .badge { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.35rem 0.85rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; transition: background-color 0.3s ease, color 0.3s ease; }
        /* Status Indicator Dot */
        .badge::before { content: ''; display: inline-block; width: 6px; height: 6px; border-radius: 50%; background-color: currentColor; flex-shrink: 0; }
        .status-new { background-color: var(--badge-new-bg); color: var(--badge-new-text); }
        .status-resolved { background-color: var(--badge-res-bg); color: var(--badge-res-text); }
        .status-pending { background-color: var(--badge-pen-bg); color: var(--badge-pen-text); }
        .status-reassigned { background-color: var(--badge-rea-bg); color: var(--badge-rea-text); }
        .status-closed { background-color: var(--badge-clo-bg); color: var(--badge-clo-text); }
        
        /* Modify dot behavior based on specific status context */
        .status-closed::before { display: none; } /* Closed tickets don't need active indicators */
        .status-resolved::before { opacity: 0.5; } /* Subdued dot for resolved */
        .status-pending::before { animation: blink 1.5s infinite; } /* Pending requires attention */

        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

        /* --- Enhanced Priority Badges --- */
        .badge-priority { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; line-height: 1.2; border-style: solid; border-width: 1px; border-radius: 9999px; transition: all 0.2s ease; }
        .priority-low { background-color: var(--prio-low-bg); border-color: var(--prio-low-border); color: var(--prio-low-text); }
        .priority-medium { background-color: var(--prio-med-bg); border-color: var(--prio-med-border); color: var(--prio-med-text); }
        .priority-high { background-color: var(--prio-high-bg); border-color: var(--prio-high-border); color: var(--prio-high-text); }
        .priority-default { background-color: var(--prio-def-bg); border-color: var(--prio-def-border); color: var(--prio-def-text); }
        
        /* Critical Priority gets solid fill + emergency pulse animation */
        .priority-critical { background-color: var(--prio-crit-bg); border-color: var(--prio-crit-border); color: var(--prio-crit-text); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); animation: pulse-critical 2s infinite; }

        @keyframes pulse-critical { 0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); } 70% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); } 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); } }

        /* --- Modern UI Pagination --- */
        .pagination-wrapper { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-light); width: 100%; transition: border-color 0.3s ease; }
        .pagination-wrapper nav { display: flex; flex-direction: column; gap: 1.25rem; width: 100%; align-items: center; }
        .pagination-wrapper p { margin: 0; font-size: 0.875rem; color: var(--text-muted); font-weight: 500; text-align: center; transition: color 0.3s ease; }
        .pagination-wrapper p span { font-weight: 700; color: var(--text-dark); transition: color 0.3s ease; }
        .pagination-wrapper div > span.relative.z-0.inline-flex,
        .pagination-wrapper .flex.justify-between { display: flex; flex-wrap: wrap; gap: 0.5rem; box-shadow: none !important; justify-content: center; align-items: center; }
        .pagination-wrapper a, 
        .pagination-wrapper span[aria-current="page"] > span,
        .pagination-wrapper span[aria-disabled="true"] > span { display: inline-flex; align-items: center; justify-content: center; min-width: 2.25rem; height: 2.25rem; padding: 0 0.5rem; border-radius: 0.375rem !important; font-size: 0.875rem; font-weight: 600; font-family: 'Inter', sans-serif; transition: all 0.2s ease; border: 1px solid transparent; margin: 0 !important; text-decoration: none; line-height: 1; }
        .pagination-wrapper a { background-color: var(--card-bg); color: var(--text-muted); border-color: var(--border-light); }
        .pagination-wrapper a:hover { background-color: var(--bg-alt); color: var(--text-dark); border-color: var(--input-border); transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .pagination-wrapper span[aria-current="page"] > span { background-color: #4f46e5; color: #ffffff; border-color: #4f46e5; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.25); z-index: 2; position: relative; }
        .pagination-wrapper span[aria-disabled="true"] > span { background-color: var(--bg-alt); color: var(--text-muted); border-color: var(--border-light); cursor: not-allowed; opacity: 0.7; }
        .pagination-wrapper span[aria-disabled="true"]:not([aria-label]) > span { background: transparent; border: none; opacity: 1; color: var(--text-muted); }
        .pagination-wrapper svg { width: 1.25rem !important; height: 1.25rem !important; display: block; }

        /* Mobile Specific Overrides */
        @media (max-width: 639px) {
            .pagination-wrapper nav .hidden { display: none !important; }
            .pagination-wrapper nav .sm\:hidden { display: flex; width: 100%; justify-content: space-between; }
        }

        /* Desktop & Tablet Overrides */
        @media (min-width: 768px) {
            .content-panel { padding: 2rem; }
            .header-flex { flex-direction: row; justify-content: space-between; align-items: center; }
            .action-container { flex-direction: row; justify-content: space-between; align-items: center; }
            .search-form { max-width: 350px; min-width: 250px; width: auto; }
            .auto-reload-label { width: auto; padding: 0; justify-content: flex-start; }
            .btn { width: auto; }
            
            .pagination-wrapper nav { flex-direction: row; justify-content: space-between; }
            .pagination-wrapper nav > div.sm\:hidden { display: none !important; }
            .pagination-wrapper nav > div.hidden.sm\:flex-1 { display: flex !important; width: 100%; justify-content: space-between; align-items: center; }
        }
    </style>

    <div id="main-content" class="page-wrapper">
        <div id="ticketsContent">
            <div class="content-panel">

                <div class="header-flex">
                    <h3 class="title">Re-Assigned Tickets</h3>
                </div>

                <div class="action-container">
                    <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                        <label class="auto-reload-label">
                            <input type="checkbox" id="autoReloadCheckbox" class="auto-reload-checkbox">
                            <span>(<span id="countdown">60</span>s) Auto-Reload</span>
                        </label>
                    </div>

                    @can('search_assignedtome_tickets')
                    <form action="{{ route('assignedtome_tickets.index') }}" method="GET" class="search-form">
                        <input type="text" name="search_query" value="{{ request('search_query') }}" placeholder="Search tickets..." class="search-input" autocomplete="off">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search" style="margin: 0;"></i>
                        </button>
                    </form>
                    @endcan
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="text-center">Tracking ID</th>
                                <th>Requested By</th>
                                <th>Request Details</th>
                                <th>Re-Assigned By</th>
                                <th>Previous Assigned</th>
                                <th>Re-Assigned To</th>
                                <th>Notes</th>
                                <th>Date Re-Assigned</th>
                                <th class="text-center">Priority</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tickets as $ticket)
                                <tr>
                                    <td class="font-bold text-center" style="font-size: 0.95rem;">{{ $ticket->ticket_number ?? 'N/A' }}</td>
                                    <td>{{ $ticket->requested_by ?? 'N/A' }}</td>
                                    <td>
                                        <span class="text-truncate" title="{{ $ticket->request ?? '' }}">
                                            {{ $ticket->request ?? '' }}
                                        </span>
                                    </td>
                                    <td>{{ $ticket->assigned_by ?? 'N/A' }}</td>
                                    <td>{{ $ticket->previous_assigned ?? 'N/A' }}</td>
                                    <td>{{ $ticket->re_assigned_to ?? 'N/A' }}</td>
                                    <td>
                                        <span class="text-truncate" title="{{ $ticket->notes ?? '' }}">
                                            {{ $ticket->notes ?? '' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $ticket->re_assigned_at
                                            ? \Carbon\Carbon::parse($ticket->re_assigned_at)->format('M d, Y h:i A')
                                            : 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $priority = trim($ticket->priority);
                                            $badgeClass = match($priority) {
                                                'Low'       => 'priority-low',
                                                'Medium'    => 'priority-medium',
                                                'High'      => 'priority-high',
                                                'Critical'  => 'priority-critical',
                                                default     => 'priority-default',
                                            };
                                        @endphp
                                        <span class="badge-priority {{ $badgeClass }}">
                                            {{ $ticket->priority }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        @php
                                            $status = trim($ticket->status);
                                            $badgeClass = match($status) {
                                                'Resolved'              => 'status-resolved',
                                                'Pending'               => 'status-pending',
                                                'Pending/Re-Assigned'   => 'status-reassigned',
                                                default                 => 'status-default',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center" style="padding: 3rem; color: var(--text-muted); font-size: 1rem;">
                                        No Re-Assigned Tickets found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Flash Messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{!! addslashes(session("success")) !!}',
                    timer: 2500,
                    showConfirmButton: false,
                    background: getComputedStyle(document.body).getPropertyValue('--card-bg').trim(),
                    color: getComputedStyle(document.body).getPropertyValue('--text-dark').trim()
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Notice!',
                    text: '{!! addslashes(session("error")) !!}',
                    timer: 3000,
                    showConfirmButton: false,
                    background: getComputedStyle(document.body).getPropertyValue('--card-bg').trim(),
                    color: getComputedStyle(document.body).getPropertyValue('--text-dark').trim()
                });
            @endif

            // === AUTO-RELOAD & COUNTDOWN ===
            const checkbox = document.getElementById('autoReloadCheckbox');
            const countdownDisplay = document.getElementById('countdown');
            let intervalId = null;
            let countdown = 60;

            if (checkbox) {
                const isChecked = localStorage.getItem('autoReload') === 'true';
                checkbox.checked = isChecked;

                if (isChecked) startAutoReload();

                checkbox.addEventListener('change', function () {
                    localStorage.setItem('autoReload', checkbox.checked);
                    if (checkbox.checked) {
                        startAutoReload();
                    } else {
                        stopAutoReload();
                    }
                });
            }

            function startAutoReload() {
                countdown = 60;
                updateCountdown();
                intervalId = setInterval(() => {
                    countdown--;
                    updateCountdown();
                    if (countdown <= 0) {
                        location.reload();
                    }
                }, 1000);
            }

            function stopAutoReload() {
                clearInterval(intervalId);
                countdown = 60;
                updateCountdown();
            }

            function updateCountdown() {
                if (countdownDisplay) countdownDisplay.textContent = countdown;
            }
        });
    </script>
</x-app-layout>
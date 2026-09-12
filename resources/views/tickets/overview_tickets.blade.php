<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

    @if(auth()->user()->hasAnyRole(['Super Admin', 'ICTS Admin']))
    <style>
        /* --- Theme Variables --- */
        :root {
            --bg-main: #f1f5f9;
            --card-bg: #ffffff;
            --bg-alt: #f8fafc;
            --text-dark: #0f172a;
            --text-main: #334155;
            --text-muted: #64748b;
            --border-light: #e2e8f0;
            --border-subtle: #f1f5f9;

            /* Stat Icon Backgrounds */
            --icon-indigo-bg: #e0e7ff; --icon-indigo-text: #4f46e5;
            --icon-green-bg: #dcfce7; --icon-green-text: #10b981;
            --icon-blue-bg: #dbeafe; --icon-blue-text: #3b82f6;
            --icon-red-bg: #fee2e2; --icon-red-text: #ef4444;

            /* Badges */
            --badge-green-bg: #dcfce7; --badge-green-text: #166534;
            --badge-yellow-bg: #fef9c3; --badge-yellow-text: #854d0e;
            --badge-blue-bg: #eff6ff; --badge-blue-text: #1e40af;
            --badge-gray-bg: #f1f5f9; --badge-gray-text: #475569;
        }

        body.dark {
            --bg-main: #020617;
            --card-bg: #0f172a; 
            --bg-alt: #1e293b; 
            --text-dark: #f8fafc;
            --text-main: #e2e8f0;
            --text-muted: #9ca3af;
            --border-light: #334155; 
            --border-subtle: #1e293b;

            /* Stat Icon Backgrounds - Dark mode adjusted */
            --icon-indigo-bg: rgba(99, 102, 241, 0.2); --icon-indigo-text: #818cf8;
            --icon-green-bg: rgba(16, 185, 129, 0.2); --icon-green-text: #34d399;
            --icon-blue-bg: rgba(59, 130, 246, 0.2); --icon-blue-text: #60a5fa;
            --icon-red-bg: rgba(239, 68, 68, 0.2); --icon-red-text: #f87171;

            /* Badges - Dark mode adjusted */
            --badge-green-bg: rgba(22, 101, 52, 0.4); --badge-green-text: #4ade80;
            --badge-yellow-bg: rgba(133, 77, 14, 0.4); --badge-yellow-text: #facc15;
            --badge-blue-bg: rgba(30, 58, 138, 0.4); --badge-blue-text: #60a5fa;
            --badge-gray-bg: rgba(71, 85, 105, 0.4); --badge-gray-text: #cbd5e1;
        }

        /* Global Box Sizing & Font Fix */
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: var(--bg-main); color: var(--text-main); transition: background-color 0.3s ease, color 0.3s ease; margin: 0; padding: 0; }

        /* Dashboard Container */
        .dashboard-panel { background-color: var(--card-bg); border-radius: 1rem; border: 1px solid var(--border-light); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); padding: 2rem; width: 100%; transition: background-color 0.3s ease, border-color 0.3s ease; }
        .dashboard-header-flex { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem; }
        .dashboard-title { font-size: 1.75rem; font-weight: 800; margin: 0; color: var(--text-dark); letter-spacing: -0.025em; transition: color 0.3s ease; }

        /* Stats Cards */
        .stat-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 240px), 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; }
        .stat-card { border-radius: 1rem; padding: 1.5rem; background-color: var(--card-bg); border: 1px solid var(--border-light); box-shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: space-between; border-left: 5px solid; transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); }

        .stat-left { display: flex; align-items: center; gap: 1.25rem; }
        .stat-icon { width: 56px; height: 56px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; transition: background-color 0.3s ease, color 0.3s ease; }
        .stat-label { font-size: 0.85rem; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); transition: color 0.3s ease; }
        .stat-value { font-size: 2.25rem; font-weight: 800; margin: 0.25rem 0 0 0; text-align: left; line-height: 1; color: var(--text-dark); transition: color 0.3s ease; }

        /* Card Themes */
        .card-indigo { border-left-color: #6366f1; }
        .card-indigo .stat-icon { background-color: var(--icon-indigo-bg); color: var(--icon-indigo-text); }
        .card-green { border-left-color: #10b981; }
        .card-green .stat-icon { background-color: var(--icon-green-bg); color: var(--icon-green-text); }
        .card-blue { border-left-color: #3b82f6; }
        .card-blue .stat-icon { background-color: var(--icon-blue-bg); color: var(--icon-blue-text); }
        .card-red { border-left-color: #ef4444; }
        .card-red .stat-icon { background-color: var(--icon-red-bg); color: var(--icon-red-text); }

        /* Grid Tables */
        .tables-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 300px), 1fr)); gap: 1.5rem; margin-bottom: 3rem; }
        .table-card { background-color: var(--card-bg); border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); padding: 1.5rem; border: 1px solid var(--border-light); border-top: 4px solid var(--border-light); overflow: hidden; display: flex; flex-direction: column; transition: all 0.3s ease; }
        .table-card-title { font-size: 1.15rem; font-weight: 700; margin-top: 0; margin-bottom: 1.25rem; color: var(--text-dark); display: flex; align-items: center; gap: 0.75rem; transition: color 0.3s ease; }

        /* Accent Colors for Table Cards */
        .tc-indigo { border-top-color: #4f46e5; }
        .tc-green { border-top-color: #10b981; }
        .tc-yellow { border-top-color: #eab308; }
        .tc-red { border-top-color: #ef4444; }

        .table-responsive { width: 100%; overflow-x: auto; flex-grow: 1; -webkit-overflow-scrolling: touch; }

        /* Small Grid Tables */
        .data-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem; }
        .data-table th, .data-table td { padding: 0.85rem 1rem; white-space: nowrap; border-bottom: 1px solid var(--border-subtle); transition: border-color 0.3s ease; }
        .data-table th { background-color: var(--bg-alt); color: var(--text-muted); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; transition: background-color 0.3s ease, color 0.3s ease; }
        .data-table td { color: var(--text-main); font-weight: 500; transition: color 0.3s ease; }
        .data-table tbody tr { transition: background-color 0.15s; }
        .data-table tbody tr:hover { background-color: var(--bg-alt); }
        .text-right { text-align: right; }

        /* Bottom Full Table */
        .full-table-container { background-color: var(--card-bg); box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-radius: 1rem; border: 1px solid var(--border-light); overflow-x: auto; margin-top: 1.5rem; -webkit-overflow-scrolling: touch; transition: background-color 0.3s ease, border-color 0.3s ease; }
        .full-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; min-width: 1000px; }
        .full-table th { padding: 1.25rem 1.5rem; background-color: var(--bg-alt); color: var(--text-muted); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid var(--border-light); white-space: nowrap; transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease; }
        .full-table td { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-subtle); color: var(--text-main); font-weight: 500; vertical-align: middle; transition: color 0.3s ease, border-color 0.3s ease; } 
        .full-table tbody tr { transition: background-color 0.15s; }
        .full-table tbody tr:hover { background-color: var(--bg-alt); }

        /* Badges */
        .badge { padding: 0.4rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; display: inline-block; text-align: center; white-space: nowrap; letter-spacing: 0.025em; transition: background-color 0.3s ease, color 0.3s ease; }
        .badge-green { background-color: var(--badge-green-bg); color: var(--badge-green-text); }
        .badge-yellow { background-color: var(--badge-yellow-bg); color: var(--badge-yellow-text); }
        .badge-blue { background-color: var(--badge-blue-bg); color: var(--badge-blue-text); }
        .badge-gray { background-color: var(--badge-gray-bg); color: var(--badge-gray-text); }

        /* Region Select & Export Box */
        .export-container { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .region-filter-form { display: flex; align-items: center; margin: 0; }
        .form-group { display: flex; flex-direction: row; align-items: center; gap: 8px; margin: 0; }
        .form-label { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.025em; margin: 0; white-space: nowrap; }

        .form-select { appearance: none; background-color: var(--card-bg); border: 1px solid var(--border-light); color: var(--text-dark); padding: 9px 36px 9px 14px; border-radius: 8px; font-size: 0.875rem; font-family: inherit; min-width: 160px; cursor: pointer; outline: none; transition: all 0.2s ease-in-out; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; background-size: 16px; }
        .form-select:hover, .form-select:focus { border-color: var(--text-muted); }

        .btn-export-pdf { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 9px 16px; background-color: var(--card-bg); color: var(--text-dark); border: 1px solid var(--border-light); border-radius: 8px; font-size: 0.875rem; font-weight: 500; text-decoration: none; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); transition: all 0.2s ease-in-out; cursor: pointer; }
        .btn-export-pdf:hover { background-color: var(--bg-alt); border-color: var(--border-light); transform: translateY(-1px); }
        .btn-export-pdf .material-symbols-outlined { font-size: 1.15rem; color: #ef4444; }

        /* General Mobile Responsiveness */
        @media (max-width: 640px) {
            .dashboard-panel { padding: 1.25rem; }
            .dashboard-header-flex { flex-direction: column; align-items: stretch; }
            .export-container { justify-content: stretch; flex-direction: column; align-items: stretch; width: 100%; }
            .region-filter-form { width: 100%; }
            .form-group { flex-direction: column; align-items: flex-start; width: 100%; }
            .form-select { width: 100%; }
            .btn-export-pdf { width: 100%; }
            .stat-card { padding: 1.25rem; }
            .stat-icon { width: 48px; height: 48px; font-size: 1.25rem; }
            .stat-value { font-size: 1.75rem; }
            .dashboard-wrapper { padding: 0.5rem; }
            .table-card { padding: 1.25rem; }
        }
    </style>

    @can('view_overview_tickets')
    <div id="main-content" class="page-wrapper">
        <div id="dashboardContent" class="dashboard-wrapper">
            <div class="dashboard-panel">

                <div class="dashboard-header-flex">
                    <h3 class="dashboard-title">Tickets Overview</h3>

                    <div class="export-container">

                        @can('filter_ticket_by_region')
                        <form method="GET" action="{{ url()->current() }}" class="region-filter-form">
                            <div class="form-group">
                                <label for="region" class="form-label">Select Region:</label>
                                <select name="region" id="region" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Regions</option>
                                    @if(!empty($regions))
                                        @foreach($regions as $region)
                                            <option value="{{ trim($region) }}" {{ request('region') == trim($region) ? 'selected' : '' }}>
                                                {{ trim($region) }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </form>
                        @endcan

                        <a href="{{ route('tickets.export_pdf', ['region' => request('region')]) }}" class="btn-export-pdf" title="PDF File Download">
                            <span class="material-symbols-outlined">download</span>
                            Download Report
                        </a>
                    </div>
                </div>

                {{-- Dashboard Cards --}}
                <div class="stat-cards">
                    <span class="material-symbols-outlined" style="display: none;">confirmation_number</span> <!-- IGNORE: For preloading icons -->
                    <span class="material-symbols-outlined" style="display: none;">hourglass_top</span> <!-- IGNORE: For preloading icons -->
                    <span class="material-symbols-outlined" style="display: none;">check_circle</span> <!-- IGNORE: For preloading icons -->
                    <span class="material-symbols-outlined" style="display: none;">error</span> <!-- IGNORE: For preloading icons -->
                    @php
                        $cards = [
                            ['label' => 'Total Tickets', 'icon' => 'confirmation_number', 'theme' => 'indigo', 'value' => $total ?? 0],
                            ['label' => 'Pending Tickets', 'icon' => 'hourglass_top', 'theme' => 'green', 'value' => $pending ?? 0],
                            ['label' => 'Resolved Tickets', 'icon' => 'check_circle', 'theme' => 'blue', 'value' => $resolved ?? 0],
                            ['label' => 'Overdue Tickets', 'icon' => 'error', 'theme' => 'red', 'value' => $overdue ?? 0],
                        ];
                    @endphp

                    @foreach ($cards as $card)
                        <div class="stat-card card-{{ $card['theme'] }}">
                            <div class="stat-left">
                                <div class="stat-icon">
                                    <span class="material-symbols-outlined">{{ $card['icon'] }}</span>
                                </div>
                                <div>
                                    <h4 class="stat-label">{{ $card['label'] }}</h4>
                                    <p class="stat-value">{{ $card['value'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- IT Area, Personnel, Service, Overdue --}}
                <div class="tables-grid">

                    @can('view tickets by region')
                    {{-- Tickets by Region --}}
                    <div class="table-card tc-indigo">
                        <h4 class="table-card-title" style="color: var(--icon-indigo-text);">
                            Tickets by Region
                        </h4>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>IT Area</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($byItArea ?? [] as $area)
                                        <tr>
                                            <td>{{ $area->it_area }}</td>
                                            <td class="text-right" style="font-weight: 700; color: var(--text-dark);">{{ $area->total }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" style="text-align: center; color: var(--text-muted); padding: 2rem;">No data available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endcan

                    {{-- Tickets by Technical Personnel --}}
                    <div class="table-card tc-green">
                        <h4 class="table-card-title" style="color: var(--icon-green-text);">
                            Tickets by Technical Personnel 
                            <span style="font-size: 0.85em; font-weight: normal; font-style: italic; opacity: 0.85;">
                                (Including Re-Assigned)
                            </span>
                        </h4>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Technical Personnel</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($byItPersonnel ?? [] as $person)
                                        <tr>
                                            <td>{{ $person->it_personnel }}</td>
                                            <td class="text-right" style="font-weight: 700; color: var(--text-dark);">{{ $person->total }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" style="text-align: center; color: var(--text-muted); padding: 2rem;">No data available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tickets by Service --}}
                    <div class="table-card tc-yellow">
                        <h4 class="table-card-title" style="color: #eab308;">
                            Tickets by Technical Service
                        </h4>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Service Category</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($byService ?? [] as $service)
                                        <tr>
                                            <td>{{ $service->service }}</td>
                                            <td class="text-right" style="font-weight: 700; color: var(--text-dark);">{{ $service->total }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" style="text-align: center; color: var(--text-muted); padding: 2rem;">No data available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Overdue Tickets --}}
                    <div class="table-card tc-red">
                        <h4 class="table-card-title" style="color: var(--icon-red-text);">
                            Overdue Tickets
                        </h4>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Request Details</th>
                                        <th class="text-right">Assigned To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($overdueTickets ?? [] as $personnel => $tickets)
                                        @foreach ($tickets as $ticket)
                                            <tr>
                                                <td style="white-space: normal; min-width: 150px;">{{ $ticket->request }}</td>
                                                <td class="text-right" style="font-weight: 700; color: var(--text-dark);">{{ $personnel }}</td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="2" style="text-align: center; color: var(--text-muted); padding: 2rem;">No overdue tickets.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Recently Resolved Tickets --}}
                <div style="margin-top: 1rem;">
                    <h4 class="table-card-title" style="color: var(--text-dark);">
                        <span class="material-symbols-outlined" style="color: var(--icon-blue-text);">history</span> Recently Resolved Tickets
                    </h4>

                    <div class="full-table-container">
                        <table class="full-table">
                            <thead>
                                <tr>
                                    <th>Ticket Number</th>
                                    <th>Requested By</th>
                                    <th>Division</th>
                                    <th>Service</th>
                                    <th>Assigned Personnel</th>
                                    <th>Date Created</th>
                                    <th>Date Resolved</th>
                                    <th style="text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentlyResolved ?? [] as $ticket)
                                    <tr>
                                        <td style="font-weight: 700; color: var(--text-dark);">{{ $ticket->ticket_number }}</td>
                                        <td>{{ $ticket->firstname }} {{ $ticket->lastname }}</td>
                                        <td>{{ $ticket->division }}</td>
                                        <td>{{ $ticket->service }}</td>
                                        <td>{{ $ticket->it_personnel }}</td>
                                        <td style="color: var(--text-muted);">{{ \Carbon\Carbon::parse($ticket->date_created)->format('M d, Y h:i A') }}</td>
                                        <td style="color: var(--text-muted);">{{ \Carbon\Carbon::parse($ticket->date_resolved)->format('M d, Y h:i A') }}</td>

                                        @php
                                            $status = trim($ticket->status);
                                            $badgeClass = match($status) {
                                                'Resolved' => 'badge-green',
                                                'Pending' => 'badge-yellow',
                                                'Pending/Re-Assigned' => 'badge-blue',
                                                default => 'badge-gray',
                                            };
                                        @endphp

                                        <td style="text-align: center;">
                                            <span class="badge {{ $badgeClass }}">
                                                {{ $ticket->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-muted); font-size: 1rem;">No recently resolved tickets to display.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endcan
    @endif
</x-app-layout>
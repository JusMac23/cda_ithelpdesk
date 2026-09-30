<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <!-- Include Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        .stat-info { display: flex; flex-direction: column; }
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

        /* Grid Charts - STRICT 3-COLUMN LAYOUT WITH STRETCH SUPPORT */
        .tables-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.5rem; margin-bottom: 3rem; width: 100%; }
        .table-card { background-color: var(--card-bg); border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); padding: 1.5rem; border: 1px solid var(--border-light); border-top: 4px solid var(--border-light); overflow: hidden; display: flex; flex-direction: column; transition: all 0.3s ease; width: 100%; }
        .table-card-title { font-size: 1.15rem; font-weight: 700; margin-top: 0; margin-bottom: 1.25rem; color: var(--text-dark); display: flex; align-items: center; gap: 0.5rem; transition: color 0.3s ease; }

        /* Full Width Card Modification for Grid */
        .table-card-full { grid-column: 1 / -1; }

        /* Accent Colors for Table/Chart Cards */
        .tc-indigo { border-top-color: #4f46e5; }
        .tc-green { border-top-color: #10b981; }
        .tc-yellow { border-top-color: #eab308; }
        .tc-red { border-top-color: #ef4444; }

        /* Chart Scrolling Viewport */
        .chart-viewport { position: relative; width: 100%; height: 320px; overflow: auto; -webkit-overflow-scrolling: touch; border-radius: 0.5rem; }

        /* Chart Wrapper dynamically resized */
        .chart-wrapper { position: relative; }

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

        /* Responsive Breakpoints when zooming in or on smaller screens */
        @media (max-width: 1200px) {
            .tables-grid { 
                grid-template-columns: repeat(2, minmax(0, 1fr)); 
            }
        }

        @media (max-width: 768px) {
            .tables-grid { 
                grid-template-columns: 1fr; 
            }
            .table-card-full { 
                grid-column: auto; 
            }
        }

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

                {{-- Preprocessing Data for Javascript Charts & Scroll Dimensions --}}
                @php
                    $cards = [
                        ['label' => 'Total Tickets', 'icon' => 'confirmation_number', 'theme' => 'indigo', 'value' => $total ?? 0],
                        ['label' => 'Pending Tickets', 'icon' => 'hourglass_top', 'theme' => 'green', 'value' => $pending ?? 0],
                        ['label' => 'Resolved Tickets', 'icon' => 'check_circle', 'theme' => 'blue', 'value' => $resolved ?? 0],
                        ['label' => 'Overdue Tickets', 'icon' => 'error', 'theme' => 'red', 'value' => $overdue ?? 0],
                    ];

                    $regionLabels = collect($byItArea ?? [])->pluck('it_area')->toArray();
                    $regionTotals = collect($byItArea ?? [])->pluck('total')->toArray();
                    $regionCount = count($regionLabels);

                    $personnelLabels = collect($byItPersonnel ?? [])->pluck('it_personnel')->toArray();
                    $personnelTotals = collect($byItPersonnel ?? [])->pluck('total')->toArray();
                    $personnelCount = count($personnelLabels);

                    $serviceLabels = collect($byService ?? [])->pluck('service')->toArray();
                    $serviceTotals = collect($byService ?? [])->pluck('total')->toArray();
                    $serviceCount = count($serviceLabels);

                    $overduePersonnelLabels = [];
                    $overdueCounts = [];
                    foreach(($overdueTickets ?? []) as $pName => $tList) {
                        $overduePersonnelLabels[] = $pName;
                        $overdueCounts[] = is_array($tList) || $tList instanceof \Countable ? count($tList) : 0;
                    }
                    $overdueCount = count($overduePersonnelLabels);
                @endphp

                {{-- Dashboard Cards --}}
                <div class="stat-cards">
                    @foreach ($cards as $card)
                        <div class="stat-card card-{{ $card['theme'] }}">
                            <div class="stat-info">
                                <h4 class="stat-label">{{ $card['label'] }}</h4>
                                <p class="stat-value">{{ $card['value'] }}</p>
                            </div>
                            <div class="stat-icon">
                                <span class="material-symbols-outlined">{{ $card['icon'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Visual Charts Grid --}}
                <div class="tables-grid">

                    @can('view tickets by region')
                    {{-- Tickets by Region (Full-Width Card) --}}
                    <div class="table-card tc-indigo table-card-full">
                        <h4 class="table-card-title" style="color: var(--icon-indigo-text);">
                            <span class="material-symbols-outlined">map</span> Tickets by Region
                        </h4>
                        <div class="chart-viewport">
                            <div class="chart-wrapper" style="min-width: max(100%, {{ $regionCount * 50 }}px); height: 100%;">
                                <canvas id="regionChart"></canvas>
                            </div>
                        </div>
                    </div>
                    @endcan

                    {{-- Tickets by Technical Personnel (Horizontal Bar Chart - Vertical Scrolling) --}}
                    <div class="table-card tc-green">
                        <h4 class="table-card-title" style="color: var(--icon-green-text);">
                            <span class="material-symbols-outlined">engineering</span>
                            Tickets by Technical Personnel 
                            <span style="font-size: 0.8em; font-weight: normal; font-style: italic; opacity: 0.85;">
                                (Inc. Re-Assigned)
                            </span>
                        </h4>
                        <div class="chart-viewport">
                            <div class="chart-wrapper" style="min-width: 100%; height: max(100%, {{ $personnelCount * 45 }}px);">
                                <canvas id="personnelChart"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- Tickets by Technical Service (Doughnut Chart) --}}
                    <div class="table-card tc-yellow">
                        <h4 class="table-card-title" style="color: #eab308;">
                            <span class="material-symbols-outlined">build</span> Tickets by Technical Service
                        </h4>
                        <div class="chart-viewport">
                            <div class="chart-wrapper" style="min-width: 100%; min-height: max(100%, 300px); height: 100%;">
                                <canvas id="serviceChart"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- Overdue Tickets by Personnel (Vertical Bar Chart - Horizontal Scrolling) --}}
                    <div class="table-card tc-red">
                        <h4 class="table-card-title" style="color: var(--icon-red-text);">
                            <span class="material-symbols-outlined">warning</span> Overdue Tickets by Personnel
                        </h4>
                        <div class="chart-viewport">
                            <div class="chart-wrapper" style="min-width: max(100%, {{ $overdueCount * 60 }}px); height: 100%;">
                                <canvas id="overdueChart"></canvas>
                            </div>
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

    {{-- Script to Initialize Charts --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Theme Colors Logic
            const isDark = document.body.classList.contains('dark');
            const textColor = isDark ? '#e2e8f0' : '#334155';
            const gridColor = isDark ? '#334155' : '#e2e8f0';

            // Chart JS Common Defaults
            Chart.defaults.color = textColor;
            Chart.defaults.font.family = "'Inter', system-ui, sans-serif";

            // 1. Tickets by Region (Vertical Bar)
            const regionCtx = document.getElementById('regionChart');
            if (regionCtx) {
                new Chart(regionCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($regionLabels),
                        datasets: [{
                            label: 'Tickets',
                            data: @json($regionTotals),
                            backgroundColor: '#6366f1',
                            borderRadius: 6,
                            maxBarThickness: 40
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { color: gridColor }, ticks: { color: textColor } },
                            y: { grid: { color: gridColor }, ticks: { color: textColor, stepSize: 1 }, beginAtZero: true }
                        }
                    }
                });
            }

            // 2. Tickets by Personnel (Horizontal Bar)
            const personnelCtx = document.getElementById('personnelChart');
            if (personnelCtx) {
                new Chart(personnelCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($personnelLabels),
                        datasets: [{
                            label: 'Assigned Tickets',
                            data: @json($personnelTotals),
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                            maxBarThickness: 25
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { color: gridColor }, ticks: { color: textColor, stepSize: 1 }, beginAtZero: true },
                            y: { grid: { color: gridColor }, ticks: { color: textColor } }
                        }
                    }
                });
            }

            // 3. Tickets by Technical Service (Doughnut Chart)
            const serviceCtx = document.getElementById('serviceChart');
            if (serviceCtx) {
                new Chart(serviceCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($serviceLabels),
                        datasets: [{
                            data: @json($serviceTotals),
                            backgroundColor: [
                                '#f59e0b', '#3b82f6', '#10b981', '#6366f1', 
                                '#ec4899', '#8b5cf6', '#06b6d4', '#ef4444'
                            ],
                            borderWidth: 2,
                            borderColor: isDark ? '#0f172a' : '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: { color: textColor, boxWidth: 12, font: { size: 11 } }
                            }
                        },
                        cutout: '65%'
                    }
                });
            }

            // 4. Overdue Tickets by Personnel (Bar Chart)
            const overdueCtx = document.getElementById('overdueChart');
            if (overdueCtx) {
                new Chart(overdueCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($overduePersonnelLabels),
                        datasets: [{
                            label: 'Overdue Count',
                            data: @json($overdueCounts),
                            backgroundColor: '#ef4444',
                            borderRadius: 6,
                            maxBarThickness: 40
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { color: gridColor }, ticks: { color: textColor } },
                            y: { grid: { color: gridColor }, ticks: { color: textColor, stepSize: 1 }, beginAtZero: true }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
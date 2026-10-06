<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <!-- Include Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @if(auth()->user()->hasAnyRole(['Super Admin', 'ICTS Admin']))

    @can('view_overview_tickets')
    <div id="main-content" class="w-full">
        <div id="dashboardContent" class="space-y-6">

            {{-- Dashboard Panel --}}
            <div class="w-full bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-4 sm:p-6 lg:p-8">

                {{-- Header Section --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8 pb-5 border-b border-[var(--border-subtle)]">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-sky-500 text-white flex items-center justify-center shadow-md shadow-indigo-500/20 shrink-0">
                            <span class="material-symbols-outlined text-2xl">monitoring</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                                    Tickets Overview
                                </h1>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Live Operations
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                                Visual analytics, regional workload distribution, and IT support performance metrics
                            </p>
                        </div>
                    </div>

                    {{-- Region Filter & Export Actions --}}
                    <div class="flex items-center gap-3 flex-wrap">
                        @can('filter_ticket_by_region')
                        <form method="GET" action="{{ route('overview_tickets.index') }}" class="flex items-center m-0">
                            <div class="inline-flex items-center h-11 rounded-xl bg-[var(--card-bg)] border border-[var(--border-light)] shadow-xs hover:border-indigo-300 dark:hover:border-indigo-700 transition-all overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500/30 focus-within:border-indigo-500">
                                <label for="region" class="flex items-center gap-1.5 px-3 h-full bg-slate-50 dark:bg-slate-800/60 border-r border-[var(--border-light)] text-indigo-600 dark:text-indigo-400 cursor-pointer select-none shrink-0">
                                    <span class="material-symbols-outlined text-lg">location_on</span>
                                </label>
                                <div class="relative flex items-center h-full">
                                    <select name="region" id="region"
                                        class="h-full pl-3 pr-9 min-w-[150px] sm:min-w-[190px] text-xs sm:text-sm font-semibold bg-transparent text-[var(--text-dark)] border-0 appearance-none cursor-pointer outline-none focus:ring-0"
                                        onchange="this.form.submit()">
                                        <option value="" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">All Regions</option>
                                        @if(!empty($regions))
                                            @foreach($regions as $region)
                                                <option value="{{ trim($region) }}" {{ request('region') == trim($region) ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                                    {{ trim($region) }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 flex items-center">
                                        <span class="material-symbols-outlined text-lg">unfold_more</span>
                                    </span>
                                </div>
                            </div>
                        </form>

                        @if(request('region'))
                            <a href="{{ route('overview_tickets.index') }}"
                               class="inline-flex items-center gap-1.5 h-11 px-3.5 rounded-xl text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200/80 dark:border-indigo-800/80 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 dark:hover:bg-rose-950/40 dark:hover:text-rose-400 transition-all shadow-2xs group"
                               title="Clear Region Filter">
                                <span class="material-symbols-outlined text-sm text-indigo-500 group-hover:text-rose-500 transition-colors">filter_alt_off</span>
                                <span>Clear: <strong class="font-bold">{{ request('region') }}</strong></span>
                                <span class="material-symbols-outlined text-sm font-bold ml-0.5">close</span>
                            </a>
                        @endif
                        @endcan

                        <a href="{{ route('tickets.export_pdf', ['region' => request('region')]) }}"
                            title="Download PDF Summary Report"
                            class="inline-flex items-center justify-center gap-2 h-11 px-4 sm:px-5 rounded-xl text-xs sm:text-sm font-semibold bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/50 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 shadow-xs hover:-translate-y-px active:scale-95 transition-all cursor-pointer">
                            <span class="material-symbols-outlined text-lg text-rose-600 dark:text-rose-400">picture_as_pdf</span>
                            <span>Download Report</span>
                        </a>
                    </div>
                </div>

                {{-- Preprocessing Data for Javascript Charts & Dimensions --}}
                @php
                    $totalNum    = (int) ($total ?? 0);
                    $pendingNum  = (int) ($pending ?? 0);
                    $resolvedNum = (int) ($resolved ?? 0);
                    $overdueNum  = (int) ($overdue ?? 0);

                    $resolvedRate = $totalNum > 0 ? round(($resolvedNum / $totalNum) * 100, 1) : 0;
                    $pendingRate  = $totalNum > 0 ? round(($pendingNum / $totalNum) * 100, 1) : 0;

                    $cards = [
                        [
                            'label'    => 'Total Tickets',
                            'icon'     => 'confirmation_number',
                            'theme'    => 'indigo',
                            'value'    => $totalNum,
                            'subtext'  => 'All submitted requests',
                            'badge'    => '100% Volume',
                            'bg'       => 'bg-indigo-50 dark:bg-indigo-950/70 border border-indigo-100 dark:border-indigo-800/40',
                            'text'     => 'text-indigo-600 dark:text-indigo-400',
                            'border'   => 'border-l-indigo-500 dark:border-l-indigo-400',
                            'badge_bg' => 'bg-indigo-100/80 text-indigo-700 dark:bg-indigo-950/80 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/60',
                        ],
                        [
                            'label'    => 'Pending Tickets',
                            'icon'     => 'hourglass_top',
                            'theme'    => 'amber',
                            'value'    => $pendingNum,
                            'subtext'  => 'Awaiting action or re-assigned',
                            'badge'    => $pendingRate . '% of total',
                            'bg'       => 'bg-amber-50 dark:bg-amber-950/70 border border-amber-100 dark:border-amber-800/40',
                            'text'     => 'text-amber-600 dark:text-amber-400',
                            'border'   => 'border-l-amber-500 dark:border-l-amber-400',
                            'badge_bg' => 'bg-amber-100/80 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/60',
                        ],
                        [
                            'label'    => 'Resolved Tickets',
                            'icon'     => 'task_alt',
                            'theme'    => 'emerald',
                            'value'    => $resolvedNum,
                            'subtext'  => 'Successfully closed tickets',
                            'badge'    => $resolvedRate . '% resolution rate',
                            'bg'       => 'bg-emerald-50 dark:bg-emerald-950/70 border border-emerald-100 dark:border-emerald-800/40',
                            'text'     => 'text-emerald-600 dark:text-emerald-400',
                            'border'   => 'border-l-emerald-500 dark:border-l-emerald-400',
                            'badge_bg' => 'bg-emerald-100/80 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60',
                        ],
                        [
                            'label'    => 'Overdue Tickets',
                            'icon'     => 'warning',
                            'theme'    => 'rose',
                            'value'    => $overdueNum,
                            'subtext'  => 'Exceeded SLA deadline',
                            'badge'    => $overdueNum > 0 ? 'Requires attention' : 'Zero SLA breaches',
                            'bg'       => 'bg-rose-50 dark:bg-rose-950/70 border border-rose-100 dark:border-rose-800/40',
                            'text'     => 'text-rose-600 dark:text-rose-400',
                            'border'   => 'border-l-rose-500 dark:border-l-rose-400',
                            'badge_bg' => $overdueNum > 0 
                                ? 'bg-rose-100/80 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/60'
                                : 'bg-emerald-100/80 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60',
                        ],
                    ];

                    $regionLabels   = collect($byItArea ?? [])->pluck('it_area')->map(fn($v) => trim($v))->toArray();
                    $regionTotals   = collect($byItArea ?? [])->pluck('total')->toArray();
                    $regionCount    = count($regionLabels);

                    $personnelLabels = collect($byItPersonnel ?? [])->pluck('it_personnel')->map(fn($v) => trim($v))->toArray();
                    $personnelTotals = collect($byItPersonnel ?? [])->pluck('total')->toArray();
                    $personnelCount  = count($personnelLabels);

                    $serviceLabels  = collect($byService ?? [])->pluck('service')->map(fn($v) => trim($v))->toArray();
                    $serviceTotals  = collect($byService ?? [])->pluck('total')->toArray();
                    $serviceCount   = count($serviceLabels);

                    $overduePersonnelLabels = [];
                    $overdueCounts = [];
                    foreach(($overdueTickets ?? []) as $pName => $tList) {
                        $overduePersonnelLabels[] = trim($pName);
                        $overdueCounts[] = is_array($tList) || $tList instanceof \Countable ? count($tList) : 0;
                    }
                    $overdueCount = count($overduePersonnelLabels);
                @endphp

                {{-- ─── Stat Cards Grid ─── --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-10">
                    @foreach ($cards as $card)
                        <div class="rounded-2xl p-5 sm:p-6 flex items-start justify-between bg-[var(--card-bg)] border border-[var(--border-light)] {{ $card['border'] }} border-l-4 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-300 cursor-default">
                            <div class="flex flex-col pr-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">
                                    {{ $card['label'] }}
                                </span>
                                <span class="text-3xl sm:text-4xl font-black mt-1.5 leading-none text-[var(--text-dark)] tracking-tight">
                                    {{ number_format($card['value']) }}
                                </span>
                                <span class="text-[11px] text-[var(--text-muted)] font-medium mt-2">
                                    {{ $card['subtext'] }}
                                </span>
                                <div class="mt-2.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wide {{ $card['badge_bg'] }}">
                                        {{ $card['badge'] }}
                                    </span>
                                </div>
                            </div>
                            <div class="w-12 h-12 sm:w-13 sm:h-13 rounded-2xl flex items-center justify-center {{ $card['bg'] }} {{ $card['text'] }} shrink-0 shadow-2xs">
                                <span class="material-symbols-outlined text-2xl sm:text-3xl">{{ $card['icon'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ─── Charts Section (2 Charts Per Row with Enhanced Spacing & Responsive Wrap) ─── --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-7 lg:gap-8 mb-14">

                    @can('view tickets by region')
                    {{-- Chart 1: Tickets by Region --}}
                    <div class="rounded-2xl p-4 sm:p-5 bg-[var(--card-bg)] border border-[var(--border-light)] border-t-4 border-t-indigo-600 shadow-xs hover:shadow-md flex flex-col overflow-hidden transition-all duration-300">
                        <div class="flex items-center justify-between mb-3.5 pb-2.5 border-b border-[var(--border-subtle)]">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-lg">map</span>
                                </div>
                                <div>
                                    <h2 class="text-sm sm:text-base font-bold text-[var(--text-dark)] m-0 leading-tight">
                                        Tickets by Region
                                    </h2>
                                    <p class="text-[11px] text-[var(--text-muted)] m-0">Regional workload distribution</p>
                                </div>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/60 shrink-0">
                                {{ $regionCount }} Regions
                            </span>
                        </div>

                        @if($regionCount > 0)
                            <div class="relative w-full overflow-y-auto rounded-xl pr-1" style="height: 220px; -webkit-overflow-scrolling: touch;">
                                <div class="relative w-full" style="height: max(100%, {{ max($regionCount, 1) * 34 }}px);">
                                    <canvas id="regionChart"></canvas>
                                </div>
                            </div>
                        @else
                            <div class="w-full flex flex-col items-center justify-center gap-2 text-[var(--text-muted)] rounded-xl border border-dashed border-[var(--border-light)] p-4 text-center" style="height: 220px;">
                                <span class="material-symbols-outlined text-3xl text-slate-300 dark:text-slate-600">location_off</span>
                                <p class="text-xs font-semibold m-0">No Regional Ticket Data</p>
                                <p class="text-[11px] m-0">Regional data will display once tickets with assigned regions are logged.</p>
                            </div>
                        @endif
                    </div>
                    @endcan

                    {{-- Chart 2: Tickets by Technical Personnel --}}
                    <div class="rounded-2xl p-4 sm:p-5 bg-[var(--card-bg)] border border-[var(--border-light)] border-t-4 border-t-emerald-600 shadow-xs hover:shadow-md flex flex-col overflow-hidden transition-all duration-300">
                        <div class="flex items-center justify-between mb-3.5 pb-2.5 border-b border-[var(--border-subtle)]">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-lg">engineering</span>
                                </div>
                                <div>
                                    <h2 class="text-sm sm:text-base font-bold text-[var(--text-dark)] m-0 leading-tight">
                                        Technical Personnel
                                    </h2>
                                    <p class="text-[11px] text-[var(--text-muted)] m-0">Workload (Includes Re-Assigned)</p>
                                </div>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60 shrink-0">
                                {{ $personnelCount }} Personnel
                            </span>
                        </div>

                        @if($personnelCount > 0)
                            <div class="relative w-full overflow-y-auto rounded-xl pr-1" style="height: 220px; -webkit-overflow-scrolling: touch;">
                                <div class="relative w-full" style="height: max(100%, {{ max($personnelCount, 1) * 34 }}px);">
                                    <canvas id="personnelChart"></canvas>
                                </div>
                            </div>
                        @else
                            <div class="w-full flex flex-col items-center justify-center gap-2 text-[var(--text-muted)] rounded-xl border border-dashed border-[var(--border-light)] p-4 text-center" style="height: 220px;">
                                <span class="material-symbols-outlined text-3xl text-slate-300 dark:text-slate-600">person_off</span>
                                <p class="text-xs font-semibold m-0">No Personnel Assignments</p>
                                <p class="text-[11px] m-0">Assigned tickets will automatically populate this chart.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Chart 3: Tickets by Technical Service --}}
                    <div class="rounded-2xl p-4 sm:p-5 bg-[var(--card-bg)] border border-[var(--border-light)] border-t-4 border-t-amber-500 shadow-xs hover:shadow-md flex flex-col overflow-hidden transition-all duration-300">
                        <div class="flex items-center justify-between mb-3.5 pb-2.5 border-b border-[var(--border-subtle)]">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-lg">category</span>
                                </div>
                                <div>
                                    <h2 class="text-sm sm:text-base font-bold text-[var(--text-dark)] m-0 leading-tight">
                                        Technical Services
                                    </h2>
                                    <p class="text-[11px] text-[var(--text-muted)] m-0">By service category</p>
                                </div>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/60 shrink-0">
                                {{ $serviceCount }} Categories
                            </span>
                        </div>

                        @if($serviceCount > 0)
                            <div class="relative w-full rounded-xl flex items-center justify-center p-1" style="height: 220px;">
                                <div class="relative w-full h-full max-h-[210px]">
                                    <canvas id="serviceChart"></canvas>
                                </div>
                            </div>
                        @else
                            <div class="w-full flex flex-col items-center justify-center gap-2 text-[var(--text-muted)] rounded-xl border border-dashed border-[var(--border-light)] p-4 text-center" style="height: 220px;">
                                <span class="material-symbols-outlined text-3xl text-slate-300 dark:text-slate-600">pie_chart</span>
                                <p class="text-xs font-semibold m-0">No Service Category Data</p>
                                <p class="text-[11px] m-0">Tickets categorized by service will appear here.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Chart 4: Overdue Tickets by Personnel --}}
                    <div class="rounded-2xl p-4 sm:p-5 bg-[var(--card-bg)] border border-[var(--border-light)] border-t-4 border-t-rose-600 shadow-xs hover:shadow-md flex flex-col overflow-hidden transition-all duration-300">
                        <div class="flex items-center justify-between mb-3.5 pb-2.5 border-b border-[var(--border-subtle)]">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-lg">alarm_on</span>
                                </div>
                                <div>
                                    <h2 class="text-sm sm:text-base font-bold text-[var(--text-dark)] m-0 leading-tight">
                                        Overdue by Personnel
                                    </h2>
                                    <p class="text-[11px] text-[var(--text-muted)] m-0">Tickets exceeding SLA</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full shrink-0 {{ $overdueCount > 0 ? 'bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 border border-rose-200/60 dark:border-rose-800/60' : 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60' }}">
                                {{ $overdueCount > 0 ? $overdueCount . ' Assigned' : '0 Overdue' }}
                            </span>
                        </div>

                        @if($overdueCount > 0)
                            <div class="relative w-full overflow-y-auto rounded-xl pr-1" style="height: 220px; -webkit-overflow-scrolling: touch;">
                                <div class="relative w-full" style="height: max(100%, {{ max($overdueCount, 1) * 34 }}px);">
                                    <canvas id="overdueChart"></canvas>
                                </div>
                            </div>
                        @else
                            <div class="w-full flex flex-col items-center justify-center gap-2 text-center p-4 rounded-xl border border-dashed border-emerald-300/60 dark:border-emerald-800/60 bg-emerald-50/40 dark:bg-emerald-950/20" style="height: 220px;">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shadow-xs">
                                    <span class="material-symbols-outlined text-xl">check_circle</span>
                                </div>
                                <h3 class="text-xs sm:text-sm font-bold text-emerald-800 dark:text-emerald-300 m-0">All Caught Up!</h3>
                                <p class="text-[11px] text-[var(--text-muted)] m-0 max-w-xs leading-relaxed">
                                    There are currently zero overdue tickets. All support requests are progressing within their designated SLA timeframes.
                                </p>
                            </div>
                        @endif
                    </div>

                </div>

                {{-- ─── Recently Resolved Tickets ─── --}}
                <div class="mt-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 shadow-2xs">
                                <span class="material-symbols-outlined text-xl">history</span>
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-[var(--text-dark)] m-0 leading-tight">
                                    Recently Resolved Tickets
                                </h2>
                                <p class="text-xs text-[var(--text-muted)] m-0 mt-0.5">Most recent successful technical support resolutions</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 border border-blue-200/60 dark:border-blue-800/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                <span>Latest {{ count($recentlyResolved ?? []) }} Resolutions</span>
                            </span>
                        </div>
                    </div>

                    <div class="w-full overflow-hidden rounded-2xl border border-[var(--border-light)] shadow-xs bg-[var(--card-bg)]">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-[var(--text-dark)] border-collapse min-w-[900px]">
                                <thead class="bg-slate-50/90 dark:bg-slate-800/80 text-[var(--text-muted)] text-xs uppercase font-bold tracking-wider border-b border-[var(--border-light)]">
                                    <tr>
                                        <th class="py-3.5 px-4 font-bold">Ticket Number</th>
                                        <th class="py-3.5 px-4 font-bold">Requested By</th>
                                        <th class="py-3.5 px-4 font-bold">Service Category</th>
                                        <th class="py-3.5 px-4 font-bold">Assigned IT Personnel</th>
                                        <th class="py-3.5 px-4 font-bold">Date Created</th>
                                        <th class="py-3.5 px-4 font-bold">Date Resolved</th>
                                        <th class="py-3.5 px-4 font-bold">Turnaround</th>
                                        <th class="py-3.5 px-4 text-center font-bold">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[var(--border-subtle)]">
                                    @forelse ($recentlyResolved ?? [] as $ticket)
                                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                                            <td class="py-4 px-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/60">
                                                    {{ $ticket->ticket_number }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4 whitespace-nowrap font-medium text-[var(--text-dark)]">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center text-xs font-bold text-slate-700 dark:text-slate-200 shrink-0 uppercase shadow-2xs">
                                                        {{ substr($ticket->firstname, 0, 1) }}{{ substr($ticket->lastname, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <span class="block text-sm font-semibold leading-tight">{{ $ticket->firstname }} {{ $ticket->lastname }}</span>
                                                        <span class="block text-[11px] text-[var(--text-muted)]">{{ $ticket->email ?? 'No email' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 text-[var(--text-dark)] border border-slate-200 dark:border-slate-700">
                                                    {{ $ticket->service }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4 whitespace-nowrap text-xs text-[var(--text-dark)]">
                                                <div class="flex items-center gap-1.5 font-medium">
                                                    <span class="material-symbols-outlined text-base text-emerald-600 dark:text-emerald-400">badge</span>
                                                    <span>{{ $ticket->it_personnel ?: 'Unassigned' }}</span>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 whitespace-nowrap text-xs text-[var(--text-muted)] font-mono">
                                                {{ \Carbon\Carbon::parse($ticket->date_created)->format('M d, Y h:i A') }}
                                            </td>
                                            <td class="py-4 px-4 whitespace-nowrap text-xs text-emerald-600 dark:text-emerald-400 font-mono font-medium">
                                                {{ \Carbon\Carbon::parse($ticket->date_resolved)->format('M d, Y h:i A') }}
                                            </td>
                                            <td class="py-4 px-4 whitespace-nowrap text-xs">
                                                @php
                                                    $created = \Carbon\Carbon::parse($ticket->date_created);
                                                    $resolvedDate = \Carbon\Carbon::parse($ticket->date_resolved);
                                                    $diff = $created->diffForHumans($resolvedDate, [
                                                        'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE,
                                                        'parts'  => 2,
                                                        'short'  => true,
                                                    ]);
                                                @endphp
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60" title="{{ $created->diffForHumans($resolvedDate, true) }}">
                                                    <span class="material-symbols-outlined text-xs">timer</span>
                                                    {{ $diff }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Resolved
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="py-12 px-4 text-center">
                                                <div class="flex flex-col items-center justify-center gap-2 text-[var(--text-muted)]">
                                                    <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600">task_alt</span>
                                                    <p class="text-base font-semibold m-0">No recently resolved tickets to display</p>
                                                    <p class="text-xs m-0">Resolved tickets will automatically appear here once closed by IT personnel.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endcan
    @endif

    {{-- Script to Initialize Charts with Real-Time Dark/Light Mode Dynamic Adaptation --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const chartInstances = {};

            function getThemeConfig() {
                const isDark = document.documentElement.classList.contains('dark') || document.body.classList.contains('dark');
                return {
                    isDark: isDark,
                    textColor: isDark ? '#e2e8f0' : '#334155',
                    mutedColor: isDark ? '#94a3b8' : '#64748b',
                    gridColor: isDark ? 'rgba(51, 65, 85, 0.45)' : 'rgba(226, 232, 240, 0.85)',
                    tooltipBg: isDark ? '#0f172a' : '#1e293b',
                    tooltipTitle: '#ffffff',
                    tooltipBody: '#e2e8f0',
                    tooltipBorder: isDark ? '#334155' : 'transparent',
                    doughnutBorder: isDark ? '#0f172a' : '#ffffff',
                    regionBarBg: isDark ? '#818cf8' : '#6366f1',
                    regionBarHover: isDark ? '#a5b4fc' : '#4f46e5',
                    personnelBarBg: isDark ? '#34d399' : '#10b981',
                    personnelBarHover: isDark ? '#6ee7b7' : '#059669',
                    overdueBarBg: isDark ? '#fb7185' : '#f43f5e',
                    overdueBarHover: isDark ? '#fda4af' : '#e11d48',
                };
            }

            function applyGlobalDefaults(theme) {
                Chart.defaults.color = theme.textColor;
                Chart.defaults.font.family = "'Inter', system-ui, -apple-system, sans-serif";
                Chart.defaults.font.size = 11;
                Chart.defaults.plugins.tooltip.backgroundColor = theme.tooltipBg;
                Chart.defaults.plugins.tooltip.titleColor = theme.tooltipTitle;
                Chart.defaults.plugins.tooltip.bodyColor = theme.tooltipBody;
                Chart.defaults.plugins.tooltip.borderColor = theme.tooltipBorder;
                Chart.defaults.plugins.tooltip.borderWidth = theme.isDark ? 1 : 0;
                Chart.defaults.plugins.tooltip.padding = 9;
                Chart.defaults.plugins.tooltip.cornerRadius = 8;
                Chart.defaults.plugins.tooltip.boxPadding = 4;
            }

            const initialTheme = getThemeConfig();
            applyGlobalDefaults(initialTheme);

            // 1. Tickets by Region (Compact Horizontal Bar)
            const regionCtx = document.getElementById('regionChart');
            if (regionCtx) {
                chartInstances.region = new Chart(regionCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($regionLabels),
                        datasets: [{
                            label: 'Tickets',
                            data: @json($regionTotals),
                            backgroundColor: initialTheme.regionBarBg,
                            hoverBackgroundColor: initialTheme.regionBarHover,
                            borderRadius: 5,
                            maxBarThickness: 18
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const val = context.raw || 0;
                                        return ` ${val} ticket${val === 1 ? '' : 's'}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { color: initialTheme.gridColor },
                                ticks: { color: initialTheme.mutedColor, font: { size: 10 }, stepSize: 1, precision: 0 },
                                beginAtZero: true
                            },
                            y: {
                                grid: { display: false },
                                ticks: { color: initialTheme.textColor, font: { size: 10, weight: 500 } }
                            }
                        }
                    }
                });
            }

            // 2. Tickets by Personnel (Compact Horizontal Bar)
            const personnelCtx = document.getElementById('personnelChart');
            if (personnelCtx) {
                chartInstances.personnel = new Chart(personnelCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($personnelLabels),
                        datasets: [{
                            label: 'Assigned Tickets',
                            data: @json($personnelTotals),
                            backgroundColor: initialTheme.personnelBarBg,
                            hoverBackgroundColor: initialTheme.personnelBarHover,
                            borderRadius: 5,
                            maxBarThickness: 18
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const val = context.raw || 0;
                                        return ` ${val} assigned ticket${val === 1 ? '' : 's'}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { color: initialTheme.gridColor },
                                ticks: { color: initialTheme.mutedColor, font: { size: 10 }, stepSize: 1, precision: 0 },
                                beginAtZero: true
                            },
                            y: {
                                grid: { display: false },
                                ticks: { color: initialTheme.textColor, font: { size: 10, weight: 500 } }
                            }
                        }
                    }
                });
            }

            // 3. Tickets by Technical Service (Compact Doughnut Chart)
            const serviceCtx = document.getElementById('serviceChart');
            if (serviceCtx) {
                chartInstances.service = new Chart(serviceCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($serviceLabels),
                        datasets: [{
                            data: @json($serviceTotals),
                            backgroundColor: [
                                '#6366f1', '#10b981', '#f59e0b', '#06b6d4',
                                '#ec4899', '#8b5cf6', '#3b82f6', '#f97316'
                            ],
                            borderWidth: 2,
                            borderColor: initialTheme.doughnutBorder,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    color: initialTheme.textColor,
                                    boxWidth: 10,
                                    padding: 8,
                                    font: { size: 11, weight: 500 }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const val = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const pct = total > 0 ? Math.round((val / total) * 100) : 0;
                                        return ` ${label}: ${val} (${pct}%)`;
                                    }
                                }
                            }
                        },
                        cutout: '64%'
                    }
                });
            }

            // 4. Overdue Tickets by Personnel (Compact Horizontal Bar Matching Others)
            const overdueCtx = document.getElementById('overdueChart');
            if (overdueCtx) {
                chartInstances.overdue = new Chart(overdueCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($overduePersonnelLabels),
                        datasets: [{
                            label: 'Overdue Count',
                            data: @json($overdueCounts),
                            backgroundColor: initialTheme.overdueBarBg,
                            hoverBackgroundColor: initialTheme.overdueBarHover,
                            borderRadius: 5,
                            maxBarThickness: 18
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const val = context.raw || 0;
                                        return ` ${val} overdue ticket${val === 1 ? '' : 's'}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { color: initialTheme.gridColor },
                                ticks: { color: initialTheme.mutedColor, font: { size: 10 }, stepSize: 1, precision: 0 },
                                beginAtZero: true
                            },
                            y: {
                                grid: { display: false },
                                ticks: { color: initialTheme.textColor, font: { size: 10, weight: 500 } }
                            }
                        }
                    }
                });
            }

            // Real-Time Theme Synchronizer
            function syncChartsWithTheme() {
                const theme = getThemeConfig();
                applyGlobalDefaults(theme);

                if (chartInstances.region) {
                    chartInstances.region.data.datasets[0].backgroundColor = theme.regionBarBg;
                    chartInstances.region.data.datasets[0].hoverBackgroundColor = theme.regionBarHover;
                    chartInstances.region.options.scales.x.grid.color = theme.gridColor;
                    chartInstances.region.options.scales.x.ticks.color = theme.mutedColor;
                    chartInstances.region.options.scales.y.ticks.color = theme.textColor;
                    chartInstances.region.update('none');
                }

                if (chartInstances.personnel) {
                    chartInstances.personnel.data.datasets[0].backgroundColor = theme.personnelBarBg;
                    chartInstances.personnel.data.datasets[0].hoverBackgroundColor = theme.personnelBarHover;
                    chartInstances.personnel.options.scales.x.grid.color = theme.gridColor;
                    chartInstances.personnel.options.scales.x.ticks.color = theme.mutedColor;
                    chartInstances.personnel.options.scales.y.ticks.color = theme.textColor;
                    chartInstances.personnel.update('none');
                }

                if (chartInstances.service) {
                    chartInstances.service.data.datasets[0].borderColor = theme.doughnutBorder;
                    if (chartInstances.service.options.plugins.legend) {
                        chartInstances.service.options.plugins.legend.labels.color = theme.textColor;
                    }
                    chartInstances.service.update('none');
                }

                if (chartInstances.overdue) {
                    chartInstances.overdue.data.datasets[0].backgroundColor = theme.overdueBarBg;
                    chartInstances.overdue.data.datasets[0].hoverBackgroundColor = theme.overdueBarHover;
                    chartInstances.overdue.options.scales.x.grid.color = theme.gridColor;
                    chartInstances.overdue.options.scales.x.ticks.color = theme.mutedColor;
                    chartInstances.overdue.options.scales.y.ticks.color = theme.textColor;
                    chartInstances.overdue.update('none');
                }
            }

            // Listen to Alpine theme-changed event
            window.addEventListener('theme-changed', syncChartsWithTheme);

            // Observe class changes on html or body for external/manual theme toggles
            const themeObserver = new MutationObserver(() => syncChartsWithTheme());
            themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
            themeObserver.observe(document.body, { attributes: true, attributeFilter: ['class'] });
        });
    </script>
</x-app-layout>
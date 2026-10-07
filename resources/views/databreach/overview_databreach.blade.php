<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <!-- Include Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @can('view_overview_databreach')
    <div id="main-content" class="w-full">
        <div id="dashboardContent" class="space-y-6">

            {{-- Main Content Card --}}
            <div class="w-full bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-4 sm:p-6 lg:p-8">

                {{-- Header Section --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8 pb-5 border-b border-[var(--border-subtle)]">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-rose-600 via-rose-500 to-amber-500 text-white flex items-center justify-center shadow-md shadow-rose-500/20 shrink-0">
                            <span class="material-symbols-outlined text-2xl">monitoring</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                                    Incident Overview
                                </h1>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Live Analytics
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                                Data breach notifications metrics, specific cause distributions, and reporting compliance
                            </p>
                        </div>
                    </div>

                    {{-- Actions: Year Filter & PDF Export --}}
                    <div class="flex items-center gap-3 flex-wrap">
                        <form method="GET" action="{{ route('databreach.overview') }}" class="flex items-center m-0">
                            <div class="inline-flex items-center h-11 rounded-xl bg-[var(--card-bg)] border border-[var(--border-light)] shadow-xs hover:border-indigo-300 dark:hover:border-indigo-700 transition-all overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500/30 focus-within:border-indigo-500">
                                <label for="year" class="flex items-center gap-1.5 px-3 h-full bg-slate-50 dark:bg-slate-800/60 border-r border-[var(--border-light)] text-indigo-600 dark:text-indigo-400 cursor-pointer select-none shrink-0">
                                    <span class="material-symbols-outlined text-lg">calendar_today</span>
                                </label>
                                <div class="relative flex items-center h-full">
                                    <select name="year" id="year"
                                        class="h-full pl-3 pr-9 min-w-[130px] sm:min-w-[160px] text-xs sm:text-sm font-semibold bg-transparent text-[var(--text-dark)] border-0 appearance-none cursor-pointer outline-none focus:ring-0"
                                        onchange="this.form.submit()">
                                        <option value="" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">All Years</option>
                                        @foreach($years ?? [] as $y)
                                            <option value="{{ $y }}" {{ (isset($year) && $year == $y) ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                                {{ $y }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 flex items-center">
                                        <span class="material-symbols-outlined text-lg">unfold_more</span>
                                    </span>
                                </div>
                            </div>
                        </form>

                        @if(request('year'))
                            <a href="{{ route('databreach.overview') }}"
                               class="inline-flex items-center gap-1.5 h-11 px-3.5 rounded-xl text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200/80 dark:border-indigo-800/80 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 dark:hover:bg-rose-950/40 dark:hover:text-rose-400 transition-all shadow-2xs group"
                               title="Clear Year Filter">
                                <span class="material-symbols-outlined text-sm text-indigo-500 group-hover:text-rose-500 transition-colors">filter_alt_off</span>
                                <span>Year: <strong class="font-bold">{{ request('year') }}</strong></span>
                                <span class="material-symbols-outlined text-sm font-bold ml-0.5">close</span>
                            </a>
                        @endif

                        @can('generate_databreach')
                        <form method="GET" action="{{ route('databreach.overview') }}" class="m-0 inline">
                            @if(request('year'))
                                <input type="hidden" name="year" value="{{ request('year') }}">
                            @endif
                            <button type="submit" name="action" value="generate"
                                title="Download PDF Summary Report"
                                class="inline-flex items-center justify-center gap-2 h-11 px-4 sm:px-5 rounded-xl text-xs sm:text-sm font-semibold bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/50 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 shadow-xs hover:-translate-y-px active:scale-95 transition-all cursor-pointer">
                                <span class="material-symbols-outlined text-lg text-rose-600 dark:text-rose-400">picture_as_pdf</span>
                                <span>Download Report</span>
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>

                {{-- Stat Cards Preprocessing --}}
                @php
                    $totalNum     = (int) ($totalNotifications ?? 0);
                    $mandatoryNum = (int) ($totalMandatory ?? 0);
                    $voluntaryNum = (int) ($totalVoluntary ?? 0);
                    $reportedNum  = (int) ($totalReported ?? 0);
                    $othersNum    = max(0, $totalNum - ($mandatoryNum + $voluntaryNum));

                    $mandatoryRate = $totalNum > 0 ? round(($mandatoryNum / $totalNum) * 100, 1) : 0;
                    $voluntaryRate = $totalNum > 0 ? round(($voluntaryNum / $totalNum) * 100, 1) : 0;
                    $reportedRate  = $totalNum > 0 ? round(($reportedNum / $totalNum) * 100, 1) : 0;

                    $cards = [
                        [
                            'label'    => 'Total Incidents',
                            'icon'     => 'security',
                            'value'    => $totalNum,
                            'subtext'  => 'All reported breaches',
                            'badge'    => '100% Volume',
                            'bg'       => 'bg-indigo-50 dark:bg-indigo-950/70 border border-indigo-100 dark:border-indigo-800/40',
                            'text'     => 'text-indigo-600 dark:text-indigo-400',
                            'border'   => 'border-l-indigo-500 dark:border-l-indigo-400',
                            'badge_bg' => 'bg-indigo-100/80 text-indigo-700 dark:bg-indigo-950/80 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/60',
                        ],
                        [
                            'label'    => 'Mandatory',
                            'icon'     => 'data_alert',
                            'value'    => $mandatoryNum,
                            'subtext'  => 'Required NPC notification',
                            'badge'    => $mandatoryRate . '% of total',
                            'bg'       => 'bg-rose-50 dark:bg-rose-950/70 border border-rose-100 dark:border-rose-800/40',
                            'text'     => 'text-rose-600 dark:text-rose-400',
                            'border'   => 'border-l-rose-500 dark:border-l-rose-400',
                            'badge_bg' => 'bg-rose-100/80 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/60',
                        ],
                        [
                            'label'    => 'Voluntary',
                            'icon'     => 'list_alt',
                            'value'    => $voluntaryNum,
                            'subtext'  => 'Voluntarily submitted',
                            'badge'    => $voluntaryRate . '% of total',
                            'bg'       => 'bg-amber-50 dark:bg-amber-950/70 border border-amber-100 dark:border-amber-800/40',
                            'text'     => 'text-amber-600 dark:text-amber-400',
                            'border'   => 'border-l-amber-500 dark:border-l-amber-400',
                            'badge_bg' => 'bg-amber-100/80 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/60',
                        ],
                        [
                            'label'    => 'Other Types',
                            'icon'     => 'filter_none',
                            'value'    => $othersNum,
                            'subtext'  => 'Uncategorized or misc.',
                            'badge'    => $othersNum > 0 ? $othersNum . ' Recorded' : 'Zero records',
                            'bg'       => 'bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700',
                            'text'     => 'text-slate-600 dark:text-slate-400',
                            'border'   => 'border-l-slate-500 dark:border-l-slate-400',
                            'badge_bg' => 'bg-slate-200/80 text-slate-800 dark:bg-slate-800 dark:text-slate-300 border border-slate-300/60 dark:border-slate-700',
                        ],
                        [
                            'label'    => 'Total Reported',
                            'icon'     => 'check_circle',
                            'value'    => $reportedNum,
                            'subtext'  => 'Closed & sent to NPC',
                            'badge'    => $reportedRate . '% reported rate',
                            'bg'       => 'bg-emerald-50 dark:bg-emerald-950/70 border border-emerald-100 dark:border-emerald-800/40',
                            'text'     => 'text-emerald-600 dark:text-emerald-400',
                            'border'   => 'border-l-emerald-500 dark:border-l-emerald-400',
                            'badge_bg' => 'bg-emerald-100/80 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60',
                        ],
                    ];

                    $labels = isset($causeCards) ? array_column($causeCards, 'label') : [];
                    $values = isset($causeCards) ? array_column($causeCards, 'count') : [];
                    $categoryCount = count($labels);
                    $recentIncidents = $recentlyReported ?? collect();
                @endphp

                {{-- ─── Stat Cards Grid ─── --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 sm:gap-5 mb-10">
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
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $card['bg'] }} {{ $card['text'] }} shrink-0 shadow-2xs">
                                <span class="material-symbols-outlined text-2xl sm:text-3xl">{{ $card['icon'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ─── Charts Section ─── --}}
                <div class="mb-14">
                    <div class="rounded-2xl p-5 sm:p-6 bg-[var(--card-bg)] border border-[var(--border-light)] border-t-4 border-t-indigo-600 shadow-xs hover:shadow-md flex flex-col overflow-hidden transition-all duration-300">
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[var(--border-subtle)]">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-lg">pie_chart</span>
                                </div>
                                <div>
                                    <h2 class="text-sm sm:text-base font-bold text-[var(--text-dark)] m-0 leading-tight">
                                        Incidents per Specific Cause
                                    </h2>
                                    <p class="text-[11px] text-[var(--text-muted)] m-0">Proportional distribution by evaluated incident cause</p>
                                </div>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/60 shrink-0">
                                {{ $categoryCount }} Categories
                            </span>
                        </div>

                        <div class="flex flex-col lg:flex-row items-center justify-center gap-6 p-2 min-h-[340px]">
                            <div class="relative w-full lg:w-3/5 flex items-center justify-center h-[280px]">
                                <canvas id="causePieChart"></canvas>
                            </div>
                            <div class="w-full lg:w-2/5 max-h-[280px] overflow-y-auto pr-2">
                                <ul id="customLegend" class="m-0 p-0 list-none space-y-2"></ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ─── Recently Reported Notifications Table ─── --}}
                <div class="mt-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 shadow-2xs">
                                <span class="material-symbols-outlined text-xl">history</span>
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-[var(--text-dark)] m-0 leading-tight">
                                    Recently Reported Notifications
                                </h2>
                                <p class="text-xs text-[var(--text-muted)] m-0 mt-0.5">Most recent incident reports submitted to the system</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 border border-blue-200/60 dark:border-blue-800/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                <span>Latest {{ count($recentIncidents ?? []) }} Reports</span>
                            </span>
                        </div>
                    </div>

                    <div class="w-full overflow-hidden rounded-2xl border border-[var(--border-light)] shadow-xs bg-[var(--card-bg)]">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-[var(--text-dark)] border-collapse min-w-[900px]">
                                <thead class="bg-slate-50/90 dark:bg-slate-800/80 text-[var(--text-muted)] text-xs uppercase font-bold tracking-wider border-b border-[var(--border-light)]">
                                    <tr>
                                        <th class="py-3.5 px-4 font-bold">DBN Number</th>
                                        <th class="py-3.5 px-4 font-bold">Sender</th>
                                        <th class="py-3.5 px-4 font-bold">PIC</th>
                                        <th class="py-3.5 px-4 font-bold">Date of Occurrence</th>
                                        <th class="py-3.5 px-4 font-bold">Date of Discovery</th>
                                        <th class="py-3.5 px-4 font-bold">General Cause</th>
                                        <th class="py-3.5 px-4 text-center font-bold">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[var(--border-subtle)]">
                                    @forelse ($recentIncidents as $dbn)
                                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                                            <!-- DBN Number -->
                                            <td class="py-4 px-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/60">
                                                    {{ $dbn->dbn_number }}
                                                </span>
                                            </td>

                                            <!-- Sender -->
                                            <td class="py-4 px-4 whitespace-nowrap font-medium text-[var(--text-dark)]">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-700 dark:text-slate-200 shrink-0 uppercase shadow-2xs">
                                                        {{ substr($dbn->sender_fullname ?? 'N', 0, 1) }}
                                                    </div>
                                                    <span>{{ $dbn->sender_fullname }}</span>
                                                </div>
                                            </td>

                                            <!-- PIC -->
                                            <td class="py-4 px-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 text-[var(--text-dark)] border border-slate-200 dark:border-slate-700">
                                                    {{ $dbn->pic }}
                                                </span>
                                            </td>

                                            <!-- Date of Occurrence -->
                                            <td class="py-4 px-4 whitespace-nowrap text-xs text-[var(--text-muted)] font-mono">
                                                {{ !empty($dbn->date_occurrence) ? \Carbon\Carbon::parse($dbn->date_occurrence)->format('M d, Y h:i A') : 'N/A' }}
                                            </td>

                                            <!-- Date of Discovery -->
                                            <td class="py-4 px-4 whitespace-nowrap text-xs text-[var(--text-muted)] font-mono">
                                                {{ !empty($dbn->date_discovery) ? \Carbon\Carbon::parse($dbn->date_discovery)->format('M d, Y h:i A') : 'N/A' }}
                                            </td>

                                            <!-- General Cause -->
                                            <td class="py-4 px-4 max-w-xs">
                                                <p class="truncate text-xs sm:text-sm text-[var(--text-muted)] hover:text-[var(--text-dark)] transition-colors cursor-default m-0" title="{{ $dbn->general_cause ?? '' }}">
                                                    {{ $dbn->general_cause ?? 'N/A' }}
                                                </p>
                                            </td>

                                            <!-- Status Badge -->
                                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                                @php
                                                    $status = trim($dbn->status ?? '');
                                                @endphp
                                                @if($status === 'For Evaluation')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> For Evaluation
                                                    </span>
                                                @elseif($status === 'For Assessment' || $status === 'Pending')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> For Assessment
                                                    </span>
                                                @elseif($status === 'For Reporting to NPC')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Reporting to NPC
                                                    </span>
                                                @elseif($status === 'Reported')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Reported
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700">
                                                        {{ $status ?: 'Unknown' }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="py-12 px-4 text-center">
                                                <div class="flex flex-col items-center justify-center gap-2 text-[var(--text-muted)]">
                                                    <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600">task_alt</span>
                                                    <p class="text-base font-semibold m-0">No recently reported incidents to display</p>
                                                    <p class="text-xs m-0">Reported incidents will automatically appear here once logged.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if(method_exists($recentIncidents, 'links'))
                        <div class="mt-6 pt-4 border-t border-[var(--border-light)]">
                            {{ $recentIncidents->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
    @endcan

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        
        // === CHART LOGIC ===
        const ctxEl = document.getElementById('causePieChart');
        if(!ctxEl) return;

        const ctx = ctxEl.getContext('2d');
        const labels = @json($labels); 
        const values = @json($values); 
        const hasData = values.length && values.some(v => v > 0);

        function getComputedColor(cssVar) {
            return getComputedStyle(document.body).getPropertyValue(cssVar).trim() || '#64748b';
        }

        const chartData = {
            labels: labels,
            datasets: [{
                data: hasData ? values : new Array(values.length || 1).fill(1),
                backgroundColor: hasData ? [
                    '#4f46e5','#ef4444','#10b981','#eab308','#8b5cf6','#f97316','#06b6d4',
                    '#be185d','#64748b','#1d4ed8','#b91c1c','#15803d',
                    '#92400e','#6d28d9','#4338ca','#cbd5e1'
                ] : ['#f1f5f9'], 
                borderColor: 'transparent',
                borderWidth: 2,
                hoverOffset: 6
            }]
        };

        const noDataPlugin = {
            id: 'noDataPlugin',
            afterDraw: (chart) => {
                if (!hasData) {
                    const { ctx, chartArea: { width, height, top, left } } = chart;
                    ctx.save();
                    ctx.font = 'bold 16px Inter, sans-serif';
                    ctx.fillStyle = getComputedColor('--text-muted');
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText('No Data Available', left + width / 2, top + height / 2);
                    ctx.restore();
                }
            }
        };

        const htmlLegendPlugin = {
            id: 'htmlLegend',
            afterUpdate(chart, args, options) {
                const ul = document.getElementById(options.containerID);
                if (!ul) return;

                while (ul.firstChild) { ul.firstChild.remove(); }

                if (!hasData) return;

                const items = chart.options.plugins.legend.labels.generateLabels(chart);

                items.forEach(item => {
                    const li = document.createElement('li');
                    li.className = 'flex items-center gap-2.5 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800/60 transition-colors cursor-pointer select-none';
                    li.style.opacity = item.hidden ? '0.35' : '1';

                    li.onclick = () => {
                        chart.toggleDataVisibility(item.index);
                        chart.update();
                    };

                    const dot = document.createElement('span');
                    dot.className = 'w-3 h-3 rounded-full shrink-0 shadow-2xs';
                    dot.style.backgroundColor = item.fillStyle;

                    const text = document.createElement('span');
                    text.className = 'text-xs font-medium text-[var(--text-dark)] leading-tight flex-1';
                    if (item.hidden) {
                        text.style.textDecoration = 'line-through';
                    }
                    text.textContent = item.text;

                    li.appendChild(dot);
                    li.appendChild(text);
                    ul.appendChild(li);
                });
            }
        };

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false, 
            plugins: {
                legend: {
                    display: false
                },
                htmlLegend: {
                    containerID: 'customLegend'
                },
                tooltip: {
                    enabled: hasData,
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { family: "'Inter', sans-serif", size: 13 },
                    bodyFont: { family: "'Inter', sans-serif", size: 14, weight: 'bold' },
                    padding: 12,
                    cornerRadius: 10
                }
            }
        };

        const myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: chartData,
            options: chartOptions,
            plugins: [noDataPlugin, htmlLegendPlugin]
        });

        const observer = new MutationObserver(() => {
            myPieChart.update();
        });

        observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });
    });
    </script>
</x-app-layout>
<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>

    <div id="main-content" class="w-full">
        <div id="techContent" class="space-y-6">

            {{-- Main Content Card --}}
            <div class="w-full bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-4 sm:p-6 lg:p-8">

                {{-- Header Section --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 pb-5 border-b border-[var(--border-subtle)]">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-rose-600 via-rose-500 to-amber-500 text-white flex items-center justify-center shadow-md shadow-rose-500/20 shrink-0">
                            <span class="material-symbols-outlined text-2xl">shield_radar</span>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                                All Incident Reports
                            </h1>
                            <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                                Monitor, assess, evaluate, and track data breach incident reports across all regions
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Toolbar Action Buttons & Search --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-6">
                    <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                        @can('create_databreach')
                        <a href="{{ route('databreach.create') }}"
                            class="inline-flex items-center justify-center gap-2 h-11 px-4 sm:px-5 rounded-xl font-semibold text-sm text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 active:scale-[0.98] shadow-sm shadow-emerald-600/20 hover:shadow-md hover:shadow-emerald-600/30 transition-all duration-200 cursor-pointer">
                            <span class="material-symbols-outlined text-xl">add</span>
                            <span>Add Incident Report</span>
                        </a>
                        @endcan

                        <!-- Auto-Reload Toggle -->
                        <label class="inline-flex items-center gap-2.5 h-11 px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] text-xs sm:text-sm font-semibold cursor-pointer select-none hover:text-[var(--text-dark)] hover:border-slate-300 dark:hover:border-slate-700 transition-all">
                            <input type="checkbox" id="autoReloadCheckbox" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0 cursor-pointer accent-indigo-600">
                            <span class="flex items-center gap-1.5 whitespace-nowrap">
                                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Auto-Reload (<span id="countdown" class="font-mono text-indigo-600 dark:text-indigo-400 font-bold">60</span>s)</span>
                            </span>
                        </label>
                    </div>

                    {{-- Search --}}
                    <div class="flex items-center justify-end gap-2 w-full sm:w-auto sm:ml-auto">
                        <form action="{{ route('databreach.index') }}" method="GET" class="flex-1 min-w-0 sm:w-72 md:w-80 sm:flex-initial m-0">
                            @if(request()->filled('year'))
                                <input type="hidden" name="year" value="{{ request('year') }}">
                            @endif
                            @if(request()->filled('pic'))
                                <input type="hidden" name="pic" value="{{ request('pic') }}">
                            @endif
                            @if(request()->filled('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif

                            <div class="relative flex items-center w-full">
                                <span class="absolute left-3.5 text-slate-400 dark:text-slate-500 flex items-center pointer-events-none">
                                    <span class="material-symbols-outlined text-xl">search</span>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search incident reports..." autocomplete="off"
                                    class="w-full h-11 pl-10 pr-24 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                <button type="submit" aria-label="Search"
                                    class="absolute right-1.5 h-8 px-3 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 transition-all flex items-center justify-center cursor-pointer">
                                    Search
                                </button>
                            </div>
                        </form>
                        @if(request('search'))
                            <a href="{{ route('databreach.index') }}" 
                               class="inline-flex items-center justify-center gap-1 h-11 px-3 rounded-xl border border-[var(--border-light)] bg-[var(--card-bg)] text-xs font-semibold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all whitespace-nowrap shadow-xs"
                               title="Clear search">
                                <span class="material-symbols-outlined text-base">close</span>
                                <span class="hidden xs:inline">Clear</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Filters Section --}}
                @can('filter_databreach')
                <form action="{{ route('databreach.index') }}" method="GET"
                    class="bg-slate-50 dark:bg-slate-800/40 p-3.5 sm:p-4 rounded-2xl border border-[var(--border-light)] mb-6 transition-colors duration-300">
                    
                    @if(request()->filled('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <div class="flex flex-wrap items-end gap-2.5 sm:gap-3 w-full">
                        <div class="flex-1 min-w-[130px] flex flex-col">
                            <label for="year" class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1 flex items-center gap-1 whitespace-nowrap">
                                Year
                            </label>
                            <select name="year" id="year"
                                class="w-full h-9 px-2.5 rounded-xl text-xs sm:text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                <option value="" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">All Years</option>
                                @foreach($formYears as $y)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if (!auth()->user()->hasRole('DBRT'))
                        <div class="flex-1 min-w-[130px] flex flex-col">
                            <label for="picFilter" class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1 flex items-center gap-1 whitespace-nowrap">
                                Region
                            </label>
                            <select name="pic" id="picFilter"
                                class="w-full h-9 px-2.5 rounded-xl text-xs sm:text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                <option value="" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">All Regions</option>
                                @foreach($pic as $region)
                                    <option value="{{ $region }}" {{ request('pic') == $region ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                        {{ $region }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex-1 min-w-[130px] flex flex-col">
                            <label for="statusFilter" class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1 flex items-center gap-1 whitespace-nowrap">
                                Status
                            </label>
                            <select name="status" id="statusFilter"
                                class="w-full h-9 px-2.5 rounded-xl text-xs sm:text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                <option value="" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">All Status</option>
                                <option value="For Assessment" {{ request('status') == 'For Assessment' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">For Assessment</option>
                                <option value="For Evaluation" {{ request('status') == 'For Evaluation' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">For Evaluation</option>
                                <option value="For Reporting to NPC" {{ request('status') == 'For Reporting to NPC' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">For Reporting to NPC</option>
                                <option value="Reported" {{ request('status') == 'Reported' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">Reported</option>
                            </select>
                        </div>
                        @endif

                        {{-- Action Buttons — inline with filters, bottom-aligned --}}
                        <div class="flex items-end gap-2 self-end shrink-0">
                            <a href="{{ route('databreach.index') }}"
                                class="inline-flex items-center justify-center gap-1.5 h-9 px-3.5 rounded-xl text-xs font-semibold text-[var(--text-muted)] dark:text-slate-300 hover:text-[var(--text-dark)] dark:hover:text-white border border-[var(--border-light)] bg-[var(--card-bg)] hover:bg-slate-100 dark:hover:bg-slate-800 active:scale-95 transition-all whitespace-nowrap shadow-xs">
                                <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                                <span>Reset</span>
                            </a>
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-1.5 h-9 px-4 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 shadow-sm shadow-indigo-600/20 hover:shadow-md hover:shadow-indigo-600/30 transition-all cursor-pointer whitespace-nowrap">
                                <span class="material-symbols-outlined text-[16px]">filter_alt</span>
                                <span>Apply Filter</span>
                            </button>
                        </div>
                    </div>
                </form>
                @endcan

                {{-- Data Table --}}
                <div class="w-full overflow-hidden rounded-2xl border border-[var(--border-light)] shadow-xs bg-[var(--card-bg)]">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-[var(--text-dark)] border-collapse">
                            <thead class="bg-slate-50/90 dark:bg-slate-800/80 text-[var(--text-muted)] text-xs uppercase font-bold tracking-wider border-b border-[var(--border-light)]">
                                <tr>
                                    <th class="py-3.5 px-4 text-center font-bold">DBN Number</th>
                                    <th class="py-3.5 px-4 font-bold">Sender</th>
                                    <th class="py-3.5 px-4 font-bold">PIC</th>
                                    <th class="py-3.5 px-4 font-bold">Date of Occurrence</th>
                                    <th class="py-3.5 px-4 font-bold">Date of Notification</th>
                                    <th class="py-3.5 px-4 font-bold min-w-[180px]">General Cause</th>
                                    <th class="py-3.5 px-4 text-center font-bold">Status</th>
                                    <th class="py-3.5 px-4 font-bold min-w-[200px]">Time Period</th>
                                    <th class="py-3.5 px-4 text-center font-bold min-w-[120px]">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--border-subtle)]">
                                @forelse($notifications as $notification)
                                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                                        <!-- DBN Number -->
                                        <td class="py-4 px-4 text-center whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/60">
                                                {{ $notification->dbn_number ?? 'N/A' }}
                                            </span>
                                        </td>

                                        <!-- Sender -->
                                        <td class="py-4 px-4 whitespace-nowrap font-medium text-[var(--text-dark)]">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-700 dark:text-slate-200 shrink-0 uppercase">
                                                    {{ substr($notification->sender_fullname ?? 'N', 0, 1) }}
                                                </div>
                                                <span>{{ $notification->sender_fullname ?? 'N/A' }}</span>
                                            </div>
                                        </td>

                                        <!-- PIC -->
                                        <td class="py-4 px-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 dark:bg-slate-800 text-[var(--text-dark)] border border-slate-200 dark:border-slate-700">
                                                {{ $notification->pic ?? 'N/A' }}
                                            </span>
                                        </td>

                                        <!-- Date of Occurrence -->
                                        <td class="py-4 px-4 whitespace-nowrap text-xs text-[var(--text-muted)] font-mono">
                                            {{ $notification->date_occurrence ? \Carbon\Carbon::parse($notification->date_occurrence)->format('M d, Y h:i A') : 'N/A' }}
                                        </td>

                                        <!-- Date of Notification -->
                                        <td class="py-4 px-4 whitespace-nowrap text-xs text-[var(--text-muted)] font-mono">
                                            {{ $notification->date_notification ? \Carbon\Carbon::parse($notification->date_notification)->format('M d, Y h:i A') : 'N/A' }}
                                        </td>

                                        <!-- General Cause -->
                                        <td class="py-4 px-4 max-w-xs">
                                            <p class="truncate text-xs sm:text-sm text-[var(--text-muted)] hover:text-[var(--text-dark)] transition-colors cursor-default m-0" title="{{ $notification->general_cause ?? '' }}">
                                                {{ $notification->general_cause ?? 'N/A' }}
                                            </p>
                                        </td>

                                        <!-- Status Badge -->
                                        <td class="py-4 px-4 text-center whitespace-nowrap">
                                            @php
                                                $st = trim($notification->status ?? '');
                                            @endphp
                                            @if($st === 'For Evaluation')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> For Evaluation
                                                </span>
                                            @elseif($st === 'For Assessment' || $st === 'Pending')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> For Assessment
                                                </span>
                                            @elseif($st === 'For Reporting to NPC')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Reporting to NPC
                                                </span>
                                            @elseif($st === 'Reported')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Reported
                                                </span>
                                            @elseif($st === 'Draft')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Draft
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700">
                                                    {{ $st ?: 'N/A' }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Time Period -->
                                        <td class="py-4 px-4 text-xs font-medium text-[var(--text-dark)]">
                                            {{-- PHASE 1: Active 24-Hour Timer (Pending / For Assessment) --}}
                                            @if(in_array($notification->status, ['For Assessment', 'Pending']))
                                                <div class="text-[11px] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-1">Assessment (24h)</div>
                                                @php
                                                    $deadline = \Carbon\Carbon::parse($notification->created_at)->addHours(24);
                                                @endphp
                                                <span class="incident-countdown font-mono font-bold text-sm text-[var(--text-muted)]" data-deadline="{{ $deadline->toIso8601String() }}">
                                                    Loading...
                                                </span>

                                            {{-- PHASE 2: Frozen 24h Math + Active 48h Timer (For Eval) --}}
                                            @elseif($notification->status === 'For Evaluation')
                                                @php
                                                    $rem24 = $notification->time_countdown ?? 0;
                                                    $elap24 = max(0, (24 * 3600) - $rem24);
                                                    
                                                    $r24H = str_pad(floor($rem24 / 3600), 2, '0', STR_PAD_LEFT);
                                                    $r24M = str_pad(floor(($rem24 % 3600) / 60), 2, '0', STR_PAD_LEFT);
                                                    $r24S = str_pad($rem24 % 60, 2, '0', STR_PAD_LEFT);
                                                    
                                                    $e24H = str_pad(floor($elap24 / 3600), 2, '0', STR_PAD_LEFT);
                                                    $e24M = str_pad(floor(($elap24 % 3600) / 60), 2, '0', STR_PAD_LEFT);
                                                    $e24S = str_pad($elap24 % 60, 2, '0', STR_PAD_LEFT);
                                                @endphp
                                                
                                                <div class="mb-2.5 pb-2 border-b border-dashed border-[var(--border-subtle)]">
                                                    <div class="text-[11px] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-0.5">Assessment Elapsed</div>
                                                    @if($rem24 == 0)
                                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold text-rose-600 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800">Time Expired</span>
                                                    @else
                                                        <div class="font-mono text-xs text-emerald-600 dark:text-emerald-400">
                                                            <span>24h 00m 00s</span><br>
                                                            <span>- {{ $r24H }}h {{ $r24M }}m {{ $r24S }}s</span><br>
                                                            <span class="font-bold text-emerald-700 dark:text-emerald-300">= {{ $e24H }}h {{ $e24M }}m {{ $e24S }}s</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="text-[11px] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-0.5">Evaluation (48h)</div>
                                                @php
                                                    $deadline = \Carbon\Carbon::parse($notification->updated_at)->addHours(48);
                                                @endphp
                                                <span class="incident-countdown font-mono font-bold text-sm text-[var(--text-muted)] flex items-center gap-1" data-deadline="{{ $deadline->toIso8601String() }}">
                                                    Loading...
                                                </span>

                                            {{-- PHASE 3: Assessment Elapsed & Evaluation Discrepancy Math --}}
                                            @elseif($notification->status === 'For Reporting to NPC')
                                                @php
                                                    $rem24 = $notification->time_countdown ?? 0;
                                                    $elap24 = max(0, (24 * 3600) - $rem24);
                                                    $e24H = str_pad(floor($elap24 / 3600), 2, '0', STR_PAD_LEFT);
                                                    $e24M = str_pad(floor(($elap24 % 3600) / 60), 2, '0', STR_PAD_LEFT);
                                                    $e24S = str_pad($elap24 % 60, 2, '0', STR_PAD_LEFT);

                                                    $remEval = $notification->evaluation_time_countdown ?? 0;
                                                    $elapEval = max(0, (48 * 3600) - $remEval);
                                                    $eEvalH = str_pad(floor($elapEval / 3600), 2, '0', STR_PAD_LEFT);
                                                    $eEvalM = str_pad(floor(($elapEval % 3600) / 60), 2, '0', STR_PAD_LEFT);
                                                    $eEvalS = str_pad($elapEval % 60, 2, '0', STR_PAD_LEFT);

                                                    $totElap = $elap24 + $elapEval;
                                                    $totElapH = str_pad(floor($totElap / 3600), 2, '0', STR_PAD_LEFT);
                                                    $totElapM = str_pad(floor(($totElap % 3600) / 60), 2, '0', STR_PAD_LEFT);
                                                    $totElapS = str_pad($totElap % 60, 2, '0', STR_PAD_LEFT);

                                                    $totalLimit = 48 * 3600;
                                                    $totRem = max(0, $totalLimit - $totElap);
                                                    $totRemH = str_pad(floor($totRem / 3600), 2, '0', STR_PAD_LEFT);
                                                    $totRemM = str_pad(floor(($totRem % 3600) / 60), 2, '0', STR_PAD_LEFT);
                                                    $totRemS = str_pad($totRem % 60, 2, '0', STR_PAD_LEFT);
                                                @endphp

                                                <div class="mb-2 pb-1.5 border-b border-dashed border-[var(--border-subtle)]">
                                                    <div class="text-[11px] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-0.5">Assessment Elapsed</div>
                                                    <span class="font-mono font-bold text-xs text-[var(--text-dark)]">{{ $e24H }}h {{ $e24M }}m {{ $e24S }}s</span>
                                                </div>

                                                <div class="mb-2">
                                                    <div class="text-[11px] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-0.5">Evaluation Elapsed</div>
                                                    <span class="font-mono font-bold text-xs text-[var(--text-dark)] block mb-1">{{ $eEvalH }}h {{ $eEvalM }}m {{ $eEvalS }}s</span>
                                                    
                                                    @if($totElap >= $totalLimit)
                                                        <span class="font-mono text-xs font-bold text-rose-600 dark:text-rose-400 block">
                                                            48h Limit Exceeded
                                                        </span>
                                                    @else
                                                        <div class="font-mono text-xs text-emerald-600 dark:text-emerald-400">
                                                            <span>48h 00m 00s</span><br>
                                                            <span>- {{ $totElapH }}h {{ $totElapM }}m {{ $totElapS }}s</span><br>
                                                            <span class="font-bold text-emerald-700 dark:text-emerald-300">= {{ $totRemH }}h {{ $totRemM }}m {{ $totRemS }}s</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wide bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800">
                                                    Action Taken
                                                </span>

                                            {{-- PHASE 4: Completely Reported (Show Date and Action Taken) --}}
                                            @elseif($notification->status === 'Reported')
                                                <div class="text-[11px] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-0.5">Date Reported</div>
                                                <div class="font-mono text-xs font-bold text-[var(--text-dark)] mb-1.5">
                                                    {{ \Carbon\Carbon::parse($notification->updated_at)->format('M d, Y h:i A') }}
                                                </div>
                                                
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wide bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800">
                                                    Action Taken
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Actions -->
                                        <td class="py-3 px-3 text-center whitespace-nowrap">
                                            <div class="flex flex-row items-center justify-center gap-1">
                                                @can('view_databreach')
                                                    <a href="{{ route('databreach.show', $notification->dbn_id) }}"
                                                        title="View Details"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/50 transition-all">
                                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                                    </a>
                                                @endcan

                                                @can('assess_databreach')
                                                    @if (!in_array($notification->status, ['Reported', 'For Evaluation', 'For Reporting to NPC']) && (auth()->user()->email === $notification->email || auth()->user()->hasRole('Super Admin')))
                                                        @php
                                                            $hours = $notification->time_countdown ?? 24;
                                                            $deadline = \Carbon\Carbon::parse($notification->created_at)->addHours($hours);
                                                        @endphp
                                                        <a href="{{ route('databreach.assess', $notification->dbn_id) }}" 
                                                            title="Assess"
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/50 transition-all assess-btn"
                                                            data-deadline="{{ $deadline->toIso8601String() }}">
                                                            <span class="material-symbols-outlined text-sm">content_paste_search</span>
                                                        </a>
                                                    @endif
                                                @endcan

                                                @can('evaluate_databreach')
                                                    @if (!in_array($notification->status, ['Draft', 'Reported', 'For Assessment' , 'For Reporting to NPC']))
                                                        <a href="{{ route('databreach.evaluate', $notification->dbn_id) }}"
                                                            title="Evaluate"
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/50 transition-all">
                                                            <span class="material-symbols-outlined text-sm">content_paste_go</span>
                                                        </a>
                                                    @endif
                                                @endcan

                                                @can('report_databreach')
                                                    @if ($notification->status === 'For Reporting to NPC')
                                                    <div x-data="{ open: false }" class="inline-block">
                                                        <button @click="open = true" type="button"
                                                            title="Report to NPC"
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition-all cursor-pointer">
                                                            <span class="material-symbols-outlined text-sm">send</span>
                                                        </button>
                                                        
                                                        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs z-50 flex items-center justify-center p-4 text-left" x-cloak>
                                                            <div class="relative bg-[var(--card-bg)] rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto p-6 sm:p-8 transition-colors duration-300 border border-[var(--border-light)]" @click.away="open = false" x-transition.scale.origin.bottom>
                                                                <div class="border-b border-[var(--border-light)] pb-4 mb-4 flex items-center gap-3">
                                                                    <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                                                                    <span class="material-symbols-outlined text-2xl">warning</span>
                                                                </div>
                                                                    <h2 class="text-xl sm:text-2xl font-extrabold text-[var(--text-dark)] m-0">Confirm Report</h2>
                                                                </div>
                                                                <p class="mb-6 text-[var(--text-muted)] text-sm leading-relaxed whitespace-pre-wrap break-words [overflow-wrap:anywhere]">Are you sure you want to report this incident to the National Privacy Commission (NPC)? This action cannot be undone.</p>

                                                                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2.5 w-full">
                                                                    <button @click="open = false" type="button" class="w-full sm:w-auto inline-flex items-center justify-center h-10 px-5 rounded-xl text-xs sm:text-sm font-semibold transition-all border border-[var(--border-light)] bg-[var(--card-bg)] text-[var(--text-muted)] hover:text-[var(--text-dark)] hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                                                                        Cancel
                                                                    </button>

                                                                    <form method="POST" action="{{ route('databreach.report_to_npc', $notification->dbn_id) }}" class="m-0 w-full sm:w-auto">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 h-10 px-5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 shadow-sm shadow-emerald-600/20 active:scale-95 transition-all cursor-pointer">
                                                                            <span>Confirm Report</span>
                                                                        </button>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endcan

                                                @can('delete_databreach')
                                                    @if ($notification->status !== 'Reported')
                                                        <form action="{{ route('databreach.destroy', $notification->dbn_id) }}" method="POST" class="delete-form m-0 inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                title="Delete"
                                                                class="delete-btn inline-flex items-center justify-center w-8 h-8 rounded-lg text-rose-700 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition-all cursor-pointer">
                                                                <span class="material-symbols-outlined text-sm">delete</span>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="py-12 px-4 text-center">
                                            <div class="flex flex-col items-center justify-center gap-2 text-[var(--text-muted)]">
                                                <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600">inbox</span>
                                                <p class="text-base font-semibold m-0">No incident reports found</p>
                                                <p class="text-xs m-0">Try adjusting your filters or search criteria.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination Wrapper --}}
                <div class="mt-6 pt-4 border-t border-[var(--border-light)]">
                    {{ $notifications->links() }}
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{!! addslashes(session("success")) !!}',
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

            const isChecked = localStorage.getItem('autoReload') === 'true';
            if (checkbox) checkbox.checked = isChecked;

            if (isChecked) startAutoReload();

            if (checkbox) {
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

            // === COUNTDOWN TIMERS (Handles both 24h and 48h) ===
            const timers = document.querySelectorAll('.incident-countdown');

            function updateCountdowns() {
                const now = new Date().getTime();

                timers.forEach(timer => {
                    const deadlineStr = timer.getAttribute('data-deadline');
                    if (!deadlineStr) return;

                    const deadline = new Date(deadlineStr).getTime();
                    const distance = deadline - now;

                    // If the time has completely run out
                    if (distance < 0) {
                        timer.innerHTML = "Time Expired";
                        timer.classList.add("status-time-expired");
                        timer.style.color = "#ef4444";
                        return;
                    }

                    // Removed the modulo 24 limit so hours can count above 24 (up to 48)
                    const hours = Math.floor(distance / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    const h = String(hours).padStart(2, '0');
                    const m = String(minutes).padStart(2, '0');
                    const s = String(seconds).padStart(2, '0');

                    timer.innerHTML = `${h}h ${m}m ${s}s`;
                    
                    // Visual warnings based on time left
                    if (hours < 2) {
                        timer.style.color = "#ef4444"; // Red for < 2 hours
                    } else if (hours < 12) {
                        timer.style.color = "#f59e0b"; // Orange for < 12 hours
                    } else {
                        timer.style.color = "#10b981"; // Green otherwise
                    }
                });
            }

            if (timers.length > 0) {
                updateCountdowns();
                setInterval(updateCountdowns, 1000);
            }

            // === DELETE CONFIRMATION ALERT ===
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = this.closest('.delete-form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action will permanently delete the record.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel',
                        background: getComputedStyle(document.body).getPropertyValue('--card-bg').trim(),
                        color: getComputedStyle(document.body).getPropertyValue('--text-dark').trim()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Full-Screen Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="w-12 h-12 border-4 border-white/20 border-t-white rounded-full animate-spin mb-4"></div>
        <p class="text-base font-semibold tracking-wide text-white">Processing Ticket, please wait...</p>
    </div>

    <div id="main-content" class="w-full">
        <div id="ticketsContent" class="space-y-6">

            {{-- Main Content Card --}}
            <div class="w-full bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-4 sm:p-6 lg:p-8">

                {{-- Header Section --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 pb-5 border-b border-[var(--border-subtle)]">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-sky-500 text-white flex items-center justify-center shadow-md shadow-indigo-500/20 shrink-0">
                            <span class="material-symbols-outlined text-2xl">confirmation_number</span>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                                All Tickets
                            </h1>
                            <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                                Track, filter, assign, and manage technical support requests across all divisions
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Toolbar Action Buttons & Search --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-6">
                    <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                        @can('create_ticket')
                        <button id="openAddTicketModalBtn" type="button"
                            class="inline-flex items-center justify-center gap-2 h-11 px-4 sm:px-5 rounded-xl font-semibold text-sm text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 active:scale-[0.98] shadow-sm shadow-emerald-600/20 hover:shadow-md hover:shadow-emerald-600/30 transition-all duration-200 cursor-pointer">
                            <span class="material-symbols-outlined text-xl">add</span>
                            <span>Add Ticket</span>
                        </button>
                        @endcan

                        <form action="{{ route('tickets.index') }}" method="GET" class="m-0">
                            <input type="hidden" name="filter" value="allTickets">
                            <button id="allTickets" type="submit"
                                class="inline-flex items-center justify-center gap-2 h-11 px-4 sm:px-5 rounded-xl font-semibold text-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-[0.98] shadow-sm shadow-blue-600/20 hover:shadow-md hover:shadow-blue-600/30 transition-all duration-200 cursor-pointer">
                                <span class="material-symbols-outlined text-xl">list</span>
                                <span>All Tickets</span>
                                <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-bold bg-white/20 text-white backdrop-blur-xs">
                                    {{ $ticketsCount ?? 0 }}
                                </span>
                            </button>
                        </form>

                        <form action="{{ route('tickets.index') }}" method="GET" class="m-0">
                            <input type="hidden" name="filter" value="overdue">
                            <button id="overdue" type="submit"
                                class="inline-flex items-center justify-center gap-2 h-11 px-4 sm:px-5 rounded-xl font-semibold text-sm text-white bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 active:scale-[0.98] shadow-sm shadow-rose-600/20 hover:shadow-md hover:shadow-rose-600/30 transition-all duration-200 cursor-pointer">
                                <span class="material-symbols-outlined text-xl">schedule</span>
                                <span>Overdue</span>
                                <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-bold bg-white/20 text-white backdrop-blur-xs">
                                    {{ $overdueCount ?? 0 }}
                                </span>
                            </button>
                        </form>

                        <!-- Auto-Reload Toggle -->
                        <label class="inline-flex items-center gap-2.5 h-11 px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] text-xs sm:text-sm font-semibold cursor-pointer select-none hover:text-[var(--text-dark)] hover:border-slate-300 dark:hover:border-slate-700 transition-all">
                            <input type="checkbox" id="autoReloadCheckbox" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0 cursor-pointer accent-indigo-600">
                            <span class="flex items-center gap-1.5 whitespace-nowrap">
                                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Auto-Reload (<span id="countdown" class="font-mono text-indigo-600 dark:text-indigo-400 font-bold">60</span>s)</span>
                            </span>
                        </label>
                    </div>

                    @can('search_ticket')
                    <div class="flex items-center justify-end gap-2 w-full sm:w-auto sm:ml-auto">
                        <form action="{{ route('tickets.index') }}" method="GET" class="flex-1 min-w-0 sm:w-72 md:w-80 sm:flex-initial m-0">
                            <div class="relative flex items-center w-full">
                                <span class="absolute left-3.5 text-slate-400 dark:text-slate-500 flex items-center pointer-events-none">
                                    <span class="material-symbols-outlined text-xl">search</span>
                                </span>
                                <input type="text" name="search_query" value="{{ request('search_query') }}" placeholder="Search tickets..." autocomplete="off"
                                    class="w-full h-11 pl-10 pr-24 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                <button type="submit" aria-label="Search"
                                    class="absolute right-1.5 h-8 px-3 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 transition-all flex items-center justify-center cursor-pointer">
                                    Search
                                </button>
                            </div>
                        </form>
                        @if(request('search_query'))
                            <a href="{{ route('tickets.index') }}" 
                               class="inline-flex items-center justify-center gap-1 h-11 px-3 rounded-xl border border-[var(--border-light)] bg-[var(--card-bg)] text-xs font-semibold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all whitespace-nowrap shadow-xs"
                               title="Clear search">
                                <span class="material-symbols-outlined text-base">close</span>
                                <span class="hidden xs:inline">Clear</span>
                            </a>
                        @endif
                    </div>
                    @endcan
                </div>
                
                {{-- Filters Section --}}
                <form action="{{ route('tickets.index') }}" method="GET"
                    class="bg-slate-50 dark:bg-slate-800/40 p-3.5 sm:p-4 rounded-2xl border border-[var(--border-light)] mb-6 transition-colors duration-300">
                    <div class="flex flex-wrap items-end gap-2.5 sm:gap-3 w-full">
                        @can('filter_ticket_by_region')
                        <div class="flex-1 min-w-[130px] flex flex-col">
                            <label for="it_area" class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1 flex items-center gap-1 whitespace-nowrap">
                                Region
                            </label>
                            <select name="it_area" id="it_area"
                                class="w-full h-9 px-2.5 rounded-xl text-xs sm:text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                <option value="" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">All Regions</option>
                                @if(!empty($it_area))
                                    @foreach($it_area as $area)
                                        <option value="{{ trim($area) }}" {{ request('it_area') == trim($area) ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                            {{ trim($area) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @endcan

                        @can('filter_ticket_by_status')
                        <div class="flex-1 min-w-[125px] flex flex-col">
                            <label for="status" class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1 flex items-center gap-1 whitespace-nowrap">
                                Status
                            </label>
                            <select name="status" id="status"
                                class="w-full h-9 px-2.5 rounded-xl text-xs sm:text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                <option value="" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">All Statuses</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">Pending</option>
                                <option value="Pending/Re-Assigned" {{ request('status') == 'Pending/Re-Assigned' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">Pending/Re-Assigned</option>
                                <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">Resolved</option>
                            </select>
                        </div>
                        @endcan

                        @can('filter_ticket_by_priority')
                        <div class="flex-1 min-w-[120px] flex flex-col">
                            <label for="priority" class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1 flex items-center gap-1 whitespace-nowrap">
                                Priority
                            </label>
                            <select name="priority" id="priority"
                                class="w-full h-9 px-2.5 rounded-xl text-xs sm:text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                <option value="" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">All Priorities</option>
                                <option value="Critical" {{ request('priority') == 'Critical' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">Critical</option>
                                <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">High</option>
                                <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">Medium</option>
                                <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">Low</option>
                            </select>
                        </div>
                        @endcan

                        <div class="flex-1 min-w-[150px] flex flex-col">
                            <label for="start_date" class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1 flex items-center gap-1 whitespace-nowrap">
                                Start Date
                            </label>
                            <input type="datetime-local" id="start_date" name="start_date" value="{{ request('start_date') }}"
                                class="w-full h-9 px-2.5 rounded-xl text-xs sm:text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                        </div>

                        <div class="flex-1 min-w-[150px] flex flex-col">
                            <label for="end_date" class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1 flex items-center gap-1 whitespace-nowrap">
                                End Date
                            </label>
                            <input type="datetime-local" id="end_date" name="end_date" value="{{ request('end_date') }}"
                                class="w-full h-9 px-2.5 rounded-xl text-xs sm:text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                        </div>
                    </div>

                    {{-- Filter Action Buttons (Separate Row Below) --}}
                    <div class="flex flex-wrap items-center justify-end gap-2.5 pt-3 mt-3 border-t border-[var(--border-light)]">
                        <a href="{{ route('tickets.index') }}"
                            class="inline-flex items-center justify-center gap-1.5 h-9 px-3.5 rounded-xl text-xs font-semibold text-[var(--text-muted)] dark:text-slate-300 hover:text-[var(--text-dark)] dark:hover:text-white border border-[var(--border-light)] bg-[var(--card-bg)] hover:bg-slate-100 dark:hover:bg-slate-800 active:scale-95 transition-all whitespace-nowrap shadow-xs">
                            <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                            <span>Reset</span>
                        </a>

                        <button type="submit" name="action" value="search"
                            class="inline-flex items-center justify-center gap-1.5 h-9 px-4 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 shadow-sm shadow-indigo-600/20 hover:shadow-md hover:shadow-indigo-600/30 transition-all cursor-pointer whitespace-nowrap">
                            <span class="material-symbols-outlined text-[16px]">filter_alt</span>
                            <span>Apply Filter</span>
                        </button>

                        @can('generate_report')
                        <button type="submit" name="action" value="generate" title="Excel File Download"
                            class="inline-flex items-center justify-center gap-1.5 h-9 px-4 rounded-xl text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:scale-95 shadow-sm shadow-emerald-600/20 hover:shadow-md hover:shadow-emerald-600/30 transition-all cursor-pointer whitespace-nowrap">
                            <span class="material-symbols-outlined text-[16px]">download</span>
                            <span>Generate Report</span>
                        </button>
                        @endcan
                    </div>
                </form>

                {{-- Data Table --}}
                <div class="w-full overflow-hidden rounded-2xl border border-[var(--border-light)] shadow-xs bg-[var(--card-bg)]">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-[var(--text-dark)] border-collapse">
                            <thead class="bg-slate-50/90 dark:bg-slate-800/80 text-[var(--text-muted)] text-xs uppercase font-bold tracking-wider border-b border-[var(--border-light)]">
                                <tr>
                                    <th class="py-3.5 px-4 text-center font-bold">Tracking ID</th>
                                    <th class="py-3.5 px-4 font-bold">Requested By</th>
                                    <th class="py-3.5 px-4 font-bold">Division</th>
                                    <th class="py-3.5 px-4 font-bold">Technical Service</th>
                                    <th class="py-3.5 px-4 font-bold min-w-[200px]">Request Details</th>
                                    <th class="py-3.5 px-4 font-bold">Assigned Personnel</th>
                                    <th class="py-3.5 px-4 font-bold">Date Created</th>
                                    <th class="py-3.5 px-4 text-center font-bold">Priority</th>
                                    <th class="py-3.5 px-4 text-center font-bold">Status</th>
                                    <th class="py-3.5 px-4 text-center font-bold min-w-[100px]">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--border-subtle)]">
                                @forelse ($tickets ?? [] as $ticket)
                                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                                        <!-- Tracking ID -->
                                        <td class="py-4 px-4 text-center whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/60">
                                                {{ $ticket->ticket_number }}
                                            </span>
                                        </td>

                                        <!-- Requested By -->
                                        <td class="py-4 px-4 whitespace-nowrap font-medium text-[var(--text-dark)]">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-700 dark:text-slate-200 shrink-0 uppercase">
                                                    {{ substr($ticket->firstname, 0, 1) }}{{ substr($ticket->lastname, 0, 1) }}
                                                </div>
                                                <span>{{ $ticket->firstname }} {{ $ticket->middle_initial }} {{ $ticket->lastname }}</span>
                                            </div>
                                        </td>

                                        <!-- Division -->
                                        <td class="py-4 px-4 whitespace-nowrap text-xs text-[var(--text-muted)]">
                                            {{ $ticket->division }}
                                        </td>

                                        <!-- Technical Service -->
                                        <td class="py-4 px-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 dark:bg-slate-800 text-[var(--text-dark)] border border-slate-200 dark:border-slate-700">
                                                {{ $ticket->service }}
                                            </span>
                                        </td>

                                        <!-- Request Details -->
                                        <td class="py-4 px-4 max-w-xs">
                                            <p class="truncate text-xs sm:text-sm text-[var(--text-muted)] hover:text-[var(--text-dark)] transition-colors cursor-default m-0" title="{{ $ticket->request }}">
                                                {{ $ticket->request }}
                                            </p>
                                        </td>

                                        <!-- Assigned Personnel -->
                                        <td class="py-4 px-4 whitespace-nowrap text-xs font-medium text-[var(--text-dark)]">
                                            <div class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-base text-slate-400">person</span>
                                                <span>{{ $ticket->it_personnel }}</span>
                                            </div>
                                        </td>

                                        <!-- Date Created -->
                                        <td class="py-4 px-4 whitespace-nowrap text-xs text-[var(--text-muted)] font-mono">
                                            {{ \Carbon\Carbon::parse($ticket->date_created)->format('M d, Y h:i A') }}
                                        </td>

                                        <!-- Priority Badge -->
                                        <td class="py-4 px-4 text-center whitespace-nowrap">
                                            @php
                                                $priority = trim($ticket->priority);
                                            @endphp
                                            @if($priority === 'Critical')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold uppercase tracking-wide bg-red-600 text-white shadow-xs border border-red-700 animate-[pulseCritical_2s_infinite]">
                                                    Critical
                                                </span>
                                            @elseif($priority === 'High')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800">
                                                    High
                                                </span>
                                            @elseif($priority === 'Medium')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800">
                                                    Medium
                                                </span>
                                            @elseif($priority === 'Low')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800">
                                                    Low
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700">
                                                    {{ $priority ?: 'Default' }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Status Badge -->
                                        <td class="py-4 px-4 text-center whitespace-nowrap">
                                            @php
                                                $status = trim($ticket->status);
                                            @endphp
                                            @if($status === 'Resolved')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Resolved
                                                </span>
                                            @elseif($status === 'Pending/Re-Assigned')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-sky-50 text-sky-700 border border-sky-200 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> Re-Assigned
                                                </span>
                                            @elseif($status === 'Pending')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700">
                                                    {{ $status }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Actions -->
                                        <td class="py-3 px-3 text-center whitespace-nowrap">
                                            <div class="flex flex-row items-center justify-center gap-1">
                                                @can('view_ticket_details')
                                                    <a href="{{ route('tickets.view', $ticket->ticket_id) }}"
                                                        title="View Details"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-emerald-700 dark:text-emerald-400 transition-all">
                                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                                    </a>
                                                @endcan

                                                @can('reassign_ticket')
                                                    <button type="button"
                                                        title="Re-Assign"
                                                        class="open-assign-modal inline-flex items-center justify-center w-8 h-8 text-amber-700 dark:text-amber-400 transition-all cursor-pointer"
                                                        data-id="{{ $ticket->ticket_id }}" 
                                                        data-status="{{ $ticket->status }}"
                                                        data-assigned-email="{{ $ticket->it_email }}"
                                                        data-assigned-personnel="{{ $ticket->it_personnel }}">
                                                        <span class="material-symbols-outlined text-sm">person_add</span>
                                                    </button>
                                                @endcan

                                                @can('update_status_ticket')
                                                    <button type="button"
                                                        title="Update Status"
                                                        class="open-edit-modal inline-flex items-center justify-center w-8 h-8 text-blue-700 dark:text-blue-400 transition-all cursor-pointer"
                                                        data-id="{{ $ticket->ticket_id }}"
                                                        data-status="{{ $ticket->status }}"
                                                        data-priority="{{ $ticket->priority }}"
                                                        data-action_taken="{{ $ticket->action_taken }}"
                                                        data-photo="{{ $ticket->photo }}">
                                                        <span class="material-symbols-outlined text-sm">edit</span>
                                                    </button>
                                                @endcan

                                                @can('generate_tsar')
                                                    @if($ticket->status === 'Resolved')
                                                        <a href="{{ route('tickets.generateTSAR', $ticket->ticket_id) }}"
                                                            title="Generate TSAR"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-indigo-700 dark:text-indigo-400 transition-all">
                                                            <span class="material-symbols-outlined text-sm">description</span>
                                                        </a>
                                                    @endif
                                                @endcan

                                                @can('delete_ticket')
                                                    <form id="delete-form-{{ $ticket->ticket_id }}" action="{{ route('tickets.destroy', $ticket->ticket_id) }}" method="POST" class="m-0 contents">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            title="Delete"
                                                            class="delete-btn inline-flex items-center justify-center w-8 h-8 text-rose-700 dark:text-rose-400 transition-all cursor-pointer"
                                                            data-id="{{ $ticket->ticket_id }}">
                                                            <span class="material-symbols-outlined text-sm">delete</span>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="py-12 px-4 text-center">
                                            <div class="flex flex-col items-center justify-center gap-2 text-[var(--text-muted)]">
                                                <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600">inbox</span>
                                                <p class="text-base font-semibold m-0">No tickets found</p>
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
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- MODAL 1: Add Ticket Modal                  --}}
        {{-- ========================================== --}}
        <div id="addticketModal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 transition-all duration-300 hidden">
            <div class="relative bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto p-5 sm:p-8 transition-all">
                
                <button id="closeModal" type="button" class="absolute top-4 right-4 w-9 h-9 rounded-xl flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--text-dark)] hover:bg-slate-100 dark:hover:bg-slate-800 text-2xl transition-all cursor-pointer leading-none" aria-label="Close">&times;</button>
                
                @if ($errors->any())
                    <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 p-4 rounded-xl mb-6">
                        <h4 class="m-0 mb-2 font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1.5 text-sm">
                            <span class="material-symbols-outlined text-base">error</span> Please fix the following errors:
                        </h4>
                        <ul class="m-0 pl-5 text-xs font-medium space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <h2 class="text-xl sm:text-2xl font-extrabold text-[var(--text-dark)] mb-6 pb-4 border-b border-[var(--border-light)] tracking-tight">
                    Create New Ticket
                </h2>

                <form id="createTicketForm" action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Client Information -->
                    <fieldset class="border border-[var(--border-light)] rounded-xl p-4 sm:p-6 mb-6 bg-slate-50/60 dark:bg-slate-800/30 shadow-2xs">
                        <legend class="px-2 text-xs sm:text-sm font-bold text-[var(--text-dark)] uppercase tracking-wider">Client Information</legend>

                        @php
                            $parts = explode(' ', trim(auth()->user()->name));
                            $lastName = count($parts) > 1 ? array_pop($parts) : '';
                            $middleInitial = '';

                            if (count($parts) > 0) {
                                $lastPart = end($parts);
                                if (preg_match('/^[A-Za-z]\.?$/', $lastPart)) {
                                    $middleInitial = array_pop($parts);
                                }
                            }

                            $firstName = implode(' ', $parts);
                        @endphp

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 mb-4">
                            <div>
                                <label for="firstname" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    First Name <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="firstname" name="firstname" value="{{ $firstName }}" readonly
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                            </div>
                            <div>
                                <label for="middle_initial" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Middle Initial <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="middle_initial" name="middle_initial" value="{{ $middleInitial }}" readonly
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                            </div>
                            <div>
                                <label for="lastname" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Last Name <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="lastname" name="lastname" value="{{ $lastName }}" readonly
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" readonly
                                class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Date Created</label>
                                <input type="text" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('F j, Y h:i A') }}" readonly
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                                <input type="hidden" name="date_created" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}">
                            </div>

                            <div>
                                <label for="division" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Section / Division <span class="text-rose-500">*</span>
                                </label>
                                <select class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs"
                                    id="division" name="division" required>
                                    <option value="" selected disabled>Select Division</option>
                                    @forelse ($sections_divisions ?? [] as $division)
                                        <option value="{{ $division }}">{{ $division }}</option>
                                    @empty
                                        <option value="" disabled>No divisions available</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mb-4">
                            <div>
                                <label for="device" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Device <span class="text-rose-500">*</span>
                                </label>
                                <select id="device" name="device" required
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                    <option value="" disabled selected>Select Device</option>
                                    @foreach (['Desktop PC', 'Laptop/Netbook PC', 'Tablet PC', 'All-in-1 Printer', 'Printer Only', 'Scanner Only', 'Others'] as $device)
                                        <option value="{{ $device }}">{{ $device }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="service" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Technical Service <span class="text-rose-500">*</span>
                                </label>
                                <select class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs"
                                    id="service" name="service" required>
                                    <option value="" selected disabled>Select Technical Service</option>
                                    @forelse ($technical_services ?? [] as $service)
                                        <option value="{{ $service }}">{{ $service }}</option>
                                    @empty
                                        <option value="" disabled>No technical services available</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="request" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                Request Details <span class="text-rose-500">*</span>
                                <span class="text-xs normal-case text-slate-400 ml-1">(Note: for website postings, please include Google Drive link)</span>
                            </label>
                            <textarea id="request" name="request" rows="4" placeholder="Please describe your issue or request in detail..." required
                                class="w-full px-3.5 py-3 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs resize-y"></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label for="photo" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Attach Photo <span class="text-slate-400 text-xs normal-case">(Optional)</span>
                                </label>
                                <input type="file" id="photo" name="photo" accept="image/*"
                                    class="w-full px-3 py-2 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-[var(--text-dark)] hover:file:bg-slate-200 cursor-pointer">
                                <span class="block text-[11px] text-[var(--text-muted)] mt-1">Max file size: 20MB (JPEG, PNG, JPG, GIF, WEBP)</span>
                            </div>

                            <div>
                                <label for="priority" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Priority Level <span class="text-rose-500">*</span>
                                </label>
                                <select id="priority" name="priority" required
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                    <option value="" disabled selected>Select Priority Level</option>
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            </div>
                        </div>    
                    </fieldset>

                    <!-- Designated Personnel -->
                    <fieldset class="border border-[var(--border-light)] rounded-xl p-4 sm:p-6 mb-6 bg-slate-50/60 dark:bg-slate-800/30 shadow-2xs">
                        <legend class="px-2 text-xs sm:text-sm font-bold text-[var(--text-dark)] uppercase tracking-wider">Designated Personnel</legend>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label for="it_area" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Region <span class="text-rose-500">*</span>
                                </label>
                                <select id="it_area" name="it_area" required
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                    <option value="" disabled selected>Select Region</option>
                                    @forelse ($it_area ?? [] as $area)
                                        <option value="{{ $area }}">{{ $area }}</option>
                                    @empty
                                        <option value="" disabled>No regions available</option>
                                    @endforelse
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Status</label>
                                <input type="text" id="status" name="status" value="Pending" readonly
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                            </div>
                        </div>

                        <input type="hidden" id="it_personnel" name="it_personnel" value="">
                        <input type="hidden" id="it_email" name="it_email" value="">
                    </fieldset>

                    <!-- Form Footer & Terms -->
                    <div class="mt-4 mb-6">
                        <label class="flex items-start gap-3 cursor-pointer text-xs sm:text-sm text-[var(--text-muted)] leading-relaxed select-none" for="terms_agree">
                            <input type="checkbox" id="terms_agree" name="terms_agree" required class="mt-1 w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 cursor-pointer accent-indigo-600 shrink-0">
                            <span>I have read and agree to the <a href="https://cda.gov.ph/cda-privacy-policy/" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline" target="_blank">Terms and Conditions</a> and the <a href="https://cda.gov.ph/cda-privacy-policy/" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline" target="_blank">Privacy Policy</a>, and I confirm that the information provided is accurate and true to the best of my knowledge. <span class="text-rose-500 font-bold">*</span></span>
                        </label>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-[var(--border-light)]">
                        <button type="submit" id="submitTicketBtn" disabled
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-11 px-6 rounded-xl font-semibold text-sm text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-indigo-600 transition-all shadow-sm shadow-indigo-600/20 cursor-pointer">
                            <span>Submit Ticket</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- MODAL 2: Re-Assign Modal                   --}}
        {{-- ========================================== --}}
        <div id="assignTicketModal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 transition-all duration-300 hidden">
            <div class="relative bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-5 sm:p-8 transition-all">
                <button id="closeAssignModal" type="button" class="absolute top-4 right-4 w-9 h-9 rounded-xl flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--text-dark)] hover:bg-slate-100 dark:hover:bg-slate-800 text-2xl transition-all cursor-pointer leading-none" aria-label="Close">&times;</button>

                @if ($errors->any())
                    <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 p-4 rounded-xl mb-6">
                        <h4 class="m-0 mb-2 font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1.5 text-sm">
                            <span class="material-symbols-outlined text-base">error</span> Please fix the following errors:
                        </h4>
                        <ul class="m-0 pl-5 text-xs font-medium space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h2 class="text-xl sm:text-2xl font-extrabold text-[var(--text-dark)] mb-6 pb-4 border-b border-[var(--border-light)] tracking-tight flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500">person_add</span> Re-Assign Ticket
                </h2>
                
                <form id="assignForm" method="POST" action="{{ route('tickets.re_assign') }}">
                    @csrf
                    <input type="hidden" name="ticket_id" id="assignTicketId">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label for="it_area_assign" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                Region <span class="text-rose-500">*</span>
                            </label>
                            <select name="it_area" id="it_area_assign" required
                                class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                <option selected disabled value="">Select Region</option>
                                @foreach($it_area ?? [] as $area)
                                    <option value="{{ $area }}">{{ $area }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="re_assigned_to" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                Assign To <span class="text-rose-500">*</span>
                            </label>
                            <select name="re_assigned_to" id="re_assigned_to" required
                                class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                <option selected disabled value="">Select Personnel</option>
                            </select>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label for="re_assigned_it_email" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Personnel Email</label>
                            <input type="text" name="re_assigned_it_email" id="re_assigned_it_email" readonly
                                class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="re_assign_priority" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Priority Level</label>
                            <select name="priority" id="re_assign_priority"
                                class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                <option value="">Keep Current Priority Level</option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label for="assign_notes" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Instructions / Notes</label>
                            <textarea name="notes" id="assign_notes" rows="3" placeholder="Add specific guidance or notes for the new assignee..."
                                class="w-full px-3.5 py-3 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs resize-y"></textarea>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="assigned_at" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Date Assigned</label>
                            <input type="text" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('F j, Y h:i A') }}" readonly
                                class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                            <input type="hidden" name="assigned_at" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-end gap-2.5 pt-4 mt-6 border-t border-[var(--border-light)]">
                        <button type="button" id="cancelAssignModal"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 h-11 px-5 rounded-xl font-semibold text-sm text-[var(--text-muted)] hover:text-[var(--text-dark)] bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 h-11 px-6 rounded-xl font-semibold text-sm text-white bg-amber-600 hover:bg-amber-700 active:scale-95 shadow-sm shadow-amber-600/20 transition-all cursor-pointer">
                            Re-Assign
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- MODAL 3: Edit Status Modal                 --}}
        {{-- ========================================== --}}
        <div id="editticketModal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 transition-all duration-300 hidden">
            <div class="relative bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-5 sm:p-8 transition-all">
                <button id="closeEditModal" type="button" class="absolute top-4 right-4 w-9 h-9 rounded-xl flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--text-dark)] hover:bg-slate-100 dark:hover:bg-slate-800 text-2xl transition-all cursor-pointer leading-none" aria-label="Close">&times;</button>
                
                @if ($errors->any())
                    <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 p-4 rounded-xl mb-6">
                        <h4 class="m-0 mb-2 font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1.5 text-sm">
                            <span class="material-symbols-outlined text-base">error</span> Please fix the following errors:
                        </h4>
                        <ul class="m-0 pl-5 text-xs font-medium space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h2 class="text-xl sm:text-2xl font-extrabold text-[var(--text-dark)] mb-6 pb-4 border-b border-[var(--border-light)] tracking-tight flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500">edit_note</span> Update Ticket Status
                </h2>
                
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="ticket_id" id="edit_ticket_id">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label for="edit_status" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                Status <span class="text-rose-500">*</span>
                            </label>
                            <select name="status" id="edit_status" required
                                class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                <option value="" disabled>Select status</option>
                                <option value="Pending">Pending</option>
                                <option value="Pending/Re-Assigned">Pending/Re-Assigned</option>
                                <option value="Resolved">Resolved</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="edit_priority" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                Priority Level <span class="text-rose-500">*</span>
                            </label>
                            <select name="priority" id="edit_priority" required
                                class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                <option value="" disabled>Select priority Level</option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label for="action_taken" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                Action Taken <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="action_taken" id="action_taken" rows="4" required placeholder="Describe the resolution or actions performed..."
                                class="w-full px-3.5 py-3 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs resize-y"></textarea>
                        </div>

                        <!-- Photo Evidence Field -->
                        <div class="sm:col-span-2">
                            <label for="photo_evidence" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                Photo Evidence <span class="text-slate-400 text-xs normal-case">(Optional)</span>
                            </label>
                            <input type="file" name="photo_evidence" id="photo_evidence" accept=".jpeg, .png, .jpg, .gif, .webp"
                                class="w-full px-3 py-2 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-[var(--text-dark)] hover:file:bg-slate-200 cursor-pointer">
                            <span class="block text-[11px] text-[var(--text-muted)] mt-1">Max file size: 20MB (JPEG, PNG, JPG, GIF, WEBP)</span>
                            <img id="photo_preview" class="hidden mt-2 max-h-48 rounded-lg border border-[var(--border-light)] object-contain" alt="Photo Evidence Preview">
                        </div>

                        <!-- Link Evidence Field -->
                        <div class="sm:col-span-2">
                            <label for="link_evidence" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                Link Evidence <span class="text-slate-400 text-xs normal-case">(Optional)</span>
                            </label>
                            <input type="text" name="link_evidence" id="link_evidence" placeholder="e.g., https://drive.google.com/..."
                                class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Date Resolved</label>
                            <input type="text" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('F j, Y h:i A') }}" readonly
                                class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                            <input type="hidden" name="date_resolved" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-end gap-2.5 pt-4 mt-6 border-t border-[var(--border-light)]">
                        <button type="button" id="cancelEditStatusModal"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 h-11 px-5 rounded-xl font-semibold text-sm text-[var(--text-muted)] hover:text-[var(--text-dark)] bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 h-11 px-6 rounded-xl font-semibold text-sm text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 shadow-sm shadow-indigo-600/20 transition-all cursor-pointer">
                            Save Updates
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Loading Helper Utilities
            function showLoading() {
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) overlay.classList.add('active');
            }

            function hideLoading() {
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) overlay.classList.remove('active');
            }

            // SweetAlert Flash Messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{!! addslashes(session("success")) !!}',
                    timer: 3000,
                    showConfirmButton: false,
                    background: getComputedStyle(document.body).getPropertyValue('--card-bg').trim() || '#ffffff',
                    color: getComputedStyle(document.body).getPropertyValue('--text-dark').trim() || '#000000'
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: '{!! addslashes(session("warning")) !!}',
                    timer: 3000,
                    showConfirmButton: false,
                    background: getComputedStyle(document.body).getPropertyValue('--card-bg').trim() || '#ffffff',
                    color: getComputedStyle(document.body).getPropertyValue('--text-dark').trim() || '#000000'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Notice!',
                    text: '{!! addslashes(session("error")) !!}',
                    timer: 3000,
                    showConfirmButton: false,
                    background: getComputedStyle(document.body).getPropertyValue('--card-bg').trim() || '#ffffff',
                    color: getComputedStyle(document.body).getPropertyValue('--text-dark').trim() || '#000000'
                });
            @endif
            
            // Auto-Reload & Countdown Timer
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
                        showLoading();
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

            // Modal Utilities
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

            // Add Ticket Modal Logic
            const addModal = document.getElementById('addticketModal');
            if (addModal) {
                const closeAddBtn = addModal.querySelector('#closeModal');
                const openAddBtns = document.querySelectorAll('#openAddTicketModalBtn, #openTicketModal, .open-ticket-modal, [data-modal-target="addticketModal"]');

                openAddBtns.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        openModal(addModal);
                    });
                });

                if (closeAddBtn) {
                    closeAddBtn.addEventListener('click', () => closeModal(addModal));
                }

                addModal.addEventListener('click', function(e) {
                    if (e.target === addModal) closeModal(addModal);
                });

                @if ($errors->any())
                    openModal(addModal);
                @endif

                const nextAssignmentMap = @json($nextAssignment ?? new \stdClass());
                
                const serviceSelect = addModal.querySelector('select[name="service"]');
                const regionSelect = addModal.querySelector('select[name="it_area"]');
                const personnelInput = addModal.querySelector('input[name="it_personnel"]');
                const emailInput = addModal.querySelector('input[name="it_email"]');

                function updatePersonnelAndEmails() {
                    if (!regionSelect || !serviceSelect || !personnelInput || !emailInput) return;
                    
                    const selectedRegion = regionSelect.value;
                    const selectedService = serviceSelect.value;

                    personnelInput.value = '';
                    emailInput.value = '';

                    if (!selectedRegion) return;

                    const exactKey = `${selectedRegion}_${selectedService}`;
                    const defaultKey = `${selectedRegion}_default`;
                    const assignedPerson = nextAssignmentMap[exactKey] || nextAssignmentMap[defaultKey];

                    if (assignedPerson) {
                        personnelInput.value = assignedPerson.name;
                        emailInput.value = assignedPerson.email;
                    } else {
                        personnelInput.value = 'No personnel found for this region';
                        
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'No Personnel Found',
                                text: 'There is no IT personnel assigned to this region/service yet.',
                                timer: 2500,
                                showConfirmButton: false
                            });
                        }
                    }
                }

                if (serviceSelect) serviceSelect.addEventListener('change', updatePersonnelAndEmails);
                if (regionSelect) regionSelect.addEventListener('change', updatePersonnelAndEmails);

                // Form validation & submission enhancement
                const ticketForm = addModal.querySelector('#createTicketForm');
                if (ticketForm) {
                    ticketForm.addEventListener('submit', function(e) {
                        let isValid = true;
                        const requiredFields = this.querySelectorAll('[required]');
                        
                        requiredFields.forEach(field => {
                            if (!field.value.trim()) {
                                isValid = false;
                                field.classList.add('border-red-500', 'bg-red-50');
                            } else {
                                field.classList.remove('border-red-500', 'bg-red-50');
                            }
                        });

                        if (!isValid) {
                            e.preventDefault();
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Missing Information',
                                    text: 'Please fill in all required fields marked with *.',
                                    confirmButtonColor: '#3085d6'
                                });
                            } else {
                                alert('Please fill in all required fields marked with *.');
                            }
                        } else {
                            showLoading();
                        }
                    });
                }

                const termsCheckbox = addModal.querySelector('#terms_agree');
                const submitBtn = addModal.querySelector('#submitTicketBtn');

                if (termsCheckbox && submitBtn) {
                    submitBtn.disabled = !termsCheckbox.checked;

                    termsCheckbox.addEventListener('change', function() {
                        submitBtn.disabled = !this.checked;
                    });
                }
            }

            // Re-Assign Ticket Modal Logic
            const assignModal = document.getElementById('assignTicketModal');
            const currentUserEmail = @json(auth()->user()->email ?? '');
            const currentUserName = @json(auth()->user()->name ?? '');
            const currentUserRole = @json(auth()->user()->role ?? '');

            const rawItMapping = @json($itMapping ?? $reassignable_it_mapping ?? $it_mapping ?? []);
            const itMapping = typeof rawItMapping === 'string' ? JSON.parse(rawItMapping) : rawItMapping;

            if (assignModal) {
                const closeAssignBtn = document.getElementById('closeAssignModal');
                const cancelAssignBtn = document.getElementById('cancelAssignModal');
                const regionSelectAssign = assignModal.querySelector('#it_area_assign') || assignModal.querySelector('select[name="it_area"]');
                const assigneeSelect = assignModal.querySelector('#re_assigned_to') || assignModal.querySelector('select[name="re_assigned_to"]');
                const assigneeEmail = assignModal.querySelector('#re_assigned_it_email') || assignModal.querySelector('input[name="re_assigned_it_email"]');
                const assignForm = assignModal.querySelector('form');

                if (assignForm) {
                    assignForm.addEventListener('submit', function() {
                        showLoading();
                    });
                }

                document.querySelectorAll('.open-assign-modal').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const ticketId = this.dataset.id;
                        const currentStatus = (this.dataset.status || '').trim();
                        const currentAssigneeName = (this.dataset.assignedPersonnel || this.dataset.assignedTo || '').trim();
                        const currentAssigneeEmail = (this.dataset.assignedEmail || this.dataset.itEmail || '').trim().toLowerCase();

                        if (currentStatus === 'Resolved') {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({ title: 'Ticket Locked', text: 'Ticket was already resolved. Re-assignment is not allowed.', icon: 'warning', confirmButtonColor: '#4f46e5' });
                            } else {
                                alert('Cannot reassign a resolved ticket.');
                            }
                            return;
                        }

                        const isAdmin = ['Super Admin', 'ICTS Admin'].includes(currentUserRole);

                        if ((currentAssigneeEmail || currentAssigneeName) && (currentStatus === 'Pending/Re-Assigned')) {
                            const isAssignedUser = (currentUserEmail && currentUserEmail.toLowerCase() === currentAssigneeEmail) || 
                                                (currentUserName && currentUserName.toLowerCase() === currentAssigneeName.toLowerCase());

                            if (!isAssignedUser && !isAdmin) {
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        title: 'Access Restricted',
                                        text: `This ticket is currently re-assigned to ${currentAssigneeName || 'another personnel'}.`,
                                        icon: 'error',
                                        confirmButtonColor: '#4f46e5'
                                    });
                                } else {
                                    alert(`Only ${currentAssigneeName || 'the assigned personnel'} or an Admin can reassign this ticket.`);
                                }
                                return;
                            }
                        }

                        const assignTicketIdInput = document.getElementById('assignTicketId');
                        if (assignTicketIdInput) assignTicketIdInput.value = ticketId;

                        assignModal.dataset.currentAssigneeEmail = currentAssigneeEmail;
                        assignModal.dataset.currentAssigneeName = currentAssigneeName;

                        if (regionSelectAssign) regionSelectAssign.selectedIndex = 0;
                        if (assigneeSelect) assigneeSelect.innerHTML = '<option disabled selected value="">Select Personnel</option>';
                        if (assigneeEmail) assigneeEmail.value = '';

                        openModal(assignModal);
                    });
                });

                const closeAssignFunc = () => closeModal(assignModal);

                if (closeAssignBtn) closeAssignBtn.addEventListener('click', closeAssignFunc);
                if (cancelAssignBtn) cancelAssignBtn.addEventListener('click', closeAssignFunc);
                assignModal.addEventListener('click', e => { if (e.target === assignModal) closeAssignFunc(); });

                if (regionSelectAssign) {
                    regionSelectAssign.addEventListener('change', function () {
                        if (assigneeSelect) assigneeSelect.innerHTML = '<option disabled selected value="">Select Personnel</option>';
                        if (assigneeEmail) assigneeEmail.value = '';

                        const selectedRegionVal = this.value ? this.value.trim().toLowerCase() : '';
                        if (!selectedRegionVal) return;

                        let rawPersonnelData = [];

                        for (const areaKey in itMapping) {
                            if (areaKey.trim().toLowerCase() === selectedRegionVal) {
                                rawPersonnelData = itMapping[areaKey];
                                break;
                            }
                        }

                        const personnelList = Array.isArray(rawPersonnelData) ? rawPersonnelData : Object.values(rawPersonnelData || {});

                        const currentAssigneeEmail = (assignModal.dataset.currentAssigneeEmail || '').toLowerCase();
                        const currentAssigneeName = (assignModal.dataset.currentAssigneeName || '').toLowerCase();

                        const availablePersonnel = personnelList.filter(p => {
                            const pEmail = (p.email || p.it_email || '').trim().toLowerCase();

                            const fName = p.firstname ? p.firstname.trim() : '';
                            const mName = p.middle_initial ? p.middle_initial.trim() : '';
                            const lName = p.lastname ? p.lastname.trim() : '';
                            const computedName = [fName, mName, lName].filter(Boolean).join(' ').toLowerCase();
                            const pName = (p.name || computedName).trim().toLowerCase();

                            if (currentAssigneeEmail && pEmail && pEmail === currentAssigneeEmail) return false;
                            if (currentAssigneeName && pName && pName === currentAssigneeName) return false;

                            return true;
                        });

                        if (availablePersonnel.length === 0) {
                            const opt = document.createElement('option');
                            opt.disabled = true;
                            opt.selected = true;
                            opt.textContent = 'No other personnel available in this area';
                            if (assigneeSelect) assigneeSelect.appendChild(opt);
                            return;
                        }

                        availablePersonnel.forEach(p => {
                            const opt = document.createElement('option');

                            const fName = p.firstname ? p.firstname.trim() : '';
                            const mName = p.middle_initial ? p.middle_initial.trim() : '';
                            const lName = p.lastname ? p.lastname.trim() : '';

                            const computedName = [fName, mName, lName].filter(Boolean).join(' ');
                            const pName = p.name || computedName || 'Unknown Personnel';
                            const pEmail = p.email || p.it_email || '';

                            opt.value = pName;
                            opt.textContent = pName;
                            opt.setAttribute('data-email', pEmail);

                            if (assigneeSelect) assigneeSelect.appendChild(opt);
                        });
                    });
                }

                if (assigneeSelect) {
                    assigneeSelect.addEventListener('change', function () {
                        const sel = this.options[this.selectedIndex];
                        if (assigneeEmail && sel) {
                            assigneeEmail.value = sel.getAttribute('data-email') || '';
                        }
                    });
                }
            }

            // Edit Ticket Modal Logic
            const editModal = document.getElementById('editticketModal');
            if (editModal) {
                const editCloseBtn = document.getElementById('closeEditModal');
                const cancelEditBtn = document.getElementById('cancelEditStatusModal');
                const editForm = document.getElementById('editForm');
                const statusSelect = editModal.querySelector('#edit_status');
                const prioritySelect = editModal.querySelector('#edit_priority');

                const closeEditFunc = () => closeModal(editModal);
                if (editCloseBtn) editCloseBtn.addEventListener('click', closeEditFunc);
                if (cancelEditBtn) cancelEditBtn.addEventListener('click', closeEditFunc);
                editModal.addEventListener('click', e => { if (e.target === editModal) closeEditFunc(); });

                if (statusSelect) {
                    statusSelect.addEventListener('change', function () {
                        if (this.value === 'Pending/Re-Assigned') {
                            showAlert('warning', 'Notice', 'You must re-assign the ticket first.');
                        } else if (this.value !== 'Resolved') {
                            showAlert('info', 'Notice', 'Please update the ticket into resolved.');
                        }
                    });
                }

                if (editForm) {
                    editForm.addEventListener('submit', function (e) {
                        const currentStatus = statusSelect ? statusSelect.value : '';

                        if (currentStatus === 'Pending/Re-Assigned') {
                            e.preventDefault();
                            showAlert('warning', 'Action Required', 'You must re-assign the ticket first.');
                            return;
                        }

                        if (currentStatus !== 'Resolved') {
                            e.preventDefault();
                            showAlert('warning', 'Action Required', 'Please update the ticket into resolved.');
                            return;
                        }

                        showLoading();
                    });
                }

                document.querySelectorAll('.open-edit-modal').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const ticketId = this.dataset.id;
                        const status = this.dataset.status || '';
                        
                        let rawPriority = this.dataset.priority || '';
                        let priority = rawPriority ? (rawPriority.charAt(0).toUpperCase() + rawPriority.slice(1).toLowerCase()) : '';
                        
                        const actionTaken = this.dataset.actionTaken || '';
                        const photoUrl = this.dataset.photo || '';

                        if (status === 'Resolved') {
                            showAlert('info', 'Ticket Locked', 'This ticket is already resolved.');
                            return;
                        }

                        if (editForm) editForm.action = `/tickets/${ticketId}`;
                        
                        const editIdInput = editModal.querySelector('#edit_ticket_id');
                        const actionTextarea = editModal.querySelector('#action_taken');
                        const photoPreview = editModal.querySelector('#photo_preview');

                        if (editIdInput) editIdInput.value = ticketId;
                        if (prioritySelect) prioritySelect.value = priority;
                        if (statusSelect) statusSelect.value = status;
                        if (actionTextarea) actionTextarea.value = actionTaken;

                        if (photoPreview) {
                            if (photoUrl) {
                                photoPreview.src = photoUrl;
                                photoPreview.classList.remove('hidden');
                            } else {
                                photoPreview.src = '';
                                photoPreview.classList.add('hidden');
                            }
                        }

                        openModal(editModal);
                    });
                });

                function showAlert(icon, title, message) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: icon,
                            title: title,
                            text: message,
                            confirmButtonColor: '#4f46e5'
                        });
                    } else {
                        alert(message);
                    }
                }
            }

            // Delete Alert Action
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    Swal.fire({
                        title: 'Delete this Ticket?',
                        text: "This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel'
                    }).then(res => {
                        if (res.isConfirmed) {
                            showLoading();
                            const deleteForm = document.getElementById('delete-form-' + id);
                            if (deleteForm) deleteForm.submit();
                        }
                    });
                });
            });
            
            // Escape Key Binding
            document.addEventListener('keydown', function(event) {
                if (event.key === "Escape") {
                    if (addModal) closeModal(addModal); 
                    if (assignModal) closeModal(assignModal);
                    if (editModal) closeModal(editModal);
                }
            });
        });
    </script>
</x-app-layout>
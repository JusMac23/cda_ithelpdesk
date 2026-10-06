<x-app-layout>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

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
                            <span class="material-symbols-outlined text-2xl">receipt_long</span>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                                My Requested Tickets
                            </h1>
                            <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                                View, monitor, and submit personal IT support tickets and track resolution progress
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Action Toolbar & Search --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-6">
                    <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                        @can('create_myrequested_tickets')
                        <button id="openAddTicketModalBtn" type="button"
                            class="inline-flex items-center justify-center gap-2 h-11 px-4 sm:px-5 rounded-xl font-semibold text-sm text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 active:scale-[0.98] shadow-sm shadow-emerald-600/20 hover:shadow-md hover:shadow-emerald-600/30 transition-all duration-200 cursor-pointer">
                            <span class="material-symbols-outlined text-xl">add</span>
                            <span>Add Ticket</span>
                        </button>
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

                    @can('search_myrequested_tickets')
                    <div class="flex items-center justify-end gap-2 w-full sm:w-auto sm:ml-auto">
                        <form action="{{ route('myrequested_tickets.index') }}" method="GET" class="flex-1 min-w-0 sm:w-72 md:w-80 sm:flex-initial m-0">
                            <div class="relative flex items-center w-full">
                                <span class="absolute left-3.5 text-slate-400 dark:text-slate-500 flex items-center pointer-events-none">
                                    <span class="material-symbols-outlined text-xl">search</span>
                                </span>
                                <input type="text" name="search_query" value="{{ request('search_query') }}" placeholder="Search my tickets..." autocomplete="off"
                                    class="w-full h-11 pl-10 pr-24 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                <button type="submit" aria-label="Search"
                                    class="absolute right-1.5 h-8 px-3 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 transition-all flex items-center justify-center cursor-pointer">
                                    Search
                                </button>
                            </div>
                        </form>
                        @if(request('search_query'))
                            <a href="{{ route('myrequested_tickets.index') }}" 
                               class="inline-flex items-center justify-center gap-1 h-11 px-3 rounded-xl border border-[var(--border-light)] bg-[var(--card-bg)] text-xs font-semibold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all whitespace-nowrap shadow-xs"
                               title="Clear search">
                                <span class="material-symbols-outlined text-base">close</span>
                                <span class="hidden xs:inline">Clear</span>
                            </a>
                        @endif
                    </div>
                    @endcan
                </div>

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
                                    <th class="py-3.5 px-4 text-center font-bold min-w-[70px]">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--border-subtle)]">
                                @forelse ($tickets as $ticket)
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
                                            <div class="flex items-center justify-center gap-1">
                                                @can('view_ticket_details_myrequested_tickets')
                                                    <a href="{{ route('tickets.myrequested.view', $ticket->ticket_id) }}"
                                                        title="View Details"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-emerald-700 dark:text-emerald-400 transition-all">
                                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="py-12 px-4 text-center">
                                            <div class="flex flex-col items-center justify-center gap-2 text-[var(--text-muted)]">
                                                <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600">inbox</span>
                                                <p class="text-base font-semibold m-0">No Requested Ticket found</p>
                                                <p class="text-xs m-0">Click "Add Ticket" to create a new support request.</p>
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
        {{-- MODAL: Add Ticket Modal                    --}}
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
                    <fieldset class="border border-[var(--border-light)] rounded-xl p-4 sm:p-6 mb-6 bg-[var(--card-bg)] shadow-2xs">
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
                    <fieldset class="border border-[var(--border-light)] rounded-xl p-4 sm:p-6 mb-6 bg-[var(--card-bg)] shadow-2xs">
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

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

            // --- AUTO-RELOAD & COUNTDOWN ---
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
                    if (countdown <= 0) location.reload();
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

            // --- SHARED MODAL FUNCTIONS ---
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

            // --- ADD TICKET MODAL LOGIC ---
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

                // Get NextAssignment Map safely
                const nextAssignmentMap = @json($nextAssignment ?? new \stdClass());
                
                // Scope elements to addModal to avoid selector collisions
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
                                showConfirmButton: false,
                                background: getComputedColor('--card-bg'),
                                color: getComputedColor('--text-dark')
                            });
                        }
                    }
                }

                if (serviceSelect) serviceSelect.addEventListener('change', updatePersonnelAndEmails);
                if (regionSelect) regionSelect.addEventListener('change', updatePersonnelAndEmails);

                // --- FORM VALIDATION & SUBMIT LOADING ---
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
                                    confirmButtonColor: '#4f46e5',
                                    background: getComputedColor('--card-bg'),
                                    color: getComputedColor('--text-dark')
                                });
                            } else {
                                alert('Please fill in all required fields marked with *.');
                            }
                        } else {
                            showLoading();
                        }
                    });
                }

                // Enable/Disable Submit button on Terms acceptance
                const termsCheckbox = addModal.querySelector('#terms_agree');
                const submitBtn = addModal.querySelector('#submitTicketBtn');

                if (termsCheckbox && submitBtn) {
                    submitBtn.disabled = !termsCheckbox.checked;

                    termsCheckbox.addEventListener('change', function() {
                        submitBtn.disabled = !this.checked;
                    });
                }
            }
            
            // --- KEYBOARD ACCESSIBILITY (ESC KEY) ---
            document.addEventListener('keydown', function(event) {
                if (event.key === "Escape") {
                    if (addModal) closeModal(addModal);
                }
            });
        });
    </script>
</x-app-layout>
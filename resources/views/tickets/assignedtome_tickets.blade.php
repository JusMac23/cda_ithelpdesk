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
                            <span class="material-symbols-outlined text-2xl">assignment_ind</span>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                                Tickets Assigned to Me
                            </h1>
                            <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                                Manage, resolve, and update technical support requests dispatched to your queue
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Toolbar Action Buttons & Search --}}
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                    <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                        @can('create_assignedtome_tickets')
                        <button id="openModal" type="button"
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

                    @can('search_assignedtome_tickets')
                    <form action="{{ route('assignedtome_tickets.index') }}" method="GET" class="w-full lg:w-80 m-0">
                        <div class="relative flex items-center w-full">
                            <span class="absolute left-3.5 text-slate-400 dark:text-slate-500 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-xl">search</span>
                            </span>
                            <input type="text" name="search_query" value="{{ request('search_query') }}" placeholder="Search assigned tickets..." autocomplete="off"
                                class="w-full h-11 pl-10 pr-24 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                            <button type="submit" aria-label="Search"
                                class="absolute right-1.5 h-8 px-3 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 transition-all flex items-center justify-center cursor-pointer">
                                Search
                            </button>
                        </div>
                    </form>
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
                                    <th class="py-3.5 px-4 font-bold">Device</th>
                                    <th class="py-3.5 px-4 font-bold">Service</th>
                                    <th class="py-3.5 px-4 font-bold min-w-[180px]">Request Details</th>
                                    <th class="py-3.5 px-4 font-bold">Assigned Personnel</th>
                                    <th class="py-3.5 px-4 font-bold min-w-[160px]">Action Taken</th>
                                    <th class="py-3.5 px-4 font-bold">Date Created</th>
                                    <th class="py-3.5 px-4 font-bold">Date Resolved</th>
                                    <th class="py-3.5 px-4 text-center font-bold">Photo</th>
                                    <th class="py-3.5 px-4 text-center font-bold">Status</th>
                                    <th class="py-3.5 px-4 text-center font-bold min-w-[150px]">Actions</th>
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
                                                <span>{{ $ticket->firstname }} {{ $ticket->lastname }}</span>
                                            </div>
                                        </td>

                                        <!-- Division -->
                                        <td class="py-4 px-4 whitespace-nowrap text-xs text-[var(--text-muted)]">
                                            {{ $ticket->division }}
                                        </td>

                                        <!-- Device -->
                                        <td class="py-4 px-4 whitespace-nowrap text-xs text-[var(--text-dark)]">
                                            {{ $ticket->device }}
                                        </td>

                                        <!-- Service -->
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

                                        <!-- Action Taken -->
                                        <td class="py-4 px-4 max-w-xs">
                                            <p class="truncate text-xs text-[var(--text-muted)] m-0" title="{{ $ticket->action_taken }}">
                                                {{ $ticket->action_taken ?: '—' }}
                                            </p>
                                        </td>

                                        <!-- Date Created -->
                                        <td class="py-4 px-4 whitespace-nowrap text-xs text-[var(--text-muted)] font-mono">
                                            {{ \Carbon\Carbon::parse($ticket->date_created)->format('M d, Y h:i A') }}
                                        </td>

                                        <!-- Date Resolved -->
                                        <td class="py-4 px-4 whitespace-nowrap text-xs font-mono">
                                            @if($ticket->date_resolved)
                                                <span class="text-emerald-600 dark:text-emerald-400 font-medium">
                                                    {{ \Carbon\Carbon::parse($ticket->date_resolved)->format('M d, Y h:i A') }}
                                                </span>
                                            @else
                                                <span class="text-rose-500 italic font-semibold">Not Resolved</span>
                                            @endif
                                        </td>

                                        <!-- Photo Thumbnail -->
                                        <td class="py-4 px-4 text-center whitespace-nowrap">
                                            @if($ticket->photo)
                                                <a href="{{ asset('storage/' . $ticket->photo) }}" target="_blank" class="inline-block">
                                                    <img src="{{ asset('storage/' . $ticket->photo) }}" alt="Evidence" class="w-10 h-10 object-cover rounded-lg border border-[var(--border-light)] hover:opacity-80 transition-opacity shadow-2xs">
                                                </a>
                                            @else
                                                <span class="text-xs text-slate-400 font-medium">—</span>
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
                                        <td class="py-4 px-4 text-center whitespace-nowrap">
                                            <div class="flex flex-col gap-1.5 min-w-[140px]">
                                                @can('reassign_assignedtome_tickets')
                                                    <button type="button"
                                                        class="open-assign-modal inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-amber-700 bg-amber-50/80 hover:bg-amber-100 border border-amber-200 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-800 dark:hover:bg-amber-900/50 transition-all cursor-pointer text-left"
                                                        data-id="{{ $ticket->ticket_id }}" 
                                                        data-status="{{ $ticket->status }}">
                                                        <span class="material-symbols-outlined text-base">person_add</span> Re-Assign
                                                    </button>
                                                @endcan

                                                @can('update_status_assignedtome_tickets')
                                                    <button type="button"
                                                        class="open-edit-modal inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-blue-700 bg-blue-50/80 hover:bg-blue-100 border border-blue-200 dark:bg-blue-950/30 dark:text-blue-400 dark:border-blue-800 dark:hover:bg-blue-900/50 transition-all cursor-pointer text-left"
                                                        data-id="{{ $ticket->ticket_id }}"
                                                        data-status="{{ $ticket->status }}"
                                                        data-action_taken="{{ $ticket->action_taken }}"
                                                        data-photo="{{ $ticket->photo }}">
                                                        <span class="material-symbols-outlined text-base">edit</span> Update Status
                                                    </button>
                                                @endcan

                                                @can('generate_tsar')
                                                    @if($ticket->status === 'Resolved')
                                                        <a href="{{ route('tickets.generateTSAR', $ticket->ticket_id) }}"
                                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-indigo-700 bg-indigo-50/80 hover:bg-indigo-100 border border-indigo-200 dark:bg-indigo-950/30 dark:text-indigo-400 dark:border-indigo-800 dark:hover:bg-indigo-900/50 transition-all">
                                                            <span class="material-symbols-outlined text-base">description</span> Generate TSAR
                                                        </a>
                                                    @endif
                                                @endcan

                                                @can('delete_assignedtome_tickets')
                                                    <form id="delete-form-{{ $ticket->ticket_id }}" action="{{ route('tickets.destroy', $ticket->ticket_id) }}" method="POST" class="m-0 w-full">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="delete-btn w-full inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-rose-700 bg-rose-50/80 hover:bg-rose-100 border border-rose-200 dark:bg-rose-950/30 dark:text-rose-400 dark:border-rose-800 dark:hover:bg-rose-900/50 transition-all cursor-pointer text-left"
                                                            data-id="{{ $ticket->ticket_id }}">
                                                            <span class="material-symbols-outlined text-base">delete</span> Delete
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="py-12 px-4 text-center">
                                            <div class="flex flex-col items-center justify-center gap-2 text-[var(--text-muted)]">
                                                <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600">inbox</span>
                                                <p class="text-base font-semibold m-0">No Tickets Assigned to you</p>
                                                <p class="text-xs m-0">New tickets assigned to your queue will be listed here.</p>
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
                    {{ $tickets->links() ?? '' }}
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- MODAL 1: Add Ticket Modal                  --}}
        {{-- ========================================== --}}
        <div id="ticketModal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 transition-all duration-300 hidden">
            <div class="relative bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto p-5 sm:p-8 transition-all">
                <button id="closeModal" type="button" class="absolute top-4 right-4 w-9 h-9 rounded-xl flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--text-dark)] hover:bg-slate-100 dark:hover:bg-slate-800 text-2xl transition-all cursor-pointer leading-none" aria-label="Close Modal">&times;</button>
                
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

                <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <fieldset class="border border-[var(--border-light)] rounded-xl p-4 sm:p-6 mb-6 bg-[var(--card-bg)] shadow-2xs">
                        <legend class="px-2 text-xs sm:text-sm font-bold text-[var(--text-dark)] uppercase tracking-wider">Client Information</legend>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mb-4">
                            <div>
                                <label for="firstname" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    First Name <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="firstname" id="firstname" placeholder="e.g., Juan" required
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                            </div>
                            <div>
                                <label for="lastname" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Last Name <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="lastname" id="lastname" placeholder="e.g., Dela Cruz" required
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                            </div>
                            
                            <div class="sm:col-span-2">
                                <label for="email" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Email <span class="text-rose-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" placeholder="j_delacruz@cda.gov.ph" required
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                            </div>
                            
                            <div>
                                <label for="division" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Division <span class="text-rose-500">*</span>
                                </label>
                                <select name="division" id="division" required
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                    <option value="" disabled selected>Select Division</option>
                                    @foreach ($sections_divisions as $division)
                                        <option value="{{ $division }}">{{ $division }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="device" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Device <span class="text-rose-500">*</span>
                                </label>
                                <select name="device" id="device" required
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                    <option value="" disabled selected>Select Device</option>
                                    <option value="Desktop PC">Desktop PC</option>
                                    <option value="Laptop/Netbook PC">Laptop/Netbook PC</option>
                                    <option value="Tablet PC">Tablet PC</option>
                                    <option value="All-in-1 Printer">All-in-1 Printer</option>
                                    <option value="Printer Only">Printer Only</option>
                                    <option value="Scanner Only">Scanner Only</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="service" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Technical Service <span class="text-rose-500">*</span>
                                </label>
                                <select name="service" id="service" required
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                    <option value="" disabled selected>Select Service</option>
                                    @foreach ($technical_services as $service)
                                        <option value="{{ $service }}">{{ $service }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="date_created_display" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Date Created</label>
                                <input type="text" id="date_created_display" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" readonly
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                                <input type="hidden" name="date_created" id="date_created" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d') }}">
                            </div>

                            <div class="sm:col-span-2">
                                <label for="request" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Request Details <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="request" id="request" rows="3" required placeholder="Please describe your issue or request in detail."
                                    class="w-full px-3.5 py-3 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs resize-y"></textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="photo" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Attach Photo <span class="text-slate-400 text-xs normal-case">(Optional)</span>
                                </label>
                                <input type="file" name="photo" id="photo"
                                    class="w-full px-3 py-2 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-[var(--text-dark)] hover:file:bg-slate-200 cursor-pointer">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-[var(--border-light)] rounded-xl p-4 sm:p-6 mb-6 bg-[var(--card-bg)] shadow-2xs">
                        <legend class="px-2 text-xs sm:text-sm font-bold text-[var(--text-dark)] uppercase tracking-wider">Designated Personnel</legend>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label for="it_area_add" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Region <span class="text-rose-500">*</span>
                                </label>
                                <select name="it_area" id="it_area_add" required
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                    <option selected disabled value="">Select Region</option>
                                    @foreach($it_area as $area)
                                        <option value="{{ $area }}">{{ $area }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="it_personnel_add" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Assigned Personnel</label>
                                <select name="it_personnel" id="it_personnel_add"
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                    <option selected disabled value="">Select Personnel</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="it_email_add" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">IT Email</label>
                                <input type="text" name="it_email" id="it_email_add" readonly
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                            </div>
                            <div>
                                <label for="status_add" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Status</label>
                                <input type="text" name="status" id="status_add" value="Pending" readonly
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-amber-600 font-bold cursor-not-allowed">
                            </div>
                        </div>
                    </fieldset>

                    <div class="flex flex-col sm:flex-row items-center justify-end gap-2.5 pt-4 border-t border-[var(--border-light)]">
                        <button type="button" id="cancelAddModal"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 h-11 px-5 rounded-xl font-semibold text-sm text-[var(--text-muted)] hover:text-[var(--text-dark)] bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-11 px-6 rounded-xl font-semibold text-sm text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 transition-all shadow-sm shadow-indigo-600/20 cursor-pointer">
                            <span class="material-symbols-outlined text-lg">send</span>
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
                <button id="closeAssignModal" type="button" class="absolute top-4 right-4 w-9 h-9 rounded-xl flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--text-dark)] hover:bg-slate-100 dark:hover:bg-slate-800 text-2xl transition-all cursor-pointer leading-none" aria-label="Close Modal">&times;</button>
                <h2 class="text-xl sm:text-2xl font-extrabold text-[var(--text-dark)] mb-6 pb-4 border-b border-[var(--border-light)] tracking-tight flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500">person_add</span> Re-Assign Ticket
                </h2>
                
                <form id="assignForm" method="POST" action="{{ route('tickets.assign') }}">
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
                            <label for="assigned_to" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                Assign To <span class="text-rose-500">*</span>
                            </label>
                            <select name="assigned_to" id="assigned_to" required
                                class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                                <option selected disabled value="">Select Personnel</option>
                            </select>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label for="assigned_it_email" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Personnel Email</label>
                            <input type="text" name="assigned_it_email" id="assigned_it_email" readonly
                                class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label for="assigned_at" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Date Assigned</label>
                            <input type="text" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" readonly
                                class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                            <input type="hidden" name="assigned_at" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d') }}">
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label for="assign_notes" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Instructions / Notes</label>
                            <textarea name="notes" id="assign_notes" rows="3" placeholder="Add specific guidance or notes for the new assignee..."
                                class="w-full px-3.5 py-3 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs resize-y"></textarea>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-end gap-2.5 pt-4 mt-6 border-t border-[var(--border-light)]">
                        <button type="button" id="cancelAssignModal"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 h-11 px-5 rounded-xl font-semibold text-sm text-[var(--text-muted)] hover:text-[var(--text-dark)] bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 h-11 px-6 rounded-xl font-semibold text-sm text-white bg-amber-600 hover:bg-amber-700 active:scale-95 shadow-sm shadow-amber-600/20 transition-all cursor-pointer">
                            <span class="material-symbols-outlined text-lg">person_add</span> Re-Assign
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
                <button id="closeEditModal" type="button" class="absolute top-4 right-4 w-9 h-9 rounded-xl flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--text-dark)] hover:bg-slate-100 dark:hover:bg-slate-800 text-2xl transition-all cursor-pointer leading-none" aria-label="Close Modal">&times;</button>
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
                            <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Date Resolved</label>
                            <input type="text" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" readonly
                                class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                            <input type="hidden" name="date_resolved" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d') }}">
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label for="action_taken" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                Action Taken <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="action_taken" id="action_taken" rows="4" required placeholder="Describe the resolution or actions performed..."
                                class="w-full px-3.5 py-3 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs resize-y"></textarea>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label for="edit_photo" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                Update Photo Evidence <span class="text-slate-400 text-xs normal-case">(Optional)</span>
                            </label>
                            <input type="file" name="photo" id="edit_photo" accept="image/*"
                                class="w-full px-3 py-2 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-[var(--text-dark)] hover:file:bg-slate-200 cursor-pointer">
                            <div class="mt-3">
                                <img id="photo_preview" src="" alt="Uploaded Photo" class="hidden h-24 w-24 object-cover rounded-xl border border-[var(--border-light)] shadow-xs">
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-end gap-2.5 pt-4 mt-6 border-t border-[var(--border-light)]">
                        <button type="button" id="cancelEditStatusModal"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 h-11 px-5 rounded-xl font-semibold text-sm text-[var(--text-muted)] hover:text-[var(--text-dark)] bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 h-11 px-6 rounded-xl font-semibold text-sm text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 shadow-sm shadow-indigo-600/20 transition-all cursor-pointer">
                            <span class="material-symbols-outlined text-lg">save</span> Save Updates
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.body;

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
                    if (checkbox.checked) startAutoReload();
                    else stopAutoReload();
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

            // ======= Shared Modal Functions =======
            function openModal(modal) {
                modal.classList.remove('hidden');
                body.classList.add('overflow-hidden');
            }

            function closeModal(modal) {
                modal.classList.add('hidden');
                body.classList.remove('overflow-hidden');
            }

            const itMapping = @json($it_mapping ?? []);

            // ======= ADD TICKET MODAL =======
            const addModal = document.getElementById('ticketModal');
            if (addModal) {
                const openAddBtn = document.getElementById('openModal');
                const closeAddBtn = document.getElementById('closeModal');
                const cancelAddBtn = document.getElementById('cancelAddModal');

                if (openAddBtn) openAddBtn.addEventListener('click', () => openModal(addModal));
                
                const closeAddFunc = () => closeModal(addModal);
                if (closeAddBtn) closeAddBtn.addEventListener('click', closeAddFunc);
                if (cancelAddBtn) cancelAddBtn.addEventListener('click', closeAddFunc);
                addModal.addEventListener('click', e => { if (e.target === addModal) closeAddFunc(); });

                const regionSelectAdd = addModal.querySelector('#it_area_add');
                const personnelSelectAdd = addModal.querySelector('#it_personnel_add');
                const emailInputAdd = addModal.querySelector('#it_email_add');

                if (regionSelectAdd && personnelSelectAdd && emailInputAdd) {
                    regionSelectAdd.addEventListener('change', function () {
                        personnelSelectAdd.innerHTML = '<option disabled selected value="">Select Personnel</option>';
                        emailInputAdd.value = '';
                        (itMapping[this.value] || []).forEach(p => {
                            const opt = document.createElement('option');
                            opt.value = p.name;
                            opt.text = p.name;
                            personnelSelectAdd.appendChild(opt);
                        });
                    });
                    personnelSelectAdd.addEventListener('change', function () {
                        const p = itMapping[regionSelectAdd.value].find(x => x.name === this.value);
                        emailInputAdd.value = p ? p.email : '';
                    });
                }
            }

            // ======= ASSIGN TICKET MODAL =======
            const assignModal = document.getElementById('assignTicketModal');
            if (assignModal) {
                const closeAssignBtn = document.getElementById('closeAssignModal');
                const cancelAssignBtn = document.getElementById('cancelAssignModal');
                const regionSelectAssign = assignModal.querySelector('#it_area_assign');
                const assigneeSelect = assignModal.querySelector('#assigned_to');
                const assigneeEmail = assignModal.querySelector('#assigned_it_email');

                document.querySelectorAll('.open-assign-modal').forEach(btn => {
                    btn.addEventListener('click', function () {
                        if (this.dataset.status === 'Resolved') {
                            Swal.fire({ title: 'Ticket Locked', text: 'Resolved tickets cannot be re-assigned.', icon: 'warning', confirmButtonColor: '#4f46e5', background: getComputedStyle(document.body).getPropertyValue('--card-bg').trim(), color: getComputedStyle(document.body).getPropertyValue('--text-dark').trim() });
                            return;
                        }
                        if (this.dataset.status === 'Pending/Re-Assigned') {
                            Swal.fire({ title: 'Already Re-Assigned', text: 'Please follow up with the assigned personnel.', icon: 'info', confirmButtonColor: '#4f46e5', background: getComputedStyle(document.body).getPropertyValue('--card-bg').trim(), color: getComputedStyle(document.body).getPropertyValue('--text-dark').trim() });
                            return;
                        }

                        document.getElementById('assignTicketId').value = this.dataset.id;
                        regionSelectAssign.selectedIndex = 0;
                        assigneeSelect.innerHTML = '<option disabled selected value="">Select Personnel</option>';
                        assigneeEmail.value = '';
                        openModal(assignModal);
                    });
                });

                if (closeAssignBtn) closeAssignBtn.addEventListener('click', () => closeModal(assignModal));
                if (cancelAssignBtn) cancelAssignBtn.addEventListener('click', () => closeModal(assignModal));
                assignModal.addEventListener('click', e => { if (e.target === assignModal) closeModal(assignModal); });

                const assignForm = document.getElementById('assignForm');
                if (assignForm) {
                    assignForm.addEventListener('submit', function (e) {
                        const currentStatus = this.dataset.status?.trim() || '';
                        if (currentStatus === 'Resolved') {
                            e.preventDefault();
                            Swal.fire({ title: 'Ticket Locked', text: 'You cannot assign a resolved ticket.', icon: 'error', confirmButtonColor: '#ef4444', background: getComputedStyle(document.body).getPropertyValue('--card-bg').trim(), color: getComputedStyle(document.body).getPropertyValue('--text-dark').trim() });
                        }
                    });
                }

                if (regionSelectAssign) {
                    regionSelectAssign.addEventListener('change', function () {
                        assigneeSelect.innerHTML = '<option disabled selected value="">Select Personnel</option>';
                        assigneeEmail.value = '';
                        (itMapping[this.value] || []).forEach(p => {
                            const opt = document.createElement('option');
                            opt.value = p.name;
                            opt.text = p.name;
                            opt.setAttribute('data-email', p.email);
                            assigneeSelect.appendChild(opt);
                        });
                    });
                }

                if (assigneeSelect) {
                    assigneeSelect.addEventListener('change', function () {
                        const sel = this.options[this.selectedIndex];
                        assigneeEmail.value = sel.getAttribute('data-email') || '';
                    });
                }
            }

            // ======= EDIT TICKET MODAL =======
            const editModal = document.getElementById('editticketModal');
            if (editModal) {
                const closeEditBtn = document.getElementById('closeEditModal');
                const cancelEditBtn = document.getElementById('cancelEditStatusModal');
                const editForm = document.getElementById('editForm');
                const photoPreview = editModal.querySelector('#photo_preview');

                document.querySelectorAll('.open-edit-modal').forEach(btn => {
                    btn.addEventListener('click', function () {
                        if (this.dataset.status === 'Resolved') {
                            Swal.fire({ title: 'Ticket Locked', text: 'This ticket is already resolved.', icon: 'info', confirmButtonColor: '#4f46e5', background: getComputedStyle(document.body).getPropertyValue('--card-bg').trim(), color: getComputedStyle(document.body).getPropertyValue('--text-dark').trim() });
                            return;
                        }
                        
                        editForm.action = `/tickets/${this.dataset.id}`;
                        document.getElementById('edit_ticket_id').value = this.dataset.id;
                        document.getElementById('edit_status').value = this.dataset.status;
                        document.getElementById('action_taken').value = this.dataset.action_taken || '';

                        if (this.dataset.photo && photoPreview) {
                            photoPreview.src = `/storage/${this.dataset.photo}`;
                            photoPreview.classList.remove('hidden');
                        } else if (photoPreview) {
                            photoPreview.classList.add('hidden');
                        }
                        
                        editForm.dataset.originalStatus = this.dataset.status;
                        openModal(editModal);
                    });
                });

                if (closeEditBtn) closeEditBtn.addEventListener('click', () => closeModal(editModal));
                if (cancelEditBtn) cancelEditBtn.addEventListener('click', () => closeModal(editModal));
                editModal.addEventListener('click', e => { if (e.target === editModal) closeModal(editModal); });

                const statusSelect = document.getElementById('edit_status');
                if (statusSelect) {
                    statusSelect.addEventListener('change', function () {
                        if (this.value === 'Pending/Re-Assigned') {
                            Swal.fire({ title: 'Reminder', text: 'You must re-assign the ticket to another personnel.', icon: 'info', confirmButtonColor: '#4f46e5', background: getComputedStyle(document.body).getPropertyValue('--card-bg').trim(), color: getComputedStyle(document.body).getPropertyValue('--text-dark').trim() });
                        }
                    });
                }

                if (editForm) {
                    editForm.addEventListener('submit', function (e) {
                        const newStatus = statusSelect.value;
                        const originalStatus = this.dataset.originalStatus;

                        if (originalStatus === 'Pending' && newStatus === 'Pending') {
                            e.preventDefault();
                            Swal.fire({ title: 'Status Not Updated', text: 'Please update your status before submitting.', icon: 'warning', confirmButtonColor: '#4f46e5', background: getComputedStyle(document.body).getPropertyValue('--card-bg').trim(), color: getComputedStyle(document.body).getPropertyValue('--text-dark').trim() });
                        } else if (originalStatus === 'Pending' && newStatus === 'Pending/Re-Assigned') {
                            e.preventDefault();
                            Swal.fire({ title: 'Assignment Needed', text: 'Assign the ticket to another Personnel before submitting.', icon: 'warning', confirmButtonColor: '#4f46e5', background: getComputedStyle(document.body).getPropertyValue('--card-bg').trim(), color: getComputedStyle(document.body).getPropertyValue('--text-dark').trim() });
                        } else if (originalStatus === 'Pending/Re-Assigned' && (newStatus === 'Pending' || newStatus === 'Pending/Re-Assigned')) {
                            e.preventDefault();
                            Swal.fire({ title: 'Ticket Already Re-Assigned', text: 'Please follow up to the Re-Assigned Personnel.', icon: 'warning', confirmButtonColor: '#4f46e5', background: getComputedStyle(document.body).getPropertyValue('--card-bg').trim(), color: getComputedStyle(document.body).getPropertyValue('--text-dark').trim() });
                        }
                    });
                }
            }

            // === DELETE CONFIRMATION ALERT ===
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
                        cancelButtonText: 'Cancel',
                        background: getComputedStyle(document.body).getPropertyValue('--card-bg').trim(),
                        color: getComputedStyle(document.body).getPropertyValue('--text-dark').trim()
                    }).then(res => {
                        if (res.isConfirmed) document.getElementById('delete-form-' + id).submit();
                    });
                });
            });
            
            // Allow closing modals with Escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === "Escape") {
                    if(addModal && !addModal.classList.contains('hidden')) closeModal(addModal);
                    if(assignModal && !assignModal.classList.contains('hidden')) closeModal(assignModal);
                    if(editModal && !editModal.classList.contains('hidden')) closeModal(editModal);
                }
            });
        });
    </script>
</x-app-layout>
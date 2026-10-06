<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <div id="main-content" class="w-full">
        <div id="ticketsContent" class="space-y-6">

            {{-- Main Content Card --}}
            <div class="w-full bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-4 sm:p-6 lg:p-8">

                {{-- Header Section --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 pb-5 border-b border-[var(--border-subtle)]">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 via-yellow-500 to-orange-500 text-white flex items-center justify-center shadow-md shadow-amber-500/20 shrink-0">
                            <span class="material-symbols-outlined text-2xl">published_with_changes</span>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                                Re-Assigned Tickets
                            </h1>
                            <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                                Audit log and tracking of transferred or escalated tickets between technical personnel
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Action Toolbar & Search --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-6">
                    <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                        <!-- Auto-Reload Toggle -->
                        <label class="inline-flex items-center gap-2.5 h-11 px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] text-xs sm:text-sm font-semibold cursor-pointer select-none hover:text-[var(--text-dark)] hover:border-slate-300 dark:hover:border-slate-700 transition-all">
                            <input type="checkbox" id="autoReloadCheckbox" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0 cursor-pointer accent-indigo-600">
                            <span class="flex items-center gap-1.5 whitespace-nowrap">
                                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Auto-Reload (<span id="countdown" class="font-mono text-indigo-600 dark:text-indigo-400 font-bold">60</span>s)</span>
                            </span>
                        </label>
                    </div>

                    @canany(['search_reassigned_tickets', 'search_assignedtome_tickets'])
                    <div class="flex items-center justify-end gap-2 w-full sm:w-auto sm:ml-auto">
                        <form action="{{ route('reassigned_tickets.index') }}" method="GET" class="flex-1 min-w-0 sm:w-72 md:w-80 sm:flex-initial m-0">
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
                            <a href="{{ route('reassigned_tickets.index') }}" 
                               class="inline-flex items-center justify-center gap-1 h-11 px-3 rounded-xl border border-[var(--border-light)] bg-[var(--card-bg)] text-xs font-semibold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all whitespace-nowrap shadow-xs"
                               title="Clear search">
                                <span class="material-symbols-outlined text-base">close</span>
                                <span class="hidden xs:inline">Clear</span>
                            </a>
                        @endif
                    </div>
                    @endcanany
                </div>

                {{-- Data Table --}}
                <div class="w-full overflow-hidden rounded-2xl border border-[var(--border-light)] shadow-xs bg-[var(--card-bg)]">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-[var(--text-dark)] border-collapse">
                            <thead class="bg-slate-50/90 dark:bg-slate-800/80 text-[var(--text-muted)] text-xs uppercase font-bold tracking-wider border-b border-[var(--border-light)]">
                                <tr>
                                    <th class="py-3.5 px-4 text-center font-bold">Tracking ID</th>
                                    <th class="py-3.5 px-4 font-bold">Requested By</th>
                                    <th class="py-3.5 px-4 font-bold min-w-[180px]">Request Details</th>
                                    <th class="py-3.5 px-4 font-bold">Re-Assigned By</th>
                                    <th class="py-3.5 px-4 font-bold">Previous Assigned</th>
                                    <th class="py-3.5 px-4 font-bold">Re-Assigned To</th>
                                    <th class="py-3.5 px-4 font-bold min-w-[180px]">Notes</th>
                                    <th class="py-3.5 px-4 font-bold whitespace-nowrap">Date Re-Assigned</th>
                                    <th class="py-3.5 px-4 text-center font-bold">Priority</th>
                                    <th class="py-3.5 px-4 text-center font-bold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--border-subtle)]">
                                @forelse ($tickets as $ticket)
                                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                                        <!-- Tracking ID -->
                                        <td class="py-4 px-4 text-center whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/60">
                                                {{ $ticket->ticket_number ?? 'N/A' }}
                                            </span>
                                        </td>

                                        <!-- Requested By -->
                                        <td class="py-4 px-4 whitespace-nowrap font-medium text-[var(--text-dark)]">
                                            <div class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-base text-slate-400">person</span>
                                                <span>{{ $ticket->requested_by ?? 'N/A' }}</span>
                                            </div>
                                        </td>

                                        <!-- Request Details -->
                                        <td class="py-4 px-4 max-w-xs">
                                            <p class="truncate text-xs sm:text-sm text-[var(--text-muted)] hover:text-[var(--text-dark)] transition-colors cursor-default m-0" title="{{ $ticket->request ?? '' }}">
                                                {{ $ticket->request ?? 'No details provided' }}
                                            </p>
                                        </td>

                                        <!-- Re-Assigned By -->
                                        <td class="py-4 px-4 whitespace-nowrap text-xs text-[var(--text-muted)]">
                                            <span class="inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm text-slate-400">assignment_ind</span>
                                                <span>{{ $ticket->assigned_by ?? 'N/A' }}</span>
                                            </span>
                                        </td>

                                        <!-- Previous Assigned -->
                                        <td class="py-4 px-4 whitespace-nowrap text-xs text-slate-500 line-through">
                                            {{ $ticket->previous_assigned ?? 'N/A' }}
                                        </td>

                                        <!-- Re-Assigned To -->
                                        <td class="py-4 px-4 whitespace-nowrap text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                                            <div class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-base">arrow_forward</span>
                                                <span>{{ $ticket->re_assigned_to ?? 'N/A' }}</span>
                                            </div>
                                        </td>

                                        <!-- Notes -->
                                        <td class="py-4 px-4 max-w-xs">
                                            <p class="truncate text-xs sm:text-sm text-[var(--text-muted)] italic m-0" title="{{ $ticket->notes ?? '' }}">
                                                {{ $ticket->notes ?? '—' }}
                                            </p>
                                        </td>

                                        <!-- Date Re-Assigned -->
                                        <td class="py-4 px-4 whitespace-nowrap text-xs text-[var(--text-muted)] font-mono">
                                            {{ $ticket->re_assigned_at
                                                ? \Carbon\Carbon::parse($ticket->re_assigned_at)->format('M d, Y h:i A')
                                                : 'N/A' }}
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
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="py-12 px-4 text-center">
                                            <div class="flex flex-col items-center justify-center gap-2 text-[var(--text-muted)]">
                                                <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600">published_with_changes</span>
                                                <p class="text-base font-semibold m-0">No Re-Assigned Tickets found</p>
                                                <p class="text-xs m-0">Transferred and reassigned tickets will be listed here.</p>
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
<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    
    @can('view_ticket_details_myrequested_tickets')
    <div id="main-content" class="w-full">
        <div class="max-w-5xl mx-auto space-y-6">

            {{-- Main View Container --}}
            <div class="bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-4 sm:p-6 lg:p-8">

                {{-- Header Title Banner --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-[var(--border-light)]">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-600 text-white flex items-center justify-center shadow-md shadow-indigo-500/20 shrink-0">
                            <span class="material-symbols-outlined text-2xl">confirmation_number</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                                    Ticket #{{ $ticket->ticket_number }}
                                </h1>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/60">
                                    My Request Details
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                                Tracking status, details, and technical resolution proof for your submitted ticket
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('myrequested_tickets.index') }}" id="close"
                        class="inline-flex items-center justify-center gap-1.5 h-10 px-4 rounded-xl text-xs sm:text-sm font-semibold bg-slate-100 dark:bg-slate-800 text-[var(--text-muted)] hover:text-[var(--text-dark)] hover:bg-slate-200 dark:hover:bg-slate-700 active:scale-95 transition-all cursor-pointer">
                        <span class="material-symbols-outlined text-lg">arrow_back</span>
                        <span>Back to My Tickets</span>
                    </a>
                </div>

                {{-- Status & Priority Summary Bar --}}
                <div class="flex flex-wrap items-center justify-between gap-4 p-4 mt-6 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-[var(--border-light)]">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">Status:</span>
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
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">Priority:</span>
                        @php
                            $priority = trim($ticket->priority);
                        @endphp
                        @if($priority === 'Critical')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wide bg-red-600 text-white shadow-xs border border-red-700 animate-[pulseCritical_2s_infinite]">
                                <span class="material-symbols-outlined text-xs">warning</span> Critical
                            </span>
                        @elseif($priority === 'High')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800">
                                High
                            </span>
                        @elseif($priority === 'Medium')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800">
                                Medium
                            </span>
                        @elseif($priority === 'Low')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800">
                                Low
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700">
                                {{ $priority ?: 'Default' }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Card 1: Client & Request Information --}}
                <div class="mt-8 rounded-2xl p-5 sm:p-6 border border-[var(--border-light)] bg-[var(--card-bg)] shadow-2xs">
                    <h2 class="text-base sm:text-lg font-bold text-[var(--text-dark)] m-0 mb-5 flex items-center gap-2 pb-3 border-b border-[var(--border-subtle)]">
                        <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400">person</span>
                        <span>Client Information</span>
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Requested By</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $ticket->firstname }} {{ $ticket->middle_initial }} {{ $ticket->lastname }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Section / Division</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $ticket->division ?: 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Device Equipment</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $ticket->device ?: 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Technical Service</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $ticket->service ?: 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Issue Overview & Client Attached Screenshot Evidence --}}
                <div class="mt-6 rounded-2xl p-5 sm:p-6 border border-[var(--border-light)] bg-[var(--card-bg)] shadow-2xs">
                    <h2 class="text-base sm:text-lg font-bold text-[var(--text-dark)] m-0 mb-5 flex items-center gap-2 pb-3 border-b border-[var(--border-subtle)]">
                        <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">report_problem</span>
                        <span>Issue Overview & Initial Evidence</span>
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Request Description</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4 whitespace-pre-wrap leading-relaxed">
                                {{ $ticket->request }}
                            </div>
                        </div>

                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-2">Screenshot / Issue Attachment</span>
                            @if(!empty($ticketIssuePhotoEvidenceDataUri))
                                <div class="p-4 rounded-xl border-2 border-dashed border-[var(--border-light)] bg-slate-50/50 dark:bg-slate-800/30 flex flex-col items-center justify-center gap-3">
                                    <div class="relative group cursor-pointer rounded-xl overflow-hidden border border-[var(--border-light)] bg-[var(--card-bg)] shadow-sm max-w-md transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5"
                                         onclick="openImageModal('{{ $ticketIssuePhotoEvidenceDataUri }}', 'Client Initial Issue Screenshot')">
                                        <img src="{{ $ticketIssuePhotoEvidenceDataUri }}" alt="Client Issue Evidence" class="w-full max-h-64 object-contain mx-auto block group-hover:scale-102 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white gap-2 font-medium text-xs">
                                            <span class="material-symbols-outlined text-xl">zoom_in</span>
                                            <span>Click to preview</span>
                                        </div>
                                    </div>
                                    <button type="button" 
                                            class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 transition-colors cursor-pointer"
                                            onclick="openImageModal('{{ $ticketIssuePhotoEvidenceDataUri }}', 'Client Initial Issue Screenshot')">
                                        <span class="material-symbols-outlined text-base">fullscreen</span>
                                        <span>View FullScreen</span>
                                    </button>
                                </div>
                            @else
                                <div class="flex items-center gap-2 p-4 rounded-xl border border-dashed border-[var(--border-light)] text-xs text-[var(--text-muted)] italic">
                                    <span class="material-symbols-outlined text-base text-[var(--text-muted)]">image_not_supported</span>
                                    <span>No photo evidence was attached by the client for this issue.</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Card 3: Assignment & IT Resolution Details --}}
                <div class="mt-6 rounded-2xl p-5 sm:p-6 border border-[var(--border-light)] bg-[var(--card-bg)] shadow-2xs">
                    <h2 class="text-base sm:text-lg font-bold text-[var(--text-dark)] m-0 mb-5 flex items-center gap-2 pb-3 border-b border-[var(--border-subtle)]">
                        <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">check_circle</span>
                        <span>Assignment & IT Resolution Details</span>
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Assigned IT Personnel</span>
                                <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                    {{ $ticket->it_personnel ?: 'Unassigned' }}
                                </div>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Date & Time Created</span>
                                <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                    {{ \Carbon\Carbon::parse($ticket->date_created)->format('F d, Y - h:i A') }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Date & Time Resolved</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                @if($ticket->date_resolved)
                                    <span class="text-emerald-600 dark:text-emerald-400 font-semibold">
                                        {{ \Carbon\Carbon::parse($ticket->date_resolved)->format('F d, Y - h:i A') }}
                                    </span>
                                @else
                                    <span class="text-rose-500 italic font-medium">Not Resolved Yet</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Action Taken</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4 whitespace-pre-wrap leading-relaxed">
                                {{ $ticket->action_taken ?: 'No resolution actions recorded yet.' }}
                            </div>
                        </div>

                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-2">IT Resolution Link Evidence</span>
                            @if(!empty($ticket->link_evidence))
                                <a href="{{ $ticket->link_evidence }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-900/60 transition-colors break-all">
                                    <span class="material-symbols-outlined text-base">link</span>
                                    <span>{{ $ticket->link_evidence }}</span>
                                    <span class="material-symbols-outlined text-xs">open_in_new</span>
                                </a>
                            @else
                                <div class="flex items-center gap-2 p-3 rounded-xl border border-dashed border-[var(--border-light)] text-xs text-[var(--text-muted)] italic">
                                    <span class="material-symbols-outlined text-base">link_off</span>
                                    <span>No external link evidence submitted by IT Personnel.</span>
                                </div>
                            @endif
                        </div>

                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-2">IT Resolution Photo Evidence</span>
                            @if(!empty($resolvedTicketPhotoEvidenceDataUri))
                                <div class="p-4 rounded-xl border-2 border-dashed border-[var(--border-light)] bg-slate-50/50 dark:bg-slate-800/30 flex flex-col items-center justify-center gap-3">
                                    <div class="relative group cursor-pointer rounded-xl overflow-hidden border border-[var(--border-light)] bg-[var(--card-bg)] shadow-sm max-w-md transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5"
                                         onclick="openImageModal('{{ $resolvedTicketPhotoEvidenceDataUri }}', 'IT Resolution Photo Evidence')">
                                        <img src="{{ $resolvedTicketPhotoEvidenceDataUri }}" alt="IT Resolution Evidence" class="w-full max-h-64 object-contain mx-auto block group-hover:scale-102 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white gap-2 font-medium text-xs">
                                            <span class="material-symbols-outlined text-xl">zoom_in</span>
                                            <span>Click to preview</span>
                                        </div>
                                    </div>
                                    <button type="button" 
                                            class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 transition-colors cursor-pointer"
                                            onclick="openImageModal('{{ $resolvedTicketPhotoEvidenceDataUri }}', 'IT Resolution Photo Evidence')">
                                        <span class="material-symbols-outlined text-base">fullscreen</span>
                                        <span>View FullScreen</span>
                                    </button>
                                </div>
                            @else
                                <div class="flex items-center gap-2 p-4 rounded-xl border border-dashed border-[var(--border-light)] text-xs text-[var(--text-muted)] italic">
                                    <span class="material-symbols-outlined text-base text-[var(--text-muted)]">image_not_supported</span>
                                    <span>No photo proof attached for ticket resolution.</span>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Reusable Lightbox Fullscreen Modal --}}
    <div id="imageModal" 
         class="fixed inset-0 z-50 backdrop-blur-md bg-slate-950/90 hidden [&.active]:flex flex-col items-center justify-center p-4 transition-all duration-300"
         onclick="if(event.target === this) closeImageModal()">
        
        <div class="absolute top-4 right-4 sm:top-6 sm:right-6 flex items-center gap-2 z-10">
            <button type="button" 
                    class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors cursor-pointer" 
                    onclick="toggleNativeFullscreen()" 
                    title="Toggle Fullscreen">
                <span class="material-symbols-outlined text-xl">fullscreen</span>
            </button>
            <button type="button" 
                    class="w-10 h-10 rounded-xl bg-white/10 hover:bg-rose-600 text-white flex items-center justify-center transition-colors cursor-pointer" 
                    onclick="closeImageModal()" 
                    title="Close">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        <div class="max-w-4xl max-h-[80vh] flex flex-col items-center">
            <img class="max-w-full max-h-[75vh] object-contain rounded-xl shadow-2xl border border-white/10" 
                 id="modalImg" 
                 src="" 
                 alt="Proof Evidence Preview">
            <div class="text-white text-sm sm:text-base font-semibold mt-4 text-center px-4" id="modalCaption"></div>
        </div>
    </div>

    {{-- Modal Scripts --}}
    <script>
        function openImageModal(imgSrc, captionText) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImg');
            const caption = document.getElementById('modalCaption');
            
            modalImg.src = imgSrc;
            caption.textContent = captionText || 'Evidence Photo Preview';
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('active');
            }, 10);
            
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('active');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
            
            document.body.style.overflow = 'auto';
            if (document.fullscreenElement) {
                document.exitFullscreen();
            }
        }

        function toggleNativeFullscreen() {
            const img = document.getElementById('modalImg');
            if (!document.fullscreenElement) {
                if (img.requestFullscreen) { img.requestFullscreen(); }
                else if (img.webkitRequestFullscreen) { img.webkitRequestFullscreen(); }
                else if (img.msRequestFullscreen) { img.msRequestFullscreen(); }
            } else {
                if (document.exitFullscreen) { document.exitFullscreen(); }
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape" && document.getElementById('imageModal') && !document.getElementById('imageModal').classList.contains('hidden')) {
                closeImageModal();
            }
        });
    </script>
    @endcan
</x-app-layout>
<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    
    @can('view_ticket_details_myrequested_tickets')
    <style>
        /* CSS Variables for Light & Dark Mode Theming */
        :root {
            /* Light Mode (Default) */
            --bg-page: #f8fafc;
            --bg-card: #ffffff;
            --text-heading: #0f172a;
            --text-main: #334155;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --border-dashed: #cbd5e1;
            --bg-field: #f1f5f9;
            --btn-close-bg: #f1f5f9;
            --btn-close-hover: #e2e8f0;
            --accent-primary: #2563eb;
            --link-bg: #eff6ff;
            --link-border: #bfdbfe;
            --link-text: #1d4ed8;
            --link-hover: #dbeafe;
            
            /* Status Badges */
            --bg-res: #dcfce7; --text-res: #15803d; --border-res: #bbf7d0;
            --bg-pen: #fef3c7; --text-pen: #b45309; --border-pen: #fde68a;
            --bg-rea: #e0e7ff; --text-rea: #4338ca; --border-rea: #c7d2fe;
            --bg-def: #f1f5f9; --text-def: #475569; --border-def: #e2e8f0;

            /* Priority Badges */
            --bg-low: #e0f2fe; --text-low: #0369a1; --border-low: #bae6fd;
            --bg-med: #fef9c3; --text-med: #a16207; --border-med: #fef08a;
            --bg-hig: #ffedd5; --text-hig: #c2410c; --border-hig: #fed7aa;
            --bg-cri: #ffe4e6; --text-cri: #be123c; --border-cri: #fecdd3;
        }

        /* Dark Mode Definitions */
        .dark, [data-theme="dark"] {
            --bg-page: #0f172a;
            --bg-card: #1e293b;
            --text-heading: #f8fafc;
            --text-main: #cbd5e1;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --border-dashed: #475569;
            --bg-field: #0f172a;
            --btn-close-bg: #334155;
            --btn-close-hover: #475569;
            --accent-primary: #60a5fa;
            --link-bg: #1e3a8a;
            --link-border: #1e40af;
            --link-text: #93c5fd;
            --link-hover: #1d4ed8;

            /* Status Badges Dark */
            --bg-res: rgba(21, 128, 61, 0.2); --text-res: #4ade80; --border-res: #166534;
            --bg-pen: rgba(180, 83, 9, 0.2); --text-pen: #fbbf24; --border-pen: #78350f;
            --bg-rea: rgba(67, 56, 202, 0.2); --text-rea: #818cf8; --border-rea: #3730a3;
            --bg-def: rgba(71, 85, 105, 0.2); --text-def: #94a3b8; --border-def: #334155;

            /* Priority Badges Dark */
            --bg-low: rgba(3, 105, 161, 0.2); --text-low: #7dd3fc; --border-low: #075985;
            --bg-med: rgba(161, 98, 7, 0.2); --text-med: #fde047; --border-med: #713f12;
            --bg-hig: rgba(194, 65, 12, 0.2); --text-hig: #fdba74; --border-hig: #7c2d12;
            --bg-cri: rgba(190, 18, 60, 0.2); --text-cri: #fca5a5; --border-cri: #881337;
        }

        /* Fallback for OS-level Dark Mode Preference if manual class isn't toggled */
        @media (prefers-color-scheme: dark) {
            :root:not(.light) {
                --bg-page: #0f172a; --bg-card: #1e293b; --text-heading: #f8fafc; --text-main: #cbd5e1; --text-muted: #94a3b8; --border-color: #334155; --border-dashed: #475569; --bg-field: #0f172a; --btn-close-bg: #334155; --btn-close-hover: #475569; --accent-primary: #60a5fa; --link-bg: #1e3a8a; --link-border: #1e40af; --link-text: #93c5fd; --link-hover: #1d4ed8; --bg-res: rgba(21, 128, 61, 0.2); --text-res: #4ade80; --border-res: #166534; --bg-pen: rgba(180, 83, 9, 0.2); --text-pen: #fbbf24; --border-pen: #78350f; --bg-rea: rgba(67, 56, 202, 0.2); --text-rea: #818cf8; --border-rea: #3730a3; --bg-def: rgba(71, 85, 105, 0.2); --text-def: #94a3b8; --border-def: #334155; --bg-low: rgba(3, 105, 161, 0.2); --text-low: #7dd3fc; --border-low: #075985; --bg-med: rgba(161, 98, 7, 0.2); --text-med: #fde047; --border-med: #713f12; --bg-hig: rgba(194, 65, 12, 0.2); --text-hig: #fdba74; --border-hig: #7c2d12; --bg-cri: rgba(190, 18, 60, 0.2); --text-cri: #fca5a5; --border-cri: #881337;
            }
        }

        /* Global Box Sizing & Font Fix */
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; transition: background-color 0.3s ease, color 0.3s ease; }

        .view-wrapper { background-color: var(--bg-page); border-radius: 12px; padding: 2.5rem; max-width: 1100px; margin: 2rem auto; position: relative; font-family: 'Inter', sans-serif; color: var(--text-main); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); transition: background-color 0.3s ease; }
        .view-card { background: var(--bg-card); border-radius: 12px; padding: 1.75rem; border: 1px solid var(--border-color); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 1.5rem; transition: box-shadow 0.2s ease, background-color 0.3s ease, border-color 0.3s ease; }
        .view-card:hover { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.08); }
        
        .header-banner { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid var(--border-color); padding-bottom: 1.25rem; margin-bottom: 1.5rem; position: relative; transition: border-color 0.3s ease; }
        .report-title { font-size: 1.65rem; font-weight: 800; color: var(--text-heading); display: flex; align-items: center; gap: 0.75rem; }
        .close-btn { color: var(--text-muted); font-size: 1.5rem; background: var(--btn-close-bg); border: none; cursor: pointer; transition: all 0.2s; border-radius: 50%; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; }
        .close-btn:hover { color: var(--text-heading); background-color: var(--btn-close-hover); transform: scale(1.05); }
        
        .section-title { font-size: 1.15rem; font-weight: 700; color: var(--text-heading); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; border-left: 4px solid var(--accent-primary); padding-left: 0.75rem; }
        
        /* Fixed Grid Layouts */
        .details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem; }
        .details-grid-col-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; margin-bottom: 1.25rem; }
        .detail-group { display: flex; flex-direction: column; gap: 0.4rem; }
        .detail-group.full-width { grid-column: 1 / -1; }
        
        .detail-label { font-size: 0.85rem; font-weight: 700; color: var(--text-muted); letter-spacing: 0.05em; }
        .detail-value { font-size: 0.95rem; font-weight: 500; color: var(--text-main); background-color: var(--bg-field); padding: 0.85rem 1rem; border-radius: 8px; border: 1px solid var(--border-color); word-break: break-word; transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease; }
        .detail-value.full-width { white-space: pre-wrap; line-height: 1.6; }
        
        /* Status & Priority Badges */
        .badge { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.85rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 700; width: max-content; transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease; }
        .status-resolved { background-color: var(--bg-res); color: var(--text-res); border: 1px solid var(--border-res); }
        .status-pending { background-color: var(--bg-pen); color: var(--text-pen); border: 1px solid var(--border-pen); }
        .status-reassigned { background-color: var(--bg-rea); color: var(--text-rea); border: 1px solid var(--border-rea); }
        .status-default { background-color: var(--bg-def); color: var(--text-def); border: 1px solid var(--border-def); }
        
        .priority-low { background-color: var(--bg-low); color: var(--text-low); border: 1px solid var(--border-low); }
        .priority-medium { background-color: var(--bg-med); color: var(--text-med); border: 1px solid var(--border-med); }
        .priority-high { background-color: var(--bg-hig); color: var(--text-hig); border: 1px solid var(--border-hig); }
        .priority-critical { background-color: var(--bg-cri); color: var(--text-cri); border: 1px solid var(--border-cri); }
        .priority-default { background-color: var(--bg-def); color: var(--text-def); border: 1px solid var(--border-def); }

        /* Media / Evidence Section Styling */
        .evidence-card { border: 2px dashed var(--border-dashed); border-radius: 10px; padding: 1.25rem; background-color: var(--bg-page); text-align: center; transition: background-color 0.3s ease, border-color 0.3s ease; }
        .evidence-img-container { position: relative; cursor: pointer; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-dashed); background: var(--bg-card); display: inline-block; max-width: 100%; transition: transform 0.2s, box-shadow 0.2s, border-color 0.3s ease; }
        .evidence-img-container:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        .evidence-img { max-width: 100%; height: auto; max-height: 280px; object-fit: contain; display: block; }
        
        .click-hint { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; color: var(--link-text); font-weight: 600; cursor: pointer; background: var(--link-bg); padding: 0.6rem 1.25rem; border-radius: 8px; border: 1px solid var(--link-border); margin-top: 1rem; transition: all 0.2s; }
        .click-hint:hover { background: var(--link-hover); transform: translateY(-1px); }
        
        .link-evidence-box { display: flex; align-items: center; gap: 0.5rem; background: var(--link-bg); border: 1px solid var(--link-border); color: var(--link-text); padding: 0.85rem 1rem; border-radius: 8px; font-weight: 600; word-break: break-all; text-decoration: none; transition: all 0.2s; }
        .link-evidence-box:hover { background: var(--link-hover); text-decoration: underline; }

        /* Fullscreen Lightbox Modal */
        .img-modal { display: none; position: fixed; z-index: 99999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(15, 23, 42, 0.95); align-items: center; justify-content: center; backdrop-filter: blur(8px); flex-direction: column; opacity: 0; transition: opacity 0.3s ease; }
        .img-modal.active { display: flex; opacity: 1; }
        .modal-content { max-width: 90vw; max-height: 80vh; object-fit: contain; border-radius: 8px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
        .modal-caption { color: #f8fafc; font-size: 1.1rem; font-weight: 600; margin-top: 1.5rem; text-align: center; }
        
        .modal-controls { position: absolute; top: 20px; right: 25px; display: flex; gap: 1rem; z-index: 100000; }
        .modal-btn { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #ffffff; border-radius: 8px; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; }
        .modal-btn:hover { background: rgba(255,255,255,0.25); }
        .modal-btn.close:hover { background: #ef4444; border-color: #ef4444; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .view-wrapper { padding: 1.25rem; margin: 1rem; border-radius: 8px; }
            .view-card { padding: 1.25rem; }
            .header-banner { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .close-btn { position: absolute; top: 0; right: 0; }
            .details-grid-col-2 { grid-template-columns: 1fr; margin-bottom: 0; }
            .details-grid { grid-template-columns: 1fr; }
            .modal-controls { top: 15px; right: 15px; gap: 0.5rem; }
        }
    </style>

    <div id="main-content" class="page-wrapper">
        <div class="view-wrapper">
            
            <!-- Header Title Banner -->
            <div class="header-banner">
                <div class="report-title" style="font-size: 1.75rem; color: var(--accent-primary);">
                    Ticket Number: {{ $ticket->ticket_number }}
                </div>
                <button id="close" onclick="window.location.href='{{ route('myrequested_tickets.index') }}'" class="close-btn" aria-label="Close details" title="Close">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Card 1: Client & Request Meta Information -->
            <div class="view-card">
                <h3 class="section-title">
                    <span class="material-symbols-outlined">person</span>
                    Client Details
                </h3>
                <div class="details-grid-col-2">
                    <div class="detail-group">
                        <span class="detail-label">Requested By</span>
                        <span class="detail-value">{{ $ticket->firstname }} {{ $ticket->middle_initial }} {{ $ticket->lastname }}</span>
                    </div>
                    <div class="detail-group">
                        <span class="detail-label">Section / Division</span>
                        <span class="detail-value">{{ $ticket->division ?: 'N/A' }}</span>
                    </div>
                </div>

                <div class="details-grid-col-2">
                    <div class="detail-group">
                        <span class="detail-label">Device Equipment</span>
                        <span class="detail-value">{{ $ticket->device ?: 'N/A' }}</span>
                    </div>
                    <div class="detail-group">
                        <span class="detail-label">Technical Service</span>
                        <span class="detail-value">{{ $ticket->service ?: 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Issue Overview & Client's Screenshot Evidence -->
            <div class="view-card">
                <h3 class="section-title">
                    <span class="material-symbols-outlined">report_problem</span>
                    Issue Overview & Initial Evidence
                </h3>
                <div class="details-grid">
                    
                    <div class="detail-group full-width">
                        <span class="detail-label">Request Description</span>
                        <span class="detail-value full-width">{{ $ticket->request }}</span>
                    </div>

                    <div class="detail-group">
                        <span class="detail-label">Priority Level</span>
                        <div>
                            @php
                                $priorityBadge = match(trim($ticket->priority)) {
                                    'Low'      => 'priority-low',
                                    'Medium'   => 'priority-medium',
                                    'High'     => 'priority-high',
                                    'Critical' => 'priority-critical',
                                    default    => 'priority-default',
                                };
                            @endphp
                            <span class="badge {{ $priorityBadge }}">
                                <span class="material-symbols-outlined" style="font-size: 1.1rem;">flag</span>
                                {{ $ticket->priority }}
                            </span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <span class="detail-label">Current Status</span>
                        <div>
                            @php
                                $statusBadge = match(trim($ticket->status)) {
                                    'Resolved'             => 'status-resolved',
                                    'Pending'              => 'status-pending',
                                    'Pending/Re-Assigned'  => 'status-reassigned',
                                    default                => 'status-default',
                                };
                            @endphp
                            <span class="badge {{ $statusBadge }}">
                                <span class="material-symbols-outlined" style="font-size: 1.1rem;">info</span>
                                {{ $ticket->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Client Attached Issue Photo Evidence -->
                    <div class="detail-group full-width" style="margin-top: 0.5rem;">
                        <span class="detail-label" style="margin-bottom: 0.35rem;">Screenshot / Issue Attachment</span>
                        @if(!empty($ticketIssuePhotoEvidenceDataUri))
                            <div class="evidence-card">
                                <div class="evidence-img-container" onclick="openImageModal('{{ $ticketIssuePhotoEvidenceDataUri }}', 'Client Initial Issue Screenshot')">
                                    <img src="{{ $ticketIssuePhotoEvidenceDataUri }}" alt="Client Issue Evidence" class="evidence-img">
                                </div>
                                <div>
                                    <button type="button" class="click-hint" onclick="openImageModal('{{ $ticketIssuePhotoEvidenceDataUri }}', 'Client Initial Issue Screenshot')">
                                        <span class="material-symbols-outlined">zoom_in</span>
                                        View FullScreen
                                    </button>
                                </div>
                            </div>
                        @else
                            <span class="detail-value" style="color: var(--text-muted); font-style: italic; background: transparent; border-style: dashed;">No photo evidence was attached by the client for this issue.</span>
                        @endif
                    </div>

                </div>
            </div>

            <!-- Card 3: IT Personnel Resolution Details & Resolution Proof -->
            <div class="view-card">
                <h3 class="section-title">
                    <span class="material-symbols-outlined">check_circle</span>
                    Assignment & IT Resolution Details
                </h3>
                <div class="details-grid">
                    
                    <div class="detail-group full-width">
                        <span class="detail-label">Assigned IT Personnel</span>
                        <span class="detail-value">{{ $ticket->it_personnel ?: 'Unassigned' }}</span>
                    </div>

                    <div class="detail-group full-width">
                        <span class="detail-label">Action Taken</span>
                        <span class="detail-value full-width">{{ $ticket->action_taken ?: 'No resolution actions recorded yet.' }}</span>
                    </div>

                    <div class="details-grid-col-2" style="grid-column: 1 / -1; margin-bottom: 0;">
                        <div class="detail-group">
                            <span class="detail-label">Date & Time Created</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($ticket->date_created)->format('F d, Y - h:i A') }}</span>
                        </div>

                        <div class="detail-group">
                            <span class="detail-label">Date & Time Resolved</span>
                            <span class="detail-value">
                                @if($ticket->date_resolved)
                                    {{ \Carbon\Carbon::parse($ticket->date_resolved)->format('F d, Y - h:i A') }}
                                @else
                                    <span style="color: var(--text-cri); font-style: italic; font-weight: 600;">Not Resolved Yet</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- RESOLVED TICKET: Attached Link Evidence -->
                    <div class="detail-group full-width" style="margin-top: 0.5rem;">
                        <span class="detail-label">IT Resolution Link Evidence</span>
                        @if(!empty($ticket->link_evidence))
                            <a href="{{ $ticket->link_evidence }}" target="_blank" class="link-evidence-box">
                                <span class="material-symbols-outlined">link</span>
                                {{ $ticket->link_evidence }}
                            </a>
                        @else
                            <span class="detail-value" style="color: var(--text-muted); font-style: italic; background: transparent; border-style: dashed;">No external link evidence submitted by IT Personnel.</span>
                        @endif
                    </div>

                    <!-- RESOLVED TICKET: Attached Photo Evidence -->
                    <div class="detail-group full-width">
                        <span class="detail-label" style="margin-bottom: 0.35rem;">IT Resolution Photo Evidence</span>
                        @if(!empty($resolvedTicketPhotoEvidenceDataUri))
                            <div class="evidence-card">
                                <div class="evidence-img-container" onclick="openImageModal('{{ $resolvedTicketPhotoEvidenceDataUri }}', 'IT Resolution Photo Evidence')">
                                    <img src="{{ $resolvedTicketPhotoEvidenceDataUri }}" alt="IT Resolution Evidence" class="evidence-img">
                                </div>
                                <div>
                                    <button type="button" class="click-hint" onclick="openImageModal('{{ $resolvedTicketPhotoEvidenceDataUri }}', 'IT Resolution Photo Evidence')">
                                        <span class="material-symbols-outlined">zoom_in</span>
                                        View FullScreen
                                    </button>
                                </div>
                            </div>
                        @else
                            <span class="detail-value" style="color: var(--text-muted); font-style: italic; background: transparent; border-style: dashed;">No photo proof attached for ticket resolution.</span>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Reusable Lightbox Fullscreen Modal -->
    <div id="imageModal" class="img-modal">
        <div class="modal-controls">
            <button type="button" class="modal-btn" onclick="toggleNativeFullscreen()" title="Toggle Fullscreen">
                <span class="material-symbols-outlined">fullscreen</span>
            </button>
            <button type="button" class="modal-btn close" onclick="closeImageModal()" title="Close">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <img class="modal-content" id="modalImg" src="" alt="Proof Evidence Preview">
        <div class="modal-caption" id="modalCaption"></div>
    </div>

    <!-- Modal Scripts -->
    <script>
        function openImageModal(imgSrc, captionText) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImg');
            const caption = document.getElementById('modalCaption');
            
            modalImg.src = imgSrc;
            caption.textContent = captionText || 'Evidence Photo Preview';
            
            // Allow display block to apply before adding active for opacity transition
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('active');
            }, 10);
            
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('active');
            
            // Wait for transition to finish before hiding
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
            
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

        // Close modal when clicking backdrop
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal on Escape key press
        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape" && document.getElementById('imageModal').classList.contains('active')) {
                closeImageModal();
            }
        });
    </script>
    @endcan
</x-app-layout>
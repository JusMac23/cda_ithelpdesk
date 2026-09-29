<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    
    @can('view_ticket_details_myrequested_tickets')
    <style>
        .view-wrapper { background-color: #ffffff; border-radius: 8px; padding: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); max-width: 1000px; margin: 2rem auto; position: relative; font-family: 'Inter', sans-serif; }
        .close-btn { position: absolute; top: 1.25rem; right: 1.25rem; color: #6b7280; font-size: 2rem; background: none; border: none; cursor: pointer; transition: all 0.2s; line-height: 1; border-radius: 0.25rem; padding: 0 0.5rem; }
        .close-btn:hover { color: #111827; }
        .report-title { font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 1.5rem; border-bottom: 2px solid #f3f4f6; padding-bottom: 0.75rem; }
        .details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
        .detail-group { display: flex; flex-direction: column; gap: 0.25rem; }
        .detail-label { font-size: 0.875rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; }
        .detail-value { font-size: 1rem; color: #1f2937; background-color: #f9fafb; padding: 0.75rem; border-radius: 6px; border: 1px solid #e5e7eb; }
        .detail-value.full-width { grid-column: 1 / -1; white-space: pre-wrap; }
        .badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; text-align: center; }
        
        /* Status Badges */
        .status-resolved { background-color: #d1fae5; color: #065f46; }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-reassigned { background-color: #e0e7ff; color: #3730a3; }
        .status-default { background-color: #f3f4f6; color: #374151; }
        
        /* Priority Badges */
        .priority-low { background-color: #dbeafe; color: #1e40af; }
        .priority-medium { background-color: #fef08a; color: #854d0e; }
        .priority-high { background-color: #fed7aa; color: #c2410c; }
        .priority-critical { background-color: #fecaca; color: #991b1b; }
        .priority-default { background-color: #f3f4f6; color: #374151; }
        
        /* Evidence Photo Section */
        .section-header { grid-column: 1 / -1; font-size: 1.125rem; font-weight: 600; color: #374151; margin-top: 1rem; margin-bottom: 0.5rem; }
        .evidence-img-wrapper { display: inline-flex; flex-direction: column; align-items: flex-start; gap: 0.5rem; }
        .evidence-img-container { position: relative; cursor: pointer; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb; background-color: #f9fafb; display: inline-block; }
        .evidence-img { max-width: 100%; height: auto; max-height: 380px; object-fit: contain; display: block; transition: transform 0.2s ease, opacity 0.2s ease; }
        .evidence-img-container:hover .evidence-img { transform: scale(1.02); opacity: 0.9; }
        .click-hint { display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.875rem; color: #2563eb; font-weight: 600; cursor: pointer; background: #eff6ff; padding: 0.4rem 0.8rem; border-radius: 6px; border: 1px solid #bfdbfe; transition: background 0.2s; }
        .click-hint:hover { background: #dbeafe; }
        
        /* Lightbox Fullscreen Modal */
        .img-modal { display: none; position: fixed; z-index: 99999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.88); align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .img-modal.active { display: flex; }
        .modal-content { max-width: 90vw; max-height: 88vh; object-fit: contain; border-radius: 6px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
        .close-modal { position: absolute; top: 20px; right: 25px; color: #ffffff; font-size: 36px; font-weight: bold; cursor: pointer; z-index: 100000; line-height: 1; transition: color 0.2s; }
        .close-modal:hover { color: #ef4444; }
        .fullscreen-btn { position: absolute; top: 22px; right: 75px; color: #ffffff; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); padding: 0.4rem; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; z-index: 100000; }
        .fullscreen-btn:hover { background: rgba(255,255,255,0.4); }
    </style>

    <div id="main-content" class="page-wrapper">
        <div class="view-wrapper">
            <button id="close" onclick="window.location.href='{{ route('myrequested_tickets.index') }}'" class="close-btn" aria-label="Close form" title="Close">
                &times;
            </button>

            <h1 class="report-title">Ticket Number : {{ $ticket->ticket_number }}</h1>
            
            <div class="details-grid">
                
                <!-- Requester Information -->
                <h3 class="section-header">Client Information</h3>
                <div class="detail-group">
                    <span class="detail-label">Requested By</span>
                    <span class="detail-value">{{ $ticket->firstname }} {{ $ticket->middle_initial }} {{ $ticket->lastname }}</span>
                </div>
                <div class="detail-group">
                    <span class="detail-label">Division</span>
                    <span class="detail-value">{{ $ticket->division }}</span>
                </div>
                <div class="detail-group">
                    <span class="detail-label">Device</span>
                    <span class="detail-value">{{ $ticket->device }}</span>
                </div>
                <div class="detail-group">
                    <span class="detail-label">Technical Service</span>
                    <span class="detail-value">{{ $ticket->service }}</span>
                </div>

                <!-- Request Details -->
                <h3 class="section-header">Request Overview</h3>
                <div class="detail-group" style="grid-column: 1 / -1;">
                    <span class="detail-label">Request Details</span>
                    <span class="detail-value full-width">{{ $ticket->request }}</span>
                </div>

                <div class="detail-group">
                    <span class="detail-label">Priority</span>
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
                        <span class="badge {{ $priorityBadge }}">{{ $ticket->priority }}</span>
                    </div>
                </div>

                <div class="detail-group">
                    <span class="detail-label">Status</span>
                    <div>
                        @php
                            $statusBadge = match(trim($ticket->status)) {
                                'Resolved'             => 'status-resolved',
                                'Pending'              => 'status-pending',
                                'Pending/Re-Assigned'  => 'status-reassigned',
                                default                => 'status-default',
                            };
                        @endphp
                        <span class="badge {{ $statusBadge }}">{{ $ticket->status }}</span>
                    </div>
                </div>

                <!-- Assignment & Resolution -->
                <h3 class="section-header">Assignment & Resolution</h3>
                <div class="detail-group" style="grid-column: 1 / -1;">
                    <span class="detail-label">Assigned IT Personnel</span>
                    <span class="detail-value">{{ $ticket->it_personnel ?: 'Unassigned' }}</span>
                </div>
                
                <div class="detail-group" style="grid-column: 1 / -1;">
                    <span class="detail-label">Action Taken</span>
                    <span class="detail-value full-width">{{ $ticket->action_taken ?: 'No actions recorded yet.' }}</span>
                </div>

                <div class="detail-group">
                    <span class="detail-label">Date & Time Created</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($ticket->date_created)->format('F d, Y h:i A') }}</span>
                </div>

                <div class="detail-group">
                    <span class="detail-label">Date & Time Resolved</span>
                    <span class="detail-value">
                        @if($ticket->date_resolved)
                            {{ \Carbon\Carbon::parse($ticket->date_resolved)->format('F d, Y h:i A') }}
                        @else
                            <span style="color: #ef4444; font-style: italic;">Not Resolved</span>
                        @endif
                    </span>
                </div>

                <!-- Evidence Photo (LONGBLOB Data URI Rendering) -->
                <h3 class="section-header">Attached Evidence / Photo</h3>
                <div class="detail-group" style="grid-column: 1 / -1;">
                    @if(!empty($photoDataUri))
                        <div class="evidence-img-wrapper">
                            <div class="evidence-img-container" onclick="openImageModal()">
                                <img src="{{ $photoDataUri }}" alt="Ticket Evidence" class="evidence-img">
                            </div>
                            <button type="button" class="click-hint" onclick="openImageModal()">
                                <span class="material-symbols-outlined">zoom_in</span>
                                View Full Size
                            </button>
                        </div>
                    @else
                        <span class="detail-value" style="color: #6b7280; font-style: italic;">No photo evidence provided.</span>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- Fullscreen Lightbox Modal -->
    @if(!empty($photoDataUri))
    <div id="imageModal" class="img-modal">
        <span class="close-modal" onclick="closeImageModal()" title="Close">&times;</span>
        <button type="button" class="fullscreen-btn" onclick="toggleNativeFullscreen()" title="Toggle Native Fullscreen">
            <span class="material-symbols-outlined">fullscreen</span>
        </button>
        <img class="modal-content" id="modalImg" src="{{ $photoDataUri }}" alt="Full Screen Evidence">
    </div>

    <!-- Modal Scripts -->
    <script>
        function openImageModal() {
            document.getElementById('imageModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.remove('active');
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

        // Close modal when clicking dark backdrop
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal on Escape key press
        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") {
                closeImageModal();
            }
        });
    </script>
    @endif
    @endcan    
</x-app-layout>
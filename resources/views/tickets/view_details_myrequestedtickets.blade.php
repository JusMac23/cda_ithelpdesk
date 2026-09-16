<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    
    @can('view_ticket_details_myrequested_tickets')
    <style>
        .view-wrapper { background-color: #ffffff; border-radius: 8px; padding: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); max-width: 1000px; margin: 2rem auto; position: relative; font-family: 'Inter', sans-serif; }
        .close-btn { position: absolute; top: 1.25rem; right: 1.25rem; color: var(--text-muted); font-size: 2rem; background: none; border: none; cursor: pointer; transition: all 0.2s; line-height: 1; border-radius: 0.25rem; padding: 0 0.5rem; }
        .close-btn:hover { color: var(--text-dark); }
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
        
        .evidence-img { max-width: 100%; height: auto; border-radius: 6px; border: 1px solid #e5e7eb; margin-top: 0.5rem; max-height: 400px; object-fit: contain; }
        .section-header { grid-column: 1 / -1; font-size: 1.125rem; font-weight: 600; color: #374151; margin-top: 1rem; margin-bottom: 0.5rem; }
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

                <!-- Evidence Photo -->
                <h3 class="section-header">Attached Evidence / Photo</h3>
                <div class="detail-group" style="grid-column: 1 / -1;">
                    @if($ticket->photo)
                        <a href="{{ asset('storage/' . $ticket->photo) }}" target="_blank" title="Click to view full image">
                            <img src="{{ asset('storage/' . $ticket->photo) }}" alt="Ticket Evidence" class="evidence-img">
                        </a>
                    @else
                        <span class="detail-value" style="color: #6b7280; font-style: italic;">No photo evidence provided.</span>
                    @endif
                </div>

            </div>
        </div>
    </div>
    @endcan    
</x-app-layout>
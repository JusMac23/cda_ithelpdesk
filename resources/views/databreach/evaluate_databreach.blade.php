<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    
    <style>
        /* --- Theme Variables --- */
        :root {
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --text-muted: #475569;
            --border-light: #e2e8f0;
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
            --input-text: #334155;
            --error-bg: #fef2f2;
            --error-border: #fecaca;
            --error-text: #991b1b;
            --error-title: #7f1d1d;
            --close-btn-hover: #f1f5f9;
            --close-btn-text: #94a3b8;
            --readonly-bg: #f8fafc;
        }

        body.dark {
            --card-bg: #0f172a; 
            --text-dark: #f8fafc;
            --text-muted: #9ca3af;
            --border-light: #334155; 
            --input-bg: #0f172a;
            --input-border: #4b5563;
            --input-text: #f1f5f9;
            --error-bg: rgba(153, 27, 27, 0.2);
            --error-border: rgba(248, 113, 113, 0.4);
            --error-text: #fca5a5;
            --error-title: #f87171;
            --close-btn-hover: #1e293b;
            --close-btn-text: #64748b;
            --readonly-bg: #1e293b;
        }

        /* Global Box Sizing & Font Fix */
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; transition: background-color 0.3s ease, color 0.3s ease; }

        /* Main Layout */
        .container { max-width: 80rem; margin: 0 auto; padding: 0 2rem; }
        @media (max-width: 640px) { .container { padding: 0.01rem; } }

        /* Form Card - Added outline matching dark mode specs */
        .form-card { background-color: var(--card-bg); border-radius: 1rem; border: 1px solid var(--border-light); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); padding: 2.5rem; position: relative; width: 100%; transition: background-color 0.3s ease, border-color 0.3s ease; }
        @media (max-width: 640px) { .form-card { padding: 1.5rem; border-radius: 0.75rem; } }

        /* Header */
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .form-title { font-size: 1.75rem; font-weight: 800; color: var(--text-dark); margin-top: 0; margin-bottom: 2rem; border-bottom: 1px solid var(--border-light); padding-bottom: 1.5rem; letter-spacing: -0.025em; width: 100%; transition: color 0.3s ease, border-color 0.3s ease; }
        .form-section-title { font-size: 1.15rem; font-weight: 700; color: var(--text-dark); margin: 1.5rem 0 1.25rem; padding-bottom: 0.5rem; width: 100%; transition: color 0.3s ease; }
        
        /* Close Button */
        .close-btn { position: absolute; top: 1.25rem; right: 1.25rem; color: var(--text-muted); font-size: 2.25rem; background: none; border: none; cursor: pointer; transition: color 0.2s, background-color 0.2s; line-height: 1; border-radius: 0.25rem; padding: 0 0.5rem; }
        .close-btn:hover { color: var(--text-dark); }

        /* Error Box */
        .error-box { background-color: var(--error-bg); border: 1px solid var(--error-border); color: var(--error-text); padding: 1.25rem 1.5rem; border-radius: 0.75rem; margin-bottom: 2rem; display: flex; gap: 0.75rem; align-items: flex-start; width: 100%; transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease; }
        .error-icon { font-size: 1.5rem; margin-top: 0.125rem; color: #ef4444; }
        .error-title { font-weight: 700; font-size: 0.95rem; margin: 0 0 0.5rem 0; color: var(--error-title); transition: color 0.3s ease; }
        .error-list { list-style-type: disc; padding-left: 1.5rem; margin: 0; font-size: 0.9rem; line-height: 1.6; color: var(--error-text); font-weight: 500; transition: color 0.3s ease; }

        /* Grid Layouts */
        .grid-2 { display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-top: 1.5rem; }
        @media (min-width: 768px) { .grid-2 { grid-template-columns: repeat(2, 1fr); } }

        /* Form Controls - Unified Heights */
        .form-group { display: flex; flex-direction: column; margin-top: 1.5rem; }
        .grid-2 .form-group { margin-top: 0; }
        .form-label { font-size: 0.875rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.5rem; transition: color 0.3s ease; }
        .form-label-lg { font-size: 1rem; font-weight: 700; color: var(--text-dark); margin-bottom: 0.75rem; display: block; transition: color 0.3s ease; }
        .required-mark { color: #ef4444; margin-left: 0.125rem; }
        
        .form-input, .form-select { height: 44px; padding: 0 1rem; border: 1px solid var(--input-border); border-radius: 0.5rem; font-size: 0.95rem; color: var(--input-text); width: 100%; background-color: var(--input-bg); transition: all 0.2s; font-family: inherit; }
        .form-textarea { padding: 0.75rem 1rem; border: 1px solid var(--input-border); border-radius: 0.5rem; font-size: 0.95rem; color: var(--input-text); width: 100%; background-color: var(--input-bg); transition: all 0.2s; font-family: inherit; resize: vertical; min-height: 120px; }
        
        .form-input:focus, .form-select:focus, .form-textarea:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }
        .form-input::placeholder, .form-textarea::placeholder { color: var(--text-muted); opacity: 0.7; }
        
        /* Readonly States */
        .form-input[readonly] { background-color: var(--readonly-bg); color: var(--text-muted); cursor: not-allowed; border-color: var(--border-light); }
        .form-input[readonly]:focus { border-color: var(--border-light); box-shadow: none; }
        
        .readonly-box { background-color: var(--readonly-bg); border: 1px solid var(--border-light); border-radius: 0.5rem; padding: 0.85rem 1rem; width: 100%; box-sizing: border-box; color: var(--text-muted); font-size: 0.95rem; transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease; }
        .readonly-box p { margin: 0 0 0.35rem 0; }
        .readonly-box p:last-child { margin: 0; }
        .readonly-box strong { color: var(--text-dark); transition: color 0.3s ease; }

        /* Validation Error State */
        .input-error { border-color: #ef4444 !important; }
        .input-error:focus { box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important; }

        /* Checkboxes and Radios */
        .checkbox-group { display: flex; flex-direction: column; gap: 0.85rem; margin-top: 0.5rem; }
        .checkbox-label { display: flex; align-items: flex-start; gap: 0.75rem; font-size: 0.95rem; font-weight: 500; color: var(--input-text); cursor: pointer; line-height: 1.4; transition: color 0.3s ease; }
        .checkbox-input { margin-top: 0.15rem; width: 1.15rem; height: 1.15rem; accent-color: #4f46e5; cursor: pointer; border-radius: 0.25rem; flex-shrink: 0; }
        
        .radio-group { display: flex; align-items: center; gap: 2rem; margin-top: 0.25rem; }
        .radio-label { display: flex; align-items: center; gap: 0.5rem; font-size: 0.95rem; font-weight: 500; color: var(--input-text); cursor: pointer; transition: color 0.3s ease; }
        .radio-input { width: 1.15rem; height: 1.15rem; accent-color: #4f46e5; cursor: pointer; margin: 0; }

        /* Buttons & Footer */
        .page-footer { display: flex; border-top: 1px solid var(--border-light); margin-top: 2.5rem; padding-top: 1.5rem; transition: border-color 0.3s ease; }
        .page-footer.right { justify-content: flex-end; }
        .page-footer.between { justify-content: space-between; align-items: center; gap: 1rem; }
        
        /* Unified Button Styling */
        .btn { display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 2rem; font-size: 0.95rem; font-weight: 600; border-radius: 0.5rem; cursor: pointer; border: none; color: white; transition: all 0.2s ease; font-family: inherit; min-width: 160px; }
        .btn i { margin-right: 0.5rem; font-size: 1rem; }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        
        /* Modern Indigo */
        .btn-indigo { background-color: #4f46e5; box-shadow: 0 1px 2px rgba(79, 70, 229, 0.2); }
        .btn-indigo:hover { background-color: #4338ca; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
        .btn-indigo:active { box-shadow: 0 1px 2px rgba(79, 70, 229, 0.2); }
        
        /* Modern Red */
        .btn-red { background-color: #ef4444; box-shadow: 0 1px 2px rgba(239, 68, 68, 0.2); }
        .btn-red:hover { background-color: #dc2626; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }
        .btn-red:active { box-shadow: 0 1px 2px rgba(239, 68, 68, 0.2); }
        
        /* Utility */
        .hidden-page { display: none !important; }
        
        /* Smooth Page Transition */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
        #page1:not(.hidden-page), #page2:not(.hidden-page) { animation: fadeIn 0.3s ease forwards; }

        @media (max-width: 640px) {
            .btn { width: 100%; justify-content: center; }
            .page-footer.between { flex-direction: column-reverse; gap: 1rem; }
            .close-btn { top: 1.5rem; right: 2rem; }
        }
    </style>

    @can('evaluate_databreach')
    <div id="main-content" class="page-wrapper">
        <div class="container">
            <div class="form-card">

                <button id="close" onclick="window.location.href='{{ route('databreach.index') }}'" class="close-btn" aria-label="Close form" title="Close">
                    &times;
                </button>

                <h2 class="form-title">Incident Report Evaluation</h2>

                @if ($errors->any())
                    <div class="error-box">
                        <i class="fas fa-exclamation-circle error-icon"></i>
                        <div>
                            <h4 class="error-title">Please fix the following errors:</h4>
                            <ul class="error-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('databreach.update_evaluation', $notification->dbn_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div id="page1">

                        <h3 class="form-section-title">A. Notification Type</h3>

                        <div class="form-group" style="margin-top: 0;">
                            <label for="dbn_number" class="form-label">
                                DBN Number <span class="required-mark">*</span>
                            </label>
                            <input type="text" id="dbn_number" name="dbn_number"
                                value="{{ old('dbn_number', $notification->dbn_number) }}"
                                placeholder="e.g., CDA-DBN-2025-01" required readonly class="form-input">
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label for="pic" class="form-label">
                                    Personal Information Controller <span class="required-mark">*</span>
                                </label>
                                <select id="pic" name="pic" required class="form-select">
                                    <option value="">-- Select PIC --</option>
                                    <option value="CDA HO" {{ old('pic', $notification->pic) == 'CDA HO' ? 'selected' : '' }}>CDA HO</option>
                                    <option value="CDA CAR" {{ old('pic', $notification->pic) == 'CDA CAR' ? 'selected' : '' }}>CDA CAR</option>
                                    <option value="CDA NIR" {{ old('pic', $notification->pic) == 'CDA NIR' ? 'selected' : '' }}>CDA NIR</option>
                                    <option value="CDA NCR" {{ old('pic', $notification->pic) == 'CDA NCR' ? 'selected' : '' }}>CDA NCR</option>
                                    <option value="CDA Region I" {{ old('pic', $notification->pic) == 'CDA Region I' ? 'selected' : '' }}>CDA Region I</option>
                                    <option value="CDA Region II" {{ old('pic', $notification->pic) == 'CDA Region II' ? 'selected' : '' }}>CDA Region II</option>
                                    <option value="CDA Region III" {{ old('pic', $notification->pic) == 'CDA Region III' ? 'selected' : '' }}>CDA Region III</option>
                                    <option value="CDA Region IV-A" {{ old('pic', $notification->pic) == 'CDA Region IV-A' ? 'selected' : '' }}>CDA Region IV-A</option>
                                    <option value="CDA Region IV-B" {{ old('pic', $notification->pic) == 'CDA Region IV-B' ? 'selected' : '' }}>CDA Region IV-B</option>
                                    <option value="CDA Region V" {{ old('pic', $notification->pic) == 'CDA Region V' ? 'selected' : '' }}>CDA Region V</option>
                                    <option value="CDA Region VI" {{ old('pic', $notification->pic) == 'CDA Region VI' ? 'selected' : '' }}>CDA Region VI</option>
                                    <option value="CDA Region VII" {{ old('pic', $notification->pic) == 'CDA Region VII' ? 'selected' : '' }}>CDA Region VII</option>
                                    <option value="CDA Region VIII" {{ old('pic', $notification->pic) == 'CDA Region VIII' ? 'selected' : '' }}>CDA Region VIII</option>
                                    <option value="CDA Region IX" {{ old('pic', $notification->pic) == 'CDA Region IX' ? 'selected' : '' }}>CDA Region IX</option>
                                    <option value="CDA Region X" {{ old('pic', $notification->pic) == 'CDA Region X' ? 'selected' : '' }}>CDA Region X</option>
                                    <option value="CDA Region XI" {{ old('pic', $notification->pic) == 'CDA Region XI' ? 'selected' : '' }}>CDA Region XI</option>
                                    <option value="CDA Region XII" {{ old('pic', $notification->pic) == 'CDA Region XII' ? 'selected' : '' }}>CDA Region XII</option>
                                    <option value="CDA Region XIII" {{ old('pic', $notification->pic) == 'CDA Region XIII' ? 'selected' : '' }}>CDA Region XIII</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">
                                    Email Address <span class="required-mark">*</span>
                                </label>
                                <input type="text" id="email" name="email" value="{{ old('email', $notification->team_email ?? $notification->email) }}" required readonly class="form-input">
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label for="representative" class="form-label">
                                    Representative <span class="required-mark">*</span>
                                </label>
                                <input type="text" id="representative" name="representative" value="{{ old('representative', $notification->representative) }}" readonly class="form-input">
                            </div>

                            <div class="form-group">
                                <label for="representative_email_address" class="form-label">
                                    Representative Email <span class="required-mark">*</span>
                                </label>
                                <input type="text" id="representative_email_address" name="representative_email_address" value="{{ old('representative_email_address', $notification->representative_email_address) }}" readonly class="form-input">
                            </div>
                        </div>

                        <div class="grid-2" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                            <div class="form-group">
                                <label for="date_occurrence" class="form-label">
                                    Date of Occurrence <span class="required-mark">*</span>
                                </label>
                                <input type="datetime-local" id="date_occurrence" name="date_occurrence"
                                    value="{{ old('date_occurrence', $notification->date_occurrence) }}" required class="form-input">
                            </div>
                            <div class="form-group">
                                <label for="date_discovery" class="form-label">
                                    Date of Discovery <span class="required-mark">*</span>
                                </label>
                                <input type="datetime-local" id="date_discovery" name="date_discovery"
                                    value="{{ old('date_discovery', $notification->date_discovery) }}" required class="form-input">
                            </div>
                            <div class="form-group">
                                <label for="date_notification" class="form-label">
                                    Date of Notification <span class="required-mark">*</span>
                                </label>
                                <input type="datetime-local" id="date_notification" name="date_notification"
                                    value="{{ old('date_notification', $notification->date_notification) }}" required readonly class="form-input">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="brief_summary" class="form-label">
                                Brief Summary of the Incident <span class="required-mark">*</span>
                            </label>
                            <textarea id="brief_summary" name="brief_summary" required rows="4" class="form-textarea">{{ old('brief_summary', $notification->brief_summary) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label-lg">Notification Type Criteria</label>
                            <div class="checkbox-group">
                                @php
                                    // Decode JSON manually if still stored as string
                                    $notifTypes = $notification->notification_type_description;
                                    if (is_string($notifTypes)) {
                                        $notifTypes = json_decode($notifTypes, true);
                                    }
                                    $notifTypes = old('notification_type_description', $notifTypes ?? []);
                                @endphp

                                @foreach ([
                                    'Involves SPI or Data that may enable identity fraud',
                                    'Acquired by an unauthorized person',
                                    'Likely to give rise to harm to data subjects'
                                ] as $option)
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="notification_type_description[]" value="{{ $option }}"
                                            {{ is_array($notifTypes) && in_array($option, $notifTypes) ? 'checked' : '' }}
                                            class="checkbox-input">
                                        <span>{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="page-footer right">
                            <button type="button" id="next-page" class="btn btn-indigo">
                                Continue <span class="material-symbols-outlined" style="font-size: 1.25rem; margin-right: 0.2rem;">arrow_forward</span>
                            </button>
                        </div>
                    </div>

                    <div id="page2" class="hidden-page">

                        <h3 class="form-section-title">B. Data Breach Notification Details</h3>

                        <div class="grid-2">
                            <div class="form-group">
                                <label for="sector_name" class="form-label">
                                    Sector Name <span class="required-mark">*</span>
                                </label>
                                <input type="text" id="sector_name" name="sector_name" value="{{ old('sector_name', $notification->sector_name) }}" required class="form-input">
                            </div>

                            <div class="form-group">
                                <label for="subsector_name" class="form-label">
                                    Subsector Name <span class="required-mark">*</span>
                                </label>
                                <input type="text" id="subsector_name" name="subsector_name" value="{{ old('subsector_name', $notification->subsector_name) }}" required class="form-input">
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label for="notification_type" class="form-label">
                                    Notification Type <span class="required-mark">*</span>
                                </label>
                                <select id="notification_type" name="notification_type" required class="form-select">
                                    <option value="">-- Select Notification Type --</option>
                                    <option value="Mandatory" {{ old('notification_type', $notification->notification_type) == 'Mandatory' ? 'selected' : '' }}>Mandatory</option>
                                    <option value="Voluntary" {{ old('notification_type', $notification->notification_type) == 'Voluntary' ? 'selected' : '' }}>Voluntary</option>
                                    <option value="Others" {{ old('notification_type', $notification->notification_type) == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="timeliness" class="form-label">
                                    Timeliness <span class="required-mark">*</span>
                                </label>
                                <input type="text" id="timeliness" name="timeliness" value="{{ old('timeliness', $notification->timeliness) }}" required class="form-input">
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label for="general_cause" class="form-label">
                                    General Cause <span class="required-mark">*</span>
                                </label>
                                <select id="general_cause" name="general_cause" required class="form-select">
                                    <option value="">-- Select General Cause --</option>
                                    @foreach (['Malicious Attack', 'Malicious Attack/Human Error', 'Human Error', 'System Glitch', 'Malicious Attack/System Glitch', 'System Glitch/Human Error', 'Others'] as $cause)
                                        <option value="{{ $cause }}" {{ old('general_cause', $notification->general_cause) == $cause ? 'selected' : '' }}>
                                            {{ $cause }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="specific_cause" class="form-label">
                                    Specific Cause <span class="required-mark">*</span>
                                </label>
                                <select id="specific_cause" name="specific_cause" required class="form-select">
                                    <option value="{{ old('specific_cause', $notification->specific_cause) }}">{{ old('specific_cause', $notification->specific_cause) }}</option>
                                </select>

                                <label for="general_incident" class="form-label" style="margin-top: 1.25rem;">
                                    General Incident <span class="required-mark">*</span>
                                </label>
                                <input type="text" name="general_incident" id="general_incident" required readonly
                                    value="{{ old('general_incident', $notification->general_incident) }}" class="form-input">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                With Request? <span class="required-mark">*</span>
                            </label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="with_request" value="Yes" class="radio-input" {{ old('with_request', $notification->with_request) == 'Yes' ? 'checked' : '' }}>
                                    <span>Yes</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="with_request" value="No" class="radio-input" {{ old('with_request', $notification->with_request) == 'No' ? 'checked' : '' }}>
                                    <span>No</span>
                                </label>
                            </div>
                        </div>

                        @php
                            $fields = [
                                'how_breach_occured' => '1.A How Breach Occurred + DPS Vulnerability',
                                'chronology' => '1.B Chronology',
                                'num_records' => '1.C Number of Data Subject / Records',
                                'description_nature' => '1.D Description / Nature',
                                'likely_consequences' => '1.E Likely Consequences',
                                'dpo' => '1.F Data Protection Officer (DPO)',
                                'spi' => '2.A SPI',
                                'other_info' => '2.B Other Information',
                                'measures_to_address' => '3.A Measures to Address the Breach',
                                'measures_to_secure' => '3.B Measures to Secure/Recover Personal Data',
                                'actions_to_mitigate' => '3.C Actions to Mitigate Harm',
                                'actions_to_inform' => '3.D Actions to Inform Data Subjects',
                                'actions_to_prevent' => '3.E Measures to Prevent Recurrence of Incidence',
                            ];
                        @endphp

                        @foreach ($fields as $name => $label)
                            <div class="form-group">
                                <label for="{{ $name }}" class="form-label">
                                    {{ $label }} <span class="required-mark">*</span>
                                </label>

                                @if (strtolower($name) === 'dpo')
                                    {{-- Automatically fetch and display DPO details independently as readonly --}}
                                    <div id="{{ $name }}" class="readonly-box" style="display: flex; flex-direction: column; gap: 4px;">
                                        <p><strong>Name:</strong> {{ $dpoDetails->name ?? 'No DPO Assigned' }}</p>
                                        <p><strong>Email:</strong> {{ $dpoDetails->email ?? 'N/A' }}</p>
                                        <p><strong>Contact:</strong> {{ $dpoDetails->contact_number ?? 'N/A' }}</p>
                                    </div>
                                    {{-- Hidden fallback to supply the payload on form submit --}}
                                    <input type="hidden" name="{{ $name }}" value="{{ $dpoDetails->name ?? '' }} | {{ $dpoDetails->email ?? '' }}">
                                @else
                                    <textarea id="{{ $name }}" name="{{ $name }}" class="form-textarea" placeholder="Provide details...">{{ old($name, $notification->$name ?? '') }}</textarea>
                                @endif
                            </div>

                            {{-- Insert the extra "Provide Details" field right after num_records --}}
                            @if ($name === 'num_records')
                                <div class="form-group">
                                    <label for="num_records_provide_details" class="form-label">
                                        Number of Records - Provide Details <span class="required-mark">*</span>
                                    </label>
                                    <textarea name="num_records_provide_details" id="num_records_provide_details" rows="3"
                                        placeholder="e.g., 1000 employees consisting of names, contact details, and social security numbers."
                                        class="form-textarea" required>{{ old('num_records_provide_details', $notification->num_records_provide_details ?? '') }}</textarea>
                                
                                    @error('num_records_provide_details')
                                        <span style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem; font-weight: 500;">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        @endforeach

                        <div class="grid-2">
                            <div class="form-group">
                                <label for="record_type" class="form-label">
                                    Record Type <span class="required-mark">*</span>
                                </label>
                                <select id="record_type" name="record_type" required class="form-select">
                                    <option value="">-- Select Record Type --</option>
                                    @foreach ([
                                        'Digital Records in Electronic Systems',
                                        'Digital Records in Email',
                                        'Digital Records in Removable Media or Portable Device',
                                        'Physical Records'
                                    ] as $type)
                                        <option value="{{ $type }}" {{ old('record_type', $notification->record_type) == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="data_subjects" class="form-label">
                                    Data Subjects <span class="required-mark">*</span>
                                </label>
                                <select id="data_subjects" name="data_subjects" required class="form-select">
                                    <option value="">-- Select Data Subjects --</option>
                                    @foreach ([
                                        'Own Employees',
                                        'Customers',
                                        'Personal Data of Vulnerable Groups',
                                        'Others'
                                    ] as $subject)
                                        <option value="{{ $subject }}" {{ old('data_subjects', $notification->data_subjects) == $subject ? 'selected' : '' }}>
                                            {{ $subject }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="page-footer between">
                            <button type="button" id="prev-page" class="btn btn-red">
                                <span class="material-symbols-outlined" style="font-size: 1.25rem; margin-right: 0.2rem;">arrow_back</span> Back
                            </button>

                            <button type="submit" class="btn btn-indigo">
                                <span class="material-symbols-outlined" style="font-size: 1.25rem; margin-right: 0.2rem;">save</span> Save Evaluation
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // PAGE NAVIGATION
            const nextPageBtn = document.getElementById('next-page');
            const prevPageBtn = document.getElementById('prev-page');
            const page1 = document.getElementById('page1');
            const page2 = document.getElementById('page2');

            if (nextPageBtn && prevPageBtn && page1 && page2) {
                nextPageBtn.addEventListener('click', () => {
                    page1.classList.add('hidden-page');
                    page2.classList.remove('hidden-page');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });

                prevPageBtn.addEventListener('click', () => {
                    page2.classList.add('hidden-page');
                    page1.classList.remove('hidden-page');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }

            // AUTO-FILL CURRENT DATE/TIME (Asia/Manila)
            const now = new Date();
            const options = {
                timeZone: 'Asia/Manila',
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            };
            const formatter = new Intl.DateTimeFormat('en-CA', options);
            const parts = formatter.formatToParts(now);

            const year = parts.find(p => p.type === 'year').value;
            const month = parts.find(p => p.type === 'month').value;
            const day = parts.find(p => p.type === 'day').value;
            const hour = parts.find(p => p.type === 'hour').value;
            const minute = parts.find(p => p.type === 'minute').value;
            const manilaDateTime = `${year}-${month}-${day}T${hour}:${minute}`;

            const dateTimeFields = ['date_time_of_containment', 'incident_verified_date'];
            dateTimeFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = manilaDateTime;
            });

            // INCIDENT CATEGORY AUTO-POPULATION
            const incidentTypeEl = document.getElementById("general_cause");
            const incidentCategory = document.getElementById("specific_cause");

            const categoryOptions = {
                "Malicious Attack": [
                    "Hacking-Cloud", "Hacking-Database", "Hacking-Email Account", "Hacking-Infrastructure",
                    "Hacking-Server", "Hacking-Website", "Hacking-Others", "Theft", "Social Engineering",
                    "Malware-Ransomware", "Malware-Trojan Horse", "Hacking-SQL Injection", "Phishing",
                    "Smishing", "Hacking-Phishing", "Malware-Virus", "Hacking-Man-In-The-Middle", "Identity Fraud", 
                    "Malicious Code", "Hacking", "Others (Specify)"
                ],
                "Malicious Attack/Human Error": [
                    "Misuse of Resources", "Phishing", "Smishing", "Social Engineering", "Undertrained Staff",
                    "Insider Threat", "Negligence", "Stolen Device", "Hacking-Database", "Unauthorized Disclosure", 
                    "Sabotage / Physical Damage", "Others (Specify)"
                ],
                "Human Error": [
                    "Undertrained Staff", "Loss of Equipment", "Loss of Documents", "Misdelivered Documents",
                    "Negligence", "Accidental Email", "Misuse of Resources", "User Error", "Others (Specify)"
                ],
                "System Glitch": [
                    "System Error", "Connection Error", "Hardware Failure", "System Misconfiguration", "Software Failure", "Others (Specify)"
                ],
                "Malicious Attack/System Glitch": [
                    "Misconfiguration", "System Error", "Connection Error", "Hardware Failure", "Others (Specify)"
                ],
                "System Glitch/Human Error": [
                    "System Misconfiguration", "Software Maintenance Error", "Communication Failure", "Operation Error", 
                    "Design Error", "Others (Specify)"
                ],
                "Others": [
                    "Natural Disaster", "Third Party / Service Provider"
                ]
            };

            if (incidentTypeEl && incidentCategory) {
                incidentTypeEl.addEventListener("change", function () {
                    const selectedType = this.value;
                    incidentCategory.innerHTML = '<option value="">-- Select Specific Cause --</option>';

                    if (categoryOptions[selectedType]) {
                        categoryOptions[selectedType].forEach(category => {
                            const option = document.createElement("option");
                            option.value = category;
                            option.textContent = category;
                            incidentCategory.appendChild(option);
                        });
                    }
                });
            }

            // GENERAL INCIDENT AUTO-TYPE
            const specificCauseEl = document.getElementById("specific_cause");
            const generalIncidentEl = document.getElementById("general_incident");

            const categoryOptionsIncident = {
                "Identity Fraud": "Identity Fraud",
                "Social Engineering": "Identity Fraud",
                "Phishing": "Identity Fraud",
                "Smishing": "Identity Fraud",
                "Hacking-Phishing": "Identity Fraud",
                "Unauthorized Disclosure": "Identity Fraud",

                "Malicious Code": "Malicious Code",
                "Malware-Trojan Horse": "Malicious Code",
                "Malware-Ransomware": "Malicious Code",
                "Malware-Virus": "Malicious Code",

                "Hacking-Cloud": "Hacking",
                "Hacking-Database": "Hacking",
                "Hacking-Email Account": "Hacking",
                "Hacking-Infrastructure": "Hacking",
                "Hacking-Server": "Hacking",
                "Hacking-Website": "Hacking",
                "Hacking-SQL Injection": "Hacking",
                "Hacking-Man-In-The-Middle": "Hacking",
                "Hacking-Others": "Hacking",

                "Theft": "Theft",
                "Stolen Device": "Theft",
                "Loss of Equipment": "Theft",
                "Loss of Documents": "Theft",

                "Hardware Failure": "Hardware Failure",
                "System Error": "Software Failure",
                "Software Failure": "Software Failure",

                "User Error": "User Error",
                "Accidental Email": "User Error",
                "Misdelivered Documents": "User Error",
                "Undertrained Staff": "User Error",
                "Negligence": "User Error",

                "Misconfiguration": "Software Maintenance Error",
                "System Misconfiguration": "Software Maintenance Error",
                "Software Maintenance Error": "Software Maintenance Error",

                "Sabotage / Physical Damage": "Sabotage / Physical Damage",
                "Insider Threat": "Insider Threat",
                "Misuse of Resources": "Misuse of Resources",

                "Communication Failure": "Communication Failure",
                "Connection Error": "Communication Failure",
                "Operation Error": "Operation Error",
                "Design Error": "Design Error",

                "Natural Disaster": "Natural Disaster",
                "Third Party / Service Provider": "Third Party / Service Provider",

                "Others": "Others",
                "Others (Specify)": "Others"
            };

            if(specificCauseEl && generalIncidentEl) {
                specificCauseEl.addEventListener("change", function () {
                    const selectedCause = this.value;
                    generalIncidentEl.value = categoryOptionsIncident[selectedCause] || "Others";
                });
            }

            // EMAIL AUTO-FILL BASED ON PIC REGION
            const picSelect = document.getElementById('pic');
            const emailField = document.getElementById('email');

            if (picSelect && emailField) {
                picSelect.addEventListener('change', function () {
                    const region = this.value;

                    if (!region) {
                        emailField.value = '';
                        return;
                    }

                    fetch(`/get-dbrt-email/${encodeURIComponent(region)}`)
                        .then(response => response.json())
                        .then(data => {
                            emailField.value = data.email ?? '';
                        })
                        .catch(() => {
                            emailField.value = '';
                        });
                });
            }

            // FORM VALIDATION ENHANCEMENT
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    let isValid = true;
                    const requiredFields = this.querySelectorAll('[required]');

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('input-error');
                        } else {
                            field.classList.remove('input-error');
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Missing Information',
                            text: 'Please fill in all required fields marked with *.',
                            confirmButtonColor: '#4f46e5',
                            background: getComputedStyle(document.body).getPropertyValue('--card-bg').trim(),
                            color: getComputedStyle(document.body).getPropertyValue('--text-dark').trim()
                        });
                        
                        // Force back to page 1 if errors are there
                        if(page1 && page2) {
                             page2.classList.add('hidden-page');
                             page1.classList.remove('hidden-page');
                             window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    }
                });

                // Real-time validation feedback
                form.querySelectorAll('[required]').forEach(field => {
                    field.addEventListener('blur', function () {
                        if (!this.value.trim()) {
                            this.classList.add('input-error');
                        } else {
                            this.classList.remove('input-error');
                        }
                    });
                });
            }
        });
    </script>
    @endcan
</x-app-layout>
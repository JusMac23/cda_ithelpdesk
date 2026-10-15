@php
    $year = now()->year;
    $clientType = old('client_type', $existingFeedback->client_type ?? 'Government (Employee or another agency)');
    $sex = old('sex', $existingFeedback->sex ?? '');
    $age = old('age', $existingFeedback->age ?? '');
    $servicesSelected = old('services', $existingFeedback->services ?? ['Request for Technical Assistance']);
    if (is_string($servicesSelected)) {
        $decoded = json_decode($servicesSelected, true);
        $servicesSelected = is_array($decoded) ? $decoded : [$servicesSelected];
    } elseif (!is_array($servicesSelected)) {
        $servicesSelected = [];
    }
    $serviceOther = old('service_other', $existingFeedback->service_other ?? '');
    $cc1 = old('cc1', $existingFeedback->cc1 ?? '');
    $cc2 = old('cc2', $existingFeedback->cc2 ?? '');
    $cc3 = old('cc3', $existingFeedback->cc3 ?? '');
    $email = old('email', $existingFeedback->email ?? $ticket->email ?? '');
    $suggestions = old('suggestions', $existingFeedback->suggestions ?? '');

    $questions = [
        1 => 'My requested service is delivered in a timely manner (Responsiveness)',
        2 => "The procedures of services are clearly indicated in the Citizen's Charter displayed in the lobby. (Reliability)",
        3 => 'The facilities are easily accessible and convenient for transactions. (access and facilities)',
        4 => 'My queries/ needs are answered in a language that can be easily understood. (Communication)',
        5 => 'I paid an acceptable amount of fees for my transaction. (Costs)',
        6 => 'The CDA personnel is honest and fair in his/her delivery of the CDA programs/services. (Integrity)',
        7 => "The CDA personnel is knowledgeable, understanding, and helpful with the citizen/ client's needs. (Assurance)",
        8 => 'I am satisfied with the service rendered. (Outcome)',
    ];
    $displayDate = old('feedback_date', $existingFeedback->feedback_date ?? date('Y-m-d'));
    $displayRegion = old('region', $existingFeedback->region ?? ($ticket->it_area ?? 'NCR'));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CDA – Client Satisfaction Measurement (CSM) Form</title>
    <link rel="icon" href="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ══════════════════════════════════════════════════════
           CSM FEEDBACK FORM — Matches Official CDA Reference Layout
           Fits 1× A4 page at 100% default print scale
        ══════════════════════════════════════════════════════ */

        /* Checkbox & Radio styling */
        .csm-cb {
            appearance: none; -webkit-appearance: none;
            width: 12px; height: 12px; min-width: 12px; min-height: 12px;
            border: 1.5px solid #374151; border-radius: 1px;
            background: #fff; display: inline-grid; place-content: center;
            cursor: pointer; flex-shrink: 0; vertical-align: middle; margin: 0;
            -webkit-print-color-adjust: exact; print-color-adjust: exact;
        }
        .csm-cb:checked { background: #000; border-color: #000; }
        .csm-cb:checked::before { content: '✔'; color: #fff; font-size: 8px; font-weight: 900; line-height: 1; }

        /* Checkbox & Radio option wrapper */
        .csm-option-label {
            display: flex; align-items: flex-start; gap: 5px;
            cursor: pointer; margin-bottom: 1px; line-height: 1.35;
        }

        /* SQD Table Rating Radios */
        .csm-rate {
            appearance: none; -webkit-appearance: none;
            width: 14px; height: 14px; min-width: 14px; min-height: 14px;
            border: 1.5px solid #374151; border-radius: 1px;
            background: #fff; display: inline-grid; place-content: center;
            cursor: pointer; margin: 0 auto;
            -webkit-print-color-adjust: exact; print-color-adjust: exact;
        }
        .csm-rate:checked { background: #000; border-color: #000; }
        .csm-rate:checked::before { content: '✔'; color: #fff; font-size: 9px; font-weight: 900; line-height: 1; }

        /* Underline inputs */
        .csm-field {
            border: none; border-bottom: 1px solid #111827;
            background: transparent; outline: none; padding: 0 2px;
            font-size: 10px; color: #111827; line-height: 1.3; font-family: inherit;
        }
        .csm-field:focus { border-bottom-color: #2563eb; }
        .csm-field:disabled { background: transparent; cursor: default; color: #1e3a5f; }

        /* Rating Table */
        .sqd-table { border-collapse: collapse; width: 100%; font-size: 9px; table-layout: fixed; }
        .sqd-table th, .sqd-table td { border: 1px solid #374151; vertical-align: middle; }
        .sqd-table th {
            text-align: center; font-weight: 700; background: #fff;
            font-size: 8.5px; line-height: 1.15; padding: 3px 2px;
            -webkit-print-color-adjust: exact; print-color-adjust: exact;
        }
        .sqd-table td { padding: 4px 5px; line-height: 1.25; }
        .sqd-table td:first-child { text-align: left; }
        .sqd-table td:not(:first-child) { text-align: center; }

        /* Screen View Paper */
        .paper-wrap {
            width: 210mm; min-height: 297mm; margin: 0 auto;
            padding: 10mm 13mm;
            box-sizing: border-box; background: #fff;
            font-family: 'Inter', Arial, sans-serif;
            font-size: 10px; color: #111827;
        }
        #csmForm { display: flex; flex-direction: column; }
        .sqd-bottom { padding-top: 6px; }

        /* ══════ PRINT VIEW ══════ */
        @media print {
            @page { size: A4 portrait; margin: 10mm 13mm; }
            *, *::before, *::after {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                box-sizing: border-box !important;
            }
            html, body {
                background: #fff !important; margin: 0 !important; padding: 0 !important;
                width: 100% !important; height: auto !important;
                display: block !important; overflow: visible !important;
                font-size: 10px !important;
            }
            header, footer, .no-print { display: none !important; }
            main {
                display: block !important; margin: 0 !important; padding: 0 !important;
                width: 100% !important; height: auto !important; flex: none !important;
            }
            .paper-wrap {
                width: 100% !important; height: auto !important;
                margin: 0 !important; padding: 0 !important;
                box-shadow: none !important; overflow: visible !important;
                page-break-inside: avoid !important;
            }
            #csmForm {
                height: auto !important; min-height: 0 !important;
            }
            .sqd-table { font-size: 9px !important; }
            .sqd-table td { padding: 3px 5px !important; line-height: 1.25 !important; }
            .sqd-table th { padding: 3px 2px !important; }
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased min-h-screen flex flex-col">

{{-- ═══ TOPBAR (hidden on print) ══════════════════════════════════════════════ --}}
<header class="no-print sticky top-0 z-50 bg-gray-900 border-b border-white/10 shadow">
    <div class="h-0.5 bg-gradient-to-r from-blue-500 via-indigo-400 to-emerald-400"></div>
    <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between gap-4">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 text-white font-bold text-sm no-underline hover:opacity-80 transition">
            <img src="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" alt="CDA" class="w-8 h-8 object-contain"
                 onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Cooperative_Development_Authority_%28CDA%29.svg/1200px-Cooperative_Development_Authority_%28CDA%29.svg.png'">
            <span class="hidden sm:inline">CDA–ICT Helpdesk</span>
        </a>
        <div class="flex-1 flex justify-center">
            <span class="text-xs text-gray-400">
                Ticket <strong class="text-white">#{{ $ticket->ticket_number }}</strong>
                &mdash; <span class="text-gray-300">{{ $ticket->firstname }} {{ $ticket->lastname }}</span>
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded text-xs font-semibold text-gray-300 hover:text-white hover:bg-white/10 border border-white/15 transition cursor-pointer">
                <span class="material-symbols-outlined text-sm leading-none">print</span>
                <span class="hidden sm:inline">Print</span>
            </button>
            <a href="{{ url('/') }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded text-xs text-gray-400 hover:text-white hover:bg-white/10 transition">
                <span class="material-symbols-outlined text-sm leading-none">close</span>
            </a>
        </div>
    </div>
</header>

{{-- ═══ SUCCESS FLASH (hidden on print) ══════════════════════════════════════ --}}
@if(session('success'))
    <div class="no-print max-w-4xl mx-auto mt-4 px-4 w-full">
        <div class="flex items-center gap-3 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-300 text-emerald-800 text-sm">
            <span class="material-symbols-outlined text-xl text-emerald-600">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

{{-- ═══ MAIN CONTAINER ═════════════════════════════════════════════════════════ --}}
<main class="flex-1 w-full py-6 flex justify-center items-start">

    {{-- ─── A4 PAPER ────────────────────────────────────────────────────────── --}}
    <div class="paper-wrap shadow-xl">

        <form id="csmForm" action="{{ route('tickets.saveFeedback', $ticket->ticket_id) }}" method="POST">
            @csrf

            {{-- ══ HEADER: Logo + Title centered | Form Code far right ══ --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                <div style="display:flex; align-items:center; gap:10px; flex:1; justify-content:center; padding-right:80px;">
                    <img src="{{ asset('images/CDA-logo-RA11364-PNG.png') }}"
                         alt="CDA Seal"
                         style="width:65px; height:65px; object-fit:contain; flex-shrink:0;"
                         onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Cooperative_Development_Authority_%28CDA%29.svg/1200px-Cooperative_Development_Authority_%28CDA%29.svg.png'">
                    <h1 style="font-size:15px; font-weight:900; text-transform:uppercase; letter-spacing:0.02em; line-height:1.2; color:#111827; margin:0;">
                        COOPERATIVE DEVELOPMENT AUTHORITY
                    </h1>
                </div>
                <div style="text-align:right; font-size:9px; font-weight:700; color:#111827; line-height:1.5; flex-shrink:0; width:90px;">
                    CDA-CC-FR-008<br>Revision No. 2<br>June 16, 2023
                </div>
            </div>

            {{-- ── INTRO TEXT ── --}}
            <p style="font-size:10px; line-height:1.4; margin-bottom:8px; text-align:justify;">
                This Client Satisfaction Measurement (CSM) tracks the customer experience of government offices.
                Your feedback on your <span style="text-decoration:underline; font-weight:600;">recently concluded transaction</span>
                will help this office provide a better service. Personal information shared will be kept confidential
                and you always have the option to not answer this form.
            </p>

            {{-- ── CLIENT TYPE ── --}}
            <div style="display:flex; flex-wrap:wrap; align-items:center; gap:0 16px; margin-bottom:4px; font-size:10px;">
                <span style="font-weight:700;">Client type:</span>
                @foreach(['Citizen','Business','Government (Employee or another agency)'] as $ct)
                    <label style="display:inline-flex; align-items:center; gap:4px; cursor:pointer;">
                        <input type="radio" name="client_type" value="{{ $ct }}" class="csm-cb" {{ $clientType === $ct ? 'checked' : '' }}>
                        <span>{{ $ct }}</span>
                    </label>
                @endforeach
            </div>

            {{-- ── DATE · SEX · AGE · REGION ── --}}
            <div style="display:flex; flex-wrap:wrap; align-items:flex-end; gap:0 16px; margin-bottom:6px; font-size:10px;">
                <div style="display:flex; align-items:flex-end; gap:3px;">
                    <span style="font-weight:700;">Date:</span>
                    <input type="text" value="{{ $autoDate }}" disabled class="csm-field" style="width:90px;" title="Auto-generated">
                    <input type="hidden" name="feedback_date" value="{{ $autoDate }}">
                </div>
                <div style="display:flex; align-items:flex-end; gap:6px;">
                    <span style="font-weight:700;">Sex:</span>
                    <label style="display:inline-flex; align-items:center; gap:3px; cursor:pointer;">
                        <input type="radio" name="sex" value="Male" class="csm-cb" {{ $sex === 'Male' ? 'checked' : '' }}> <span>Male</span>
                    </label>
                    <label style="display:inline-flex; align-items:center; gap:3px; cursor:pointer;">
                        <input type="radio" name="sex" value="Female" class="csm-cb" {{ $sex === 'Female' ? 'checked' : '' }}> <span>Female</span>
                    </label>
                </div>
                <div style="display:flex; align-items:flex-end; gap:3px;">
                    <span style="font-weight:700;">Age:</span>
                    <input type="number" name="age" min="15" max="120" value="{{ $age }}" class="csm-field" style="width:40px; text-align:center;" placeholder="__">
                </div>
                <div style="display:flex; align-items:flex-end; gap:3px; flex:1; min-width:140px;">
                    <span style="font-weight:700; white-space:nowrap;">Region of residence:</span>
                    <input type="text" value="{{ $autoRegion }}" disabled class="csm-field" style="flex:1;">
                    <input type="hidden" name="region" value="{{ $autoRegion }}">
                </div>
            </div>

            <hr style="border:none; border-top:1px solid #374151; margin:2px 0 6px 0;">

            {{-- ══ SERVICES ══════════════════════════════════════════════════════════ --}}
            <div style="margin-bottom:4px;">
                <p style="text-align:center; font-weight:700; text-transform:uppercase; font-size:10px; letter-spacing:0.04em; margin-bottom:1px;">SERVICES</p>
                <p style="text-align:center; font-size:9.5px; margin-bottom:4px; color:#374151;">Please put a mark in the appropriate box corresponding to the service you have acquired.</p>
                
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0 18px; font-size:10px;">
                    {{-- LEFT COLUMN --}}
                    <div>
                        @php
                            $leftServices = [
                                'Registration of Cooperatives',
                                'Amendment of Articles of Cooperation and/or Bylaws',
                                'Establishment of Branch/Satellite',
                                'Merger/Consolidation',
                                'Recognition of Laboratory Cooperative',
                                'Issuance of Certificate of Compliance',
                            ];
                        @endphp
                        @foreach($leftServices as $svc)
                            <label class="csm-option-label">
                                <input type="checkbox" name="services[]" value="{{ $svc }}" class="csm-cb" style="margin-top:2px;" {{ in_array($svc, $servicesSelected) ? 'checked' : '' }}>
                                <span>{{ $svc }}</span>
                            </label>
                        @endforeach
                    </div>
                    {{-- RIGHT COLUMN --}}
                    <div>
                        @php
                            $rightServices = [
                                'Issuance of Accreditation of Cooperative External Auditor',
                                'Issuance of Accreditation of Cooperative External Provider',
                                'Request for Legal Services',
                                'Request for Technical Assistance',
                            ];
                        @endphp
                        @foreach($rightServices as $svc)
                            <label class="csm-option-label">
                                <input type="checkbox" name="services[]" value="{{ $svc }}" class="csm-cb" style="margin-top:2px;" {{ in_array($svc, $servicesSelected) ? 'checked' : '' }}>
                                <span>{{ $svc }}</span>
                            </label>
                        @endforeach
                        {{-- OTHERS --}}
                        <div style="display:flex; align-items:flex-end; gap:4px; margin-top:3px;">
                            <span style="white-space:nowrap; font-weight:600;">OTHERS,</span>
                            <span style="white-space:nowrap;">please specify</span>
                            <input type="text" name="service_other" value="{{ $serviceOther }}" class="csm-field" style="flex:1;">
                        </div>
                        <div style="border-bottom:1px solid #111827; margin-top:8px;"></div>
                    </div>
                </div>
            </div>

            <hr style="border:none; border-top:2px solid #111827; margin:6px 0;">

            {{-- ══ CITIZENS CHARTER ════════════════════════════════════════════════ --}}
            <div style="margin-bottom:4px; font-size:10px;">
                <p style="line-height:1.4; margin-bottom:6px; text-align:justify;">
                    <strong>INSTRUCTIONS:</strong> Please put a <strong>checkmark ( ✔ )</strong> on your answer to the Citizen's Charter (CC) questions. The Citizen's Charter is an official document that reflects the services of a government agency/office including its requirements, fees, and processing times among others.
                </p>
                
                {{-- CC1 --}}
                <div style="display:flex; gap:8px; margin-bottom:4px;">
                    <span style="font-weight:700; width:28px; flex-shrink:0;">CC1</span>
                    <div style="flex:1;">
                        <p style="font-weight:600; margin-bottom:2px;">Which of the following best describes your awareness of a CC?</p>
                        @foreach([
                            '1' => "1. I know what a CC is and I saw this office's CC.",
                            '2' => "2. I know what a CC is but I did NOT see this office's CC.",
                            '3' => "3. I learned of the CC only when I saw this office's CC.",
                            '4' => "4. I do not know what a CC is and I did not see one in this office. (Answer 'N/A' on CC2 and CC3)"
                        ] as $val => $label)
                            <label class="csm-option-label">
                                <input type="radio" name="cc1" value="{{ $val }}" id="cc1_{{ $val }}" class="csm-cb" style="margin-top:2px;" {{ $cc1 == $val ? 'checked' : '' }}>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- CC2 --}}
                <div style="display:flex; gap:8px; margin-bottom:4px;">
                    <span style="font-weight:700; width:28px; flex-shrink:0;">CC2</span>
                    <div style="flex:1;">
                        <p style="font-weight:600; margin-bottom:2px;">If aware of CC (answered 1-3 in CC1), would you say that the CC of this office was ...?</p>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0 14px;">
                            @foreach([
                                '1' => '1. Easy to see',
                                '4' => '4. Not visible at all',
                                '2' => '2. Somewhat easy to see',
                                '5' => '5. N/A',
                                '3' => '3. Difficult to see'
                            ] as $val => $label)
                                <label class="csm-option-label" style="align-items:center;">
                                    <input type="radio" name="cc2" value="{{ $val }}" id="cc2_{{ $val }}" class="csm-cb" {{ $cc2 == $val ? 'checked' : '' }}>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- CC3 --}}
                <div style="display:flex; gap:8px; margin-bottom:2px;">
                    <span style="font-weight:700; width:28px; flex-shrink:0;">CC3</span>
                    <div style="flex:1;">
                        <p style="font-weight:600; margin-bottom:2px;">If aware of CC (answered codes 1-3 in CC1), how much did the CC help you in your transaction?</p>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0 14px;">
                            @foreach([
                                '1' => '1. Helped very much',
                                '3' => '3. Did not help',
                                '2' => '2. Somewhat helped',
                                '4' => '4. N/A'
                            ] as $val => $label)
                                <label class="csm-option-label" style="align-items:center;">
                                    <input type="radio" name="cc3" value="{{ $val }}" id="cc3_{{ $val }}" class="csm-cb" {{ $cc3 == $val ? 'checked' : '' }}>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ SQD RATING TABLE ══ --}}
            <div>
                <p style="font-size:10px; margin-bottom:2px; font-weight:700;">INSTRUCTIONS:</p>
                <p style="font-size:10px; margin-bottom:4px;">
                    Please put a <strong>checkmark ( ✔ )</strong> on the column that best corresponds to your answer.
                </p>
                <table class="sqd-table">
                    <thead>
                        <tr>
                            <th style="width:36%; text-align:left; padding:4px 5px;"></th>
                            {{-- Strongly Disagree --}}
                            <th style="width:10.5%;">
                                <div style="display:flex; flex-direction:column; align-items:center; gap:2px; padding:3px 1px;">
                                    <svg width="24" height="24" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="18" cy="18" r="15"/><line x1="9" y1="11" x2="14" y2="14" stroke-linecap="round"/><line x1="27" y1="11" x2="22" y2="14" stroke-linecap="round"/><circle cx="12" cy="18" r="1.5" fill="currentColor" stroke="none"/><circle cx="24" cy="18" r="1.5" fill="currentColor" stroke="none"/><path d="M11 27 Q18 21 25 27" stroke-linecap="round" fill="none"/></svg>
                                    <span>Strongly<br>Disagree</span>
                                </div>
                            </th>
                            {{-- Disagree --}}
                            <th style="width:10.5%;">
                                <div style="display:flex; flex-direction:column; align-items:center; gap:2px; padding:3px 1px;">
                                    <svg width="24" height="24" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="18" cy="18" r="15"/><circle cx="12" cy="16" r="1.5" fill="currentColor" stroke="none"/><circle cx="24" cy="16" r="1.5" fill="currentColor" stroke="none"/><path d="M12 27 Q18 22 24 27" stroke-linecap="round" fill="none"/></svg>
                                    <span>Disagree</span>
                                </div>
                            </th>
                            {{-- Neither --}}
                            <th style="width:11%;">
                                <div style="display:flex; flex-direction:column; align-items:center; gap:2px; padding:3px 1px;">
                                    <svg width="24" height="24" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="18" cy="18" r="15"/><circle cx="12" cy="16" r="1.5" fill="currentColor" stroke="none"/><circle cx="24" cy="16" r="1.5" fill="currentColor" stroke="none"/><line x1="12" y1="25" x2="24" y2="25" stroke-linecap="round"/></svg>
                                    <span>Neither Agree<br>nor Disagree</span>
                                </div>
                            </th>
                            {{-- Agree --}}
                            <th style="width:10.5%;">
                                <div style="display:flex; flex-direction:column; align-items:center; gap:2px; padding:3px 1px;">
                                    <svg width="24" height="24" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="18" cy="18" r="15"/><circle cx="12" cy="16" r="1.5" fill="currentColor" stroke="none"/><circle cx="24" cy="16" r="1.5" fill="currentColor" stroke="none"/><path d="M12 24 Q18 29 24 24" stroke-linecap="round" fill="none"/></svg>
                                    <span>Agree</span>
                                </div>
                            </th>
                            {{-- Strongly Agree --}}
                            <th style="width:10.5%;">
                                <div style="display:flex; flex-direction:column; align-items:center; gap:2px; padding:3px 1px;">
                                    <svg width="24" height="24" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="18" cy="18" r="15"/><circle cx="12" cy="16" r="1.5" fill="currentColor" stroke="none"/><circle cx="24" cy="16" r="1.5" fill="currentColor" stroke="none"/><path d="M12 24 Q18 31 24 24" stroke-linecap="round" fill="none"/></svg>
                                    <span>Strongly<br>Agree</span>
                                </div>
                            </th>
                            {{-- N/A --}}
                            <th style="width:11%;">
                                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:1px; padding:3px 1px;">
                                    <span style="font-weight:700;">N/A</span>
                                    <span>Not<br>Applicable</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($questions as $key => $question)
                        <tr>
                            <td>{{ $question }}</td>
                            <td><input type="radio" name="sqd{{ $key }}" value="1" class="csm-rate"></td>
                            <td><input type="radio" name="sqd{{ $key }}" value="2" class="csm-rate"></td>
                            <td><input type="radio" name="sqd{{ $key }}" value="3" class="csm-rate"></td>
                            <td><input type="radio" name="sqd{{ $key }}" value="4" class="csm-rate"></td>
                            <td><input type="radio" name="sqd{{ $key }}" value="5" class="csm-rate"></td>
                            <td><input type="radio" name="sqd{{ $key }}" value="N/A" class="csm-rate"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ══ BOTTOM CONTENT ══ --}}
            <div class="sqd-bottom" style="font-size:10px;">
                <div style="margin-bottom:4px;">
                    <p style="margin-bottom:3px;">Suggestions on how we can further improve our services (optional):</p>
                    <div style="border-bottom:1px solid #111827; margin-bottom:10px; min-height:14px;">
                        <textarea name="suggestions" rows="1" style="width:100%; border:none; background:transparent; font-size:10px; outline:none; resize:none; padding:0; font-family:inherit; line-height:1.4;">{{ $suggestions }}</textarea>
                    </div>
                    <div style="border-bottom:1px solid #111827; margin-bottom:12px;"></div>
                </div>
                <div style="display:flex; align-items:flex-end; gap:4px; margin-bottom:14px;">
                    <span>Email address (optional):</span>
                    <input type="email" name="email" value="{{ $email }}" class="csm-field" style="width:240px;">
                </div>
                <p style="text-align:center; font-weight:900; font-size:14px; margin:0; color:#111827;">THANK YOU!</p>

                {{-- ── ACTION BUTTONS (hidden in print) ── --}}
                <div class="no-print" style="margin-top:14px; padding-top:12px; border-top:1px solid #e5e7eb; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:8px;">
                    <a href="{{ url('/') }}"
                       class="px-4 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 text-center transition">
                       Cancel &amp; Return
                    </a>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <button type="submit" id="submitBtn"
                                class="inline-flex items-center justify-center gap-1.5 px-5 py-1.5 rounded-lg text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow shadow-blue-500/25 transition cursor-pointer">
                            Submit Feedback
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

{{-- ═══ FOOTER (hidden on print) ════════════════════════════════════════════ --}}
<footer class="no-print mt-4 py-3 border-t border-gray-300 text-center text-[10px] text-gray-500">
    &copy; {{ $year }} Cooperative Development Authority – Information &amp; Communications Technology Division.
</footer>

<script>
    // CC1 option 4 → auto-select N/A on CC2 and CC3
    document.querySelectorAll('input[name="cc1"]').forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === '4' && this.checked) {
                const cc2NA = document.getElementById('cc2_5');
                const cc3NA = document.getElementById('cc3_4');
                if (cc2NA) cc2NA.checked = true;
                if (cc3NA) cc3NA.checked = true;
            }
        });
    });
</script>

</body>
</html>
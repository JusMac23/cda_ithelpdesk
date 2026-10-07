<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>

    @can('evaluate_databreach')
    <div id="main-content" class="w-full">
        <div class="max-w-6xl mx-auto space-y-6">

            {{-- Main Form Card --}}
            <div class="relative bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-5 sm:p-8 lg:p-10">

                {{-- Close / Back button --}}
                <button id="close" type="button" onclick="window.location.href='{{ route('databreach.index') }}'"
                    class="absolute top-5 right-5 sm:top-8 sm:right-8 w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 flex items-center justify-center transition-all cursor-pointer shadow-xs"
                    aria-label="Close form"
                    title="Return to Incident List">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>

                {{-- Header Section --}}
                <div class="flex items-center gap-3.5 pb-6 mb-8 border-b border-[var(--border-subtle)] pr-12">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-violet-600 via-purple-600 to-indigo-600 text-white flex items-center justify-center shadow-md shadow-violet-500/20 shrink-0">
                        <span class="material-symbols-outlined text-2xl">rate_review</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                                Incident Report Evaluation
                            </h1>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-violet-50 dark:bg-violet-950/50 text-violet-700 dark:text-violet-300 border border-violet-200 dark:border-violet-800/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-violet-500"></span>
                                {{ $notification->dbn_number }}
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                            Examine breach notifications, verify incident details, and record evaluation findings
                        </p>
                    </div>
                </div>

                {{-- Stepper Progress Navigation --}}
                <div class="mb-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-6">
                        <!-- Step 1 Button -->
                        <button type="button" id="step-indicator-1" onclick="switchPage(1)"
                            class="group text-left p-3.5 sm:p-4 rounded-2xl border-2 transition-all flex items-center gap-3.5 border-violet-600 bg-violet-50/50 dark:bg-violet-950/30 cursor-pointer">
                            <div id="step-badge-1"
                                class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center font-bold text-sm bg-violet-600 text-white shrink-0 shadow-sm shadow-violet-500/20 transition-all">
                                1
                            </div>
                            <div class="min-w-0">
                                <span id="step-sub-1" class="block text-[11px] font-bold uppercase tracking-wider text-violet-600 dark:text-violet-400">Step A</span>
                                <span id="step-title-1" class="block text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate">Notification Type</span>
                            </div>
                        </button>

                        <!-- Step 2 Button -->
                        <button type="button" id="step-indicator-2" onclick="switchPage(2)"
                            class="group text-left p-3.5 sm:p-4 rounded-2xl border-2 transition-all flex items-center gap-3.5 border-slate-200 dark:border-slate-800 bg-transparent hover:border-slate-300 dark:hover:border-slate-700 cursor-pointer">
                            <div id="step-badge-2"
                                class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center font-bold text-sm bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 shrink-0 transition-all">
                                2
                            </div>
                            <div class="min-w-0">
                                <span id="step-sub-2" class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Step B</span>
                                <span id="step-title-2" class="block text-xs sm:text-sm font-bold text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white truncate transition-colors">Evaluation Details</span>
                            </div>
                        </button>
                    </div>
                </div>

                {{-- Validation Errors Banner --}}
                @if ($errors->any())
                    <div class="mb-8 p-4 sm:p-5 rounded-2xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/60 text-red-900 dark:text-red-200 flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-xl">error</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-bold text-red-800 dark:text-red-300 m-0">Please resolve the following errors:</h4>
                            <ul class="mt-2 text-xs sm:text-sm space-y-1 list-disc list-inside text-red-700 dark:text-red-400 font-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Main Evaluation Form --}}
                <form action="{{ route('databreach.update_evaluation', $notification->dbn_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- PAGE 1: Notification Type --}}
                    <div id="page1" class="transition-opacity duration-300">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-violet-100 dark:bg-violet-950/80 text-violet-700 dark:text-violet-300">Part A</span>
                            <h2 class="text-lg font-bold text-[var(--text-dark)] m-0">Notification Type & Baseline Information</h2>
                        </div>

                        {{-- DBN Number --}}
                        <div class="mb-6">
                            <label for="dbn_number" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                DBN Number <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="dbn_number" name="dbn_number"
                                    value="{{ old('dbn_number', $notification->dbn_number) }}"
                                    placeholder="e.g., CDA-DBN-2025-01" required readonly
                                    class="w-full px-4 py-2.5 bg-slate-100/80 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-mono font-bold text-slate-700 dark:text-slate-300 cursor-not-allowed select-none">
                                <span class="material-symbols-outlined absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">lock</span>
                            </div>
                        </div>

                        {{-- PIC and PIC Email --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label for="pic" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                    Personal Information Controller <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <div class="relative">
                                    <select id="pic" name="pic" required
                                        class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 transition-all appearance-none cursor-pointer pr-10">
                                        <option value="">-- Select PIC --</option>
                                        @foreach ([
                                            'CDA HO', 'CDA CAR', 'CDA NIR', 'CDA NCR', 'CDA Region I', 'CDA Region II',
                                            'CDA Region III', 'CDA Region IV-A', 'CDA Region IV-B', 'CDA Region V',
                                            'CDA Region VI', 'CDA Region VII', 'CDA Region VIII', 'CDA Region IX',
                                            'CDA Region X', 'CDA Region XI', 'CDA Region XII', 'CDA Region XIII'
                                        ] as $picOption)
                                            <option value="{{ $picOption }}" {{ old('pic', $notification->pic) == $picOption ? 'selected' : '' }}>
                                                {{ $picOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">expand_more</span>
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                    Email Address <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="email" name="email" value="{{ old('email', $notification->team_email ?? $notification->email) }}" required readonly
                                        class="w-full px-4 py-2.5 bg-slate-100/80 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 cursor-not-allowed select-none">
                                    <span class="material-symbols-outlined absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">mail</span>
                                </div>
                            </div>
                        </div>

                        {{-- Representative & Representative Email --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label for="representative" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                    Representative <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="representative" name="representative" value="{{ old('representative', $notification->representative) }}" readonly
                                        class="w-full px-4 py-2.5 bg-slate-100/80 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 cursor-not-allowed select-none">
                                    <span class="material-symbols-outlined absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">person</span>
                                </div>
                            </div>

                            <div>
                                <label for="representative_email_address" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                    Representative Email <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="representative_email_address" name="representative_email_address" value="{{ old('representative_email_address', $notification->representative_email_address) }}" readonly
                                        class="w-full px-4 py-2.5 bg-slate-100/80 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 cursor-not-allowed select-none">
                                    <span class="material-symbols-outlined absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">alternate_email</span>
                                </div>
                            </div>
                        </div>

                        {{-- Key Incident Dates --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
                            <div>
                                <label for="date_occurrence" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                    Date of Occurrence <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <input type="datetime-local" id="date_occurrence" name="date_occurrence"
                                    value="{{ old('date_occurrence', $notification->date_occurrence) }}" required
                                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 transition-all">
                            </div>

                            <div>
                                <label for="date_discovery" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                    Date of Discovery <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <input type="datetime-local" id="date_discovery" name="date_discovery"
                                    value="{{ old('date_discovery', $notification->date_discovery) }}" required
                                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 transition-all">
                            </div>

                            <div>
                                <label for="date_notification" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                    Date of Notification <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <input type="datetime-local" id="date_notification" name="date_notification"
                                    value="{{ old('date_notification', $notification->date_notification) }}" required readonly
                                    class="w-full px-4 py-2.5 bg-slate-100/80 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 cursor-not-allowed select-none">
                            </div>
                        </div>

                        {{-- Brief Summary of the Incident --}}
                        <div class="mb-6">
                            <label for="brief_summary" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                Brief Summary of the Incident <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <textarea id="brief_summary" name="brief_summary" required rows="4"
                                placeholder="Provide a brief summary of the incident..."
                                class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-[var(--text-dark)] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 transition-all resize-y min-h-[110px]">{{ old('brief_summary', $notification->brief_summary) }}</textarea>
                        </div>

                        {{-- Notification Type Criteria --}}
                        <div class="mb-8">
                            <label class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-3">
                                Notification Type Criteria
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                                @php
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
                                    <label class="relative flex items-start gap-3 p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/30 hover:border-violet-300 dark:hover:border-violet-700/60 hover:bg-white dark:hover:bg-slate-800/60 cursor-pointer transition-all has-checked:border-violet-600 has-checked:bg-violet-50/40 dark:has-checked:bg-violet-950/20 shadow-2xs">
                                        <input type="checkbox" name="notification_type_description[]" value="{{ $option }}"
                                            {{ is_array($notifTypes) && in_array($option, $notifTypes) ? 'checked' : '' }}
                                            class="w-4 h-4 mt-0.5 rounded text-violet-600 focus:ring-violet-500 border-slate-300 dark:border-slate-700 shrink-0 cursor-pointer">
                                        <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 leading-snug select-none">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Page 1 Footer --}}
                        <div class="pt-6 border-t border-[var(--border-subtle)] flex justify-end">
                            <button type="button" id="next-page"
                                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-violet-600 hover:bg-violet-700 shadow-md shadow-violet-500/20 hover:shadow-violet-500/30 transition-all cursor-pointer">
                                <span>Continue to Evaluation Details</span>
                                <span class="material-symbols-outlined text-lg">arrow_forward</span>
                            </button>
                        </div>
                    </div>

                    {{-- PAGE 2: Evaluation Details --}}
                    <div id="page2" class="hidden transition-opacity duration-300">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-violet-100 dark:bg-violet-950/80 text-violet-700 dark:text-violet-300">Part B</span>
                            <h2 class="text-lg font-bold text-[var(--text-dark)] m-0">Data Breach Notification Details & Impact Evaluation</h2>
                        </div>

                        {{-- Sector & Subsector --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label for="sector_name" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                    Sector Name <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <input type="text" id="sector_name" name="sector_name" value="{{ old('sector_name', $notification->sector_name) }}" required
                                    placeholder="e.g., Government"
                                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-[var(--text-dark)] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 transition-all">
                            </div>

                            <div>
                                <label for="subsector_name" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                    Subsector Name <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <input type="text" id="subsector_name" name="subsector_name" value="{{ old('subsector_name', $notification->subsector_name) }}" required
                                    placeholder="e.g., Cooperatives"
                                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-[var(--text-dark)] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 transition-all">
                            </div>
                        </div>

                        {{-- Notification Type & Timeliness --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label for="notification_type" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                    Notification Type <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <div class="relative">
                                    <select id="notification_type" name="notification_type" required
                                        class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 transition-all appearance-none cursor-pointer pr-10">
                                        <option value="">-- Select Notification Type --</option>
                                        <option value="Mandatory" {{ old('notification_type', $notification->notification_type) == 'Mandatory' ? 'selected' : '' }}>Mandatory</option>
                                        <option value="Voluntary" {{ old('notification_type', $notification->notification_type) == 'Voluntary' ? 'selected' : '' }}>Voluntary</option>
                                        <option value="Others" {{ old('notification_type', $notification->notification_type) == 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">expand_more</span>
                                </div>
                            </div>

                            <div>
                                <label for="timeliness" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                    Timeliness <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <input type="text" id="timeliness" name="timeliness" value="{{ old('timeliness', $notification->timeliness) }}" required
                                    placeholder="e.g., Within 72 hours"
                                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-[var(--text-dark)] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 transition-all">
                            </div>
                        </div>

                        {{-- Causes & General Incident --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label for="general_cause" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                    General Cause <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <div class="relative">
                                    <select id="general_cause" name="general_cause" required
                                        class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 transition-all appearance-none cursor-pointer pr-10">
                                        <option value="">-- Select General Cause --</option>
                                        @foreach (['Malicious Attack', 'Malicious Attack/Human Error', 'Human Error', 'System Glitch', 'Malicious Attack/System Glitch', 'System Glitch/Human Error', 'Others'] as $cause)
                                            <option value="{{ $cause }}" {{ old('general_cause', $notification->general_cause) == $cause ? 'selected' : '' }}>
                                                {{ $cause }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">expand_more</span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label for="specific_cause" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                        Specific Cause <span class="text-rose-500 font-bold">*</span>
                                    </label>
                                    <div class="relative">
                                        <select id="specific_cause" name="specific_cause" required
                                            class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 transition-all appearance-none cursor-pointer pr-10">
                                            <option value="{{ old('specific_cause', $notification->specific_cause) }}">{{ old('specific_cause', $notification->specific_cause) ?: '-- Select Specific Cause --' }}</option>
                                        </select>
                                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">expand_more</span>
                                    </div>
                                </div>

                                <div>
                                    <label for="general_incident" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                        General Incident <span class="text-rose-500 font-bold">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="general_incident" id="general_incident" required readonly
                                            value="{{ old('general_incident', $notification->general_incident) }}"
                                            class="w-full px-4 py-2.5 bg-slate-100/80 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 cursor-not-allowed select-none">
                                        <span class="material-symbols-outlined absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">auto_awesome</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- With Request? --}}
                        <div class="mb-6">
                            <label class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                With Request? <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <div class="flex items-center gap-4">
                                <label class="relative flex items-center gap-2.5 px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 hover:border-violet-400 dark:hover:border-violet-600 cursor-pointer transition-all has-checked:border-violet-600 has-checked:bg-violet-50/50 dark:has-checked:bg-violet-950/30">
                                    <input type="radio" name="with_request" value="Yes" class="w-4 h-4 text-violet-600 focus:ring-violet-500 border-slate-300 dark:border-slate-700" {{ old('with_request', $notification->with_request) == 'Yes' ? 'checked' : '' }}>
                                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">Yes</span>
                                </label>
                                <label class="relative flex items-center gap-2.5 px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 hover:border-violet-400 dark:hover:border-violet-600 cursor-pointer transition-all has-checked:border-violet-600 has-checked:bg-violet-50/50 dark:has-checked:bg-violet-950/30">
                                    <input type="radio" name="with_request" value="No" class="w-4 h-4 text-violet-600 focus:ring-violet-500 border-slate-300 dark:border-slate-700" {{ old('with_request', $notification->with_request) == 'No' ? 'checked' : '' }}>
                                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">No</span>
                                </label>
                            </div>
                        </div>

                        {{-- Evaluation Detailed Sections --}}
                        @php
                            $fieldSections = [
                                'Incident Analysis & Scope' => [
                                    'how_breach_occured' => '1.A How Breach Occurred + DPS Vulnerability',
                                    'chronology' => '1.B Chronology',
                                    'num_records' => '1.C Number of Data Subject / Records',
                                    'description_nature' => '1.D Description / Nature',
                                    'likely_consequences' => '1.E Likely Consequences',
                                    'dpo' => '1.F Data Protection Officer (DPO)',
                                ],
                                'Sensitive Personal Information (SPI)' => [
                                    'spi' => '2.A SPI',
                                    'other_info' => '2.B Other Information',
                                ],
                                'Incident Response & Mitigation' => [
                                    'measures_to_address' => '3.A Measures to Address the Breach',
                                    'measures_to_secure' => '3.B Measures to Secure/Recover Personal Data',
                                    'actions_to_mitigate' => '3.C Actions to Mitigate Harm',
                                    'actions_to_inform' => '3.D Actions to Inform Data Subjects',
                                    'actions_to_prevent' => '3.E Measures to Prevent Recurrence of Incidence',
                                ]
                            ];
                        @endphp

                        @foreach ($fieldSections as $sectionTitle => $fields)
                            <div class="mb-8 p-5 sm:p-6 rounded-2xl bg-slate-50/40 dark:bg-slate-800/20 border border-slate-200/80 dark:border-slate-800/80 space-y-5">
                                <div class="flex items-center gap-2 border-b border-slate-200/60 dark:border-slate-700/60 pb-3">
                                    <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 m-0">{{ $sectionTitle }}</h3>
                                </div>

                                @foreach ($fields as $name => $label)
                                    <div>
                                        <label for="{{ $name }}" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                            {{ $label }} <span class="text-rose-500 font-bold">*</span>
                                        </label>

                                        @if (strtolower($name) === 'dpo')
                                            {{-- DPO details card --}}
                                            <div id="{{ $name }}" class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xs space-y-3">
                                                <div class="flex items-center gap-2 text-violet-600 dark:text-violet-400 font-semibold text-xs tracking-wider uppercase">
                                                    <span class="material-symbols-outlined text-base">badge</span>
                                                    <span>Assigned Data Protection Officer</span>
                                                </div>
                                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm pt-2 border-t border-slate-100 dark:border-slate-800">
                                                    <div>
                                                        <span class="text-xs text-slate-400 block font-medium">Name</span>
                                                        <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $dpoDetails->name ?? 'No DPO Assigned' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-xs text-slate-400 block font-medium">Email Address</span>
                                                        <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $dpoDetails->email ?? 'N/A' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-xs text-slate-400 block font-medium">Contact Number</span>
                                                        <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $dpoDetails->contact_number ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Hidden fallback for payload --}}
                                            <input type="hidden" name="{{ $name }}" value="{{ $dpoDetails->name ?? '' }} | {{ $dpoDetails->email ?? '' }}">
                                        @else
                                            <textarea id="{{ $name }}" name="{{ $name }}" rows="3"
                                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-[var(--text-dark)] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 transition-all resize-y min-h-[90px]"
                                                placeholder="Provide detailed description...">{{ old($name, $notification->$name ?? '') }}</textarea>
                                        @endif
                                    </div>

                                    {{-- Insert extra field right after num_records --}}
                                    @if ($name === 'num_records')
                                        <div>
                                            <label for="num_records_provide_details" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                                Number of Records - Provide Details <span class="text-rose-500 font-bold">*</span>
                                            </label>
                                            <textarea name="num_records_provide_details" id="num_records_provide_details" rows="3"
                                                placeholder="e.g., 1,000 employees consisting of names, contact details, and identification numbers."
                                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-[var(--text-dark)] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 transition-all resize-y min-h-[90px]"
                                                required>{{ old('num_records_provide_details', $notification->num_records_provide_details ?? '') }}</textarea>

                                            @error('num_records_provide_details')
                                                <span class="text-rose-500 text-xs font-semibold mt-1.5 block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach

                        {{-- Record Type & Data Subjects --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                            <div>
                                <label for="record_type" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                    Record Type <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <div class="relative">
                                    <select id="record_type" name="record_type" required
                                        class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 transition-all appearance-none cursor-pointer pr-10">
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
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">expand_more</span>
                                </div>
                            </div>

                            <div>
                                <label for="data_subjects" class="block text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                                    Data Subjects <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <div class="relative">
                                    <select id="data_subjects" name="data_subjects" required
                                        class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 transition-all appearance-none cursor-pointer pr-10">
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
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">expand_more</span>
                                </div>
                            </div>
                        </div>

                        {{-- Page 2 Footer Actions --}}
                        <div class="pt-6 border-t border-[var(--border-subtle)] flex flex-col sm:flex-row items-center justify-between gap-4">
                            <button type="button" id="prev-page"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 transition-all cursor-pointer">
                                <span class="material-symbols-outlined text-lg">arrow_back</span>
                                <span>Back to Step A</span>
                            </button>

                            <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-violet-600 hover:bg-violet-700 shadow-md shadow-violet-500/20 hover:shadow-violet-500/30 transition-all cursor-pointer">
                                <span class="material-symbols-outlined text-lg">check_circle</span>
                                <span>Save Evaluation</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // STEP NAVIGATION LOGIC
        function switchPage(pageNumber) {
            const page1 = document.getElementById('page1');
            const page2 = document.getElementById('page2');
            const stepInd1 = document.getElementById('step-indicator-1');
            const stepInd2 = document.getElementById('step-indicator-2');
            const stepBadge1 = document.getElementById('step-badge-1');
            const stepBadge2 = document.getElementById('step-badge-2');
            const stepSub1 = document.getElementById('step-sub-1');
            const stepSub2 = document.getElementById('step-sub-2');
            const stepTitle1 = document.getElementById('step-title-1');
            const stepTitle2 = document.getElementById('step-title-2');

            if (pageNumber === 1) {
                if (page1) page1.classList.remove('hidden');
                if (page2) page2.classList.add('hidden');

                if (stepInd1) {
                    stepInd1.className = 'group text-left p-3.5 sm:p-4 rounded-2xl border-2 transition-all flex items-center gap-3.5 border-violet-600 bg-violet-50/50 dark:bg-violet-950/30 cursor-pointer';
                    stepBadge1.className = 'w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center font-bold text-sm bg-violet-600 text-white shrink-0 shadow-sm shadow-violet-500/20 transition-all';
                    stepBadge1.innerHTML = '1';
                    stepSub1.className = 'block text-[11px] font-bold uppercase tracking-wider text-violet-600 dark:text-violet-400';
                    stepTitle1.className = 'block text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate';
                }

                if (stepInd2) {
                    stepInd2.className = 'group text-left p-3.5 sm:p-4 rounded-2xl border-2 transition-all flex items-center gap-3.5 border-slate-200 dark:border-slate-800 bg-transparent hover:border-slate-300 dark:hover:border-slate-700 cursor-pointer';
                    stepBadge2.className = 'w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center font-bold text-sm bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 shrink-0 transition-all';
                    stepBadge2.innerHTML = '2';
                    stepSub2.className = 'block text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500';
                    stepTitle2.className = 'block text-xs sm:text-sm font-bold text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white truncate transition-colors';
                }
            } else if (pageNumber === 2) {
                if (page1) page1.classList.add('hidden');
                if (page2) page2.classList.remove('hidden');

                if (stepInd1) {
                    stepInd1.className = 'group text-left p-3.5 sm:p-4 rounded-2xl border-2 transition-all flex items-center gap-3.5 border-emerald-500/80 bg-emerald-50/40 dark:bg-emerald-950/20 hover:border-emerald-500 cursor-pointer';
                    stepBadge1.className = 'w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center font-bold text-sm bg-emerald-600 text-white shrink-0 shadow-sm shadow-emerald-500/20 transition-all';
                    stepBadge1.innerHTML = '<span class="material-symbols-outlined text-lg">check</span>';
                    stepSub1.className = 'block text-[11px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400';
                    stepTitle1.className = 'block text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 truncate';
                }

                if (stepInd2) {
                    stepInd2.className = 'group text-left p-3.5 sm:p-4 rounded-2xl border-2 transition-all flex items-center gap-3.5 border-violet-600 bg-violet-50/50 dark:bg-violet-950/30 cursor-pointer';
                    stepBadge2.className = 'w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center font-bold text-sm bg-violet-600 text-white shrink-0 shadow-sm shadow-violet-500/20 transition-all';
                    stepBadge2.innerHTML = '2';
                    stepSub2.className = 'block text-[11px] font-bold uppercase tracking-wider text-violet-600 dark:text-violet-400';
                    stepTitle2.className = 'block text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate';
                }
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        document.addEventListener("DOMContentLoaded", function () {
            const nextPageBtn = document.getElementById('next-page');
            const prevPageBtn = document.getElementById('prev-page');

            if (nextPageBtn) {
                nextPageBtn.addEventListener('click', () => switchPage(2));
            }

            if (prevPageBtn) {
                prevPageBtn.addEventListener('click', () => switchPage(1));
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

            const year = parts.find(p => p.type === 'year')?.value;
            const month = parts.find(p => p.type === 'month')?.value;
            const day = parts.find(p => p.type === 'day')?.value;
            const hour = parts.find(p => p.type === 'hour')?.value;
            const minute = parts.find(p => p.type === 'minute')?.value;
            if (year && month && day && hour && minute) {
                const manilaDateTime = `${year}-${month}-${day}T${hour}:${minute}`;
                const dateTimeFields = ['date_time_of_containment', 'incident_verified_date'];
                dateTimeFields.forEach(id => {
                    const el = document.getElementById(id);
                    if (el && !el.value) el.value = manilaDateTime;
                });
            }

            // INCIDENT CAUSE & SPECIFIC CAUSE OPTIONS
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

            function populateSpecificCauses(selectedType, currentSelectedValue) {
                if (!incidentCategory) return;
                incidentCategory.innerHTML = '<option value="">-- Select Specific Cause --</option>';

                if (categoryOptions[selectedType]) {
                    categoryOptions[selectedType].forEach(category => {
                        const option = document.createElement("option");
                        option.value = category;
                        option.textContent = category;
                        if (currentSelectedValue && currentSelectedValue === category) {
                            option.selected = true;
                        }
                        incidentCategory.appendChild(option);
                    });
                }
            }

            if (incidentTypeEl && incidentCategory) {
                const initialGeneralCause = incidentTypeEl.value;
                const initialSpecificCause = incidentCategory.value;

                if (initialGeneralCause) {
                    populateSpecificCauses(initialGeneralCause, initialSpecificCause);
                }

                incidentTypeEl.addEventListener("change", function () {
                    populateSpecificCauses(this.value, '');
                });
            }

            // GENERAL INCIDENT AUTO-TYPE MAPPING
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

            if (specificCauseEl && generalIncidentEl) {
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
                    let firstInvalidField = null;
                    const requiredFields = this.querySelectorAll('[required]');

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('border-red-500', 'ring-2', 'ring-red-500/20');
                            if (!firstInvalidField) firstInvalidField = field;
                        } else {
                            field.classList.remove('border-red-500', 'ring-2', 'ring-red-500/20');
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Missing Required Information',
                            text: 'Please fill in all required fields marked with * before submitting.',
                            confirmButtonColor: '#7c3aed',
                            background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a'
                        });

                        // If the first invalid field is in page 1, switch to page 1
                        const p1 = document.getElementById('page1');
                        if (firstInvalidField && p1 && p1.contains(firstInvalidField)) {
                            switchPage(1);
                        } else {
                            switchPage(2);
                        }
                    }
                });

                // Real-time validation blur effect
                form.querySelectorAll('[required]').forEach(field => {
                    field.addEventListener('blur', function () {
                        if (!this.value.trim()) {
                            this.classList.add('border-red-500', 'ring-2', 'ring-red-500/20');
                        } else {
                            this.classList.remove('border-red-500', 'ring-2', 'ring-red-500/20');
                        }
                    });

                    field.addEventListener('input', function () {
                        if (this.value.trim()) {
                            this.classList.remove('border-red-500', 'ring-2', 'ring-red-500/20');
                        }
                    });
                });
            }
        });
    </script>
    @endcan
</x-app-layout>
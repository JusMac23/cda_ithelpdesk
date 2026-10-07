<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>

    @can('view_databreach')
    <div id="main-content" class="w-full">
        <div class="max-w-5xl mx-auto space-y-6">

            {{-- Main View Container --}}
            <div class="bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-4 sm:p-6 lg:p-8">

                {{-- Header Title Banner --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-[var(--border-light)]">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-rose-600 via-red-600 to-orange-500 text-white flex items-center justify-center shadow-md shadow-rose-500/20 shrink-0">
                            <span class="material-symbols-outlined text-2xl">shield_with_heart</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                                    DBN #{{ $notification->dbn_number }}
                                </h1>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 border border-rose-200/60 dark:border-rose-800/60">
                                    Breach Report
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                                Data Breach Incident Report — full notification overview and incident details
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        @can('generate_databreach')
                        <a href="{{ route('databreach.generatePdf', $notification->dbn_id) }}"
                            class="inline-flex items-center justify-center gap-1.5 h-10 px-4 rounded-xl text-xs sm:text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white shadow-sm transition-all">
                            <span class="material-symbols-outlined text-base">download</span>
                            <span>Download PDF</span>
                        </a>
                        @endcan
                        <a href="{{ route('databreach.index') }}" id="close"
                            class="inline-flex items-center justify-center gap-1.5 h-10 px-4 rounded-xl text-xs sm:text-sm font-semibold bg-slate-100 dark:bg-slate-800 text-[var(--text-muted)] hover:text-[var(--text-dark)] hover:bg-slate-200 dark:hover:bg-slate-700 active:scale-95 transition-all cursor-pointer">
                            <span class="material-symbols-outlined text-lg">arrow_back</span>
                            <span>Back</span>
                        </a>
                    </div>
                </div>

                {{-- Status Summary Bar --}}
                <div class="flex flex-wrap items-center justify-between gap-4 p-4 mt-6 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-[var(--border-light)]">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">Type:</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            {{ $notification->notification_type }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">Sector:</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-violet-50 text-violet-700 border border-violet-200 dark:bg-violet-950/40 dark:text-violet-300 dark:border-violet-800">
                            {{ $notification->sector_name }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">Records:</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold tracking-wide bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800">
                            {{ $notification->num_records }} @if($notification->hundred_plus) (≥100) @endif
                        </span>
                    </div>
                </div>

                {{-- Brief Summary Banner --}}
                <div class="mt-6 p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-rose-50 to-orange-50 dark:from-rose-950/30 dark:to-orange-950/20 border border-rose-200/70 dark:border-rose-800/40">
                    <span class="block text-xs font-bold uppercase tracking-widest text-rose-600 dark:text-rose-400 mb-2">Brief Summary / Scenario</span>
                    <p class="text-sm sm:text-base font-medium text-[var(--text-dark)] m-0 leading-relaxed">
                        {{ $notification->brief_summary }}
                    </p>
                </div>

                {{-- Card A: Notification Overview --}}
                <div class="mt-6 rounded-2xl p-5 sm:p-6 border border-[var(--border-light)] bg-[var(--card-bg)] shadow-2xs">
                    <h2 class="text-base sm:text-lg font-bold text-[var(--text-dark)] m-0 mb-5 flex items-center gap-2 pb-3 border-b border-[var(--border-subtle)]">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                        <span>A. Notification Overview</span>
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">DBN Number</span>
                            <div class="text-sm font-bold text-[var(--text-dark)] font-mono bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $notification->dbn_number }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Personal Information Controller (PIC)</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $notification->pic }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">PIC Email Address</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $notification->email }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Representative</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $notification->representative }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Representative Email</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $notification->representative_email_address }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Date / Time of Occurrence</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] font-mono bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $notification->date_occurrence->format('F d, Y – h:i A') }}
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Date / Time of Discovery</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] font-mono bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $notification->date_discovery->format('F d, Y – h:i A') }}
                            </div>
                        </div>
                    </div>

                    @if(!empty($notification->notification_type_description))
                        @php
                            $rawDescription = $notification->notification_type_description;
                            if (is_array($rawDescription)) {
                                $types = $rawDescription;
                            } elseif (is_string($rawDescription)) {
                                $types = json_decode($rawDescription, true);
                                if (!is_array($types)) {
                                    $types = explode(',', $rawDescription);
                                }
                            } else {
                                $types = [];
                            }
                        @endphp
                        <div class="mt-4">
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-2">Notification Type Description</span>
                            <ul class="space-y-1.5 bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4">
                                @foreach($types as $type)
                                    <li class="flex items-start gap-2 text-sm font-medium text-[var(--text-dark)]">
                                        <span class="material-symbols-outlined text-base text-indigo-500 mt-0.5 shrink-0">check_circle</span>
                                        {{ trim($type, ' "[]') }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                {{-- Card B: Data Breach Details --}}
                <div class="mt-6 rounded-2xl p-5 sm:p-6 border border-[var(--border-light)] bg-[var(--card-bg)] shadow-2xs">
                    <h2 class="text-base sm:text-lg font-bold text-[var(--text-dark)] m-0 mb-5 flex items-center gap-2 pb-3 border-b border-[var(--border-subtle)]">
                        <span class="material-symbols-outlined text-rose-600 dark:text-rose-400">report_problem</span>
                        <span>B. Data Breach Details</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Sector Name</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $notification->sector_name }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Subsector Name</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $notification->subsector_name }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Type of Notification</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800">
                                    {{ $notification->notification_type }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">General Cause</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $notification->general_cause }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Specific Cause</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $notification->specific_cause }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">General Incident</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $notification->general_incident }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">With Request?</span>
                            <div class="text-sm font-bold bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5 flex items-center gap-2">
                                @if(strtoupper($notification->with_request) === 'YES')
                                    <span class="material-symbols-outlined text-base text-emerald-500">check_circle</span>
                                    <span class="text-emerald-600 dark:text-emerald-400">YES</span>
                                @else
                                    <span class="material-symbols-outlined text-base text-rose-500">cancel</span>
                                    <span class="text-rose-600 dark:text-rose-400">NO</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Justification for Request</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $notification->num_records_provide_details ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 mt-4">
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">How the Breach Occurred &amp; DPS Vulnerability</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4 leading-relaxed whitespace-pre-wrap">
                                {{ $notification->how_breach_occured }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Chronology of Events</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4 leading-relaxed whitespace-pre-wrap">
                                {{ $notification->chronology }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Number of Data Subjects / Records</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4 space-y-2">
                                <p class="m-0 font-bold">
                                    Count: {{ $notification->num_records }} records @if($notification->hundred_plus) <span class="text-rose-600 dark:text-rose-400">(≥100)</span> @endif
                                </p>
                                <div class="border-t border-[var(--border-light)] pt-2 text-[var(--text-main)]">
                                    {{ $notification->num_records_provide_details }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Description / Nature of the Personal Data Breach</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4 leading-relaxed whitespace-pre-wrap">
                                {{ $notification->description_nature }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Likely Consequences</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4 leading-relaxed whitespace-pre-wrap">
                                {{ $notification->likely_consequences }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Data Protection Officer (DPO) Details</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4 leading-relaxed whitespace-pre-wrap">
                                {{ $notification->dpo }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Types of Sensitive Personal Information Involved</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4 leading-relaxed whitespace-pre-wrap">
                                {{ $notification->spi }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Other Information That May Enable Identity Fraud</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4 leading-relaxed whitespace-pre-wrap">
                                {{ $notification->other_info }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card C: Measures Taken --}}
                <div class="mt-6 rounded-2xl p-5 sm:p-6 border border-[var(--border-light)] bg-[var(--card-bg)] shadow-2xs">
                    <h2 class="text-base sm:text-lg font-bold text-[var(--text-dark)] m-0 mb-5 flex items-center gap-2 pb-3 border-b border-[var(--border-subtle)]">
                        <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">check_circle</span>
                        <span>C. Measures Taken to Address the Breach</span>
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Measures Taken to Address the Breach</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4 leading-relaxed whitespace-pre-wrap">
                                {{ $notification->measures_to_address }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Measures to Secure / Recover Personal Data</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4 leading-relaxed whitespace-pre-wrap">
                                {{ $notification->measures_to_secure }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Actions to Mitigate Harm</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4 leading-relaxed whitespace-pre-wrap">
                                {{ $notification->actions_to_mitigate }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Actions to Inform Data Subjects / Assistance Provided</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4 leading-relaxed whitespace-pre-wrap">
                                {{ $notification->actions_to_inform ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Measures to Prevent Recurrence</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4 leading-relaxed whitespace-pre-wrap">
                                {{ $notification->actions_to_prevent }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card D: Record Type & Data Subjects --}}
                <div class="mt-6 rounded-2xl p-5 sm:p-6 border border-[var(--border-light)] bg-[var(--card-bg)] shadow-2xs">
                    <h2 class="text-base sm:text-lg font-bold text-[var(--text-dark)] m-0 mb-5 flex items-center gap-2 pb-3 border-b border-[var(--border-subtle)]">
                        <span class="material-symbols-outlined text-violet-600 dark:text-violet-400">group</span>
                        <span>D. Record Type &amp; Data Subjects</span>
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1">Record Type</span>
                            <div class="text-sm font-medium text-[var(--text-dark)] bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl px-3.5 py-2.5">
                                {{ $notification->record_type }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-2">Affected Data Subjects</span>
                            <ul class="space-y-1.5 bg-slate-50 dark:bg-slate-800/50 border border-[var(--border-light)] rounded-xl p-4">
                                @foreach(explode(',', $notification->data_subjects) as $subject)
                                    <li class="flex items-start gap-2 text-sm font-medium text-[var(--text-dark)]">
                                        <span class="material-symbols-outlined text-base text-indigo-500 mt-0.5 shrink-0">check_circle</span>
                                        {{ trim($subject) }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endcan
</x-app-layout>
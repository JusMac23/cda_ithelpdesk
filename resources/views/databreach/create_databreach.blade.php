<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>

    @can('create_databreach')
    <div id="main-content" class="w-full">
        <div class="max-w-4xl mx-auto space-y-6">

            {{-- Main Form Card --}}
            <div class="relative bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-5 sm:p-8 lg:p-10">

                {{-- Close / Back button --}}
                <button id="close" type="button" onclick="window.location.href='{{ route('databreach.index') }}'"
                    class="absolute top-5 right-5 sm:top-8 sm:right-8 w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-[var(--text-muted)] hover:text-[var(--text-dark)] flex items-center justify-center transition-all cursor-pointer shadow-xs"
                    aria-label="Close form"
                    title="Return to Incident List">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>

                {{-- Header Section --}}
                <div class="flex items-center gap-3.5 pb-6 mb-8 border-b border-[var(--border-subtle)] pr-12">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-rose-600 via-red-600 to-amber-600 text-white flex items-center justify-center shadow-md shadow-rose-500/20 shrink-0">
                        <span class="material-symbols-outlined text-2xl">shield_locked</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                                Incident Report Form
                            </h1>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                CDA-DBRS
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                            Submit a formal report of a suspected or confirmed personal data breach or security incident
                        </p>
                    </div>
                </div>

                {{-- Validation Errors Banner --}}
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

                {{-- Incident Report Form --}}
                <form id="createIncidentForm" action="{{ route('databreach.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Section 1: Reporter Information -->
                    <fieldset class="border border-[var(--border-light)] rounded-xl p-4 sm:p-6 mb-6 bg-slate-50/60 dark:bg-slate-800/30 shadow-2xs">
                        <legend class="px-2 text-xs sm:text-sm font-bold text-[var(--text-dark)] uppercase tracking-wider flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-base text-indigo-600 dark:text-indigo-400">person</span>
                            <span>Reporter Information</span>
                        </legend>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label for="sender_fullname" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Full Name <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="sender_fullname" name="sender_fullname"
                                    value="{{ old('sender_fullname', auth()->user() ? auth()->user()->name : '') }}"
                                    placeholder="e.g., Juan A. Dela Cruz" required autocomplete="name"
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                            </div>

                            <div>
                                <label for="sender_email" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Email Address <span class="text-rose-500">*</span>
                                </label>
                                <input type="email" id="sender_email" name="sender_email"
                                    value="{{ old('sender_email', auth()->user() ? auth()->user()->email : '') }}"
                                    placeholder="e.g., j_delacruz@cda.gov.ph" required autocomplete="email"
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                            </div>
                        </div>
                    </fieldset>

                    <!-- Section 2: Incident Timeline & Location -->
                    <fieldset class="border border-[var(--border-light)] rounded-xl p-4 sm:p-6 mb-6 bg-slate-50/60 dark:bg-slate-800/30 shadow-2xs">
                        <legend class="px-2 text-xs sm:text-sm font-bold text-[var(--text-dark)] uppercase tracking-wider flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-base text-indigo-600 dark:text-indigo-400">schedule</span>
                            <span>Incident Timeline & Location</span>
                        </legend>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 mb-4">
                            <div>
                                <label for="date_occurrence" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Date of Occurrence <span class="text-rose-500">*</span>
                                </label>
                                <input type="datetime-local" id="date_occurrence" name="date_occurrence" required
                                    value="{{ old('date_occurrence') }}"
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                            </div>

                            <div>
                                <label for="date_discovery" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Date of Discovery <span class="text-rose-500">*</span>
                                </label>
                                <input type="datetime-local" id="date_discovery" name="date_discovery" required
                                    value="{{ old('date_discovery') }}"
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                            </div>

                            <div>
                                <label for="date_notification" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                    Date of Notification <span class="text-rose-500">*</span>
                                </label>
                                <input type="datetime-local" id="date_notification" name="date_notification" required readonly
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                            </div>
                        </div>

                        <div>
                            <label for="pic" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                Personal Information Controller (PIC) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="pic" name="pic" required
                                    class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all appearance-none cursor-pointer pr-10 shadow-xs">
                                    <option value="" disabled selected>-- Select PIC / Region --</option>
                                    @foreach ([
                                        'CDA HO', 'CDA CAR', 'CDA NIR', 'CDA NCR', 'CDA Region I', 'CDA Region II',
                                        'CDA Region III', 'CDA Region IV-A', 'CDA Region IV-B', 'CDA Region V',
                                        'CDA Region VI', 'CDA Region VII', 'CDA Region VIII', 'CDA Region IX',
                                        'CDA Region X', 'CDA Region XI', 'CDA Region XII', 'CDA Region XIII'
                                    ] as $region)
                                        <option value="{{ $region }}" {{ old('pic') == $region ? 'selected' : '' }}>{{ $region }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">expand_more</span>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Section 3: Incident Details -->
                    <fieldset class="border border-[var(--border-light)] rounded-xl p-4 sm:p-6 mb-6 bg-slate-50/60 dark:bg-slate-800/30 shadow-2xs">
                        <legend class="px-2 text-xs sm:text-sm font-bold text-[var(--text-dark)] uppercase tracking-wider flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-base text-indigo-600 dark:text-indigo-400">description</span>
                            <span>Incident Summary</span>
                        </legend>

                        <div>
                            <label for="brief_summary" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                                Brief Summary of the Incident <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="brief_summary" name="brief_summary" required rows="4"
                                placeholder="Provide a clear, detailed, and concise description of the incident..."
                                class="w-full px-3.5 py-3 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs resize-y min-h-[110px]">{{ old('brief_summary') }}</textarea>
                        </div>
                    </fieldset>

                    <!-- Terms & Privacy Agreement -->
                    <div class="mt-4 mb-6">
                        <label class="flex items-start gap-3 cursor-pointer text-xs sm:text-sm text-[var(--text-muted)] leading-relaxed select-none" for="terms_agree">
                            <input type="checkbox" id="terms_agree" name="terms_agree" required
                                class="mt-1 w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 cursor-pointer accent-indigo-600 shrink-0">
                            <span>
                                I have read and agree to the
                                <a href="https://cda.gov.ph/cda-privacy-policy/" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline" target="_blank" rel="noopener noreferrer">Terms and Conditions</a>
                                and the
                                <a href="https://cda.gov.ph/cda-privacy-policy/" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline" target="_blank" rel="noopener noreferrer">Privacy Policy</a>,
                                and I confirm that the information provided is accurate and true to the best of my knowledge.
                                <span class="text-rose-500 font-bold">*</span>
                            </span>
                        </label>
                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-[var(--border-light)]">
                        <button type="submit" id="submitReportBtn" disabled
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-11 px-6 rounded-xl font-semibold text-sm text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-indigo-600 transition-all shadow-sm shadow-indigo-600/20 cursor-pointer">
                            <span>Submit Report</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // SweetAlert session flash notifications
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{!! addslashes(session("success")) !!}',
                timer: 4000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{!! addslashes(session("error")) !!}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        document.addEventListener("DOMContentLoaded", function () {
            // Set Exact Manila Time for Date of Notification
            const dateNotificationInput = document.getElementById('date_notification');
            if (dateNotificationInput) {
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
                    dateNotificationInput.value = `${year}-${month}-${day}T${hour}:${minute}`;
                }
            }

            // FORM VALIDATION ENHANCEMENT
            const form = document.getElementById('createIncidentForm');
            const termsCheckbox = document.getElementById('terms_agree');
            const submitBtn = document.getElementById('submitReportBtn');

            if (form) {
                form.addEventListener('submit', function (e) {
                    let isValid = true;
                    let firstInvalidField = null;
                    const requiredFields = this.querySelectorAll('[required]');

                    requiredFields.forEach(field => {
                        if (field.type === 'checkbox') {
                            if (!field.checked) {
                                isValid = false;
                                if (!firstInvalidField) firstInvalidField = field;
                            }
                        } else if (!field.value.trim()) {
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
                            text: 'Please fill in all required fields marked with an asterisk (*), and agree to the CDA Terms and Conditions and Privacy Policy.',
                            confirmButtonColor: '#4f46e5',
                            confirmButtonText: 'Okay',
                            background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a'
                        });

                        if (firstInvalidField) {
                            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            firstInvalidField.focus();
                        }
                    }
                });

                // Real-time validation feedback
                form.querySelectorAll('input[type="text"], input[type="email"], input[type="datetime-local"], select, textarea').forEach(field => {
                    field.addEventListener('blur', function () {
                        if (this.hasAttribute('required') && !this.value.trim()) {
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

            // Terms and Conditions Checkbox Logic
            if (termsCheckbox && submitBtn) {
                submitBtn.disabled = !termsCheckbox.checked;

                termsCheckbox.addEventListener('change', function () {
                    submitBtn.disabled = !this.checked;
                });
            }
        });
    </script>
    @endcan
</x-app-layout>
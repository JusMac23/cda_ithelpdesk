@php
    $year = now()->year;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CDA-ICT Helpdesk - Data Breach Incident Report</title>
    <link rel="icon" href="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="/assets/js/sweetalert2.min.js"></script>
</head>

<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-['Inter',sans-serif] antialiased min-h-screen flex flex-col transition-colors duration-300 overflow-y-auto overflow-x-hidden">

<!-- Header Navigation Bar (Identical across welcome, create_ticket, and create_incident) -->
<header class="sticky top-0 z-50 bg-slate-900/75 backdrop-blur-xl border-b border-white/10 shadow-[0_4px_30px_rgba(0,0,0,0.1)]">
    <div class="h-[3px] bg-gradient-to-r from-blue-500 via-violet-500 to-red-500"></div>
    <div class="w-full max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="{{ url('/') }}" aria-label="Home" class="group flex items-center gap-3 text-2xl font-extrabold text-slate-50 tracking-tight transition-colors focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-4 focus-visible:rounded">
            <img src="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" alt="CDA Seal" loading="lazy" class="w-11 h-11 object-contain drop-shadow-[0_0_8px_rgba(255,255,255,0.2)] transition-transform duration-300 ease-out group-hover:scale-110 group-hover:-rotate-6 group-hover:drop-shadow-[0_0_12px_rgba(255,255,255,0.4)]">
            <span>CDA-ICT Helpdesk</span>
        </a>

        <nav aria-label="Main Navigation">
            <ul class="flex items-center gap-4 text-[0.95rem] font-semibold list-none m-0 p-0">
                @auth
                    <li>
                        <a href="{{ url('/tickets/overview_tickets') }}" class="flex items-center justify-center md:justify-start gap-2 px-3 md:px-5 py-2.5 rounded-lg text-slate-50 border border-transparent transition-all duration-300 ease-out hover:bg-blue-500/10 hover:border-blue-500/30 hover:shadow-[0_4px_12px_rgba(59,130,246,0.15)] focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-4 focus-visible:rounded" aria-label="Tickets Overview">
                            <span class="material-symbols-outlined text-[1.25rem]" aria-hidden="true">table_chart_view</span> 
                            <span class="nav-text hidden md:inline">Tickets Overview</span>
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="flex items-center justify-center md:justify-start gap-2 px-3 md:px-5 py-2.5 rounded-lg text-red-300 border border-transparent transition-all duration-300 ease-out hover:text-slate-50 hover:bg-red-500/15 hover:border-red-500/30 hover:shadow-[0_4px_12px_rgba(239,68,68,0.15)] cursor-pointer focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-4 focus-visible:rounded" aria-label="Logout">
                                <span class="material-symbols-outlined text-[1.25rem]" aria-hidden="true">logout</span> 
                                <span class="nav-text hidden md:inline">Logout</span>
                            </button>
                        </form>
                    </li>
                @else
                    <li>
                        <a href="{{ route('login') }}" class="flex items-center justify-center md:justify-start gap-2 px-3 md:px-5 py-2.5 rounded-lg text-slate-50 border border-transparent transition-all duration-300 ease-out hover:bg-blue-500/10 hover:border-blue-500/30 hover:shadow-[0_4px_12px_rgba(59,130,246,0.15)] focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-4 focus-visible:rounded" aria-label="Login">
                            <span class="material-symbols-outlined text-[1.25rem]" aria-hidden="true">login</span> 
                            <span class="nav-text hidden md:inline">Login</span>
                        </a>
                    </li>
                @endauth
            </ul>
        </nav>
    </div>
</header>

<!-- Main Page Content -->
<main class="flex-1 w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    
    <div class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-xl p-5 sm:p-8 lg:p-10 transition-colors duration-300">
        
        {{-- Close / Back button --}}
        <button id="close" 
                onclick="window.location.href='{{ url('/') }}'" 
                class="absolute top-5 right-5 sm:top-8 sm:right-8 w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 flex items-center justify-center transition-all cursor-pointer" 
                aria-label="Close form" 
                title="Cancel & Back">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>

        {{-- Form Header --}}
        <div class="flex items-center gap-3.5 pb-6 mb-8 border-b border-slate-200 dark:border-slate-800 pr-12">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-rose-600 via-red-600 to-amber-600 text-white flex items-center justify-center shadow-md shadow-rose-500/20 shrink-0">
                <span class="material-symbols-outlined text-2xl">shield_locked</span>
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white m-0">
                        Incident Report Form
                    </h1>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60">
                        CDA-DBRS
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 m-0 mt-0.5 font-medium">
                    Submit a formal report of a suspected or confirmed personal data breach or security incident
                </p>
            </div>
        </div>

        {{-- Validation Errors Banner --}}
        @if ($errors->any())
            <div class="mb-8 p-4 sm:p-5 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 flex items-start gap-3.5">
                <span class="material-symbols-outlined text-2xl text-rose-600 shrink-0">error</span>
                <div class="text-sm">
                    <h4 class="font-bold text-rose-900 dark:text-rose-100 mb-1">Please fix the following issues:</h4>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 p-4 sm:p-5 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 flex items-start gap-3.5">
                <span class="material-symbols-outlined text-2xl text-rose-600 shrink-0">error</span>
                <div class="text-sm font-semibold">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- Incident Submission Form --}}
        <form action="{{ route('incident.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Section 1: Informant / Reporter Information -->
            <fieldset class="rounded-2xl p-5 sm:p-7 border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 space-y-6">
                <legend class="px-3 text-xs sm:text-sm font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base">person</span>
                    <span>Reporter Information</span>
                </legend>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="sender_fullname" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">
                            Full Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="sender_fullname" name="sender_fullname" placeholder="e.g., Juan A. Dela Cruz" required 
                               autocomplete="name"
                               class="w-full h-11 px-3.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-all outline-none">
                    </div>

                    <div>
                        <label for="sender_email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">
                            Email Address <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" id="sender_email" name="sender_email" placeholder="e.g., j_delacruz@cda.gov.ph" required 
                               autocomplete="email"
                               class="w-full h-11 px-3.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-all outline-none">
                    </div>
                </div>
            </fieldset>

            <!-- Section 2: Timeline of the Incident -->
            <fieldset class="rounded-2xl p-5 sm:p-7 border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 space-y-6">
                <legend class="px-3 text-xs sm:text-sm font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base">schedule</span>
                    <span>Incident Timeline</span>
                </legend>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="date_occurrence" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">
                            Date of Occurrence <span class="text-rose-500">*</span>
                        </label>
                        <input type="datetime-local" id="date_occurrence" name="date_occurrence" required 
                               class="w-full h-11 px-3.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-all outline-none">
                    </div>

                    <div>
                        <label for="date_discovery" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">
                            Date of Discovery <span class="text-rose-500">*</span>
                        </label>
                        <input type="datetime-local" id="date_discovery" name="date_discovery" required 
                               class="w-full h-11 px-3.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-all outline-none">
                    </div>

                    <div>
                        <label for="date_notification" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">
                            Date of Notification <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="datetime-local" id="date_notification" name="date_notification" required readonly 
                                   class="w-full h-11 px-3.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-sm cursor-not-allowed outline-none">
                            <span class="absolute right-3 top-3 w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse" title="Auto-synced with device time"></span>
                        </div>
                    </div>
                </div>
            </fieldset>

            <!-- Section 3: Operating Unit & Summary Details -->
            <fieldset class="rounded-2xl p-5 sm:p-7 border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 space-y-6">
                <legend class="px-3 text-xs sm:text-sm font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base">description</span>
                    <span>Incident Details</span>
                </legend>

                <div>
                    <label for="pic" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">
                        Personal Information Controller (Operating Unit / Region) <span class="text-rose-500">*</span>
                    </label>
                    <select id="pic" name="pic" required 
                            class="w-full h-11 px-3.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-all outline-none">
                        <option value="" disabled selected>-- Select Region / Office --</option>
                        <option value="CDA HO">CDA HO</option>
                        <option value="CDA CAR">CDA CAR</option>
                        <option value="CDA NIR">CDA NIR</option>
                        <option value="CDA NCR">CDA NCR</option>
                        <option value="CDA Region I">CDA Region I</option>
                        <option value="CDA Region II">CDA Region II</option>
                        <option value="CDA Region III">CDA Region III</option>
                        <option value="CDA Region IV-A">CDA Region IV-A</option>
                        <option value="CDA Region IV-B">CDA Region IV-B</option>
                        <option value="CDA Region V">CDA Region V</option>
                        <option value="CDA Region VI">CDA Region VI</option>
                        <option value="CDA Region VII">CDA Region VII</option>
                        <option value="CDA Region VIII">CDA Region VIII</option>
                        <option value="CDA Region IX">CDA Region IX</option>
                        <option value="CDA Region X">CDA Region X</option>
                        <option value="CDA Region XI">CDA Region XI</option>
                        <option value="CDA Region XII">CDA Region XII</option>
                        <option value="CDA Region XIII">CDA Region XIII</option>
                    </select>
                </div>

                <div>
                    <label for="brief_summary" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">
                        Brief Summary of the Incident <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="brief_summary" name="brief_summary" rows="5" required 
                              placeholder="Provide a clear, detailed, and concise description of the data breach or security incident..." 
                              class="w-full p-3.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-all outline-none resize-y"></textarea>
                    <span class="block mt-1 text-2xs text-slate-400">Describe what occurred, impacted assets, and preliminary observations.</span>
                </div>
            </fieldset>

            <!-- Terms & Consent Agreement -->
            <div class="p-4 sm:p-5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                <label class="flex items-start gap-3 cursor-pointer text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed" for="terms_agree">
                    <input type="checkbox" id="terms_agree" name="terms_agree" required 
                           class="mt-1 w-4 h-4 rounded text-rose-600 focus:ring-rose-500 border-slate-300 dark:border-slate-700 cursor-pointer">
                    <span>
                        I have read and agree to the 
                        <a href="https://cda.gov.ph/cda-privacy-policy/" class="text-rose-600 dark:text-rose-400 font-semibold underline hover:text-rose-500" target="_blank" rel="noopener noreferrer">Terms and Conditions</a> 
                        and the 
                        <a href="https://cda.gov.ph/cda-privacy-policy/" class="text-rose-600 dark:text-rose-400 font-semibold underline hover:text-rose-500" target="_blank" rel="noopener noreferrer">Privacy Policy</a>, 
                        and I confirm that the information provided is accurate and true to the best of my knowledge. 
                        <span class="text-rose-500 font-bold">*</span>
                    </span>
                </label>
            </div>

            <!-- Form Action Footer -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
                <a href="{{ url('/') }}" 
                   class="w-full sm:w-auto h-11 px-5 rounded-xl text-xs sm:text-sm font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-all cursor-pointer">
                    Cancel
                </a>

                <button type="submit" id="submitReportBtn" disabled 
                        class="w-full sm:w-auto h-11 px-8 rounded-xl text-xs sm:text-sm font-semibold text-white bg-rose-600 hover:bg-rose-500 disabled:bg-slate-300 dark:disabled:bg-slate-800 disabled:text-slate-500 disabled:cursor-not-allowed shadow-md shadow-rose-600/20 disabled:shadow-none flex items-center justify-center gap-2 transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-lg">report</span>
                    <span>Submit Report</span>
                </button>
            </div>

        </form>
    </div>

</main>

<footer class="mt-auto py-6 border-t border-slate-200 dark:border-slate-800/60 bg-white/50 dark:bg-slate-900/50 text-center text-xs text-slate-400">
    <p class="m-0">&copy; {{ $year }} Cooperative Development Authority - Information & Communications Technology Division. All rights reserved.</p>
</footer>

<script>
    // SweetAlert notifications
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

    document.addEventListener('DOMContentLoaded', function () {
        // Fix: Sync exact device/laptop time to prevent 4-minute server delay
        const dateNotificationInput = document.getElementById('date_notification');
        if (dateNotificationInput) {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            
            // Format: YYYY-MM-DDThh:mm
            dateNotificationInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        const form = document.querySelector('form');
        const termsCheckbox = document.getElementById('terms_agree');
        const submitBtn = document.getElementById('submitReportBtn');

        // Form validation enhancement
        if (form) {
            form.addEventListener('submit', function(e) {
                let isValid = true;
                const requiredFields = this.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    if (field.type === 'checkbox') {
                        if (!field.checked) isValid = false;
                    } else if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-950/30');
                    } else {
                        field.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-950/30');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Information',
                        text: 'Please fill in all required fields marked with an asterisk (*), and agree to the CDA Terms and Conditions and Privacy Policy.',
                        confirmButtonColor: '#e11d48'
                    });
                }
            });

            // Real-time validation
            form.querySelectorAll('input[type="text"], input[type="email"], input[type="datetime-local"], select, textarea').forEach(field => {
                field.addEventListener('blur', function() {
                    if (this.hasAttribute('required') && !this.value.trim()) {
                        this.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-950/30');
                    } else {
                        this.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-950/30');
                    }
                });

                field.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-950/30');
                    }
                });
            });
        }

        // Terms and Conditions Checkbox Logic
        if (termsCheckbox && submitBtn) {
            submitBtn.disabled = !termsCheckbox.checked;

            termsCheckbox.addEventListener('change', function() {
                submitBtn.disabled = !this.checked;
            });
        }
    });
</script>
</body>
</html>
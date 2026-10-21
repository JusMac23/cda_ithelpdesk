@php
    $year = now()->year;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CDA-ICT Helpdesk - Submit Ticket</title>
    <link rel="icon" href="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="/assets/js/sweetalert2.min.js"></script>
</head>

<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-['Inter',sans-serif] antialiased min-h-screen flex flex-col transition-colors duration-300 overflow-y-auto overflow-x-hidden">

<!-- Full-Screen Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="w-12 h-12 border-4 border-white/20 border-t-white rounded-full animate-spin mb-4"></div>
    <p class="text-base font-semibold tracking-wide text-white">Submitting Ticket, please wait...</p>
</div>

<!-- Header Navigation Bar -->
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
                        <form method="POST" action="{{ route('logout') }}">
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
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-600 text-white flex items-center justify-center shadow-md shadow-indigo-500/20 shrink-0">
                <span class="material-symbols-outlined text-2xl">confirmation_number</span>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white m-0">
                    Create Support Ticket
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 m-0 mt-0.5 font-medium">
                    Submit your technical request or incident to the CDA Information & Communications Technology team
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

        {{-- Ticket Submission Form --}}
        <form action="{{ route('tickets.store.client') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Section 1: Client Information -->
            <fieldset class="border border-[var(--border-light)] dark:border-slate-800 rounded-xl p-4 sm:p-6 mb-6 bg-slate-50/60 dark:bg-slate-800/30 shadow-2xs">
                <legend class="px-2 text-xs sm:text-sm font-bold text-[var(--text-dark)] dark:text-slate-100 uppercase tracking-wider flex items-center gap-1.5">
                    <span>Client Information</span>
                </legend>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 mb-4">
                    <div>
                        <label for="firstname" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                            First Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" placeholder="e.g., Juan" required 
                            class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                    </div>

                    <div>
                        <label for="middle_initial" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                            Middle Initial <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="middle_initial" name="middle_initial" value="{{ old('middle_initial') }}" placeholder="e.g., A." 
                            class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                    </div>

                    <div>
                        <label for="lastname" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                            Last Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" placeholder="e.g., Dela Cruz" required 
                            class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                        Official Email <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="e.g., j_delacruz@cda.gov.ph" 
                        pattern=".*@cda\.gov\.ph$" 
                        title="Please use a valid @cda.gov.ph email address." 
                        required 
                        class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Date Created</label>
                        <input type="text" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('F j, Y h:i A') }}" readonly 
                            class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                        <input type="hidden" name="date_created" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}">
                    </div>

                    <div>
                        <label for="division" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                            Section / Division <span class="text-rose-500">*</span>
                        </label>
                        <select id="division" name="division" required 
                            class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                            <option value="" disabled selected>Select Division</option>
                            @forelse ($sections_divisions ?? [] as $division)
                                <option value="{{ $division }}" {{ old('division') == $division ? 'selected' : '' }}>{{ $division }}</option>
                            @empty
                                <option value="" disabled>No divisions available</option>
                            @endforelse
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mb-4">
                    <div>
                        <label for="device" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                            Device Equipment <span class="text-rose-500">*</span>
                        </label>
                        <select id="device" name="device" required 
                            class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                            <option value="" disabled selected>Select Device</option>
                            @foreach (['Desktop PC', 'Laptop/Netbook PC', 'Tablet PC', 'All-in-1 Printer', 'Printer Only', 'Scanner Only', 'Others'] as $device)
                                <option value="{{ $device }}" {{ old('device') == $device ? 'selected' : '' }}>{{ $device }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="service" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                            Technical Service <span class="text-rose-500">*</span>
                        </label>
                        <select id="service" name="service" required 
                            class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                            <option value="" disabled selected>Select Technical Service</option>
                            @forelse ($technical_services ?? [] as $service)
                                <option value="{{ $service }}" {{ old('service') == $service ? 'selected' : '' }}>{{ $service }}</option>
                            @empty
                                <option value="" disabled>No technical services available</option>
                            @endforelse
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="request" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                        Request Details <span class="text-rose-500">*</span>
                        <span class="text-xs normal-case text-slate-400 ml-1">(Note: for website postings, please include Google Drive link)</span>
                    </label>
                    <textarea id="request" name="request" rows="4" required 
                        placeholder="Please describe your issue or request in detail..." 
                        class="w-full px-3.5 py-3 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs resize-y">{{ old('request') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="photo" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                            Attach Screenshot / Evidence Photo <span class="text-slate-400 text-xs normal-case">(Optional)</span>
                        </label>
                        <input type="file" id="photo" name="photo" accept="image/*" 
                            class="w-full px-3 py-2 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-[var(--text-dark)] hover:file:bg-slate-200 cursor-pointer">
                        <span class="block text-[11px] text-[var(--text-muted)] mt-1">Max file size: 20MB (JPEG, PNG, JPG, GIF, WEBP)</span>
                    </div>

                    <div>
                        <label for="priority" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                            Priority Level <span class="text-rose-500">*</span>
                        </label>
                        <select id="priority" name="priority" required 
                            class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                            <option value="" disabled selected>Select Priority Level</option>
                            <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Critical" {{ old('priority') == 'Critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>
                </div>    
            </fieldset>

            <!-- Section 2: Designated Personnel & Assignment -->
            <fieldset class="border border-[var(--border-light)] dark:border-slate-800 rounded-xl p-4 sm:p-6 mb-6 bg-slate-50/60 dark:bg-slate-800/30 shadow-2xs">
                <legend class="px-2 text-xs sm:text-sm font-bold text-[var(--text-dark)] dark:text-slate-100 uppercase tracking-wider flex items-center gap-1.5">
                    <span>Designated Personnel</span>
                </legend>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="it_area" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">
                            Region <span class="text-rose-500">*</span>
                        </label>
                        <select id="it_area" name="it_area" required 
                            class="w-full h-11 px-3.5 rounded-xl text-sm bg-[var(--card-bg)] border border-[var(--border-light)] text-[var(--text-dark)] focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all shadow-xs">
                            <option value="" disabled selected>Select Region</option>
                            @forelse ($it_area ?? [] as $area)
                                <option value="{{ $area }}" {{ old('it_area') == $area ? 'selected' : '' }}>{{ $area }}</option>
                            @empty
                                <option value="" disabled>No regions available</option>
                            @endforelse
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Status</label>
                        <div class="relative">
                            <input type="text" id="status" name="status" value="Pending" readonly 
                                class="w-full h-11 px-3.5 rounded-xl text-sm bg-slate-100 dark:bg-slate-800/60 border border-[var(--border-light)] text-[var(--text-muted)] cursor-not-allowed">
                            <span class="absolute right-3.5 top-4 w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                        </div>
                    </div>
                </div>

                {{-- Hidden Auto-Assignment Attributes --}}
                <input type="hidden" id="it_personnel" name="it_personnel" value="">
                <input type="hidden" id="it_email" name="it_email" value="">
            </fieldset>

            <!-- Form Footer & Terms -->
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

            <!-- Form Action Footer -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-[var(--border-light)] dark:border-slate-800">
                <a href="{{ url('/') }}" 
                    class="w-full sm:w-auto h-11 px-5 rounded-xl text-xs sm:text-sm font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-all cursor-pointer">
                    Cancel
                </a>

                <button type="submit" id="submitTicketBtn" disabled 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-11 px-6 sm:px-8 rounded-xl font-semibold text-sm text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-indigo-600 transition-all shadow-sm shadow-indigo-600/20 cursor-pointer">
                    <span>Submit Ticket</span>
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
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Warning!',
            text: '{{ session('warning') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    // Complete Round-Robin Auto-Assignment Logic 
    const nextAssignmentMap = @json($nextAssignment);
    const serviceSelect = document.getElementById('service');
    const regionSelect = document.getElementById('it_area');
    const personnelInput = document.getElementById('it_personnel');
    const emailInput = document.getElementById('it_email');

    function updatePersonnelAndEmails() {
        const selectedRegion = regionSelect.value;
        const selectedService = serviceSelect.value;

        personnelInput.value = '';
        emailInput.value = '';

        if (!selectedRegion) return;

        const exactKey = `${selectedRegion}_${selectedService}`;
        const defaultKey = `${selectedRegion}_default`;

        const assignedPerson = nextAssignmentMap[exactKey] || nextAssignmentMap[defaultKey];

        if (assignedPerson) {
            personnelInput.value = assignedPerson.name;
            emailInput.value = assignedPerson.email;
        } else {
            personnelInput.value = 'No personnel found for this region';
        }
    }

    if (serviceSelect) {
        serviceSelect.addEventListener('change', updatePersonnelAndEmails);
    }
    if (regionSelect) {
        regionSelect.addEventListener('change', updatePersonnelAndEmails);
    }

    // Auto-Validate Email Domain Immediately Upon Blur
    const emailField = document.getElementById('email');
    emailField.addEventListener('blur', function() {
        const emailVal = this.value.trim();
        if (emailVal && !emailVal.toLowerCase().endsWith('@cda.gov.ph')) {
            this.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-950/30');
            Swal.fire({
                icon: 'error',
                title: 'Invalid Email Domain',
                text: 'Only @cda.gov.ph email addresses are permitted to submit a ticket. For example: j_delacruz@cda.gov.ph',
                confirmButtonColor: '#3085d6'
            });
        } else {
            this.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-950/30');
        }
    });

    // Form validation check on Submit & Overlay Trigger
    document.querySelector('form').addEventListener('submit', function(e) {
        let isValid = true;
        let errorMessage = 'Please fill in all required fields marked with *.';
        const requiredFields = this.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-950/30');
            } else {
                field.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-950/30');
            }
        });

        // Double check email domain before submitting
        const emailVal = emailField.value.trim();
        if (emailVal && !emailVal.toLowerCase().endsWith('@cda.gov.ph')) {
            isValid = false;
            emailField.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-950/30');
            errorMessage = 'Only @cda.gov.ph email addresses are permitted to submit a ticket. For example: j_delacruz@cda.gov.ph';
        }

        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: errorMessage,
                confirmButtonColor: '#3085d6'
            });
        } else {
            // Display loading overlay when validation passes
            document.getElementById('loadingOverlay').classList.add('active');
        }
    });

    // Real-time validation for missing fields
    document.querySelectorAll('[required]').forEach(field => {
        field.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-950/30');
            } else {
                this.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-950/30');
            }
        });

        field.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-950/30');
            }
            if (this.id === 'email') {
                if (this.value.trim() && !this.value.trim().toLowerCase().endsWith('@cda.gov.ph')) {
                    this.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-950/30');
                } else {
                    this.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-950/30');
                }
            }
        });
    });

    // Enable/Disable Submit button on Terms acceptance
    const termsCheckbox = document.getElementById('terms_agree');
    const submitBtn = document.getElementById('submitTicketBtn');

    if (termsCheckbox && submitBtn) {
        submitBtn.disabled = !termsCheckbox.checked;

        termsCheckbox.addEventListener('change', function() {
            submitBtn.disabled = !this.checked;
        });
    }
</script>

</body>
</html>
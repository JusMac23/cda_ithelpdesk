@php
    $year = now()->year;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CDA-ICT Helpdesk</title>
    <link rel="icon" href="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

    <script src="/assets/js/sweetalert2.min.js"></script>

    <style>
        /* CSS Variables derived from snippet & header theme */
        :root { 
            --card-bg: #ffffff;
            --border-light: #e2e8f0;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --input-border: #cbd5e1;
            --input-text: #1e293b;
            --input-bg: #ffffff;
            --bg-alt: #f8fafc;
            --btn-gray-bg: #e2e8f0;
            --btn-gray-text: #475569;
            --btn-gray-hover-bg: #cbd5e1;
            --btn-gray-hover-text: #1e293b;
            --accent-blue: #3b82f6; 
            --alert-red: #ef4444; 
            --glass-bg: rgba(15, 23, 42, 0.9); 
            --glass-border: rgba(255, 255, 255, 0.1); 
            --primary-indigo: #4f46e5; 
            --indigo-hover: #4338ca; 
            --bg-body: #f8fafc; 
            --error-bg: #fef2f2; 
            --error-text: #991b1b; 
        }

        /* Base Resets & Typography */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: var(--bg-body); color: var(--input-text); font-family: 'Inter', system-ui, -apple-system, sans-serif; -webkit-font-smoothing: antialiased; line-height: 1.5; }
        a { text-decoration: none; }
        ul { list-style: none; }

        /* Animations */
        @keyframes fade-in-down { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-down { animation: fade-in-down 0.5s ease-out forwards; }

        /* Header Styles */
        .app-header { background-color: var(--glass-bg); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); position: sticky; top: 0; z-index: 50; border-bottom: 1px solid var(--glass-border); }
        .header-gradient { height: 3px; background: linear-gradient(90deg, var(--accent-blue), var(--alert-red)); }
        .container { max-width: 1280px; margin: 0 auto; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        
        /* Branding */
        .brand { font-size: 1.5rem; font-weight: 800; color: #ffffff; display: flex; align-items: center; gap: 0.75rem; letter-spacing: -0.025em; }
        .brand img { width: 44px; height: 44px; object-fit: contain; transition: transform 0.3s ease; }
        .brand:hover img { transform: scale(1.1) rotate(-5deg); }

        /* Navigation */
        .nav-links { display: flex; gap: 1rem; align-items: center; font-weight: 600; font-size: 0.95rem; }
        .nav-link { color: #e2e8f0; padding: 0.6rem 1.25rem; border-radius: 8px; display: flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; border: 1px solid transparent; }
        .nav-link:hover { color: #ffffff; background-color: rgba(59, 130, 246, 0.1); border-color: rgba(59, 130, 246, 0.3); }
        .nav-link.nav-link-logout { color: #fca5a5; background: none; cursor: pointer; font: inherit; border: none; }
        .nav-link.nav-link-logout:hover { color: #ffffff; background-color: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); }

        @media (max-width: 768px) {
            .nav-text { display: none !important; }
            .nav-link { padding: 0.6rem 0.8rem; margin: 0 !important; justify-content: center; }
        }

        /* Form Page Container */
        .page-form-container { position: relative; background-color: var(--card-bg); border-radius: 1rem; border: 1px solid var(--border-light); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08); width: calc(100% - 2rem); max-width: 1150px; margin: 2.5rem auto 4rem; padding: 2rem; transition: background-color 0.3s ease, border-color 0.3s ease; }

        @media (max-width: 640px) {
            .page-form-container { padding: 1.25rem; margin: 1.5rem auto 2.5rem; width: calc(100% - 1.25rem); }
        }

        /* Form Title Design */
        .form-title { font-size: 1.5rem; font-weight: 800; color: var(--text-dark); margin-top: 0; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-light); padding-bottom: 1rem; padding-right: 2.5rem; letter-spacing: -0.025em; display: flex; align-items: center; gap: 0.75rem; }

        /* Close Button Design */
        .close-btn { position: absolute; top: 1.25rem; right: 1.25rem; color: var(--text-muted); font-size: 2rem; background: none; border: none; cursor: pointer; transition: all 0.2s; line-height: 1; border-radius: 0.25rem; padding: 0 0.5rem; }
        .close-btn:hover { color: var(--text-dark); }

        /* Form Grids & Layout */
        form { width: 100%; }
        .form-grid { display: grid; grid-template-columns: 1fr; gap: 1.25rem; width: 100%; }
        
        @media (min-width: 768px) {
            .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
            .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        }

        .form-group { display: flex; flex-direction: column; width: 100%; margin-bottom: 1rem; }
        .form-label { font-weight: 600; margin-bottom: 0.5rem; font-size: 0.875rem; color: var(--text-muted); transition: color 0.3s ease; }
        .text-required { color: var(--alert-red); margin-left: 0.125rem; }

        /* Form Inputs & Selects Design */
        .form-input, .form-select { height: 44px; padding: 0 1rem; border: 1px solid var(--input-border); border-radius: 0.5rem; font-size: 0.95rem; color: var(--input-text); width: 100%; box-sizing: border-box; outline: none; transition: all 0.2s; background-color: var(--input-bg); font-family: inherit; }
        .form-input:focus, .form-select:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }
        textarea.form-input { height: auto; resize: vertical; padding: 0.75rem 1rem; min-height: 100px; }

        /* Validation State Classes */
        .border-red-500 { border-color: var(--alert-red) !important; }
        .bg-red-50 { background-color: var(--error-bg) !important; }

        /* Fieldset Design */
        fieldset.form-fieldset { border: 1px solid var(--border-light); border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1.75rem; background: var(--card-bg); transition: background-color 0.3s ease, border-color 0.3s ease; }
        fieldset.form-fieldset legend { font-weight: 700; color: var(--text-dark); padding: 0 0.5rem; font-size: 1.05rem; text-transform: uppercase; letter-spacing: 0.05em; transition: color 0.3s ease; }

        /* Form Footer Design */
        .form-footer { display: flex; flex-direction: column; padding-top: 1.5rem; border-top: 1px solid var(--border-light); margin-top: 1.5rem; gap: 0.75rem; transition: border-color 0.3s ease; }
        @media (min-width: 768px) { .form-footer { flex-direction: row; justify-content: flex-end; align-items: center; } }

        /* Readonly & Disabled Input Styling */
        .form-input:read-only, .form-select:disabled, .form-input[readonly] { background-color: var(--bg-alt) !important; color: var(--text-muted) !important; cursor: not-allowed; opacity: 0.85; border-color: var(--border-light); }
        .form-input[readonly]:focus { box-shadow: none; border-color: var(--border-light); }

        /* File Input Styling */
        input[type="file"].form-input { padding: 0.4rem 0.5rem; line-height: 1.75; }
        input[type="file"]::file-selector-button { margin-right: 1rem; border: none; background: var(--btn-gray-bg); color: var(--btn-gray-text); padding: 0.4rem 0.8rem; border-radius: 0.25rem; cursor: pointer; transition: all 0.2s ease; font-weight: 600; font-size: 0.85rem; font-family: inherit; }
        input[type="file"]::file-selector-button:hover { background: var(--btn-gray-hover-bg); color: var(--btn-gray-hover-text); }

        /* Terms & Submit Button */
        .terms-wrapper { margin-top: 1.25rem; margin-bottom: 1.25rem; width: 100%; }
        .terms-label { display: flex; align-items: flex-start; gap: 0.75rem; cursor: pointer; font-size: 0.875rem; color: var(--text-muted); line-height: 1.5; }
        .terms-checkbox { margin-top: 0.2rem; width: 1.1rem; height: 1.1rem; accent-color: var(--primary-indigo); cursor: pointer; flex-shrink: 0; }
        .terms-link { color: var(--primary-indigo); font-weight: 600; }
        .terms-link:hover { text-decoration: underline; }

        .btn-submit { display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 2rem; background-color: var(--primary-indigo); color: #f8fafc; font-size: 0.95rem; font-weight: 600; border: none; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 2px rgba(79, 70, 229, 0.2); }
        .btn-submit:hover:not(:disabled) { background-color: var(--indigo-hover); color: #f8fafc; transform: translateY(-1px); }
        .btn-submit:disabled { background-color: #cbd5e1; color: #f8fafc; cursor: not-allowed; box-shadow: none; transform: none; }

        /* Error Banner */
        .alert-error { width: 100%; background-color: var(--error-bg); border-left: 4px solid var(--alert-red); color: var(--error-text); padding: 1.25rem; margin-bottom: 1.5rem; border-radius: 0.5rem; display: flex; gap: 0.75rem; }
        .alert-error h4 { margin-bottom: 0.5rem; font-size: 0.95rem; font-weight: 700; }
        .alert-error ul { padding-left: 1.25rem; list-style-type: disc; font-size: 0.875rem; }

        /* Full-Screen Loading Overlay */
        .loading-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); display: none; flex-direction: column; align-items: center; justify-content: center; z-index: 9999; color: #ffffff; }
        .loading-overlay.active { display: flex; }
        .spinner { width: 44px; height: 44px; border: 4px solid rgba(255, 255, 255, 0.3); border-top-color: #ffffff; border-radius: 50%; animation: spin 0.8s linear infinite; margin-bottom: 1rem; }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body>

<!-- Full-Screen Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="spinner"></div>
    <p style="font-size: 1rem; font-weight: 600; letter-spacing: 0.025em;">Submitting Ticket, please wait...</p>
</div>

<header class="app-header">
    <div class="header-gradient"></div>
    <div class="container">
        <h1 class="brand">
            <img src="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" alt="CDA Seal">
            <span>CDA-ICT Helpdesk</span>
        </h1>

        <nav>
            <ul class="nav-links">
                @auth
                    <li>
                        <a href="{{ url('/tickets/overview_tickets') }}" class="nav-link">
                            <span class="material-symbols-outlined" style="font-size: 1.25rem;">table_chart_view</span> 
                            <span class="nav-text">Tickets Overview</span>
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link nav-link-logout">
                                <span class="material-symbols-outlined" style="font-size: 1.25rem;">logout</span> 
                                <span class="nav-text">Logout</span>
                            </button>
                        </form>
                    </li>
                @else
                    <li>
                        <a href="{{ route('login') }}" class="nav-link">
                            <span class="material-symbols-outlined" style="font-size: 1.25rem;">login</span> 
                            <span class="nav-text">Login</span>
                        </a>
                    </li>
                @endauth
            </ul>
        </nav>
    </div>
</header>

<section class="page-form-container animate-fade-in-down">
    <button id="close" onclick="window.location.href='{{ url('/') }}'" class="close-btn" aria-label="Close form">
        &times;
    </button>

    <!-- Display Error -->
    @if ($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle text-lg mt-0.5"></i>
            <div>
                <h4>Please fix the following errors:</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <h2 class="form-title"><span>Tickets Form</span> </h2>

    <form action="{{ route('tickets.store.client') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Client Information -->
        <fieldset class="form-fieldset">
            <legend>Client Information</legend>
            <div class="form-grid grid-cols-3">
                <div class="form-group">
                    <label for="firstname" class="form-label">
                        First Name <span class="text-required">*</span>
                    </label>
                    <input type="text" id="firstname" name="firstname" placeholder="e.g., Juan" required class="form-input">
                </div>

                <div class="form-group">
                    <label for="middle_initial" class="form-label">
                        Middle Initial <span class="text-required">*</span>
                    </label>
                    <input type="text" id="middle_initial" name="middle_initial" placeholder="e.g.,A." class="form-input">
                </div>

                <div class="form-group">
                    <label for="lastname" class="form-label">
                        Last Name <span class="text-required">*</span>
                    </label>
                    <input type="text" id="lastname" name="lastname" placeholder="e.g., Dela Cruz" required class="form-input">
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">
                    Email <span class="text-required">*</span>
                </label>
                <input type="email" id="email" name="email" placeholder="e.g., j_delacruz@cda.gov.ph" pattern=".*@cda\.gov\.ph$" title="Please use a valid @cda.gov.ph email address." required class="form-input">
            </div>

            <div class="form-grid grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Date Created</label>
                    <input type="text" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('F j, Y h:i A') }}" readonly class="form-input">
                    <input type="hidden" name="date_created" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label for="division" class="form-label">
                        Division <span class="text-required">*</span>
                    </label>
                    <select id="division" name="division" required class="form-select">
                        <option value="" disabled selected>Select Division</option>
                        @foreach ($sections_divisions as $division)
                            <option value="{{ $division }}">{{ $division }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-grid grid-cols-2">
                <div class="form-group">
                    <label for="device" class="form-label">
                        Device <span class="text-required">*</span>
                    </label>
                    <select id="device" name="device" required class="form-select">
                        <option value="" disabled selected>Select Device</option>
                        @foreach (['Desktop PC', 'Laptop/Netbook PC', 'Tablet PC', 'All-in-1 Printer', 'Printer Only', 'Scanner Only', 'Others'] as $device)
                            <option value="{{ $device }}">{{ $device }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="service" class="form-label">
                        Technical Service <span class="text-required">*</span>
                    </label>
                    <select id="service" name="service" required class="form-select">
                        <option value="" disabled selected>Select Service</option>
                        @foreach ($technical_services as $service)
                            <option value="{{ $service }}">{{ $service }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="request" class="form-label">
                    Request Details <span class="text-required">*</span>
                </label>
                <textarea id="request" name="request" rows="4" placeholder="Describe the issue or request in detail..." required class="form-input"></textarea>
            </div>

            <div class="form-grid grid-cols-2">
                <div class="form-group">
                    <label for="photo" class="form-label">Attach Photo (Optional)</label>
                    <input type="file" id="photo" name="photo" accept="image/*" class="form-input">
                </div>

                <div class="form-group">
                    <label for="priority" class="form-label">Priority <span class="text-required">*</span></label>
                    <select id="priority" name="priority" required class="form-select">
                        <option value="" disabled selected>Select Priority</option>
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                        <option value="Critical">Critical</option>
                    </select>
                </div>
            </div> 

        </fieldset>

        <!-- Designated Personnel -->
        <fieldset class="form-fieldset">
            <legend>Designated Personnel</legend>
            
            <div class="form-grid grid-cols-2">
                <div class="form-group">
                    <label for="it_area" class="form-label">
                        Region <span class="text-required">*</span>
                    </label>
                    <select id="it_area" name="it_area" required class="form-select">
                        <option value="" disabled selected>Select Region</option>
                        @foreach($it_area as $area)
                            <option value="{{ $area }}">{{ $area }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <input type="text" id="status" name="status" value="Pending" readonly class="form-input">
                </div>
            </div>

            <input type="hidden" id="it_personnel" name="it_personnel" value="">
            <input type="hidden" id="it_email" name="it_email" value="">
        </fieldset>

        <!-- Form Footer & Terms -->
        <div class="terms-wrapper">
            <label class="terms-label" for="terms_agree">
                <input type="checkbox" id="terms_agree" name="terms_agree" required class="terms-checkbox">
                <span>I have read and agree to the <a href="https://cda.gov.ph/cda-privacy-policy/" class="terms-link" target="_blank">Terms and Conditions</a> and the <a href="https://cda.gov.ph/cda-privacy-policy/" class="terms-link" target="_blank">Privacy Policy</a>, and I confirm that the information provided is accurate and true to the best of my knowledge. <span class="text-required">*</span></span>
            </label>
        </div>

        <div class="form-footer">
            <button type="submit" id="submitTicketBtn" class="btn-submit" disabled>
                Submit Ticket
            </button>
        </div>
        
    </form>
</section>

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
            this.classList.add('border-red-500', 'bg-red-50');
            Swal.fire({
                icon: 'error',
                title: 'Invalid Email Domain',
                text: 'Only @cda.gov.ph email addresses are permitted to submit a ticket. For example: j_delacruz@cda.gov.ph',
                confirmButtonColor: '#3085d6'
            });
        } else {
            this.classList.remove('border-red-500', 'bg-red-50');
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
                field.classList.add('border-red-500', 'bg-red-50');
            } else {
                field.classList.remove('border-red-500', 'bg-red-50');
            }
        });

        // Double check email domain before submitting
        const emailVal = emailField.value.trim();
        if (emailVal && !emailVal.toLowerCase().endsWith('@cda.gov.ph')) {
            isValid = false;
            emailField.classList.add('border-red-500', 'bg-red-50');
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
                this.classList.add('border-red-500', 'bg-red-50');
            } else {
                this.classList.remove('border-red-500', 'bg-red-50');
            }
        });

        field.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('border-red-500', 'bg-red-50');
            }
            if (this.id === 'email') {
                if (this.value.trim() && !this.value.trim().toLowerCase().endsWith('@cda.gov.ph')) {
                    this.classList.add('border-red-500', 'bg-red-50');
                } else {
                    this.classList.remove('border-red-500', 'bg-red-50');
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
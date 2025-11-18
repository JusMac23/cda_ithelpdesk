@php
    $year = now()->year;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CDA-ITHelpdesk - Create Ticket</title>
    <link rel="icon" href="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" type="image/png">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Styles & Scripts (Vite handles Tailwind + JS build) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @keyframes fade-in-down {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.7s ease-out both;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .input-focus:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }

        body, button, input, select, textarea, h1, h2, h3, h4, p, a, span, li, legend, label, option {
            font-family: 'Figtree', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI",
                Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif !important;
        }

        .material-icons-outlined {
            font-family: 'Material Icons Outlined' !important;
        }

        .fa, .fas, .far, .fal, .fab {
            font-family: 'Font Awesome 6 Free' !important;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

<header class="bg-white shadow-lg sticky top-0 z-50 border-b border-gray-200">
    <div class="h-1 bg-gradient-to-r from-blue-600 via-indigo-500 to-purple-500"></div>
    
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <!-- Logo & Title -->
        <h1 class="text-2xl lg:text-3xl font-bold text-blue-800 flex items-center gap-3">
            <img src="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" 
                 alt="Cooperative Development Authority Seal" 
                 class="w-12 h-12 object-contain drop-shadow-sm transition-transform duration-300 hover:scale-105"/>
            <span class="tracking-tight">CDA IT-Helpdesk System</span>
        </h1>

        <!-- Navigation -->
        <nav>
            <ul class="flex space-x-6 text-base font-medium items-center">
                @auth
                    <!-- Dashboard Link -->
                    <li>
                        <a href="{{ url('/dashboard') }}" 
                           class="text-blue-600 hover:text-blue-800 hover:bg-blue-100 px-3 py-2 rounded-full flex items-center gap-2 transition-all duration-300 ease-in-out">
                            <span class="material-icons-outlined text-lg">dashboard</span> Dashboard
                        </a>
                    </li>

                    <!-- Logout Button -->
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="text-red-500 hover:text-red-700 hover:bg-red-100 px-3 py-2 rounded-full flex items-center gap-2 transition-all duration-300 ease-in-out">
                                <span class="material-icons-outlined text-lg">logout</span> Logout
                            </button>
                        </form>
                    </li>
                @else
                    <!-- Login Button -->
                    <li>
                        <a href="{{ route('login') }}" 
                           class="text-blue-600 hover:text-blue-800 hover:bg-blue-100 px-3 py-2 rounded-full flex items-center gap-2 font-medium transition-all duration-300 ease-in-out shadow-sm hover:shadow-md">
                            <span class="material-icons-outlined text-lg">login</span>
                            Login
                        </a>
                    </li>
                @endauth
            </ul>
        </nav>
    </div>
</header>

<section class="p-8 max-w-6xl mx-auto bg-white rounded-2xl shadow-xl mt-10 mb-16 animate-fade-in-down relative">
    <button id="close" onclick="window.location.href='{{ url('/') }}'" 
        class="absolute top-6 right-8 text-gray-500 hover:text-gray-700 text-3xl leading-none w-10 h-10 flex items-center justify-center bg-transparent border-none shadow-none transition-colors duration-200">
        &times;
    </button>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-800 px-6 py-4 mb-6 rounded-lg">
            <div class="flex">
                <i class="fas fa-exclamation-circle text-xl mt-1 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-sm mb-1">Please fix the following errors:</h4>
                    <ul class="list-disc pl-5 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <h2 class="text-2xl font-bold text-gray-900 mb-10 border-b-2 border-gray-200 pb-4 flex items-center gap-3">
        <i class="fas fa-file-alt text-indigo-600"></i>
        Tickets Form    
    </h2>

    <form action="{{ route('tickets.store.client') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Client Information -->
        <fieldset class="border border-gray-200 rounded-2xl p-8 bg-white card-hover shadow-sm">
            <legend class="text-xl font-bold text-indigo-700 px-3 flex items-center gap-2">
                Client Information
            </legend>
            <div class="space-y-6 mt-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label for="firstname" class="block text-sm font-semibold text-gray-700">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="firstname" name="firstname" 
                            placeholder="e.g., Juan" required
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 input-focus">
                    </div>

                    <div class="space-y-2">
                        <label for="lastname" class="block text-sm font-semibold text-gray-700">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="lastname" name="lastname" 
                            placeholder="e.g., Dela Cruz" required
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 input-focus">
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" 
                            placeholder="e.g., j_delacruz@cda.gov.ph" required
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 input-focus">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            Date Created
                        </label>
                        <input type="text" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('F j, Y h:i A') }}" readonly
                            class="w-full bg-gray-100 text-gray-600 border border-gray-300 rounded-xl px-4 py-3 focus:outline-none">
                        <input type="hidden" name="date_created" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}">
                    </div>

                    <div class="space-y-2">
                        <label for="division" class="block text-sm font-semibold text-gray-700">
                            Division <span class="text-red-500">*</span>
                        </label>
                        <select id="division" name="division" required
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 input-focus appearance-none bg-white">
                            <option value="" disabled selected>Select Division</option>
                            @foreach ($sections_divisions as $division)
                                <option value="{{ $division }}">{{ $division }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="device" class="block text-sm font-semibold text-gray-700">
                            Device <span class="text-red-500">*</span>
                        </label>
                        <select id="device" name="device" required
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 input-focus appearance-none bg-white">
                            <option value="" disabled selected>Select Device</option>
                            @foreach (['Desktop PC', 'Laptop/Netbook PC', 'Tablet PC', 'All-in-1 Printer', 'Printer Only', 'Scanner Only', 'Others'] as $device)
                                <option value="{{ $device }}">{{ $device }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="service" class="block text-sm font-semibold text-gray-700">
                            Technical Service <span class="text-red-500">*</span>
                        </label>
                        <select id="service" name="service" required
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 input-focus appearance-none bg-white">
                            <option value="" disabled selected>Select Service</option>
                            @foreach ($technical_services as $service)
                                <option value="{{ $service }}">{{ $service }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="space-y-2">
                        <label for="photo" class="block text-sm font-semibold text-gray-700">
                            Attach Photo (Optional)
                        </label>
                        <input type="file" id="photo" name="photo" accept="image/*"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="request" class="block text-sm font-semibold text-gray-700">
                        Request Details <span class="text-red-500">*</span>
                    </label>
                    <textarea id="request" name="request" rows="4"
                        placeholder="Describe the issue or request in detail..."
                        required
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 input-focus resize-none"></textarea>
                </div>
            </div>
        </fieldset>

        <!-- Designated Personnel -->
        <fieldset class="border border-gray-200 rounded-2xl p-8 bg-white card-hover shadow-sm">
            <legend class="text-xl font-bold text-gray-800 px-4 flex items-center gap-2">
                Designated Personnel
            </legend>
            <div class="space-y-6 mt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="it_area" class="block text-sm font-semibold text-gray-700">
                            Region <span class="text-red-500">*</span>
                        </label>
                        <select id="it_area" name="it_area" required
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 input-focus appearance-none bg-white">
                            <option value="" disabled selected>Select Region</option>
                            @foreach($it_area as $area)
                                <option value="{{ $area }}">{{ $area }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="it_personnel" class="block text-sm font-semibold text-gray-700">
                            Assigned Personnel
                        </label>
                        <select id="it_personnel" name="it_personnel"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 input-focus appearance-none bg-white">
                            <option value="" disabled selected>Select Personnel</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="it_email" class="block text-sm font-semibold text-gray-700">
                            IT Email
                        </label>
                        <input type="text" id="it_email" name="it_email" readonly
                            class="w-full bg-gray-100 text-gray-600 border border-gray-300 rounded-xl px-4 py-3 focus:outline-none">
                    </div>

                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-semibold text-gray-700">
                            Status
                        </label>
                        <input type="text" id="status" name="status" value="Pending" readonly
                            class="w-full bg-gray-100 text-gray-600 border border-gray-300 rounded-xl px-4 py-3 focus:outline-none">
                    </div>
                </div>
            </div>
        </fieldset>

        <!-- Action Buttons -->
        <div>
            <div class="g-recaptcha"
                data-sitekey="{{ config('services.recaptcha.site_key') }}"
                data-callback="enableLoginButton"
                data-expired-callback="disableLoginButton"
                data-error-callback="disableLoginButton"></div>

            @if ($errors->has('g-recaptcha-response'))
                <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2" />
            @endif
        </div>

        <div class="flex justify-end gap-4 pt-8 border-t border-gray-200">
            <button type="submit" id="submitTicketBtn"
                class="inline-flex items-center gap-3 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                disabled>
                <i class="fas fa-paper-plane text-sm"></i> Submit Ticket
            </button>
        </div>
    </form>
</section>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

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

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
    
    // Auto-populate IT personnel based on region selection
    const itMapping = @json($it_mapping);
    const regionSelect = document.getElementById('it_area');
    const personnelSelect = document.getElementById('it_personnel');
    const emailInput = document.getElementById('it_email');

    if (regionSelect && personnelSelect && emailInput) {
        regionSelect.addEventListener('change', function () {
            personnelSelect.innerHTML = '<option value="" disabled selected>Select Personnel</option>';
            emailInput.value = '';
            const personnelList = itMapping[this.value] || [];

            personnelList.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.name;
                opt.textContent = p.name;
                personnelSelect.appendChild(opt);
            });
        });

        personnelSelect.addEventListener('change', function () {
            const selected = (itMapping[regionSelect.value] || []).find(p => p.name === this.value);
            emailInput.value = selected ? selected.email : '';
        });
    }

    // Form validation enhancement
    document.querySelector('form').addEventListener('submit', function(e) {
        let isValid = true;
        const requiredFields = this.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500', 'bg-red-50');
            } else {
                field.classList.remove('border-red-500', 'bg-red-50');
            }
        });

        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Missing Information',
                text: 'Please fill in all required fields marked with *.',
                confirmButtonColor: '#3085d6'
            });
        }
    });

    // Real-time validation
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
        });
    });

    // Device "Others" option handling
    document.getElementById('device').addEventListener('change', function() {
        if (this.value === 'Others') {
            // You can add a text input for specifying other devices here
            console.log('Other device selected - consider adding a text input');
        }
    });

    // Service "Others" option handling (if applicable)
    document.getElementById('service').addEventListener('change', function() {
        if (this.value === 'Others') {
            // You can add a text input for specifying other services here
            console.log('Other service selected - consider adding a text input');
        }
    });

     // Called when reCAPTCHA is successfully completed
    function enableLoginButton() {
        const button = document.getElementById('submitTicketBtn');
        if (button) {
            button.disabled = false;
            button.style.opacity = '1';
            button.style.cursor = 'pointer';
            button.style.pointerEvents = 'auto';
        }
    }

    // Called when reCAPTCHA expires or fails
    function disableLoginButton() {
        const button = document.getElementById('submitTicketBtn');
        if (button) {
            button.disabled = true;
            button.style.opacity = '0.6';
            button.style.cursor = 'not-allowed';
            button.style.pointerEvents = 'none';
        }
    }

    // Always start disabled when page loads
    document.addEventListener('DOMContentLoaded', function () {
        disableLoginButton();
    });

</script>
</body>
</html>
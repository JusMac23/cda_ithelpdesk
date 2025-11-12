@php
    $year = now()->year;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CDA-ITHelpdesk - Create Incident Report</title>
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
        class="absolute top-6 right-8 text-gray-400 hover:text-gray-700 text-3xl transition-colors duration-200 leading-none bg-white rounded-full w-10 h-10 flex items-center justify-center shadow-sm hover:shadow-md">&times;
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

    <h2 class="text-3xl font-bold text-gray-900 mb-10 border-b-2 border-gray-200 pb-4 flex items-center gap-3">
        <i class="fas fa-file-alt text-indigo-600"></i>
        Create Incident Report
    </h2>

    <form action="{{ route('incident.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Incident Information -->
        <fieldset class="border border-gray-200 rounded-3xl p-8 bg-white shadow-sm hover:shadow-md transition-all duration-300 sm:rounded-lg">
            <legend class="text-xl font-bold text-indigo-700 px-3 flex items-center gap-2">
                <i class="fas fa-info-circle text-indigo-500 mr-2"></i> Incident Information
            </legend>

            <!-- Sender Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="sender_fullname" class="block text-sm font-semibold text-gray-700">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="sender_fullname" name="sender_fullname" placeholder="John A. Doe" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="sender_email" class="block text-sm font-semibold text-gray-700">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="sender_email" name="sender_email" placeholder="Email Address" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <!-- Dates (Chronology) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div>
                    <label for="date_occurrence" class="block text-sm font-semibold text-gray-700">
                        Date of Occurrence <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" id="date_occurrence" name="date_occurrence" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="date_discovery" class="block text-sm font-semibold text-gray-700">
                        Date of Discovery <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" id="date_discovery" name="date_discovery" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="date_notification" class="block text-sm font-semibold text-gray-700">
                        Date of Notification <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" id="date_notification" name="date_notification" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <!-- Personal Information Controller -->
            <div class="mt-6">
                <label for="pic" class="block text-sm font-semibold text-gray-700">
                    Personal Information Controller <span class="text-red-500">*</span>
                </label>
                <select id="pic" name="pic" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white appearance-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="">-- Select Region --</option>
                    <option>CDA HO</option>
                    <option>CDA CAR</option>
                    <option>CDA NIR</option>
                    <option>CDA NCR</option>
                    <option>CDA Region I</option>
                    <option>CDA Region II</option>
                    <option>CDA Region III</option>
                    <option>CDA Region IV-A</option>
                    <option>CDA Region IV-B</option>
                    <option>CDA Region V</option>
                    <option>CDA Region VI</option>
                    <option>CDA Region VII</option>
                    <option>CDA Region VIII</option>
                    <option>CDA Region IX</option>
                    <option>CDA Region X</option>
                    <option>CDA Region XI</option>
                    <option>CDA Region XII</option>
                    <option>CDA Region XIII</option>
                </select>
            </div>

            <!-- Brief Summary -->
            <div class="mt-6">
                <label for="brief_summary" class="block text-sm font-semibold text-gray-700">
                    Brief Summary of the Incident <span class="text-red-500">*</span>
                </label>
                <textarea id="brief_summary" name="brief_summary" required rows="4"
                    placeholder="Write a brief summary of the incident here..."
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
            </div>
        </fieldset>

        <!-- Action Buttons -->
        <div>
            <div class="g-recaptcha"
                data-sitekey="{{ config('services.recaptcha.site_key') }}"
                data-callback="enableSubmitButton"
                data-expired-callback="disableSubmitButton"
                data-error-callback="disableSubmitButton"></div>

            @if ($errors->has('g-recaptcha-response'))
                <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2" />
            @endif
        </div>

        <div class="flex justify-end gap-4 pt-8 border-t border-gray-200">
            <button type="submit" id="submitReportBtn"
                class="inline-flex items-center gap-3 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                disabled>
                <i class="fas fa-paper-plane text-sm"></i> Submit Report
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
            timer: 4000,
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


    // Called when reCAPTCHA is successfully completed
    function enableSubmitButton() {
        const button = document.getElementById('submitReportBtn');
        if (button) {
            button.disabled = false;
            button.style.opacity = '1';
            button.style.cursor = 'pointer';
            button.style.pointerEvents = 'auto';
        }
    }

    // Called when reCAPTCHA expires or fails
    function disableSubmitButton() {
        const button = document.getElementById('submitReportBtn');
        if (button) {
            button.disabled = true;
            button.style.opacity = '0.6';
            button.style.cursor = 'not-allowed';
            button.style.pointerEvents = 'none';
        }
    }

    // Always start disabled when page loads
    document.addEventListener('DOMContentLoaded', function () {
        disableSubmitButton();
    });

</script>
</body>
</html>
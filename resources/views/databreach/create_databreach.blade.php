<x-app-layout>
    
    <style>
        .responsive-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        /* Make each label/input stack vertically */
        .responsive-grid > div {
            margin-top: 0.5rem;
            display: flex;
            flex-direction: column;
        }
        /* Inputs full width */
        .responsive-grid input {
            margin-top: 0.25rem; 
            width: 100%;
            border: 1px solid #d1d5db; 
            border-radius: 0.75rem; 
            padding: 0.75rem 1rem; 
            box-sizing: border-box;
            font-size: 1rem;
        }
        .responsive-grid input:focus {
            outline: none;
            border-color: #6366f1; 
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5); 
        }
        /* Mobile: single column */
        @media (max-width: 768px) {
            .responsive-grid {
                grid-template-columns: 1fr;
            }
        }
        /* Responsive reCAPTCHA scaling */
        @media (max-width: 500px) {
            #recaptcha {
                transform: scale(0.85);
                transform-origin: top left;
            }
        }
        @media (max-width: 450px) {
            #recaptcha {
                transform: scale(0.77);
                transform-origin: top left;
            }
        }
    </style>
    @can('create_databreach')
    <div id="main-content" class="min-h-screen bg-gray-50 py-10 transition-all duration-300 ease-in-out">
        <div class="max-w-5xl mx-auto px-8 sm:px-10">
            <div class="relative bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-sm transition-all duration-300 sm:rounded-lg">

                <!-- Header -->
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 border-b-2 border-gray-200 pb-4 flex-1">
                        Incident Report Form
                    </h2>
                    <div class="flex items-center justify-center w-12 h-12 bg-gray-600 rounded-full border-2 border-white hover:bg-gray-800 transition-colors duration-300 ml-4">
                        <button id="close" 
                            onclick="window.location.href='{{ route('databreach.index') }}'" 
                            class="text-gray-800 text-xl focus:outline-none hover:text-white transition-colors duration-300">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Error Handling -->
                @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 mb-8 rounded-xl">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-exclamation-circle text-2xl mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-sm mb-2">Please fix the following errors:</h4>
                            <ul class="list-disc pl-5 space-y-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Form -->
                <form action="{{ route('databreach.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                    @csrf

                    <fieldset class="border border-gray-200 rounded-3xl p-8 bg-white shadow-sm hover:shadow-md transition-all duration-300 sm:rounded-lg">
                        <legend class="text-xl font-bold text-indigo-700 px-3 flex items-center gap-2">
                            Incident Information
                        </legend>

                        <!-- Sender Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <!-- Full Name -->
                            <div class="flex flex-col">
                                <label for="sender_fullname" class="block text-sm font-semibold text-gray-700">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="sender_fullname" name="sender_fullname" placeholder="e.g., Juan A. Dela Cruz" required
                                    class="mt-1 w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Email Address -->
                            <div class="flex flex-col">
                                <label for="sender_email" class="block text-sm font-semibold text-gray-700">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="sender_email" name="sender_email" placeholder="e.g., j_delacruz@cda.gov.ph" required
                                    class="mt-1 w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <!-- Dates (Chronology) -->
                        <div class="responsive-grid">
                            <!-- Date of Occurrence -->
                            <div>
                                <label for="date_occurrence" class="block text-sm font-semibold text-gray-700">
                                    Date of Occurrence <span style="color:#ef4444">*</span>
                                </label>
                                <input type="datetime-local" id="date_occurrence" name="date_occurrence" required>
                            </div>

                            <!-- Date of Discovery -->
                            <div>
                                <label for="date_discovery" class="block text-sm font-semibold text-gray-700">
                                    Date of Discovery <span style="color:#ef4444">*</span>
                                </label>
                                <input type="datetime-local" id="date_discovery" name="date_discovery" required>
                            </div>

                            <!-- Date of Notification -->
                            <div>
                                <label for="date_notification" class="block text-sm font-semibold text-gray-700">
                                    Date of Notification <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" id="date_notification" name="date_notification" required readonly
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d\TH:i') }}">
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

                    <!-- reCAPTCHA -->
                    <div class="mt-6 w-full max-w-sm mx-auto sm:mx-0">
                        <div class="g-recaptcha scale-100 origin-top-left w-full" id="recaptcha"

                        data-sitekey="{{ config('services.recaptcha.site_key') }}"
                        data-callback="enableSubmitButton"
                        data-expired-callback="disableSubmitButton"
                        data-error-callback="disableSubmitButton"></div>

                    @if ($errors->has('g-recaptcha-response'))
                        <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2" />
                    @endif
                    </div>

                    <div class="flex justify-end pt-8 border-t border-gray-200 mt-6">
                        <button 
                            type="submit" 
                            id="submitReportBtn"
                            class="inline-flex items-center px-6 py-3 mt-6 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-300 ease-in-out transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-60 disabled:cursor-not-allowed"
                            disabled
                        >
                            <i class="fas fa-paper-plane mr-2 text-base"></i>
                            <span>Submit Report</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // FORM VALIDATION ENHANCEMENT
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    let isValid = true;
                    const requiredFields = this.querySelectorAll('[required]');

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('border-red-500');
                        } else {
                            field.classList.remove('border-red-500');
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

                // Real-time validation feedback
                form.querySelectorAll('[required]').forEach(field => {
                    field.addEventListener('blur', function () {
                        if (!this.value.trim()) {
                            this.classList.add('border-red-500');
                        } else {
                            this.classList.remove('border-red-500');
                        }
                    });
                });
            }
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
@endcan
</x-app-layout>

<x-app-layout>
@can('edit_databreach')
    <div id="main-content" class="min-h-screen bg-gray-50 py-10 transition-all duration-300 ease-in-out">
        <div class="max-w-6xl mx-auto px-8">
            <div class="relative bg-white rounded-2xl p-8 border border-gray-200 shadow-sm transition-all duration-300 sm:rounded-lg">

                <!-- Header -->
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                        Edit Data Breach Notifications
                    </h2>
                    <div class="flex items-center justify-center w-12 h-12 bg-gray-600 rounded-full border-2 border-white transition-colors duration-300 ease-in-out hover:bg-gray-800">
                        <button id="close" 
                            onclick="window.location.href='{{ route('databreach.index') }}'" 
                            class="text-gray-800 text-xl focus:outline-none transition-colors duration-300 ease-in-out hover:text-white">
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
                <form action="{{ route('databreach.update', $notification->dbn_id) }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                    @csrf
                    @method('PUT')

                    <!-- ================= PAGE 1: SECTION A ================= -->
                    <div id="page1">
                        <fieldset class="border border-gray-200 rounded-3xl p-8 bg-white shadow-sm hover:shadow-md transition-all duration-300 sm:rounded-lg">
                            <legend class="text-xl font-bold text-indigo-700 px-3 flex items-center gap-2">
                                <i class="fas fa-info-circle text-indigo-500 mr-2"></i> A. Notification Type
                            </legend>

                            <!-- DBN Number -->
                            <div class="mt-3 space-y-2">
                                <label for="dbn_number" class="block text-sm font-semibold text-gray-700">
                                    DBN Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="dbn_number" name="dbn_number"
                                    value="{{ old('dbn_number', $notification->dbn_number) }}"
                                    placeholder="e.g., CDA-DBN-2025-01" required readonly   
                                    class="w-full border border-gray-300 bg-gray-50 text-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                            </div>

                            <!-- PIC & Email -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div>
                                    <label for="pic" class="block text-sm font-semibold text-gray-700">
                                        Personal Information Controller <span class="text-red-500">*</span>
                                    </label>
                                    <select id="pic" name="pic" required
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white appearance-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                        <option value="">-- Select Personal Information Controller --</option>
                                        <option value="CDA HO" {{ old('pic', $notification->pic) == 'CDA HO' ? 'selected' : '' }}>CDA HO</option>
                                        <option value="CDA CAR" {{ old('pic', $notification->pic) == 'CDA CAR' ? 'selected' : '' }}>CDA CAR</option>
                                        <option value="CDA NIR" {{ old('pic', $notification->pic) == 'CDA NIR' ? 'selected' : '' }}>CDA NIR</option>
                                        <option value="CDA NCR" {{ old('pic', $notification->pic) == 'CDA NCR' ? 'selected' : '' }}>CDA NCR</option>
                                        <option value="CDA Region I" {{ old('pic', $notification->pic) == 'CDA Region I' ? 'selected' : '' }}>CDA Region I</option>
                                        <option value="CDA Region II" {{ old('pic', $notification->pic) == 'CDA Region II' ? 'selected' : '' }}>CDA Region II</option>
                                        <option value="CDA Region III" {{ old('pic', $notification->pic) == 'CDA Region III' ? 'selected' : '' }}>CDA Region III</option>
                                        <option value="CDA Region IV-A" {{ old('pic', $notification->pic) == 'CDA Region IV-A' ? 'selected' : '' }}>CDA Region IV-A</option>
                                        <option value="CDA Region IV-B" {{ old('pic', $notification->pic) == 'CDA Region IV-B' ? 'selected' : '' }}>CDA Region IV-B</option>
                                        <option value="CDA Region V" {{ old('pic', $notification->pic) == 'CDA Region V' ? 'selected' : '' }}>CDA Region V</option>
                                        <option value="CDA Region VI" {{ old('pic', $notification->pic) == 'CDA Region VI' ? 'selected' : '' }}>CDA Region VI</option>
                                        <option value="CDA Region VII" {{ old('pic', $notification->pic) == 'CDA Region VII' ? 'selected' : '' }}>CDA Region VII</option>
                                        <option value="CDA Region VIII" {{ old('pic', $notification->pic) == 'CDA Region VIII' ? 'selected' : '' }}>CDA Region VIII</option>
                                        <option value="CDA Region IX" {{ old('pic', $notification->pic) == 'CDA Region IX' ? 'selected' : '' }}>CDA Region IX</option>
                                        <option value="CDA Region X" {{ old('pic', $notification->pic) == 'CDA Region X' ? 'selected' : '' }}>CDA Region X</option>
                                        <option value="CDA Region XI" {{ old('pic', $notification->pic) == 'CDA Region XI' ? 'selected' : '' }}>CDA Region XI</option>
                                        <option value="CDA Region XII" {{ old('pic', $notification->pic) == 'CDA Region XII' ? 'selected' : '' }}>CDA Region XII</option>
                                        <option value="CDA Region XIII" {{ old('pic', $notification->pic) == 'CDA Region XIII' ? 'selected' : '' }}>CDA Region XIII</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                <label for="email" class="block text-sm font-semibold text-gray-700">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="email" name="email" value="{{ old('email', $notification->team_email ?? $notification->email) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50 text-gray-700"
                                    readonly
                                >
                            </div>
                            </div>

                            <!-- Representative Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div>
                                    <label for="representative" class="block text-sm font-semibold text-gray-700">Representative <span class="text-red-500">*</span></label>
                                    <input type="text" id="representative" name="representative"
                                        value="{{ old('representative', $notification->representative) }}" required
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="representative_email_address" class="block text-sm font-semibold text-gray-700">Email Address <span class="text-red-500">*</span></label>
                                    <input type="text" id="representative_email_address" name="representative_email_address"
                                        value="{{ old('representative_email_address', $notification->representative_email_address) }}" required
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="flex flex-col md:flex-row md:items-end gap-6 mt-6">
                                <div class="flex-1">
                                    <label for="date_occurrence" class="block text-sm font-semibold text-gray-700">
                                        Date of Occurrence <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" id="date_occurrence" name="date_occurrence"
                                        value="{{ old('date_occurrence', $notification->date_occurrence) }}" required
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div class="flex-1">
                                    <label for="date_discovery" class="block text-sm font-semibold text-gray-700">
                                        Date of Discovery <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" id="date_discovery" name="date_discovery"
                                        value="{{ old('date_discovery', $notification->date_discovery) }}" required
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div class="flex-1">
                                    <label for="date_notification" class="block text-sm font-semibold text-gray-700">
                                        Date of Notification <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" id="date_notification" name="date_notification"
                                        value="{{ old('date_notification', $notification->date_notification) }}" required
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>

                            <!-- Brief Summary -->
                            <div class="mt-6">
                                <label for="brief_summary" class="block text-sm font-semibold text-gray-700">
                                    Brief Summary of the Incident <span class="text-red-500">*</span>
                                </label>
                                <textarea id="brief_summary" name="brief_summary" required rows="4"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none">{{ old('brief_summary', $notification->brief_summary) }}</textarea>
                            </div>

                            <!-- Notification Type -->
                            <div class="mt-4">
                                <label class="text-lg font-semibold text-gray-800 mb-2 block">Notification Type</label>
                                <div class="space-y-3">

                                    @php
                                        // Decode JSON manually if still stored as string
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
                                        <div class="flex items-start space-x-3">
                                            <input
                                                type="checkbox"
                                                name="notification_type_description[]"
                                                value="{{ $option }}"
                                                {{ is_array($notifTypes) && in_array($option, $notifTypes) ? 'checked' : '' }}
                                                class="mt-1 h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                            >
                                            <label class="text-base text-gray-700">{{ $option }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </fieldset>

                        <!-- Page 1 Buttons -->
                        <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                            <button type="button" id="next-page"
                                class="inline-flex items-center justify-center w-40 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Continue <i class="fas fa-arrow-right text-sm ml-3"></i>
                            </button>
                        </div>
                    </div>

                    <!-- ================= PAGE 2: SECTION B ================= -->
                    <div id="page2" class="hidden">
                        <fieldset class="border border-gray-200 rounded-2xl p-8 bg-white shadow-md hover:shadow-lg transition duration-300 ease-in-out sm:rounded-lg">
                            <legend class="text-xl font-bold text-gray-800 px-4 flex items-center">
                                <i class="fas fa-exclamation-circle text-indigo-600 mr-2"></i>
                                B. Data Breach Notification Details
                            </legend>

                            <div class="space-y-6 mt-3">
                                <!-- Sector and Subsector -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="sector_name" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Sector Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="sector_name" name="sector_name"
                                            value="{{ old('sector_name', $notification->sector_name) }}" required
                                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    </div>

                                    <div>
                                        <label for="subsector_name" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Subsector Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="subsector_name" name="subsector_name"
                                            value="{{ old('subsector_name', $notification->subsector_name) }}" required
                                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    </div>
                                </div>

                                <!-- Notification and Timeliness -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="notification_type" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Notification Type <span class="text-red-500">*</span>
                                        </label>
                                        <select id="notification_type" name="notification_type" required
                                            class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white appearance-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                            <option value="">-- Select Notification Type --</option>
                                            <option value="Mandatory" {{ old('notification_type', $notification->notification_type) == 'Mandatory' ? 'selected' : '' }}>Mandatory</option>
                                            <option value="Voluntary" {{ old('notification_type', $notification->notification_type) == 'Voluntary' ? 'selected' : '' }}>Voluntary</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="timeliness" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Timeliness <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="timeliness" name="timeliness"
                                            value="{{ old('timeliness', $notification->timeliness) }}" required
                                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    </div>
                                </div>

                                <!-- General and Specific Causes -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="general_cause" class="block text-sm font-semibold text-gray-700 mb-1">
                                            General Cause <span class="text-red-500">*</span>
                                        </label>
                                        <select id="general_cause" name="general_cause" required
                                            class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white appearance-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                            <option value="">-- Select General Cause --</option>
                                            @foreach (['Malicious Attack', 'Malicious Attack/Human Error', 'Human Error', 'System Glitch', 'Malicious Attack/System Glitch', 'System Glitch/Human Error', 'Others'] as $cause)
                                                <option value="{{ $cause }}" {{ old('general_cause', $notification->general_cause) == $cause ? 'selected' : '' }}>
                                                    {{ $cause }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="specific_cause" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Specific Cause <span class="text-red-500">*</span>
                                        </label>
                                        <select id="specific_cause" name="specific_cause" required
                                            class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white appearance-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                            <option value="{{ old('specific_cause', $notification->specific_cause) }}">{{ old('specific_cause', $notification->specific_cause) }}</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- With Request -->
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        With Request? <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center gap-6">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" name="with_request" value="Yes"
                                                {{ old('with_request', $notification->with_request) == 'Yes' ? 'checked' : '' }}
                                                class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <span class="ml-3 text-gray-700">Yes</span>
                                        </label>
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" name="with_request" value="No"
                                                {{ old('with_request', $notification->with_request) == 'No' ? 'checked' : '' }}
                                                class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <span class="ml-3 text-gray-700">No</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Textarea fields -->
                                @php
                                    $fields = [
                                        'how_breach_occured' => '1.A How Breach Occurred + DPS Vulnerability',
                                        'chronology' => '1.B Chronology',
                                        'num_records' => '1.C Number of Data Subject / Records',
                                        'description_nature' => '1.D Description / Nature',
                                        'likely_consequences' => '1.E Likely Consequences',
                                        'dpo' => '1.F Data Protection Officer (DPO)',
                                        'spi' => '2.A SPI',
                                        'other_info' => '2.B Other Information',
                                        'measures_to_address' => '3.A Measures to Address the Breach',
                                        'measures_to_secure' => '3.B Measures to Secure/Recover Personal Data',
                                        'actions_to_mitigate' => '3.C Actions to Mitigate Harm',
                                        'actions_to_inform' => '3.D Actions to Inform Data Subjects',
                                        'actions_to_prevent' => '3.E Measures to Prevent Recurrence of Incidence',
                                    ];
                                @endphp

                                @foreach ($fields as $name => $label)
                                    <div class="mt-4">
                                        <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700 mb-1">
                                            {{ $label }} <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="{{ $name }}" name="{{ $name }}" required
                                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none">{{ old($name, $notification->$name) }}</textarea>
                                    </div>

                                    {{-- Insert the extra "Provide Details" field right after 1.C --}}
                                    @if ($name === 'num_records')
                                        <div class="mt-4">
                                            <label for="num_records_provide_details" class="block text-gray-700 font-semibold mb-2">
                                                Number of Records - Provide Details
                                            </label>
                                            <textarea
                                                name="num_records_provide_details"
                                                id="num_records_provide_details"
                                                rows="3"
                                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                                                required>{{ old('num_records_provide_details', $notification->num_records_provide_details ?? '') }}</textarea>

                                            @error('num_records_provide_details')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                @endforeach

                                <!-- Record Type & Data Subjects -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="record_type" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Record Type <span class="text-red-500">*</span>
                                        </label>
                                        <select id="record_type" name="record_type" required
                                            class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white appearance-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
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
                                    </div>

                                    <div>
                                        <label for="data_subjects" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Data Subjects <span class="text-red-500">*</span>
                                        </label>
                                        <select id="data_subjects" name="data_subjects" required
                                            class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white appearance-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
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
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Page 2 Buttons -->
                        <div class="flex justify-between items-center gap-4 border-t border-gray-200 mt-8 pt-6">
                            <button type="button" id="prev-page"
                                class="inline-flex items-center justify-center w-40 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-arrow-left text-sm mr-2"></i> Back
                            </button>

                            <button type="submit"
                                class="inline-flex items-center justify-center w-40 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-save text-sm mr-2"></i> Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PAGE TOGGLE SCRIPT -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const nextPageBtn = document.getElementById('next-page');
            const prevPageBtn = document.getElementById('prev-page');
            const page1 = document.getElementById('page1');
            const page2 = document.getElementById('page2');

            if (nextPageBtn && prevPageBtn && page1 && page2) {
                nextPageBtn.addEventListener('click', () => {
                    page1.classList.add('hidden');
                    page2.classList.remove('hidden');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });

                prevPageBtn.addEventListener('click', () => {
                    page2.classList.add('hidden');
                    page1.classList.remove('hidden');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
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

            const year = parts.find(p => p.type === 'year').value;
            const month = parts.find(p => p.type === 'month').value;
            const day = parts.find(p => p.type === 'day').value;
            const hour = parts.find(p => p.type === 'hour').value;
            const minute = parts.find(p => p.type === 'minute').value;
            const manilaDateTime = `${year}-${month}-${day}T${hour}:${minute}`;

            const dateTimeFields = ['date_time_of_containment', 'incident_verified_date'];
            dateTimeFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = manilaDateTime;
            });

            // INCIDENT CATEGORY AUTO-POPULATION
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

            if (incidentTypeEl && incidentCategory) {
                incidentTypeEl.addEventListener("change", function () {
                    const selectedType = this.value;
                    incidentCategory.innerHTML = '<option value="">-- Select Specific Cause --</option>';

                    if (categoryOptions[selectedType]) {
                        categoryOptions[selectedType].forEach(category => {
                            const option = document.createElement("option");
                            option.value = category;
                            option.textContent = category;
                            incidentCategory.appendChild(option);
                        });
                    }
                });
            }

            // EMAIL AUTO-FILL BASED ON PIC REGION
            const picSelect = document.getElementById('pic');
            const emailField = document.getElementById('email');

            if (!picSelect) return;

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

            // SAVE AS DRAFT FUNCTIONALITY
            const saveDraftBtns = document.querySelectorAll('.save-draft-btn');
            saveDraftBtns.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Save as Draft?',
                        text: 'Your incident report will be saved as a draft.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, save it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire('Saved!', 'Your incident report has been saved as draft.', 'success');
                            // You can optionally trigger a hidden draft form submission here
                            // e.g. document.getElementById('draftForm').submit();
                        }
                    });
                });
            });

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
    </script>
@endcan
</x-app-layout>

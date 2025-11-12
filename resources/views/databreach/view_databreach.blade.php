<x-app-layout>
@can('view_databreach')
    <style>
        .table-fixed-force {
        table-layout: fixed;
        width: 100%;
        border-collapse: collapse; 
        border-spacing: 0;
        border: 1px solid #d1d5db; 
        }

        .table-fixed-force col {
        width: 50%;
        }

        .table-fixed-force th,
        .table-fixed-force td {
        width: 50%;
        border: 1px solid #d1d5db; 
        white-space: normal;
        overflow-wrap: anywhere;
        word-break: break-word;
        box-sizing: border-box;
        padding: 0.5rem 1rem; 
        }

        .table-fixed-force th {
        background: #f9fafb; 
        font-weight: 600;
        color: #374151; 
        }

        .table-fixed-force td {
        color: #1f2937; 
        background-color: #ffffff;
        }

        .table-fixed-force,
        .table-fixed-force th,
        .table-fixed-force td {
        border-radius: 0 !important;
        }
    </style>

    <div id="main-content" class="min-h-screen bg-gray-50 py-10">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center pt-6">
                <!-- Print Button -->
                @can('print_databreach')
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150 ease-in-out">
                    <i class="fas fa-print mr-2"></i> Print Report
                </button>
                @endcan

                <!-- Close Button -->
                <div class="flex justify-center items-center w-12 h-12 bg-gray-600 rounded-full border-2 border-white transition-colors duration-300 ease-in-out hover:bg-gray-800">
                    <button id="close"
                        onclick="window.location.href='{{ route('databreach.index') }}'"
                        class="text-gray-800 text-xl focus:outline-none transition-colors duration-300 ease-in-out hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-xl p-6 sm:p-8 md:p-10 mx-auto max-w-3xl">
                <!-- Report Title -->
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 text-center mt-6 sm:mt-10 mb-6">
                    DATA BREACH INCIDENT REPORT
                </h1>

                <!-- Facts / Scenario -->
                <section class="mb-6 text-center">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Facts / Scenario:</h2>
                    <p class="text-gray-900 leading-relaxed px-2">
                        {{ $notification->brief_summary }}
                    </p>
                </section>

                <!-- Notification Type -->
                <section class="mb-8 text-center">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">NOTIFICATION TYPE</h2>

                    <div class="overflow-x-auto px-4">
                        <table class="table-fixed-force min-w-full bg-white text-left no-radius">
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <th>PIC</th>
                                    <td>{{ $notification->pic }}</td>
                                </tr>
                                <tr>
                                    <th>Email Address</th>
                                    <td>{{ $notification->email }}</td>
                                </tr>
                                <tr>
                                    <th>Representative</th>
                                    <td>{{ $notification->representative }}</td>
                                </tr>
                                <tr>
                                    <th>Representative Email</th>
                                    <td>{{ $notification->representative_email_address }}</td>
                                </tr>
                                <tr>
                                    <th>Date/Time of Occurrence</th>
                                    <td>{{ $notification->date_occurrence->format('F d, Y – h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Date/Time of Discovery</th>
                                    <td>{{ $notification->date_discovery->format('F d, Y – h:i A') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 text-left px-4">
                        <p class="font-semibold mb-2 text-gray-700">Assistance Provided to Data Subject:</p>
                        <p class="list-disc list-inside text-gray-800">{{ $notification->actions_to_inform ?? 'N/A' }}</p>
                    </div>

                    @if(!empty($notification->notification_type_description))

                        @php
                            $types = json_decode($notification->notification_type_description, true);
                            if (is_null($types)) {
                     
                                $types = explode(',', $notification->notification_type_description);
                            }
                        @endphp

                        <div class="mt-6 text-left px-4">
                            <p class="font-semibold mb-2 text-gray-700">Notification Type Description:</p>
                            <ul class="list-disc list-inside text-gray-800">
                                @foreach($types as $type)
                                    <li>☑ {{ trim($type, ' "[]') }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </section>

                <!-- Data Breach Notification Details -->
                <section class="mb-8 text-center">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">DATA BREACH NOTIFICATION DETAILS</h2>

                    <div class="overflow-x-auto px-4">
                        <table class="table-fixed-force min-w-full bg-white text-left no-radius">
                        
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <th>Sector Name</th>
                                    <td>{{ $notification->sector_name }}</td>
                                </tr>
                                <tr>
                                    <th>Subsector Name</th>
                                    <td>{{ $notification->subsector_name }}</td>
                                </tr>
                                <tr>
                                    <th>Type of Notification</th>
                                    <td>{{ $notification->notification_type }}</td>
                                </tr>
                                <tr>
                                    <th>General Cause</th>
                                    <td>{{ $notification->general_cause }}</td>
                                </tr>
                                <tr>
                                    <th>Specific Cause</th>
                                    <td>{{ $notification->specific_cause }}</td>
                                </tr>
                                <tr>
                                    <th>With Request (YES/NO)</th>
                                    <td>{{ $notification->with_request }}</td>
                                </tr>
                                <tr>
                                    <th>Justification for Request</th>
                                    <td>{{ $notification->num_records_provide_details ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="mb-4 text-left px-4 mt-6">
                        <p class="font-semibold">How the Breach Occurred + DPS Vulnerability:</p>
                        <p class="leading-relaxed">{{ $notification->how_breach_occured }}</p>
                    </div>

                    <div class="mb-4 text-left px-4 mt-6">
                        <p class="font-semibold">Chronology of the Events:</p>
                        <p class="leading-relaxed whitespace-pre-line">{{ $notification->chronology }}</p>
                    </div>

                    <div class="mb-4 text-left px-4 mt-6">
                        <p class="font-semibold">Number of Data Subjects / Records:</p>
                        <p>{{ $notification->num_records }} records @if($notification->hundred_plus) (≥100) @endif</p>
                        <p>{{ $notification->num_records_provide_details }}</p>
                    </div>

                    <div class="mb-4 text-left px-4 mt-6">
                        <p class="font-semibold">Description / Nature of the Personal Data Breach:</p>
                        <p>{{ $notification->description_nature }}</p>
                    </div>

                    <div class="mb-4 text-left px-4 mt-6">
                        <p class="font-semibold">Likely Consequences:</p>
                        <p>{{ $notification->likely_consequences }}</p>
                    </div>

                    <div class="mb-4 text-left px-4 mt-6">
                        <p class="font-semibold">DPO Details:</p>
                        <p>{{ $notification->dpo }}</p>
                    </div>

                    <div class="mb-4 text-left px-4 mt-6">
                        <p class="font-semibold">Types of Sensitive Personal Information Involved:</p>
                        <p>{{ $notification->spi }}</p>
                    </div>

                    <div class="mb-4 text-left px-4 mt-6">
                        <p class="font-semibold">Other Information That May Enable Identity Fraud:</p>
                        <p>{{ $notification->other_info }}</p>
                    </div>
                </section>

                <!-- Measures Taken -->
                <section class="mb-6 text-center">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">MEASURES TAKEN TO ADDRESS THE BREACH</h2>
                    <div class="mb-4 text-left px-4 mt-6">
                        <p class="font-semibold">Measures to address breach:</p>
                        <p>{{ $notification->measures_to_address }}</p>
                    </div>
                    <div class="mb-4 text-left px-4 mt-6">
                        <p class="font-semibold">Measures to secure / recover personal data:</p>
                        <p>{{ $notification->measures_to_secure }}</p>
                    </div>
                    <div class="mb-4 text-left px-4 mt-6">
                        <p class="font-semibold">Actions to mitigate harm:</p>
                        <p>{{ $notification->actions_to_mitigate }}</p>
                    </div>
                    <div class="mb-4 text-left px-4 mt-6">
                        <p class="font-semibold">Actions to inform data subjects:</p>
                        <p>{{ $notification->actions_to_inform }}</p>
                    </div>
                    <div class="mb-4 text-left px-4 mt-6">
                        <p class="font-semibold">Measures to prevent recurrence:</p>
                        <p>{{ $notification->actions_to_prevent }}</p>
                    </div>
                </section>

                <!-- Record Type and Data Subject -->
                <section class="text-center">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">RECORD TYPE & DATA SUBJECTS</h2>
                    <div class="mb-4 text-left px-4 mt-6">
                        <p class="font-semibold">Record Type:</p>
                        <p> {{ $notification->record_type }}</p>
                        <p class="font-semibold mt-2">Data Subjects:</p>
                        <ul class="list-disc list-inside text-gray-800">
                            @foreach(explode(',', $notification->data_subjects) as $subject)
                                <li>☑ {{ $subject }}</li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endcan    
</x-app-layout>
<x-app-layout>
@can('view_overview_databreach')
    <style>
        .grid {
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            flex-wrap: wrap !important;
            width: 100%;
        }
    </style>
    <div id="main-content" class="min-h-screen transition-all duration-300 ease-in-out">
        <div id="dashboardContent">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <div class="text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Data Breach Notifications Overview</h3>

                        <div class="flex items-center justify-center w-12 h-12 bg-gray-600 rounded-full border-2 border-white transition-colors duration-300 ease-in-out hover:bg-gray-800">
                            <button id="close" 
                                onclick="window.location.href='{{ route('databreach.index') }}'" 
                                class="text-gray-800 text-xl focus:outline-none transition-colors duration-300 ease-in-out hover:text-white">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="mt-6 w-full">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 w-full mb-8">
                            @php
                                // Default values (in case controller data is missing)
                                $causeCards = $causeCards ?? [
                                    ['label' => 'Theft', 'icon' => 'fa-lock', 'count' => 0],
                                    ['label' => 'Identity Fraud', 'icon' => 'fa-id-card', 'count' => 0],
                                    ['label' => 'Sabotage / Physical Damage', 'icon' => 'fa-hammer', 'count' => 0],
                                    ['label' => 'Malicious Code', 'icon' => 'fa-bug', 'count' => 0],
                                    ['label' => 'Hacking', 'icon' => 'fa-user-secret', 'count' => 0],
                                    ['label' => 'Misuse of Resources', 'icon' => 'fa-network-wired', 'count' => 0],
                                    ['label' => 'Hardware Failure', 'icon' => 'fa-microchip', 'count' => 0],
                                    ['label' => 'Software Failure', 'icon' => 'fa-code', 'count' => 0],
                                    ['label' => 'Communication Failure', 'icon' => 'fa-wifi', 'count' => 0],
                                    ['label' => 'Natural Disaster', 'icon' => 'fa-cloud-bolt', 'count' => 0],
                                    ['label' => 'Design Error', 'icon' => 'fa-drafting-compass', 'count' => 0],
                                    ['label' => 'User Error', 'icon' => 'fa-user-xmark', 'count' => 0],
                                    ['label' => 'Operations Error', 'icon' => 'fa-cogs', 'count' => 0],
                                    ['label' => 'Software Maintenance Error', 'icon' => 'fa-screwdriver-wrench', 'count' => 0],
                                    ['label' => 'Third Party / Service Provider', 'icon' => 'fa-people-arrows', 'count' => 0],
                                    ['label' => 'Others', 'icon' => 'fa-ellipsis-h', 'count' => 0],
                                ];

                                // Color palette mapping
                                $colorPalettes = [
                                    'Theft' => ['bg' => 'bg-white', 'border' => 'border-gray-900', 'iconBg' => 'bg-gray-100', 'textColor' => 'text-gray-900', 'valueColor' => 'text-gray-700', ],
                                    'Identity Fraud' => ['bg' => 'bg-white', 'border' => 'border-gray-800', 'iconBg' => 'bg-gray-100', 'textColor' => 'text-gray-800', 'valueColor' => 'text-gray-700', ],
                                    'Sabotage / Physical Damage' => ['bg' => 'bg-white', 'border' => 'border-gray-700', 'iconBg' => 'bg-gray-100', 'textColor' => 'text-gray-800', 'valueColor' => 'text-gray-700', ],
                                    'Malicious Code' => ['bg' => 'bg-white', 'border' => 'border-gray-600', 'iconBg' => 'bg-gray-100', 'textColor' => 'text-gray-800', 'valueColor' => 'text-gray-700', ],
                                    'Hacking' => ['bg' => 'bg-white', 'border' => 'border-gray-500', 'iconBg' => 'bg-gray-100', 'textColor' => 'text-gray-800', 'valueColor' => 'text-gray-700', ],
                                    'Misuse of Resources' => [ 'bg' => 'bg-white', 'border' => 'border-gray-400', 'iconBg' => 'bg-gray-100', 'textColor' => 'text-gray-800', 'valueColor' => 'text-gray-700', ],
                                    'Hardware Failure' => ['bg' => 'bg-white', 'border' => 'border-gray-500', 'iconBg' => 'bg-gray-100', 'textColor' => 'text-gray-800', 'valueColor' => 'text-gray-700', ],
                                    'Software Failure' => ['bg' => 'bg-white', 'border' => 'border-gray-400', 'iconBg' => 'bg-gray-100', 'textColor' => 'text-gray-800', 'valueColor' => 'text-gray-700', ],
                                    'Communication Failure' => ['bg' => 'bg-white', 'border' => 'border-gray-400', 'iconBg' => 'bg-gray-100', 'textColor' => 'text-gray-800', 'valueColor' => 'text-gray-700', ],
                                    'Natural Disaster' => ['bg' => 'bg-white', 'border' => 'border-gray-500', 'iconBg' => 'bg-gray-100', 'textColor' => 'text-gray-900', 'valueColor' => 'text-gray-700', ],
                                    'Design Error' => ['bg' => 'bg-white','border' => 'border-gray-600', 'iconBg' => 'bg-gray-100', 'textColor' => 'text-gray-800', 'valueColor' => 'text-gray-700', ],
                                    'User Error' => ['bg' => 'bg-white', 'border' => 'border-gray-800', 'iconBg' => 'bg-gray-100', 'textColor' => 'text-gray-800','valueColor' => 'text-gray-700', ],
                                    'Operations Error' => ['bg' => 'bg-white', 'border' => 'border-gray-700', 'iconBg' => 'bg-gray-100', 'textColor' => 'text-gray-800', 'valueColor' => 'text-gray-700', ],
                                    'Software Maintenance Error' => ['bg' => 'bg-white', 'border' => 'border-gray-600', 'iconBg' => 'bg-gray-100', 'textColor' => 'text-gray-800', 'valueColor' => 'text-gray-700', ],
                                    'Third Party / Service Provider' => ['bg' => 'bg-white', 'border' => 'border-gray-700', 'iconBg' => 'bg-gray-100', 'textColor' => 'text-gray-800', 'valueColor' => 'text-gray-700', ],
                                    'Others' => ['bg' => 'bg-white', 'border' => 'border-gray-400', 'iconBg' => 'bg-gray-100', 'textColor' => 'text-gray-800', 'valueColor' => 'text-gray-700',],
                                ];
                            @endphp

                            @foreach ($causeCards as $card)
                                @php
                                    $colors = $colorPalettes[$card['label']] ?? $colorPalettes['Others'];
                                @endphp

                                <div class="{{ $colors['bg'] }} {{ $colors['border'] }} border-l-4 rounded-xl shadow-md p-4 hover:shadow-lg transition-all duration-300 flex justify-between items-center">
                                    <div class="flex items-center gap-4">
                                        <div class="{{ $colors['iconBg'] }} p-4 rounded-full flex items-center justify-center">
                                            <i class="fa-solid {{ $card['icon'] }} text-2xl {{ $colors['textColor'] }}"></i>
                                        </div>
                                        <h4 class="text-md font-semibold {{ $colors['textColor'] }}">{{ $card['label'] }}</h4>
                                    </div>
                                    <p class="text-3xl font-bold {{ $colors['valueColor'] }}">{{ $card['count'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recently Submitted Notifications -->
                    <div class="mt-4 bg-white rounded-lg shadow p-6">
                        <h4 class="text-lg font-semibold mb-4 text-gray-700 flex items-center">
                            <i class="fa-solid fa-clock-rotate-left mr-2 text-blue-600"></i> Recently Reported Notifications
                        </h4>
                        <div class="overflow-x-auto bg-white shadow-lg rounded-lg border border-gray-200">
                            <table class="min-w-full table-fixed divide-y divide-gray-200 text-left text-gray-800">
                                <thead class="bg-gray-100 text-gray-700">
                                    <tr>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">DBN No.</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">Sender</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">PIC</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">Date of Occurrence</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">Date of Discovery</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">General Cause</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentlyReported as $dbn)
                                        <tr class="hover:bg-gray-50 border-b">
                                            <td class="px-6 py-4">{{ $dbn->dbn_number }}</td>
                                            <td class="px-6 py-4">{{ $dbn->sender_fullname }}</td>
                                            <td class="px-6 py-4">{{ $dbn->pic }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($dbn->date_occurrence)->format('M d, Y h:i A') }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($dbn->date_discovery)->format('M d, Y h:i A') }}</td>
                                            <td class="px-6 py-4">{{ $dbn->general_cause }}</td>
                                            <td class="px-6 py-4 text-left">
                                                <span class="px-2 py-1 rounded-full text-sm font-semibold
                                                    {{ 
                                                        $dbn->status === 'For Evaluation' ? 'bg-green-100 text-blue-700' : 
                                                        ($dbn->status === 'Reported' ? 'bg-blue-100 text-blue-700' : 
                                                        'bg-yellow-100 text-yellow-700') 
                                                    }}">
                                                    {{ $dbn->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-6 text-gray-500 text-lg">No recently submitted notifications to display.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcan
</x-app-layout>

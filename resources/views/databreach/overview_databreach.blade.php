<x-app-layout>

    <style>
        /* Mobile: always 1 column */
        @media (max-width: 400px) {
            .cause-grid {
                grid-template-columns: 1fr !important;     
            }
        }

        /* Desktop: always 4 columns */
        @media (min-width: 1024px) {
            .cause-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            }
        }
        .chart-wrapper {
            display: flex;
            justify-content: center;  
            align-items: center;     
            height: 100%;          
        }

        /* Chart container size */
        .chart-container {
            width: 100%;             
            max-width: 520px;        
            height: 520px;             
        }

        /* Responsive adjustments for small screens */
        @media (max-width: 400px) {
            .chart-container {
                max-width: 300px;    
            }
        }
    </style>

    @can('view_overview_databreach')
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

                    <form method="GET" action="{{ route('databreach.overview') }}"
                        class="flex flex-col md:flex-row gap-4 flex-wrap">

                        <!-- Year Filter -->
                        <div class="flex flex-col">
                            <label for="year" class="font-semibold text-gray-700 mb-1">
                                Filter by Year:
                            </label>
                            <select name="year" id="year"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-4 py-2">
                                <option value="">All Years</option>
                                @foreach($years as $y)
                                    <option value="{{ $y }}" @if(isset($year) && $year == $y) selected @endif>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="flex flex-col">
                            <label for="status" class="font-semibold text-gray-700 mb-1">
                                Filter by Status:
                            </label>
                            <select name="status" id="status"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-4 py-2">
                                <option value="">All Status</option>
                                @foreach($statuses as $s)
                                    <option value="{{ $s }}" @if(isset($statusFilter) && $statusFilter == $s) selected @endif>
                                        {{ $s }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Apply Filter -->
                        <div class="flex flex-col justify-end">
                            <div class="mb-1 h-[1.25rem]"></div>

                            <button type="submit"
                                class="inline-flex items-center justify-center text-sm px-4 py-2 rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700
                                    focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-filter mr-2"></i> Apply Filter
                            </button>
                        </div>

                        <!-- Generate Report -->
                         @can('generate_databreach')
                        <div class="flex flex-col justify-end">
                            <div class="mb-1 h-[1.25rem]"></div>

                            <button type="submit" name="action" value="generate"
                                class="inline-flex items-center justify-center text-sm px-4 py-2 rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700
                                    focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-download mr-2"></i> Generate Report
                            </button>
                        </div>
                        @endcan

                    </form>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- LEFT SIDE: Vertical Cards -->
                        <div class="flex flex-col space-y-4 gap-6">
                            
                            <!-- Total Security Incidents -->
                            <div class="bg-white border-l-4 border-blue-600 rounded-xl shadow p-6 flex items-center justify-between hover:shadow-lg transition">
                                <div class="ml-3">
                                    <h4 class="text-lg font-semibold text-gray-800">Total Security Incidents</h4>
                                    <p class="text-3xl font-bold text-gray-700">
                                        {{ $totalNotifications ?? 0 }}
                                    </p>
                                </div>
                                <div class="p-4 rounded-full flex items-center justify-center">
                                    <i class="fas fa-shield-alt text-blue-700 text-3xl"></i>
                                </div>
                            </div>

                            <!-- Mandatory Incidents -->
                            <div class="bg-white border-l-4 border-red-600 rounded-xl shadow p-6 flex items-center justify-between hover:shadow-lg transition">
                                <div class="ml-3">
                                    <h4 class="text-lg font-semibold text-gray-800">Mandatory Incidents</h4>
                                    <p class="text-3xl font-bold text-gray-700">
                                        {{ $totalMandatory ?? 0 }}
                                    </p>
                                </div>
                                <div class="p-4 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-red-700 text-3xl"></i>
                                </div>
                            </div>

                            <!-- Voluntary Incidents -->
                            <div class="bg-white border-l-4 border-yellow-600 rounded-xl shadow p-6 flex items-center justify-between hover:shadow-lg transition">
                                <div class="ml-3">
                                    <h4 class="text-lg font-semibold text-gray-800">Voluntary Incidents</h4>
                                    <p class="text-3xl font-bold text-gray-700">
                                        {{ $totalVoluntary ?? 0 }}
                                    </p>
                                </div>
                                <div class="p-4 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clipboard-list text-yellow-700 text-3xl"></i>
                                </div>
                            </div>

                            <!-- Others Incidents -->
                            <div class="bg-white border-l-4 border-gray-600 rounded-xl shadow p-6 flex items-center justify-between hover:shadow-xl hover:scale-105 transition transform">
                                <div class="ml-3">
                                    <h4 class="text-lg font-semibold text-gray-800">Others</h4>
                                    <p class="text-3xl font-bold text-gray-700">
                                        {{ $totalOthers ?? 0 }}
                                    </p>
                                </div>
                                <div class="p-4 rounded-full flex items-center justify-center">
                                    <i class="fa fa-question-circle text-gray-700 text-3xl"></i>
                                </div>
                            </div>

                            <!-- Total Reported -->
                            <div class="bg-white border-l-4 border-green-600 rounded-xl shadow p-6 flex items-center justify-between hover:shadow-lg transition">
                                <div class="ml-3">
                                    <h4 class="text-lg font-semibold text-gray-800">Total Reported</h4>
                                    <p class="text-3xl font-bold text-gray-700">
                                        {{ $totalReported ?? 0 }}
                                    </p>
                                </div>
                                <div class="p-4 flex items-center justify-center">
                                    <i class="fas fa-check-circle text-green-700 text-3xl"></i>
                                </div>
                            </div>

                        </div>

                        <!-- RIGHT SIDE: Pie Chart -->
                        <div class="bg-white shadow-md rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800">
                                Incidents per Specific Cause
                            </h3>

                            <div class="chart-wrapper">
                                <div class="chart-container">
                                    <canvas id="causePieChart"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>

                    @php
                        $labels = array_column($causeCards, 'label');
                        $values = array_column($causeCards, 'count');
                    @endphp

                    <!-- Recently Submitted Notifications -->
                    <h4 class="text-lg font-semibold mb-4 text-gray-700 flex items-center mt-6">
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
                                        <td class="px-4 py-4 text-gray-600">{{ \Carbon\Carbon::parse($dbn->date_occurrence)->format('M d, Y h:i A') }}</td>
                                        <td class="px-4 py-4 text-gray-600">{{ \Carbon\Carbon::parse($dbn->date_discovery)->format('M d, Y h:i A') }}</td>
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
                                        <td colspan="7" class="text-center py-6 text-gray-500 text-lg">No recently reported incidents to display.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcan
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('causePieChart').getContext('2d');

    const labels = @json($labels); // incident labels
    const values = @json($values); // actual values

    const hasData = values.length && values.some(v => v > 0);

    const chartData = {
        labels: labels,
        datasets: [{
            data: hasData ? values : new Array(values.length).fill(1), // use 1 for each slice if no data
            backgroundColor: hasData ? [
                '#2563EB','#DC2626','#16A34A','#CA8A04','#7C3AED','#EA580C','#0891B2',
                '#9D174D','#4B5563','#1D4ED8','#B91C1C','#15803D',
                '#92400E','#6D28D9','#4338CA','#000000'
            ] : new Array(values.length).fill('#E5E7EB'), // gray if no data
            borderColor: '#FFFFFF',
            borderWidth: 2
        }]
    };

    // Custom plugin to display "No Data" in center
    const noDataPlugin = {
        id: 'noDataPlugin',
        afterDraw: (chart) => {
            if (!hasData) {
                const { ctx, chartArea: { width, height } } = chart;
                ctx.save();
                ctx.font = 'bold 16px Arial';
                ctx.fillStyle = '#6B7280';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText('No Data', width / 2, height / 2);
                ctx.restore();
            }
        }
    };

    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    boxWidth: 12,
                    font: { size: 12 }
                }
            },
            tooltip: {
                enabled: hasData // hide tooltip if no data
            }
        }
    };

    new Chart(ctx, {
        type: 'pie',
        data: chartData,
        options: chartOptions,
        plugins: [noDataPlugin]
    });
});
</script>

</x-app-layout>

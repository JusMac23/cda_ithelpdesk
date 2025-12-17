<x-app-layout>
    @if(auth()->user()->hasAnyRole(['Super Admin', 'Admin']))
    <div id="main-content" class="min-h-screen transition-all duration-300 ease-in-out">
        <div id="dashboardContent">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 sm:p-8">
                <div class="text-gray-900">
                    <h3 class="text-xl sm:text-2xl font-bold mb-4">Tickets Overview</h3>

                    {{-- Dashboard Cards --}}
                    <div class="flex flex-col lg:flex-row gap-6 lg:gap-6 mb-8">
                        @php
                            $cards = [
                                [
                                    'label' => 'Total Tickets',
                                    'icon' => 'fa-ticket',
                                    'bg' => 'bg-white',
                                    'border' => 'border-indigo-500',
                                    'iconBg' => 'bg-indigo-100',
                                    'textColor' => 'text-indigo-800',
                                    'valueColor' => 'text-indigo-600',
                                    'value' => $total,
                                ],
                                [
                                    'label' => 'Pending Tickets',
                                    'icon' => 'fa-hourglass-half',
                                    'bg' => 'bg-green-50',
                                    'border' => 'border-green-500',
                                    'iconBg' => 'bg-green-100',
                                    'textColor' => 'text-green-800',
                                    'valueColor' => 'text-green-600',
                                    'value' => $pending,
                                ],
                                [
                                    'label' => 'Resolved Tickets',
                                    'icon' => 'fa-check-circle',
                                    'bg' => 'bg-blue-50',
                                    'border' => 'border-blue-500',
                                    'iconBg' => 'bg-blue-100',
                                    'textColor' => 'text-blue-800',
                                    'valueColor' => 'text-blue-600',
                                    'value' => $resolved,
                                ],
                                [
                                    'label' => 'Overdue Tickets',
                                    'icon' => 'fa-exclamation-circle',
                                    'bg' => 'bg-red-50',
                                    'border' => 'border-red-500',
                                    'iconBg' => 'bg-red-100',
                                    'textColor' => 'text-red-800',
                                    'valueColor' => 'text-red-600',
                                    'value' => $overdue,
                                ],
                            ];
                        @endphp

                        @foreach ($cards as $card)
                            <div class="flex-1 min-w-[200px] sm:min-w-[250px] {{ $card['bg'] }} {{ $card['border'] }} border-l-4 rounded-xl shadow-md p-4 sm:p-6 hover:shadow-lg transition-all duration-300">
                                <!-- Use flex-col on small screens, flex-row on larger screens -->
                                <div class="flex flex-col sm:flex-row items-center sm:justify-between gap-4 sm:gap-6">
                                    <div class="flex items-center gap-3 sm:gap-4">
                                        <div class="{{ $card['iconBg'] }} p-3 sm:p-4 rounded-full">
                                            <i class="fa-solid {{ $card['icon'] }} text-xl sm:text-2xl {{ $card['textColor'] }}"></i>
                                        </div>
                                        <h4 class="text-md sm:text-lg font-semibold {{ $card['textColor'] }}">{{ $card['label'] }}</h4>
                                    </div>
                                    <p class="text-3xl font-bold {{ $card['valueColor'] }} mt-2 sm:mt-0 text-right">
                                        {{ $card['value'] }}
                                    </p>
                                </div>
                            </div>

                        @endforeach
                    </div>

                    {{-- IT Area, Personnel, Service, Overdue --}}
                    <div class="flex flex-col lg:flex-row gap-6 lg:gap-6 mb-8">
                        {{-- Tickets by Region --}}
                        <div class="flex-1 bg-white rounded-lg shadow p-4 sm:p-6 overflow-x-auto">
                            <h4 class="text-lg font-semibold mb-4 text-gray-700 flex items-center">
                                <i class="fa-solid fa-network-wired mr-2 text-indigo-600"></i> Tickets by Region
                            </h4>
                            <table class="w-full text-md sm:text-base table-auto">
                                <thead>
                                    <tr class="bg-indigo-100 text-indigo-800">
                                        <th class="px-3 sm:px-4 py-2 text-left">IT Area</th>
                                        <th class="px-3 sm:px-4 py-2 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($byItArea as $area)
                                        <tr class="border-b odd:bg-white even:bg-gray-50 hover:bg-gray-100">
                                            <td class="px-3 sm:px-4 py-2">{{ $area->it_area }}</td>
                                            <td class="px-3 sm:px-4 py-2 text-right font-bold">{{ $area->total }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-4 text-gray-500">No data available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Tickets by Technical Personnel --}}
                        <div class="flex-1 bg-white rounded-lg shadow p-4 sm:p-6 overflow-x-auto">
                            <h4 class="text-lg font-semibold mb-4 text-gray-700 flex items-center">
                                <i class="fa-solid fa-user-gear mr-2 text-green-600"></i> Tickets by Technical Personnel
                            </h4>
                            <table class="w-full text-md sm:text-base table-auto">
                                <thead>
                                    <tr class="bg-green-100 text-green-800">
                                        <th class="px-3 sm:px-4 py-2 text-left">Technical Personnel</th>
                                        <th class="px-3 sm:px-4 py-2 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($byItPersonnel as $person)
                                        <tr class="border-b odd:bg-white even:bg-gray-50 hover:bg-gray-100">
                                            <td class="px-3 sm:px-4 py-2">{{ $person->it_personnel }}</td>
                                            <td class="px-3 sm:px-4 py-2 text-right font-bold">{{ $person->total }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-4 text-gray-500">No data available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Tickets by Service --}}
                        <div class="flex-1 bg-white rounded-lg shadow p-4 sm:p-6 overflow-x-auto">
                            <h4 class="text-lg font-semibold mb-4 text-gray-700 flex items-center">
                                <i class="fa-solid fa-tools mr-2 text-yellow-600"></i> Tickets by Technical Services
                            </h4>
                            <table class="w-full sm:text-base table-auto">
                                <thead>
                                    <tr class="bg-yellow-100 text-yellow-800 text-md">
                                        <th class="px-3 sm:px-4 py-2 text-left">Service</th>
                                        <th class="px-3 sm:px-4 py-2 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($byService as $service)
                                        <tr class="border-b odd:bg-white even:bg-gray-50 hover:bg-gray-100 text-sm">
                                            <td class="px-3 sm:px-4 py-2">{{ $service->service }}</td>
                                            <td class="px-3 sm:px-4 py-2 text-right font-bold">{{ $service->total }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-4 text-gray-500">No data available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Overdue Tickets --}}
                        <div class="flex-1 bg-white rounded-lg shadow p-4 sm:p-6 overflow-x-auto">
                            <h4 class="text-lg font-semibold mb-4 text-gray-700 flex items-center">
                                <i class="fa-solid fa-clock mr-2 text-red-800"></i> Overdue Tickets by Personnel
                            </h4>
                            <table class="w-full sm:text-base table-auto">
                                <thead>
                                    <tr class="bg-red-100 text-red-800 text-md">
                                        <th class="px-3 sm:px-4 py-2 text-left">Request Details</th>
                                        <th class="px-3 sm:px-4 py-2 text-right">Assigned Personnel</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($overdueTickets as $personnel => $tickets)
                                        @foreach ($tickets as $ticket)
                                            <tr class="border-b odd:bg-white even:bg-gray-50 hover:bg-gray-100 text-sm">
                                                <td class="px-3 sm:px-4 py-2">{{ $ticket->request }}</td>
                                                <td class="px-3 sm:px-4 py-2 text-right font-bold">{{ $personnel }}</td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-4 text-gray-500">No overdue tickets.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Recently Resolved Tickets --}}
                   
                        <h4 class="text-lg font-semibold mb-4 text-gray-700 flex items-center">
                            <i class="fa-solid fa-clock-rotate-left mr-2 text-blue-600"></i> Recently Resolved Tickets
                        </h4>

                        {{-- Scrollable table container --}}
                        <div class="overflow-x-auto bg-white shadow-lg rounded-lg border border-gray-200">
                            <div class="min-w-screen">
                                <table class="min-w-full table-auto divide-y divide-gray-200 text-left text-gray-800 text-md">
                                    <thead class="bg-gray-100 text-gray-700">
                                        <tr>
                                            <th class="px-4 sm:px-6 py-2 font-semibold uppercase">Ticket Number</th>
                                            <th class="px-4 sm:px-6 py-2 font-semibold uppercase">Requested By</th>
                                            <th class="px-4 sm:px-6 py-2 font-semibold uppercase">Division</th>
                                            <th class="px-4 sm:px-6 py-2 font-semibold uppercase">Service</th>
                                            <th class="px-4 sm:px-6 py-2 font-semibold uppercase">Assigned Personnel</th>
                                            <th class="px-4 sm:px-6 py-2 font-semibold uppercase">Date Created</th>
                                            <th class="px-4 sm:px-6 py-2 font-semibold uppercase">Date Resolved</th>
                                            <th class="px-4 sm:px-6 py-2 font-semibold uppercase text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentlyResolved as $ticket)
                                            <tr class="hover:bg-gray-50 border-b">
                                                <td class="px-6 py-2">{{ $ticket->ticket_number}}</td>
                                                <td class="px-6 py-2">{{ $ticket->firstname }} {{ $ticket->lastname }}</td>
                                                <td class="px-6 py-2">{{ $ticket->division }}</td>
                                                <td class="px-6 py-2">{{ $ticket->service }}</td>
                                                <td class="px-6 py-2">{{ $ticket->it_personnel }}</td>
                                                <td class="px-6 py-2 text-gray-600">{{ \Carbon\Carbon::parse($ticket->date_created)->format('M d, Y h:i A') }}</td>
                                                <td class="px-6 py-2 text-gray-600">{{ \Carbon\Carbon::parse($ticket->date_resolved)->format('M d, Y h:i A') }}</td>
                                                @php
                                                    $status = trim($ticket->status);
                                                    $statusClasses = match($status) {
                                                        'Resolved' => 'bg-green-100 text-green-800',
                                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                                        'Pending/Re-Assigned' => 'bg-blue-100 text-blue-800',
                                                        default => 'bg-gray-100 text-gray-800',
                                                    };
                                                @endphp
                                                <td class="px-8 sm:px-8 text-center">
                                                    <span class="px-6 py-2 text-sm sm:text-md font-semibold rounded-full {{ $statusClasses }}">
                                                        {{ $ticket->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-6 text-gray-500">No recently resolved tickets to display.</td>
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
    @endif
</x-app-layout>

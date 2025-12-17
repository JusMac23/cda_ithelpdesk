<x-app-layout>
    <div id="main-content" class="min-h-screen transition-all duration-300 ease-in-out">
        <div id="ticketsContent">
            <div class="w-full">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                    <div class="p-2 text-gray-900">
                        <h3 class="text-xl font-bold text-gray-900 mb-4" style="font-weight: 900;">All Tickets</h3>

                        <div class="flex items-center justify-between mb-4">

                            @can('create_ticket')
                            <button id="openModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150 ease-in-out">
                                <i class="fas fa-plus mr-2 text-base"></i> Add Ticket
                            </button>
                            @endcan

                            <label class="inline-flex items-center space-x-2">
                                <input type="checkbox" id="autoReloadCheckbox" class="form-checkbox text-indigo-600 mr-2">
                                <span class="text-sm text-gray-700 ml-1">
                                    (<span id="countdown">60</span>s) Seconds Auto-Reload
                                </span>
                            </label>
                        </div>

                        <form action="{{ route('tickets.index') }}" method="GET" class="flex flex-wrap items-end gap-4 mb-4">
                            <!-- IT Area Dropdown -->
                            <div>
                                <label for="it_area" class="block text-sm font-medium text-gray-700">Filter by Region</label>
                                <select name="it_area" id="it_area"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-6 py-2">
                                    <option value="">All Category</option>
                                    @if($it_area && count($it_area))
                                        @foreach($it_area as $area)
                                            <option value="{{ $area }}" {{ request('it_area') == $area ? 'selected' : '' }}>
                                                {{ $area }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <!-- Status Dropdown -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Filter by Status</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-6 py-2">
                                    <option value="">All Tickets</option>
                                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Pending/Re-Assigned" {{ request('status') == 'Pending/Re-Assigned' ? 'selected' : '' }}>Pending/Re-Assigned</option>
                                    <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                </select>
                            </div>
                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="datetime-local" id="start_date" name="start_date" value="{{ request('start_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-3 py-2">
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="datetime-local" id="end_date" name="end_date" value="{{ request('end_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-3 py-2">
                            </div>
                            <!-- Search Button -->
                            <div class="pt-6">
                                <button type="submit" name="action" value="search"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-filter mr-2"></i> Apply Filter
                                </button>
                            </div>

                            <!-- Generate Report Button -->
                            @can('generate_report')
                            <div class="pt-6">
                                <button type="submit" name="action" value="generate"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <i class="fas fa-download mr-2"></i> Generate Report
                                </button>
                            </div>
                            @endcan

                            <!-- Search -->
                            @can('search_ticket')
                            <div class="flex justify-end pt-6">
                                <form action="{{ route('tickets.index') }}" 
                                    method="GET" 
                                    class="flex items-center gap-2">
                                    
                                    <!-- Search Input -->
                                    <input type="text" 
                                        name="search_query" 
                                        value="{{ request('search_query') }}" 
                                        placeholder="Search..." 
                                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring focus:ring-indigo-200">
                                    
                                    <!-- Search Button -->
                                    <button type="submit" 
                                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 focus:outline-none">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                            @endcan

                        </form>
                        <div class="overflow-x-auto bg-white shadow-lg rounded-lg border border-gray-200">
                            <table class="min-w-full table-fixed divide-y divide-gray-200 text-sm text-left text-gray-800">
                                <thead class="bg-gray-100 border-b border-gray-300 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-2 text-center min-w-[4rem] font-semibold text-gray-700 uppercase tracking-wider">Tracking ID</th>
                                        <th class="px-6 py-2 text-center min-w-[6rem] font-semibold text-gray-700 uppercase tracking-wider">Requested By</th>
                                        <th class="px-6 py-2 min-w-[6rem] font-semibold text-gray-700 uppercase tracking-wider">Division</th>
                                        <th class="px-6 py-2 min-w-[4rem] font-semibold text-gray-700 uppercase tracking-wider">Device</th>
                                        <th class="px-6 py-2 min-w-[6rem] font-semibold text-gray-700 uppercase tracking-wider">Service</th>
                                        <th class="px-6 py-2 text-center min-w-[8rem] font-semibold text-gray-700 uppercase tracking-wider">Request Details</th>
                                        <th class="px-6 py-2 text-center min-w-[8rem] font-semibold text-gray-700 uppercase tracking-wider">Assigned Personnel</th>
                                        <th class="px-6 py-2 text-center min-w-[8rem] font-semibold text-gray-700 uppercase tracking-wider">Action Taken</th>
                                        <th class="px-6 py-2 text-center min-w-[5rem] font-semibold text-gray-700 uppercase tracking-wider">Date Created</th>
                                        <th class="px-6 py-2 text-center min-w-[5rem] font-semibold text-gray-700 uppercase tracking-wider">Date Resolved</th>
                                        <th class="px-6 py-2 text-center min-w-[2rem] font-semibold text-gray-700 uppercase tracking-wider">Photo</th>
                                        <th class="px-6 py-2 text-center min-w-[2rem] font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-2 text-center min-w-[9rem] text-center font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @forelse ($tickets as $ticket)
                                        <tr class="hover:bg-gray-50 focus-within:bg-gray-100 transition">
                                            <td class="px-6 py-4 font-medium text-gray-900">{{ $ticket->ticket_number }}</td>
                                            <td class="px-6 py-4">{{ $ticket->firstname }} {{ $ticket->lastname }}</td>
                                            <td class="px-6 py-4">{{ $ticket->division }}</td>
                                            <td class="px-6 py-4">{{ $ticket->device }}</td>
                                            <td class="px-6 py-4">{{ $ticket->service }}</td>
                                            <td class="px-6 py-4 truncate max-w-xs" title="{{ $ticket->request }}">{{ $ticket->request }}</td>
                                            <td class="px-6 py-4">{{ $ticket->it_personnel }}</td>
                                            <td class="px-6 py-4">{{ $ticket->action_taken ?: 'N/A' }}</td>
                                            <td class="px-6 py-4 text-gray-600 text-xs">{{ \Carbon\Carbon::parse($ticket->date_created)->format('M d, Y h:i A') }}</td>
                                            <td class="px-6 py-4 text-gray-600 text-xs">
                                                @if($ticket->date_resolved)
                                                    {{ \Carbon\Carbon::parse($ticket->date_resolved)->format('M d, Y h:i A') }}
                                                @else
                                                    <span class="inline-block px-3 py-1 text-xs font-semibold italic text-red-500">Not Yet Resolved</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($ticket->photo)
                                                    <a href="{{ asset('storage/' . $ticket->photo) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $ticket->photo) }}" alt="Photo" class="w-12 h-12 object-cover rounded-md border border-gray-200 shadow-sm hover:opacity-80 transition">
                                                    </a>
                                                @else
                                                    <span>N/A</span>
                                                @endif
                                            </td>
                                            @php
                                                $status = trim($ticket->status);
                                                $statusClasses = match($status) {
                                                    'Resolved' => 'bg-green-100 text-green-800',
                                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                                    'Pending/Re-Assigned' => 'bg-blue-100 text-blue-800',
                                                    default => 'bg-gray-100 text-gray-800',
                                                };
                                            @endphp
                                            <td class="px-6 py-4 text-center ticket-status">
                                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $statusClasses }}">
                                                    {{ $ticket->status }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-1 text-base align-top">
                                                <div class="flex flex-col space-y-2 mr-2">

                                                    @can('reassign_ticket')
                                                    <div class="border rounded-lg px-1 py-1 bg-yellow-50 hover:bg-yellow-100 transition mb-1">
                                                        <a href="javascript:void(0);" 
                                                            class="flex items-center space-x-1 text-xs text-yellow-600 hover:text-yellow-800 open-assign-modal"
                                                            data-id="{{ $ticket->ticket_id }}" 
                                                            data-status="{{ $ticket->status }}">
                                                            <i class="fas fa-user-plus"></i>
                                                            <span>Re-Assign Ticket</span>
                                                        </a>
                                                    </div>
                                                    @endcan

                                                    @can('update_status_ticket')
                                                    <div class="border rounded-lg px-1 py-1 bg-blue-50 hover:bg-blue-100 transition mb-1">
                                                        <a href="javascript:void(0);"
                                                            class="flex items-center space-x-1 text-xs text-blue-600 hover:text-blue-800 open-edit-modal"
                                                            data-id="{{ $ticket->ticket_id }}"
                                                            data-status="{{ $ticket->status }}"
                                                            data-action_taken="{{ $ticket->action_taken }}"
                                                            data-photo="{{ $ticket->photo }}">
                                                            <i class="fas fa-edit"></i>
                                                            <span>Update Status</span>
                                                        </a>
                                                    </div>
                                                    @endcan

                                                    @can('generate_tsar')
                                                    @if($ticket->status === 'Resolved')
                                                        <div class="border rounded-lg px-1 py-1 bg-indigo-50 hover:bg-indigo-100 transition mb-1">
                                                            <a href="{{ route('tickets.generateTSAR', $ticket->ticket_id) }}"
                                                                class="flex items-center space-x-1 text-xs text-blue-600 hover:text-blue-800">
                                                                <i class="fas fa-file-alt"></i>
                                                                <span>Generate TSAR</span>
                                                            </a>
                                                        </div>
                                                    @endif
                                                    @endcan

                                                    @can('delete_ticket')
                                                    <div class="border rounded-lg px-1 py-1 bg-red-50 hover:bg-red-100 transition mb-1">
                                                        <form id="delete-form-{{ $ticket->ticket_id }}" 
                                                            action="{{ route('tickets.destroy', $ticket->ticket_id) }}" 
                                                            method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" 
                                                                class="flex items-center space-x-1 text-xs text-red-600 hover:text-red-800 delete-btn" 
                                                                data-id="{{ $ticket->ticket_id }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                                <span>Delete Ticket</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                    @endcan
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="13" class="text-center py-4 text-gray-500">
                                                <p>No Tickets found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 mb-0">
                            {{ $tickets->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!--Add Ticket Modal Form-->
            <div id="ticketModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-60 p-4 animate-fade-in">
                <div class="relative bg-white rounded-xl shadow-3xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-8 transform scale-95 opacity-0 transition-all duration-300 ease-out">
                    <button id="closeModal" class="absolute top-4 right-5 text-gray-400 hover:text-gray-700 text-3xl transition-colors duration-200 leading-none">&times;</button>
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-lg shadow mb-6">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-exclamation-triangle text-red-500 mt-1 text-lg"></i>
                                    <div>
                                        <h4 class="text-sm font-bold mb-1">Please fix the following error(s):</h4>
                                        <ul class="list-disc list-inside text-sm space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    <h2 class="text-2xl font-bold text-gray-900 mb-8 border-b pb-4">
                        Tickets Form
                    </h2>

                    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                        @csrf

                        <!-- Requestor Section -->
                        <fieldset class="border border-gray-300 rounded-md p-6">
                            <legend class="text-lg font-semibold text-gray-700 px-2">Client Information</legend>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mt-4">
                                <!-- First Name -->
                                <div>
                                    <label for="firstname" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="firstname" id="firstname" placeholder="e.g., Juan"
                                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-3 py-2" required>
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <label for="lastname" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="lastname" id="lastname" placeholder="e.g., Dela Cruz"
                                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-3 py-2" required>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                                    <input type="text" name="email" id="email" placeholder="j_delacruz@cda.gov.ph"
                                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-3 py-2" required>
                                </div>

                                <!-- Date Created -->
                                <div>
                                    <label for="date_created" class="block text-sm font-medium text-gray-700 mb-1">Date Created</label>
                                    <input type="text" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" readonly
                                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm bg-gray-50 text-sm px-3 py-2">
                                    <input type="hidden" name="date_created"
                                        value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d') }}">
                                </div>

                                <!-- Division -->
                                <div>
                                    <label for="division" class="block text-sm font-medium text-gray-700 mb-1">Division <span class="text-red-500">*</span></label>
                                    <select name="division" id="division" required
                                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm text-sm px-3 py-2">
                                        <option value="" disabled selected>Select Division</option>
                                        @foreach ($sections_divisions as $division)
                                            <option value="{{ $division }}">{{ $division }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Device -->
                                <div>
                                    <label for="device" class="block text-sm font-medium text-gray-700 mb-1">Device <span class="text-red-500">*</span></label>
                                    <select name="device" id="device"
                                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm text-sm px-3 py-2">
                                        <option value="" disabled selected>Select Device</option>
                                        <option value="Desktop PC">Desktop PC</option>
                                        <option value="Laptop/Netbook PC">Laptop/Netbook PC</option>
                                        <option value="Tablet PC">Tablet PC</option>
                                        <option value="All-in-1 Printer">All-in-1 Printer</option>
                                        <option value="Printer Only">Printer Only</option>
                                        <option value="Scanner Only">Scanner Only</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>

                                <!-- Technical Services -->
                                <div>
                                    <label for="service" class="block text-sm font-medium text-gray-700 mb-1">Technical Service <span class="text-red-500">*</span></label>
                                    <select name="service" id="service"
                                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm text-sm px-3 py-2">
                                        <option value="" disabled selected>Select Service</option>
                                        @foreach ($technical_services as $service)
                                            <option value="{{ $service }}">{{ $service }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Photo Upload -->
                                <div>
                                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Attach Photo (Optional)</label>
                                    <input type="file" name="photo" id="photo"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                </div>

                                <!-- Request Details -->
                                <div class="md:col-span-2">
                                    <label for="request" class="block text-sm font-medium text-gray-700 mb-1">Request Details <span class="text-red-500">*</span></label>
                                    <textarea name="request" id="request" rows="4"
                                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm text-sm px-3 py-2 placeholder-gray-400"
                                        placeholder="Please describe your issue or request in detail."></textarea>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Routed Section -->
                        <fieldset class="border border-gray-300 rounded-md p-6">
                            <legend class="text-lg font-semibold text-gray-700 px-2">Designated Personnel</legend>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mt-4">
                                <!-- Region -->
                                <div>
                                    <label for="it_area" class="block text-sm font-medium text-gray-700 mb-1">Region <span class="text-red-500">*</span></label>
                                    <select name="it_area" id="it_area"
                                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm text-sm px-3 py-2">
                                        <option selected disabled>Select Region</option>
                                        @foreach($it_area as $area)
                                            <option value="{{ $area }}">{{ $area }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- IT Personnel -->
                                <div>
                                    <label for="it_personnel" class="block text-sm font-medium text-gray-700 mb-1">Assigned Personnel</label>
                                    <select name="it_personnel" id="it_personnel"
                                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm text-sm px-3 py-2">
                                        <option selected disabled>Select Personnel</option>
                                    </select>
                                </div>

                                <!-- IT Email -->
                                <div>
                                    <label for="it_email" class="block text-sm font-medium text-gray-700 mb-1">IT Email</label>
                                    <input type="text" name="it_email" id="it_email" readonly
                                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm bg-gray-100 text-sm px-3 py-2">
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <input type="text" name="status" id="status" value="Pending" readonly
                                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm bg-gray-100 text-sm px-3 py-2">
                                </div>
                            </div>
                        </fieldset>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-6 border-t border-gray-200">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-paper-plane mr-2"></i> Submit Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Assign Ticket Modal -->
            <div id="assignTicketModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-60 p-4 animate-fade-in">
                <div class="relative bg-white rounded-xl shadow-3xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-8 transform scale-95 opacity-0 transition-all duration-300 ease-out">
                    <button id="closeAssignModal" class="absolute top-4 right-5 text-gray-400 hover:text-gray-700 text-3xl transition-colors duration-200 leading-none">&times;</button>
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-lg shadow mb-6">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-exclamation-triangle text-red-500 mt-1 text-lg"></i>
                                    <div>
                                        <h4 class="text-sm font-bold mb-1">Please fix the following error(s):</h4>
                                        <ul class="list-disc list-inside text-sm space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    <h2 class="text-2xl font-bold text-gray-900 mb-8 border-b pb-4">Re-Assign Ticket</h2>
                    
                    <form id="assignForm" method="POST" action="{{ route('tickets.assign') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf
                        <input type="hidden" name="ticket_id" id="assignTicketId">

                        <!-- Region / IT Area -->
                        <div>
                            <label for="it_area" class="block text-sm font-medium text-gray-700 mb-1">Region <span class="text-red-500">*</span></label>
                            <select name="it_area" id="it_area"
                                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-3 py-2">
                                <option selected disabled>Select Region</option>
                                @foreach($it_area as $area)
                                    <option value="{{ $area }}">{{ $area }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- IT Personnel -->
                        <div>
                            <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                            <select name="assigned_to" id="assigned_to"
                                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-3 py-2">
                                <option selected disabled>Select Personnel</option>
                            </select>
                        </div>
                        <!-- IT Email -->
                        <div>
                            <label for="assigned_it_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="text" name="assigned_it_email" id="assigned_it_email" readonly
                                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm bg-gray-100 text-gray-700 focus:ring-indigo-500 focus:border-indigo-500 text-sm px-3 py-2">
                        </div>

                        <div>
                            <label for="assigned_at" class="block text-sm font-medium text-gray-700 mb-1">Date Assigned <span class="text-red-500">*</span></label>
                            
                            <!-- Display only -->
                            <input type="text"
                                value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}"
                                readonly
                                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm bg-gray-50 text-gray-700 cursor-not-allowed text-sm px-3 py-2">
                            
                            <!-- Hidden actual date input -->
                            <input type="hidden" name="assigned_at"
                                value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d') }}">
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2">
                            <label for="assign_notes" class="block text-sm font-medium text-gray-700 mb-1">Instructions / Notes</label>
                            <textarea name="notes" id="assign_notes" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm"></textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="md:col-span-2 flex justify-end space-x-4 mt-4">
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-700 transition">
                                <i class="fas fa-user-plus mr-2"></i> Assign
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!--Edit Ticket Modal Form-->
            <div id="editticketModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-60 p-4 animate-fade-in">
                <div class="relative bg-white rounded-xl shadow-3xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-8 transform scale-95 opacity-0 transition-all duration-300 ease-out">
                    <button id="closeModal" class="absolute top-4 right-5 text-gray-400 hover:text-gray-700 text-3xl transition-colors duration-200 leading-none">&times;</button>
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-lg shadow mb-6">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-exclamation-triangle text-red-500 mt-1 text-lg"></i>
                                    <div>
                                        <h4 class="text-sm font-bold mb-1">Please fix the following error(s):</h4>
                                        <ul class="list-disc list-inside text-sm space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    <h2 class="text-2xl font-bold text-gray-900 mb-8 border-b pb-4">
                        Update Ticket
                    </h2>
                    <form method="POST" action="{{ isset($ticket) ? route('tickets.update', $ticket->ticket_id) : '#' }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf
                        @method('PUT')

                        @if(isset($ticket))
                            <input type="hidden" name="ticket_id" value="{{ $ticket->ticket_id }}">

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                <select name="status" id="status" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="" disabled {{ old('status', $ticket->status) ? '' : 'selected' }}>Select status</option>
                                    <option value="Pending" {{ old('status', $ticket->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Pending/Re-Assigned" {{ old('status', $ticket->status) == 'Pending/Re-Assigned' ? 'selected' : '' }}>Pending/Re-Assigned</option>
                                    <option value="Resolved" {{ old('status', $ticket->status) == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                </select>
                            </div>

                            <!-- Date Resolved -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date Resolved</label>
                                <input type="text" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" readonly
                                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed text-sm px-3 py-2">
                                <input type="hidden" name="date_resolved" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d') }}">
                            </div>

                            <!-- Action Taken -->
                            <div>
                                <label for="action_taken" class="block text-sm font-medium text-gray-700 mb-1">Action Taken <span class="text-red-500">*</span></label>
                                <textarea name="action_taken" id="action_taken" rows="3" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm">{{ old('action_taken', $ticket->action_taken) }}</textarea>
                            </div>

                            <!-- Photo -->
                            <div>
                                <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                                <input type="file" name="photo" id="photo" accept="image/*"
                                    class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded-md file:bg-gray-100 hover:file:bg-gray-200">
                                @if($ticket->photo)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $ticket->photo) }}" alt="Uploaded Photo" class="h-20 w-20 object-cover rounded border">
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Buttons -->
                        <div class="md:col-span-2 flex justify-end space-x-4">
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition"
                                    {{ isset($ticket) ? '' : 'disabled' }}>
                                <i class="fas fa-save mr-2"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const body = document.body;

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Notice!',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
            
            // === AUTO-RELOAD & COUNTDOWN ===
            const checkbox = document.getElementById('autoReloadCheckbox');
            const countdownDisplay = document.getElementById('countdown');
            let intervalId = null;
            let countdown = 60;

            // Load checkbox state
            const isChecked = localStorage.getItem('autoReload') === 'true';
            checkbox.checked = isChecked;

            // Start if checkbox already checked
            if (isChecked) startAutoReload();

            checkbox.addEventListener('change', function () {
                localStorage.setItem('autoReload', checkbox.checked);
                if (checkbox.checked) {
                    startAutoReload();
                } else {
                    stopAutoReload();
                }
            });

            function startAutoReload() {
                countdown = 60;
                updateCountdown();
                intervalId = setInterval(() => {
                    countdown--;
                    updateCountdown();
                    if (countdown <= 0) {
                        location.reload();
                    }
                }, 1000);
            }

            function stopAutoReload() {
                clearInterval(intervalId);
                countdown = 60;
                updateCountdown();
            }

            function updateCountdown() {
                countdownDisplay.textContent = countdown;
            }

            // ======= Shared Modal Functions =======
            function openModal(modal, modalContent) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                body.classList.add('overflow-hidden');
                void modalContent.offsetWidth;
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }

            function closeModal(modal, modalContent) {
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    body.classList.remove('overflow-hidden');
                }, 300);
            }

            const itMapping = @json($it_mapping);

            // ======= ASSIGN TICKET MODAL =======
            const assignModal = document.getElementById('assignTicketModal');
            const assignContent = assignModal.querySelector('.relative.bg-white');
            const closeAssignBtn = document.getElementById('closeAssignModal');
            const cancelAssignBtn = document.getElementById('assignCancelBtn');
            const regionSelectAssign = assignModal.querySelector('#it_area');
            const assigneeSelect = assignModal.querySelector('#assigned_to');
            const assigneeEmail = assignModal.querySelector('#assigned_it_email');
            const assignForm = document.getElementById('assignForm');

            let currentStatus = '';

            // Open modal
            document.querySelectorAll('.open-assign-modal').forEach(btn => {
                btn.addEventListener('click', function () {
                    const ticketId = this.dataset.id;
                    currentStatus = this.dataset.status;

                    if (currentStatus === 'Resolved') {
                        Swal.fire({
                            title: 'Ticket Locked',
                            text: 'Ticket was already resolved. Re-assignment is not allowed.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                        return;
                    } else if (currentStatus === 'Pending/Re-Assigned') {
                        Swal.fire({
                            title: 'Ticket Already Re-Assigned',
                            text: 'Please follow up with the re-assigned personnel.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    document.getElementById('assignTicketId').value = ticketId;
                    regionSelectAssign.selectedIndex = 0;
                    assigneeSelect.innerHTML = '<option disabled selected>Select Personnel</option>';
                    assigneeEmail.value = '';
                    openModal(assignModal, assignContent);
                });
            });

            // Close modal
            if (closeAssignBtn) closeAssignBtn.addEventListener('click', () => closeModal(assignModal, assignContent));
            if (cancelAssignBtn) cancelAssignBtn.addEventListener('click', () => closeModal(assignModal, assignContent));
            assignModal.addEventListener('click', e => {
                if (e.target === assignModal) closeAssignBtn.click();
            });

            // Prevent submit if ticket is resolved or same personnel
            assignForm.addEventListener('submit', function (e) {
                const selectedPersonnel = assigneeSelect.value.trim();
                const currentStatus = this.dataset.status?.trim() || '';

                // Prevent submission if ticket is already resolved
                if (currentStatus === 'Resolved') {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Ticket Locked',
                        text: 'You cannot assign a resolved ticket.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
            });

            // Populate assignee dropdown based on region
            if (regionSelectAssign) {
                regionSelectAssign.addEventListener('change', function () {
                    assigneeSelect.innerHTML = '<option disabled selected>Select Personnel</option>';
                    assigneeEmail.value = '';

                    (itMapping[this.value] || []).forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.name;
                        opt.text = p.name;
                        opt.setAttribute('data-email', p.email);
                        assigneeSelect.appendChild(opt);
                    });
                });
            }

            // Set assignee email on selection
            if (assigneeSelect) {
                assigneeSelect.addEventListener('change', function () {
                    const sel = this.options[this.selectedIndex];
                    assigneeEmail.value = sel.getAttribute('data-email') || '';
                });
            }

            // ======= ADD TICKET MODAL =======
            const addModal = document.getElementById('ticketModal');
            const addContent = addModal.querySelector('.relative.bg-white');
            const addCloseBtn = addModal.querySelector('#closeModal');
            const addOpenBtn = document.getElementById('openModal');

            if (addOpenBtn) addOpenBtn.addEventListener('click', () => openModal(addModal, addContent));
            if (addCloseBtn) addCloseBtn.addEventListener('click', () => closeModal(addModal, addContent));
            addModal.addEventListener('click', e => { if (e.target === addModal) closeModal(addModal, addContent); });

            const addDateInput = document.querySelector('#ticketModal #date_created');
            if (addDateInput) {
                const today = new Date();
                addDateInput.value = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
            }

            const regionSelectAdd = addModal.querySelector('#it_area');
            const personnelSelectAdd = addModal.querySelector('#it_personnel');
            const emailInputAdd = addModal.querySelector('#it_email');

            if (regionSelectAdd && personnelSelectAdd && emailInputAdd) {
                regionSelectAdd.addEventListener('change', function () {
                    personnelSelectAdd.innerHTML = '<option disabled selected>Select Personnel</option>';
                    emailInputAdd.value = '';
                    (itMapping[this.value] || []).forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.name;
                        opt.text = p.name;
                        personnelSelectAdd.appendChild(opt);
                    });
                });
                personnelSelectAdd.addEventListener('change', function () {
                    const p = itMapping[regionSelectAdd.value].find(x => x.name === this.value);
                    emailInputAdd.value = p ? p.email : '';
                });
            }

            // ======= EDIT TICKET MODAL =======
            const editModal = document.getElementById('editticketModal');
            const editContent = editModal.querySelector('.relative.bg-white');
            const editCloseBtn = editModal.querySelector('#closeModal');
            const editForm = editModal.querySelector('form');
            const statusSelect = editModal.querySelector('#status');
            const actionTakenField = editModal.querySelector('#action_taken');
            const photoPreview = editModal.querySelector('img');

            if (editCloseBtn) {
                editCloseBtn.addEventListener('click', () => closeModal(editModal, editContent));
            }
            editModal.addEventListener('click', e => {
                if (e.target === editModal) closeModal(editModal, editContent);
            });

            document.querySelectorAll('.open-edit-modal').forEach(btn => {
                btn.addEventListener('click', function () {
                    const ticketId = this.dataset.id;
                    const status = this.dataset.status;
                    const actionTaken = this.dataset.action_taken || '';
                    const photo = this.dataset.photo;

                    if (status === 'Resolved') {
                        Swal.fire({
                            title: 'Ticket Locked',
                            text: 'This ticket is already resolved.',
                            icon: 'info',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    editForm.action = `/tickets/${ticketId}`;
                    const ticketIdField = editForm.querySelector('input[name="ticket_id"]');
                    if (ticketIdField) ticketIdField.value = ticketId;
                    statusSelect.value = status;
                    actionTakenField.value = actionTaken;

                    if (photo && photoPreview) {
                        photoPreview.src = `/storage/${photo}`;
                        photoPreview.style.display = 'block';
                    } else if (photoPreview) {
                        photoPreview.style.display = 'none';
                    }

                    editForm.dataset.originalStatus = status;
                    openModal(editModal, editContent);
                });
            });

            if (statusSelect) {
                statusSelect.addEventListener('change', function () {
                    if (this.value === 'Pending/Re-Assigned') {
                        Swal.fire({
                            title: 'Reminder',
                            text: 'You must re-assign the ticket to another personnel.',
                            icon: 'info',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }

            if (editForm) {
                editForm.addEventListener('submit', function (e) {
                    const newStatus = statusSelect.value;
                    const originalStatus = this.dataset.originalStatus;

                    if (originalStatus === 'Pending' && newStatus === 'Pending') {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Status Not Updated',
                            text: 'Please update your status before submitting.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });

                    } else if (originalStatus === 'Pending' && newStatus === 'Pending/Re-Assigned') {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Assignment Needed',
                            text: 'Assign the ticket to another Personnel before submitting.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });

                    } else if (originalStatus === 'Pending/Re-Assigned' && (newStatus === 'Pending' || newStatus === 'Pending/Re-Assigned')) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Ticket Already Re-Assigned',
                            text: 'Please follow up to the Re-Assigned Personnel.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });

                    } else if (newStatus === 'Resolved') {
                        
                    }
                });
            }

            @if ($errors->any())
                const editInvalid = document.querySelector('#editticketModal form')?.checkValidity() === false;
                if (editInvalid) {
                    openModal(editModal, editContent);
                }
            @endif

            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    Swal.fire({
                        title: 'Delete this Ticket?',
                        text: "This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Delete'
                    }).then(res => {
                        if (res.isConfirmed) document.getElementById('delete-form-' + id).submit();
                    });
                });
            });
        });
</script>
</x-app-layout>
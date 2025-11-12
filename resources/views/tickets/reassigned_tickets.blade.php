<x-app-layout>
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    @endif
    <div id="main-content" class="min-h-screen transition-all duration-300 ease-in-out">
        <div id="ticketsContent">
            <div class="w-full">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                    <div class="p-2 text-gray-900">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Re-Assigned Tickets</h3>
                        <div class="overflow-x-auto bg-white shadow-lg rounded-lg border border-gray-200">
                            <table class="min-w-full table-fixed divide-y divide-gray-200 text-sm text-left text-gray-800">
                                <thead class="bg-gray-100 border-b border-gray-300 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-2 min-w-[4rem] font-semibold text-gray-700 uppercase tracking-wider">Tracking ID</th>
                                        <th class="px-6 py-2 min-w-[6rem] font-semibold text-gray-700 uppercase tracking-wider">Requested By</th>
                                        <th class="px-6 py-2 min-w-[8rem] font-semibold text-gray-700 uppercase tracking-wider">Request Details</th>
                                        <th class="px-6 py-2 min-w-[8rem] font-semibold text-gray-700 uppercase tracking-wider">Re-Assigned By</th>
                                        <th class="px-6 py-2 min-w-[8rem] font-semibold text-gray-700 uppercase tracking-wider">Previous Assigned</th>
                                        <th class="px-6 py-2 min-w-[8rem] font-semibold text-gray-700 uppercase tracking-wider">Re-Assigned To</th>
                                        <th class="px-6 py-2 min-w-[5rem] font-semibold text-gray-700 uppercase tracking-wider">Notes</th>
                                        <th class="px-6 py-2 min-w-[5rem] font-semibold text-gray-700 uppercase tracking-wider">Date Assigned</th>
                                        <th class="px-6 py-2 text-center min-w-[7rem] text-center font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @forelse ($tickets as $ticket)
                                        <tr class="hover:bg-gray-50 focus-within:bg-gray-100 transition">
                                            <td class="px-6 py-4 font-medium text-gray-900">{{ $ticket->ticket_number }}</td>
                                            <td class="px-6 py-4">{{ $ticket->requested_by }}</td>
                                            <td class="px-6 py-4 truncate max-w-xs" title="{{ $ticket->request }}">{{ $ticket->request }}</td>
                                            <td class="px-6 py-4">{{ $ticket->assigned_by }}</td>
                                            <td class="px-6 py-4">{{ $ticket->previous_assigned }}</td>
                                            <td class="px-6 py-4">{{ $ticket->assigned_to }}</td>
                                            <td class="px-6 py-4 truncate max-w-xs">{{ $ticket->notes }}</td>
                                            <td class="px-6 py-4 text-gray-600 text-xs">
                                                {{ \Carbon\Carbon::parse($ticket->assigned_at)->timezone('Asia/Manila')->format('M d, Y h:i A') }}
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
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4 text-gray-500">
                                                <p>No Re-Assigned Tickets found.</p>
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
        </div>
    </div>
</x-app-layout>
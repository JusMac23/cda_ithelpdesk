<x-app-layout>
    <div id="main-content" class="min-h-screen transition-all duration-300 ease-in-out">
        <div id="techContent">
            <div class="w-full">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 sm:p-8">
                    <div class="p-2 text-gray-900">

                        <!-- Title -->
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4" style="font-weight: 900;">
                            Data Breach Notifications Reports
                        </h3> 

                        <!-- Action Buttons and Auto-Reload -->
                        <div class="flex flex-col sm:flex-row mb-4 gap-4 sm:gap-0">
                            <!-- Left buttons -->
                            <div class="flex flex-wrap gap-3">
                                @can('create_databreach')
                                    <a href="{{ route('databreach.create') }}"
                                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150 ease-in-out">
                                        <i class="fas fa-plus mr-2 text-base"></i> Add Incident Report
                                    </a>
                                @endcan
                            </div>

                            <!-- Right section: Auto-Reload -->
                            <div class="flex justify-end">
                                <label class="inline-flex items-center text-sm text-gray-700">
                                    <input type="checkbox" id="autoReloadCheckbox" class="form-checkbox text-indigo-600 mr-2">
                                    <span>(<span id="countdown">60</span>s) Auto-Reload</span>
                                </label>
                            </div>
                        </div>
                     
                        <!-- Filters -->
                        @can('filter_databreach')
                        <form method="GET" action="{{ route('databreach.index') }}" class="flex flex-col md:flex-row md:items-end gap-4 flex-wrap">

                            <!-- Filter by Region -->
                                <div class="flex flex-col">
                                <label for="picFilter" class="block text-sm font-medium text-gray-700">Filter by Region</label>
                                <select name="pic" id="picFilter"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-4 py-2">
                                    <option value="">All Regions</option>
                                    @foreach($pic as $region)
                                        <option value="{{ $region }}" {{ request('pic') == $region ? 'selected' : '' }}>
                                            {{ $region }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter by Status -->
                                <div class="flex flex-col">
                                <label for="statusFilter" class="block text-sm font-medium text-gray-700">Filter by Status</label>
                                <select name="status" id="statusFilter"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-4 py-2">
                                    <option value="">All Status</option>
                                    <option value="For Assessment" {{ request('status') == 'For Assessment' ? 'selected' : '' }}>For Assessment</option>
                                    <option value="For Evaluation" {{ request('status') == 'For Evaluation' ? 'selected' : '' }}>For Evaluation</option>
                                    <option value="For Reporting to NPC" {{ request('status') == 'For Reporting to NPC' ? 'selected' : '' }}>For Reporting to NPC</option>
                                    <option value="Reported" {{ request('status') == 'Reported' ? 'selected' : '' }}>Reported</option>
                                </select>
                            </div>

                            <!-- Apply Filter Button -->
                            <div class="flex items-end gap-3 mt-6 md:mt-6">
                                <button type="submit"
                                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150 ease-in-out">
                                    <i class="fas fa-filter mr-2"></i> Apply Filter
                                </button>
                            </div>
                        </form>
                        @endcan

                        <!-- Scrollable Table -->
                        <div class="overflow-x-auto bg-white shadow-lg rounded-lg border border-gray-200 mt-6">
                            <div class="min-w-screen">
                                <table class="min-w-full table-auto divide-y divide-gray-200 text-left text-gray-800 text-md">
                                    <thead class="bg-gray-100 text-gray-700">
                                        <tr>
                                            <th class="px-6 py-2 font-semibold uppercase">DBN Number</th>
                                            <th class="px-6 py-2 font-semibold uppercase">Sender</th>
                                            <th class="px-6 py-2 font-semibold uppercase">PIC</th>
                                            <th class="px-4 py-2 font-semibold uppercase">Date of Occurrence</th>
                                            <th class="px-4 py-2 font-semibold uppercase">Date of Notification</th>
                                            <th class="px-6 py-2 font-semibold uppercase">General Cause</th>
                                            <th class="px-6 py-2 font-semibold uppercase">Status</th>
                                            <th class="px-6 py-2 font-semibold uppercase text-center">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($notifications as $notification)
                                            <tr class="hover:bg-gray-50 border-b">
                                                <td class="px-6 py-3">{{ $notification->dbn_number ?? 'N/A' }}</td>
                                                <td class="px-6 py-3">{{ $notification->sender_fullname ?? 'N/A' }}</td>
                                                <td class="px-6 py-3">{{ $notification->pic ?? 'N/A' }}</td>

                                                <td class="px-4 py-3">
                                                    {{ $notification->date_occurrence ? \Carbon\Carbon::parse($notification->date_occurrence)->format('M d, Y h:i A') : 'N/A' }}
                                                </td>

                                                <td class="px-4 py-3">
                                                    {{ $notification->date_notification ? \Carbon\Carbon::parse($notification->date_notification)->format('M d, Y h:i A') : 'N/A' }}
                                                </td>

                                                <td class="px-6 py-3">{{ $notification->general_cause ?? 'N/A' }}</td>

                                                <td class="px-6 py-3 whitespace-nowrap">
                                                    <span class="px-6 py-2 rounded-full text-xs font-semibold
                                                        {{
                                                            $notification->status === 'For Evaluation' ? 'bg-green-100 text-blue-700' :
                                                            ($notification->status === 'For Reporting to NPC' ? 'bg-red-50 text-red-700' :
                                                            ($notification->status === 'Reported' ? 'bg-blue-100 text-blue-700' :
                                                            'bg-yellow-100 text-yellow-500'))
                                                        }}">

                                                        {{ $notification->status }}
                                                    </span>
                                                </td>

                                                <td class="px-6 py-3 whitespace-nowrap">
                                                    <div class="flex flex-col gap-2">

                                                        @can('view_databreach')
                                                            <a href="{{ route('databreach.show', $notification->dbn_id) }}"
                                                                class="flex items-center px-2 py-1 text-blue-600 hover:bg-blue-50 hover:text-blue-800 text-sm whitespace-nowrap">
                                                                <i class="fas fa-eye mr-2"></i> View
                                                            </a>
                                                        @endcan

                                                        @can('assess_databreach')
                                                            @if (!in_array($notification->status, ['Reported', 'For Evaluation']))
                                                                <a href="{{ route('databreach.assess', $notification->dbn_id) }}"
                                                                    class="flex items-center px-2 py-1 text-yellow-600 hover:bg-yellow-50 hover:text-yellow-800 text-sm whitespace-nowrap">
                                                                    <i class="fas fa-search-plus mr-2"></i> Assess
                                                                </a>
                                                            @endif
                                                        @endcan

                                                        @can('evaluate_databreach')
                                                            @if (!in_array($notification->status, ['Reported', 'For Assessment' , 'For Reporting to NPC']))
                                                                <a href="{{ route('databreach.evaluate', $notification->dbn_id) }}"
                                                                    class="flex items-center px-2 py-1 text-green-600 hover:bg-green-50 hover:text-green-800 text-sm whitespace-nowrap">
                                                                    <i class="fas fa-check mr-2"></i> Evaluate
                                                                </a>
                                                            @endif
                                                        @endcan

                                                        @can('report_databreach')
                                                            @if ($notification->status ==='For Reporting to NPC')
                                                        <div x-data="{ open: false }" class="whitespace-nowrap">
                                                            <button 
                                                                @click="open = true" 
                                                                class="flex items-center px-2 py-1 text-red-600 hover:bg-red-50 hover:text-red-800 text-sm whitespace-nowrap">
                                                                <i class="fas fa-paper-plane mr-2"></i> Report
                                                            </button>
                                                            @endif
                                                            <div 
                                                                x-show="open"
                                                                x-transition
                                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60"
                                                                style="display: none;">

                                                                <div class="bg-white rounded-lg shadow-lg w-96 p-6">
                                                                    <h2 class="text-lg font-semibold mb-4">Confirm Report</h2>
                                                                    <p class="mb-6">Are you sure you want to report this incident to the NPC?</p>

                                                                    <div class="flex justify-end space-x-2">
                                                                        <button 
                                                                            @click="open = false" 
                                                                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                                                                            Cancel
                                                                        </button>

                                                                        <form method="POST" action="{{ route('databreach.report_to_npc', $notification->dbn_id) }}">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                                                                Confirm
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        @endcan

                                                        @can('delete_databreach')
                                                            @if ($notification->status !== 'Reported')
                                                                <form action="{{ route('databreach.destroy', $notification->dbn_id) }}" method="POST" class="inline-block whitespace-nowrap">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="flex items-center px-2 py-1 text-red-600 hover:bg-red-50 hover:text-red-800 text-sm whitespace-nowrap">
                                                                        <i class="fas fa-trash-alt mr-2"></i> Delete
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endcan

                                                    </div>
                                                </td>
                                            </tr>

                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-gray-500">
                                                    No Incident Reports found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4 mb-0">
                            {{ $notifications->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
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

            // Delete confirmation alert
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = this.closest('.delete-form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action will permanently delete the records.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Delete',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>

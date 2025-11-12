<x-app-layout>
    <div id="main-content" class="min-h-screen transition-all duration-300 ease-in-out">
        <div id="techContent">
            <div class="w-full">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                    <div class="p-2 text-gray-900">
                        <h3 class="text-xl font-bold text-gray-900 mb-4" style="font-weight: 900;">
                            Data Breach Notifications Reports
                        </h3> 
                        <div class="flex items-center justify-between mb-4">
            
                            <div class="flex items-center space-x-3">
                                @can('create_databreach')
                                <a href="{{ route('databreach.create') }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150 ease-in-out">
                                    <i class="fas fa-plus mr-2 text-base"></i> Add Report
                                </a>
                                @endcan

                                @can('view_overview_databreach')
                                <a href="{{ route('databreach.overview') }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150 ease-in-out">
                                    <i class="fas fa-chart-bar text-base mr-2"></i> Overview
                                </a>
                                @endcan
                            </div>

                            <!-- Right section: Auto-Reload -->
                            <div class="flex items-center">
                                <label class="inline-flex items-center text-sm text-gray-700">
                                    <input type="checkbox" id="autoReloadCheckbox" class="form-checkbox text-indigo-600 mr-2">
                                    <span>(<span id="countdown">60</span>s) Seconds Auto-Reload</span>
                                </label>
                            </div>
                        </div>
                     
                        @can('filter_databreach')
                        <form method="GET" action="{{ route('databreach.index') }}">
                            <div class="flex items-end space-x-4 mb-4">
                                <!-- Filter by Region -->
                                <div>
                                    <label for="picFilter" class="block text-sm font-medium text-gray-700">Filter by Region</label>
                                    <select name="pic" id="picFilter"
                                        class="mt-1 block w-48 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-6 py-2">
                                        <option value="">All Regions</option>
                                        @foreach($pic as $region)
                                            <option value="{{ $region }}" {{ request('pic') == $region ? 'selected' : '' }}>
                                                {{ $region }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filter by Status -->
                                <div>
                                    <label for="statusFilter" class="block text-sm font-medium text-gray-700">Filter by Status</label>
                                    <select name="status" id="statusFilter"
                                        class="mt-1 block w-48 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-6 py-2">
                                        <option value="">All Status</option>
                                        <option value="For Assessment" {{ request('status') == 'For Assessment' ? 'selected' : '' }}>For Assessment</option>
                                        <option value="For Evaluation" {{ request('status') == 'For Evaluation' ? 'selected' : '' }}>For Evaluation</option>
                                        <option value="Reported" {{ request('status') == 'Reported' ? 'selected' : '' }}>Reported</option>
                                    </select>
                                </div>

                                <!-- Apply Filter Button -->
                                <div class="pt-6">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150 ease-in-out">
                                        <i class="fas fa-filter mr-2"></i> Apply Filter
                                    </button>
                                </div>
                                <!-- Apply Filter Button -->
                                <div class="pt-6">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150 ease-in-out">
                                        <i class="fas fa-download mr-2"></i> Generate CSV
                                    </button>
                                </div>
                            </div>
                        </form>
                        @endcan

                        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                            <table class="min-w-full border-collapse">
                                <thead class="bg-gray-100 text-gray-700">
                                    <tr>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">DBN No.</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">Sender</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">PIC</th>
                                        <th class="px-4 py-2 font-semibold uppercase text-left">Date of Occurence</th>
                                        <th class="px-4 py-2 font-semibold uppercase text-left">Date of Notification</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">General Cause</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">Status</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($notifications as $notification)
                                        <tr class="hover:bg-gray-50 border-b">
                                            <td class="px-6 py-4">{{ $notification->dbn_number ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $notification->sender_fullname ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $notification->pic ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">
                                                {{ $notification->date_occurrence ? \Carbon\Carbon::parse($notification->date_occurrence)->format('M d, Y h:i A') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $notification->date_notification ? \Carbon\Carbon::parse($notification->date_notification)->format('M d, Y h:i A') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4">{{ $notification->general_cause ?? 'N/A' }}</td>
                                            <td class="px-8 py-2">
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                    {{ 
                                                        $notification->status === 'For Evaluation' ? 'bg-green-100 text-blue-700' : 
                                                        ($notification->status === 'Reported' ? 'bg-blue-100 text-blue-700' : 
                                                        'bg-yellow-100 text-yellow-700') 
                                                    }}">
                                                    {{ $notification->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex justify-center items-center gap-3 h-full">

                                                    @can('view_databreach')
                                                        <a href="{{ route('databreach.show', $notification->dbn_id) }}" class="text-blue-600 hover:text-blue-800" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endcan

                                                    @can('edit_databreach')
                                                        @if ($notification->status === 'Reported')
                                                            <i class="fas fa-edit text-gray-400 cursor-not-allowed" title="Edit Disabled"></i>
                                                        @else
                                                            <a href="{{ route('databreach.edit', $notification->dbn_id) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endif
                                                    @endcan

                                                    @can('delete_databreach')
                                                        @if ($notification->status === 'Reported')
                                                            <i class="fas fa-trash-alt text-gray-400 cursor-not-allowed" title="Delete Disabled"></i>
                                                        @else
                                                            <form action="{{ route('databreach.destroy', $notification->dbn_id) }}" method="POST" class="inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button
                                                                    type="button"
                                                                    class="text-red-600 hover:text-red-800 delete-btn"
                                                                    title="Delete"
                                                                >
                                                                    <i class="fas fa-trash-alt"></i>
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
                                                No Notification Reports found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

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
                    timer: 2000,
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

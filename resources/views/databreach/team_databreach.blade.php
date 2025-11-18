<x-app-layout>
    <div id="main-content" class="min-h-screen transition-all duration-300 ease-in-out">
        <div id="techContent">
            <div class="w-full">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                    <div class="p-2 text-gray-900">
                        <h3 class="text-xl font-bold text-gray-900 mb-4" style="font-weight: 900;">
                            Data Breach Team
                        </h3> 
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                @can('create_dbrt')
                                <button id="openAddModal"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150 ease-in-out">
                                    <i class="fas fa-plus mr-2 text-base"></i> Add DBRT Member
                                </button>
                                @endcan
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                            <table class="min-w-full border-collapse">
                                <thead class="bg-gray-100 text-gray-700">
                                    <tr>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">Fullname</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">Email</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">Region</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dbrtTeam as $team)
                                        <tr class="hover:bg-gray-50 border-b">
                                            <td class="px-6 py-4">
                                                {{ $team->firstname }} {{ $team->middle_initial ?? '' }} {{ $team->lastname ?? '' }}
                                            </td>
                                            <td class="px-6 py-4">{{ $team->email ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $team->region ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 text-left">
                                                <div class="flex justify-left items-left gap-3 h-full">
                                                    @can('edit_dbrt')
                                                    <button class="flex items-center border border-blue-400 space-x-1 text-sm text-blue-600 hover:text-blue-800 px-2 py-1 rounded-lg edit-btn"
                                                            data-id="{{ $team->dbrt_id }}"
                                                            data-firstname="{{ $team->firstname }}"
                                                            data-middle_initial="{{ $team->middle_initial }}"
                                                            data-lastname="{{ $team->lastname }}"
                                                            data-email="{{ $team->email }}"
                                                            data-region="{{ $team->region }}">
                                                        <i class="fas fa-edit"></i> 
                                                        <span class="ml-1">Edit</span>
                                                    </button>
                                                    @endcan

                                                    @can('delete_dbrt')
                                                    <form action="{{ route('databreach.team_databreach.destroy', $team->dbrt_id) }}" method="POST" class="inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="flex items-center border border-red-400 space-x-1 text-sm text-red-600 hover:text-red-800 px-2 py-1 rounded-lg delete-btn" title="Delete">
                                                            <i class="fas fa-trash-alt"></i><span class="ml-1">Delete</span>
                                                        </button>
                                                    </form> 
                                                    @endcan 
                                                </div> 
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-gray-500">No DBRT Member found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 mb-0">
                            {{ $dbrtTeam->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- ADD MEMBER MODAL -->
            <div id="addModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-60 p-4">
                <div class="relative bg-white rounded-xl shadow-3xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-8">
                    <button id="closeAddModal" class="absolute top-4 right-5 text-gray-400 hover:text-gray-700 text-3xl">&times;</button>
                    <h2 class="text-2xl font-bold text-gray-900 mb-8 border-b pb-4">Add Data Breach Response Team</h2>

                    <form action="{{ route('databreach.team_databreach.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" name="firstname" id="firstname" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="middle_initial" class="block text-sm font-medium text-gray-700">Middle Initial</label>
                                <input type="text" name="middle_initial" id="middle_initial" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" name="lastname" id="lastname" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="region" class="block text-sm font-medium text-gray-700">Region</label>
                                <select id="region" name="region" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                                    <option value="">-- Select Region --</option>
                                    <option>CDA HO</option><option>CDA CAR</option><option>CDA NIR</option>
                                    <option>CDA NCR</option><option>CDA Region I</option><option>CDA Region II</option>
                                    <option>CDA Region III</option><option>CDA Region IV-A</option><option>CDA Region IV-B</option>
                                    <option>CDA Region V</option><option>CDA Region VI</option><option>CDA Region VII</option>
                                    <option>CDA Region VIII</option><option>CDA Region IX</option><option>CDA Region X</option>
                                    <option>CDA Region XI</option><option>CDA Region XII</option><option>CDA Region XIII</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end pt-6 border-t mt-6">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                <i class="fas fa-paper-plane mr-2"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- EDIT MEMBER MODAL -->
            <div id="editModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-60 p-4">
                <div class="relative bg-white rounded-xl shadow-3xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-8">
                    <button id="closeEditModal" class="absolute top-4 right-5 text-gray-400 hover:text-gray-700 text-3xl">&times;</button>
                    <h2 class="text-2xl font-bold text-gray-900 mb-8 border-b pb-4">Edit Data Breach Response Team</h2>
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" name="firstname" id="edit_firstname" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="edit_middle_initial" class="block text-sm font-medium text-gray-700">Middle Initial</label>
                                <input type="text" name="middle_initial" id="edit_middle_initial" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="edit_lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" name="lastname" id="edit_lastname" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="edit_email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="edit_email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="edit_region" class="block text-sm font-medium text-gray-700">Region</label>
                                <select id="edit_region" name="region" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                                    <option value="">-- Select Region --</option>
                                    <option>CDA HO</option><option>CDA CAR</option><option>CDA NIR</option>
                                    <option>CDA NCR</option><option>CDA Region I</option><option>CDA Region II</option>
                                    <option>CDA Region III</option><option>CDA Region IV-A</option><option>CDA Region IV-B</option>
                                    <option>CDA Region V</option><option>CDA Region VI</option><option>CDA Region VII</option>
                                    <option>CDA Region VIII</option><option>CDA Region IX</option><option>CDA Region X</option>
                                    <option>CDA Region XI</option><option>CDA Region XII</option><option>CDA Region XIII</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end pt-6 border-t mt-6">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
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

            // SweetAlert success message
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: @json(session('success')),
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');
            const openAddModalBtn = document.getElementById('openAddModal');
            const closeAddModalBtn = document.getElementById('closeAddModal');
            const closeEditModalBtn = document.getElementById('closeEditModal');
            const editForm = document.getElementById('editForm');

            // Open Add Modal
            openAddModalBtn.addEventListener('click', () => addModal.classList.remove('hidden'));
            closeAddModalBtn.addEventListener('click', () => addModal.classList.add('hidden'));

            // Correct selector for Edit button
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const dbrtId = button.dataset.id;
                    document.getElementById('edit_firstname').value = button.dataset.firstname;
                    document.getElementById('edit_middle_initial').value = button.dataset.middle_initial;
                    document.getElementById('edit_lastname').value = button.dataset.lastname;
                    document.getElementById('edit_email').value = button.dataset.email;
                    document.getElementById('edit_region').value = button.dataset.region;

                    // Correctly set form action
                    editForm.action = "{{ url('/databreach/team_databreach') }}/" + dbrtId;

                    editModal.classList.remove('hidden');
                });
            });

            // Close Edit Modal
            closeEditModalBtn.addEventListener('click', () => editModal.classList.add('hidden'));

            // Delete confirmation
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action will permanently delete the record.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Delete',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
</x-app-layout>

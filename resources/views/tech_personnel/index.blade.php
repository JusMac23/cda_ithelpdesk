<x-app-layout>
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Notice!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif
    <div id="main-content" class="min-h-screen transition-all duration-300 ease-in-out">
        <div id="techContent">
            <div class="w-full">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                    <div class="p-2 text-gray-900">
                        <h3 class="text-xl font-bold text-gray-900 mb-4" style="font-weight: 900;">All Technical Personnel</h3>
                        @can('create_technical_personnel')
                        <div class="flex items-center justify-between mb-4">
                            <button id="openModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150 ease-in-out">
                                <i class="fas fa-plus text-base mr-2"></i> Add Personnel
                            </button>
                        </div>
                        @endcan

                        @can('search_technical_personnel')
                        <div class="flex justify-end mb-4">
                            <form action="{{ route('tech_personnel.index') }}" 
                                method="GET" 
                                class="flex items-center gap-2">
                                
                                <!-- Search Input -->
                                <input type="text" 
                                    name="search_query" 
                                    value="{{ request('search_query') }}" 
                                    placeholder="Search..." 
                                    class="px-2 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring focus:ring-indigo-200">
                                
                                <!-- Search Button -->
                                <button type="submit" 
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 focus:outline-none">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        @endcan

                        <div class="overflow-x-auto bg-white shadow-lg rounded-lg border border-gray-200">
                            <table class="min-w-full table-fixed divide-y divide-gray-200 text-left text-gray-800">
                                <thead class="bg-gray-100 text-gray-700">
                                    <tr class="hover:bg-gray-50 border-b">
                                        <th class="px-6 py-2 min-w-[6rem] font-semibold text-gray-700 uppercase">FullName/Division</th>
                                        <th class="px-6 py-2 min-w-[6rem] font-semibold text-gray-700 uppercase">Email</th>
                                        <th class="px-6 py-2 min-w-[4rem] font-semibold text-gray-700 uppercase">Region</th>
                                        <th class="px-6 py-2 min-w-[5rem] font-semibold text-gray-700 uppercase">Date Added</th>
                                        <th class="px-6 py-2 min-w-[5rem] font-semibold text-gray-700 uppercase">Date Updated</th>
                                        <th class="px-6 py-2 text-center min-w-[7rem] font-semibold text-gray-700 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @forelse ($technical_personnel as $tech_personnel)
                                        <tr class="hover:bg-gray-50 focus-within:bg-gray-100 transition">
                                            <td class="px-4 py-2">{{ $tech_personnel->firstname }} {{ $tech_personnel->middle_initial }} {{ $tech_personnel->lastname }}</td>
                                            <td class="px-4 py-2">{{ $tech_personnel->it_email }}</td>
                                            <td class="px-4 py-2">{{ $tech_personnel->it_area }}</td>
                                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($tech_personnel->date_added)->format('M d, Y h:i A') }}</td>
                                            <td class="px-4 py-2">
                                                @if($tech_personnel->date_updated)
                                                    {{ \Carbon\Carbon::parse($tech_personnel->date_updated)->format('M d, Y h:i A') }}
                                                @else
                                                    <span class="inline-block px-3 py-1 text-xs font-semibold italic text-red-500">No changes</span>
                                                @endif
                                            </td>
                                            <td class="px-9 py-3 text-base align-middle items-center">
                                                <div class="flex items-center justify-center space-x-3">
                                                    <!-- Edit Button -->
                                                    @can('edit_technical_personnel')
                                                    <div class="w-24 border rounded-lg px-2 py-1 bg-blue-50 hover:bg-blue-100 transition mb-1 flex justify-center">
                                                        <button class="editBtn flex items-center space-x-1 text-sm text-blue-600 hover:text-blue-800"
                                                            data-id="{{ $tech_personnel->id }}"
                                                            data-firstname="{{ $tech_personnel->firstname }}"
                                                            data-middle_initial="{{ $tech_personnel->middle_initial }}"
                                                            data-lastname="{{ $tech_personnel->lastname }}"
                                                            data-it_email="{{ $tech_personnel->it_email }}"
                                                            data-it_area="{{ $tech_personnel->it_area }}">
                                                            <i class="fas fa-edit"></i>
                                                            <span>Edit</span>
                                                        </button>
                                                    </div>
                                                    @endcan

                                                    <!-- Delete Button -->
                                                    @can('delete_technical_personnel')
                                                    <div class="w-24 border rounded-lg px-2 py-1 bg-red-50 hover:bg-red-100 transition mb-1 flex justify-center">
                                                        <form id="delete-form-{{ $tech_personnel->id }}" 
                                                            action="{{ route('tech_personnel.destroy', $tech_personnel->id) }}" 
                                                            method="POST" 
                                                            class="inline-flex items-center space-x-1">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" 
                                                                    class="flex items-center space-x-1 text-sm text-red-600 hover:text-red-800 delete-btn" 
                                                                    data-id="{{ $tech_personnel->id }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                                <span>Delete</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                    @endcan

                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-gray-500">
                                                <p>No Technical Personnel found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 mb-0">
                            {{ $technical_personnel->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for Adding Personnel -->
            <div id="personnelModal" 
                class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-60 p-4 transition-opacity duration-300 ease-out">

                <div id="personnelModalContent" 
                    class="relative bg-white rounded-xl shadow-3xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-8 transform scale-95 opacity-0 transition-all duration-300 ease-out">

                    <button id="closeModal" 
                            class="absolute top-4 right-5 text-gray-400 hover:text-gray-700 text-3xl transition-colors duration-200 leading-none"
                            aria-label="Close Modal">&times;
                    </button>

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
                        Create a Technical Personnel
                    </h2>

                    <form action="{{ route('tech_personnel.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
  
                            <div>
                                <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" name="firstname" id="firstname" required 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="middle_initial" class="block text-sm font-medium text-gray-700">Middle Initial</label>
                                <input type="text" name="middle_initial" id="middle_initial"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" name="lastname" id="lastname" required 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div class="sm:col-span-2">
                                <label for="it_email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="it_email" id="it_email" required 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="it_area" class="block text-sm font-medium text-gray-700 mb-1">IT Area <span class="text-red-500">*</span></label>
                                <select name="it_area" id="it_area" required
                                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm text-sm px-3 py-2">
                                    <option value="" disabled selected>Select Region</option>
                                    @foreach ($region as $area)
                                        <option value="{{ $area }}">{{ $area }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="date_added" class="block text-sm font-medium text-gray-700 mb-1">Date Added</label>
                                <input type="text" 
                                    value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" 
                                    readonly
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 text-sm px-3 py-2">
                                <input type="hidden" name="date_added"
                                    value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="flex justify-end pt-6 border-t border-gray-200">
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-paper-plane mr-2"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal for Edit Personnel -->
            <div id="editModal" 
                class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-60 p-4 transition-opacity duration-300 ease-out">

                <div id="editModalContent" 
                    class="relative bg-white rounded-xl shadow-3xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-8 transform scale-95 opacity-0 transition-all duration-300 ease-out">

                    <button id="closeEditModal" 
                            class="absolute top-4 right-5 text-gray-400 hover:text-gray-700 text-3xl transition-colors duration-200 leading-none"
                            aria-label="Close Modal">&times;
                    </button>

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
                        Edit Technical Personnel
                    </h2>

                    <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="firstname" id="edit_firstname"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="edit_middle_initial" class="block text-sm font-medium text-gray-700">Middle Initial</label>
                            <input type="text" name="middle_initial" id="edit_middle_initial"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="edit_lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="lastname" id="edit_lastname"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="edit_it_email">Email</label>
                            <input type="email" name="it_email" id="edit_it_email"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="edit_it_area" class="block text-sm font-medium text-gray-700">IT Area</label>
                            <select name="it_area" id="edit_it_area"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
                                <option value="" disabled>Select Region</option>
                                @foreach ($region as $area)
                                    <option value="{{ $area }}">{{ $area }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-8">
                            <label for="date_updated" class="block text-sm font-medium text-gray-700 mb-1">Date Updated</label>
                            <input type="text" 
                                value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" 
                                readonly
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
                            <input type="hidden" name="date_added"
                                value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="flex justify-end pt-6 border-t border-gray-200">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Update
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Add Personnel Modal
            const addModal = document.getElementById("personnelModal");
            const openAddBtn = document.getElementById("openModal");
            const closeAddBtn = document.getElementById("closeModal");

            if (openAddBtn && addModal) {
                openAddBtn.addEventListener("click", () => {
                    addModal.classList.remove("hidden");
                    setTimeout(() => {
                        addModal.querySelector("div > div").classList.remove("scale-95", "opacity-0");
                        addModal.querySelector("div > div").classList.add("scale-100", "opacity-100");
                    }, 10);
                });
            }

            if (closeAddBtn && addModal) {
                closeAddBtn.addEventListener("click", () => {
                    addModal.querySelector("div > div").classList.add("scale-95", "opacity-0");
                    addModal.querySelector("div > div").classList.remove("scale-100", "opacity-100");
                    setTimeout(() => {
                        addModal.classList.add("hidden");
                    }, 300);
                });
            }

            if (addModal) {
                addModal.addEventListener("click", (e) => {
                    if (e.target === addModal) closeAddBtn.click();
                });
            }

            // Edit Personnel Modal
            const editModal = document.getElementById("editModal");
            const editModalContent = document.getElementById("editModalContent");
            const closeEditBtn = document.getElementById("closeEditModal");
            const editButtons = document.querySelectorAll(".editBtn");

            const editForm = document.getElementById("editForm");
            const editFirstname = document.getElementById("edit_firstname");
            const editMiddleInitial = document.getElementById("edit_middle_initial");
            const editLastname = document.getElementById("edit_lastname");
            const editEmail = document.getElementById("edit_it_email");
            const editArea = document.getElementById("edit_it_area");

            editButtons.forEach(button => {
                button.addEventListener("click", (e) => {
                    e.preventDefault();

                    // Get dataset values
                    const id = button.dataset.id;
                    const firstname = button.dataset.firstname;
                    const middle_initial = button.dataset.middle_initial;
                    const lastname = button.dataset.lastname;
                    const it_email = button.dataset.it_email;
                    const it_area = button.dataset.it_area;

                    // Fill modal inputs
                    editFirstname.value = firstname;
                    editMiddleInitial.value = middle_initial;
                    editLastname.value = lastname;
                    editEmail.value = it_email;
                    editArea.value = it_area;

                    // Update form action dynamically
                    editForm.action = `/tech_personnel/${id}`;

                    // Show modal
                    editModal.classList.remove("hidden");
                    setTimeout(() => {
                        editModalContent.classList.remove("scale-95", "opacity-0");
                        editModalContent.classList.add("scale-100", "opacity-100");
                    }, 10);
                });
            });

            // Close Edit Modal
            if (closeEditBtn && editModal) {
                closeEditBtn.addEventListener("click", () => {
                    editModalContent.classList.add("scale-95", "opacity-0");
                    editModalContent.classList.remove("scale-100", "opacity-100");
                    setTimeout(() => {
                        editModal.classList.add("hidden");
                    }, 300);
                });
            }

            // Delete confirmation
            document.querySelectorAll('.delete-btn').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Delete this Personnel?',
                        text: "This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Delete'
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
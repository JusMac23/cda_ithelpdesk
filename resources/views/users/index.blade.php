<x-app-layout>

    <div id="main-content" class="min-h-screen transition-all duration-300 ease-in-out">
        <div id="techContent">
            <div class="w-full">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                    <div class="p-2 text-gray-900">
                        <h3 class="text-xl font-bold text-gray-900 mb-4" style="font-weight: 900;">All Users</h3>

                        @can('create_tech_users')
                        <div class="flex items-center justify-between mb-4">
                            <button id="openModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150 ease-in-out">
                                <i class="fas fa-plus text-base mr-2"></i> Add User
                            </button>
                        </div>
                        @endcan

                        <div class="flex justify-end mb-4">
                            <form action="{{ route('users.index') }}"
                                  method="GET"
                                  class="flex items-center gap-2">
                                <input type="text"
                                       name="search_query"
                                       value="{{ request('search_query') }}"
                                       placeholder="Search..."
                                       class="px-2 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring focus:ring-indigo-200">
                                <button type="submit"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 focus:outline-none">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>

                        <div class="overflow-x-auto bg-white shadow-lg rounded-lg border border-gray-200">
                            <table class="min-w-full table-fixed divide-y divide-gray-200 text-left text-gray-800">
                                <thead class="bg-gray-100 text-gray-700">
                                    <tr>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">FullName</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">Email</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">Role</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">Date Added</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-left">Date Updated</th>
                                        @can('delete_tech_users')<th class="px-6 py-2 font-semibold uppercase text-center">Actions</th>@endcan
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @forelse ($users as $user)
                                        <tr class="hover:bg-gray-50 border-b">
                                            <td class="px-4 py-2">{{ $user->name }}</td>
                                            <td class="px-4 py-2">{{ $user->email }}</td>

                                            <td class="px-4 py-2">
                                                @if($user->roles->isNotEmpty())
                                                    @foreach($user->roles as $role)
                                                        <span class="inline-block px-2 py-1 text-sm font-semibold rounded-full bg-indigo-100 text-indigo-700">
                                                            {{ $role->name }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="inline-block px-3 py-1 text-xs font-semibold italic text-red-500">
                                                        No Role Assigned
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y h:i A') }}</td>
                                            <td class="px-4 py-2">
                                                @if($user->updated_at)
                                                    {{ \Carbon\Carbon::parse($user->updated_at)->format('M d, Y h:i A') }}
                                                @else
                                                    <span class="inline-block px-3 py-1 text-xs font-semibold italic text-red-500">No changes</span>
                                                @endif
                                            </td>

                                            <td class="px-9 py-3 text-base align-middle items-center">
                                                <div class="flex items-center justify-center space-x-3">

                                                    {{-- Edit Button --}}
                                                    @can('edit_tech_users')
                                                    <div class="w-24 border rounded-lg px-2 py-1 bg-blue-50 hover:bg-blue-100 transition mb-1 flex justify-center">
                                                        <button class="editBtn flex items-center space-x-1 text-sm text-blue-600 hover:text-blue-800"
                                                            data-id="{{ $user->id }}"
                                                            data-name="{{ $user->name }}"
                                                            data-email="{{ $user->email }}"
                                                            data-role-id="{{ optional($user->roles->first())->id }}">
                                                            <i class="fas fa-edit"></i>
                                                            <span>Edit</span>
                                                        </button>
                                                    </div>
                                                    @endcan

                                                    {{-- Delete Button --}}
                                                    @can('delete_tech_users')
                                                    <div class="w-24 border rounded-lg px-2 py-1 bg-red-50 hover:bg-red-100 transition mb-1 flex justify-center">
                                                        <form id="delete-form-{{ $user->id }}"
                                                              action="{{ route('users.destroy', $user->id) }}"
                                                              method="POST"
                                                              class="inline-flex items-center space-x-1">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                    class="flex items-center space-x-1 text-sm text-red-600 hover:text-red-800 delete-btn"
                                                                    data-id="{{ $user->id }}">
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
                                            <td colspan="6" class="text-center py-8 text-gray-500 text-lg">
                                                <p>No User found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 mb-0">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal: Add User --}}
            <div id="userModal"
                class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-60 p-4 transition-opacity duration-300 ease-out">

                <div id="userModalContent"
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

                    @error('role')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <h2 class="text-2xl font-bold text-gray-900 mb-8 border-b pb-4">
                        Add New User
                    </h2>

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="name" id="name" required
                                        placeholder="John A. Doe"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div class="sm:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" required
                                        placeholder="j_doe@cda.gov.ph"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <input type="password" name="password" id="password" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div class="sm:col-span-2">
                                <span class="block text-sm font-medium text-gray-700 mb-2">Select Role</span>
                                @foreach ($roles as $role)
                                    <div class="flex items-center space-x-2 mb-2">
                                        <input type="radio" name="role" id="add_role_{{ $loop->index }}" value="{{ $role->id }}"
                                            class="h-4 w-4 text-blue-600 border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:ring-opacity-50 mr-2"
                                            {{ old('role') == $role->id ? 'checked' : '' }}>
                                        <label for="add_role_{{ $loop->index }}" class="text-sm text-gray-700">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                @endforeach
                                @error('role')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="created_at" class="block text-sm font-medium text-gray-700 mb-1">Date Added</label>
                                <input type="text"
                                    value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}"
                                    readonly
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 text-sm px-3 py-2">
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

            {{-- Modal: Edit User --}}
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
                        Edit User
                    </h2>

                    <form id="editForm" method="POST" action="#">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="name" id="edit_name" value="" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div class="sm:col-span-2">
                                <label for="edit_email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="edit_email" value="" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div class="sm:col-span-2">
                                <span class="block text-sm font-medium text-gray-700 mb-2">Select Role</span>
                                @foreach ($roles as $role)
                                    <div class="flex items-center space-x-2 mb-2">
                                        <input
                                            type="radio"
                                            name="role"
                                            id="edit_role_{{ $loop->index }}"
                                            value="{{ $role->id }}"
                                            class="h-4 w-4 text-blue-600 border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:ring-opacity-50 mr-2">
                                        <label for="edit_role_{{ $loop->index }}" class="text-sm text-gray-700">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                @endforeach
                                @error('role')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                                <input type="text"
                                    value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}"
                                    readonly
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm px-3 py-2">
                            </div>
                        </div>

                        <div class="flex justify-end pt-6 border-t border-gray-200">
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-paper-plane mr-2"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    timer: 3000,
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

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    showConfirmButton: true
                });
            @endif

            // Add Modal
            const addModal = document.getElementById("userModal");
            const openAddBtn = document.getElementById("openModal");
            const closeAddBtn = document.getElementById("closeModal");
            const addModalInner = document.getElementById("userModalContent");

            if (openAddBtn && addModal) {
                openAddBtn.addEventListener("click", () => {
                    addModal.classList.remove("hidden");
                    setTimeout(() => {
                        addModalInner.classList.remove("scale-95", "opacity-0");
                        addModalInner.classList.add("scale-100", "opacity-100");
                    }, 10);
                });
            }

            if (closeAddBtn && addModal) {
                closeAddBtn.addEventListener("click", () => {
                    addModalInner.classList.add("scale-95", "opacity-0");
                    addModalInner.classList.remove("scale-100", "opacity-100");
                    setTimeout(() => addModal.classList.add("hidden"), 300);
                });
            }

            if (addModal) {
                addModal.addEventListener("click", (e) => {
                    if (e.target === addModal) closeAddBtn.click();
                });
            }

            // Edit Modal
            const editModal = document.getElementById("editModal");
            const editModalContent = document.getElementById("editModalContent");
            const closeEditBtn = document.getElementById("closeEditModal");
            const editButtons = document.querySelectorAll(".editBtn");

            const editForm = document.getElementById("editForm");
            const editName = document.getElementById("edit_name");
            const editEmail = document.getElementById("edit_email");

            editButtons.forEach(button => {
                button.addEventListener("click", (e) => {
                    e.preventDefault();

                    const id = button.dataset.id;
                    const name = button.dataset.name;
                    const email = button.dataset.email;
                    const roleId = button.dataset.roleId; // may be undefined

                    // Fill modal inputs
                    editName.value = name;
                    editEmail.value = email;

                    // Update form action dynamically
                    editForm.action = `/users/${id}`;

                    // Preselect role radio (single-select)
                    document.querySelectorAll('input[name="role"][id^="edit_role_"]').forEach(r => {
                        r.checked = (String(r.value) === String(roleId));
                    });

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
                    setTimeout(() => editModal.classList.add("hidden"), 300);
                });
            }

            // Delete confirmation
            document.querySelectorAll('.delete-btn').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Delete this User?',
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

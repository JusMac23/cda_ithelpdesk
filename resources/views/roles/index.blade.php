<x-app-layout>
    
    <div id="main-content" class="min-h-screen transition-all duration-300 ease-in-out">
        <div id="techContent">
            <div class="w-full">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                    <div class="p-2 text-gray-900">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">All Roles</h3>

                        @can('create_roles')
                        <div class="flex items-center justify-between mb-4">
                            <button id="openModal" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                <i class="fas fa-plus mr-2"></i> Add Role
                            </button>
                        </div>
                        @endcan

                        <div class="overflow-x-auto bg-white shadow-lg rounded-lg border border-gray-200">
                            <table class="min-w-full table-fixed divide-y divide-gray-200 text-left text-gray-800">
                                <thead class="bg-gray-100 text-gray-700">
                                    <tr>
                                        <th class="px-6 py-2 font-semibold uppercase text-center">ROLE NAME</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-center">PERSMISSION</th>
                                        <th class="px-6 py-2 font-semibold uppercase text-center">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($roles as $role)
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="px-4 py-2 text-center">{{ $role->name }}</td>
                                        <td class="px-4 py-2">
                                            @if($role->permissions->isNotEmpty())
                                            <div class="space-y-2">
                                                @foreach($role->permissions->chunk(5) as $chunk)
                                                <div class="flex flex-wrap">
                                                    @foreach($chunk as $permission)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold mr-2 mb-2">
                                                        {{ $permission->name }}
                                                    </span>
                                                    @endforeach
                                                </div>
                                                @endforeach
                                            </div>
                                            @else
                                            <span class="text-gray-500 italic">No permissions assigned</span>
                                            @endif
                                        </td>
                                        <td class="px-9 py-3 text-center">
                                            <div class="flex items-center space-x-3">
                                                <!-- Edit Button -->
                                                @can('edit_roles')
                                                <div class="w-24 border rounded-lg px-2 py-1 bg-blue-50 hover:bg-blue-100 flex justify-center">
                                                    <button class="editBtn text-sm text-blue-600 hover:text-blue-800 flex items-center space-x-1"
                                                        data-id="{{ $role->id }}"
                                                        data-name="{{ $role->name }}"
                                                        data-permissions='@json($role->permissions->pluck("id"))'>
                                                        <i class="fas fa-edit"></i>
                                                        <span>Edit</span>
                                                    </button>
                                                </div>
                                                @endcan

                                                <!-- Delete Button -->
                                                @can('delete_roles')
                                                <div class="w-24 border rounded-lg px-2 py-1 bg-red-50 hover:bg-red-100 flex justify-center">
                                                    <form id="delete-form-{{ $role->id }}" 
                                                        action="{{ route('roles.destroy', $role->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" 
                                                                class="delete-btn text-sm text-red-600 hover:text-red-800 flex items-center space-x-1" 
                                                                data-id="{{ $role->id }}">
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
                                            <p>No Roles found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 mb-0">
                            {{ $roles->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Modal -->
            <div id="permissionModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-60 p-4 min-h-screen">
                <div id="permissionModalContent" class="relative bg-white rounded-xl shadow-3xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-8 transform scale-95 opacity-0 transition-all duration-300 ease-out">
                    <button id="closeModal" class="absolute top-4 right-5 text-gray-400 hover:text-gray-700 text-3xl">&times;</button>
                    <h2 class="text-2xl font-bold mb-8 border-b pb-4">Add Role</h2>

                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Role Name</label>
                                <input type="text" name="name" id="name" required class="mt-1 block w-full border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Permissions</label>
                                <div class="space-y-2 mt-1">
                                    @foreach ($permissions as $permission)
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" 
                                            name="permissions[]" 
                                            value="{{ $permission->id }}" 
                                            id="add_perm_{{ $permission->id }}" 
                                            class="h-4 w-4 text-blue-600 border-gray-300 rounded mr-2">
                                        <label for="add_perm_{{ $permission->id }}">{{ $permission->name }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6 border-t mt-6">
                            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                <i class="fas fa-paper-plane mr-2"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Modal -->
            <div id="editModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-60 p-4 min-h-screen">
                <div id="editModalContent" class="relative bg-white rounded-xl shadow-3xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-8 transform scale-95 opacity-0 transition-all duration-300 ease-out">
                    <button id="closeEditModal" class="absolute top-4 right-5 text-gray-400 hover:text-gray-700 text-3xl">&times;</button>
                    <h2 class="text-2xl font-bold mb-8 border-b pb-4">Edit Role</h2>

                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_name" class="block text-sm font-medium text-gray-700">Role Name</label>
                                <input type="text" name="name" id="edit_name" class="mt-1 block w-full border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Permissions</label>
                                <div class="space-y-2 mt-1">
                                    @foreach ($permissions as $permission)
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" 
                                            name="permissions[]" 
                                            value="{{ $permission->id }}" 
                                            id="edit_perm_{{ $permission->id }}" 
                                            class="edit-permission h-4 w-4 text-blue-600 border-gray-300 rounded mr-2">
                                        <label for="edit_perm_{{ $permission->id }}">{{ $permission->name }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6 border-t mt-6">
                            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
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

        // Add Role Modal
        const addModal = document.getElementById("permissionModal");
        const addContent = document.getElementById("permissionModalContent");
        const openAddBtn = document.getElementById("openModal");
        const closeAddBtn = document.getElementById("closeModal");

        openAddBtn.addEventListener("click", () => {
            addModal.classList.remove("hidden");
            setTimeout(() => addContent.classList.replace("opacity-0","opacity-100"), 10);
        });

        closeAddBtn.addEventListener("click", () => {
            addContent.classList.replace("opacity-100","opacity-0");
            setTimeout(() => addModal.classList.add("hidden"), 300);
        });

        // Edit Role Modal
        const editModal = document.getElementById("editModal");
        const editContent = document.getElementById("editModalContent");
        const closeEditBtn = document.getElementById("closeEditModal");
        const editButtons = document.querySelectorAll(".editBtn");
        const editForm = document.getElementById("editForm");
        const editName = document.getElementById("edit_name");

        editButtons.forEach(button => {
            button.addEventListener("click", e => {
                e.preventDefault();
                const id = button.dataset.id;
                const name = button.dataset.name;
                const permissions = JSON.parse(button.dataset.permissions || "[]");

                editName.value = name;
                editForm.action = `/roles/${id}`;

                // Reset all checkboxes first
                document.querySelectorAll(".edit-permission").forEach(cb => cb.checked = false);
                // Check the correct permissions
                permissions.forEach(pid => {
                    const cb = document.getElementById("edit_perm_" + pid);
                    if(cb) cb.checked = true;
                });

                editModal.classList.remove("hidden");
                setTimeout(() => editContent.classList.replace("opacity-0","opacity-100"), 10);
            });
        });

        closeEditBtn.addEventListener("click", () => {
            editContent.classList.replace("opacity-100","opacity-0");
            setTimeout(() => editModal.classList.add("hidden"), 300);
        });

        // Delete confirmation
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const form = this.closest('form');
                Swal.fire({
                    title: 'Delete this Role?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Delete'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    });
    </script>
</x-app-layout>

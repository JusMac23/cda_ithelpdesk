<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>

    <!-- Full-Screen Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="w-12 h-12 border-4 border-white/20 border-t-white rounded-full animate-spin mb-4"></div>
        <p class="text-base font-semibold tracking-wide text-white">Processing Role, please wait...</p>
    </div>

    <div id="main-content" class="w-full">
        <div class="panel bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-4 sm:p-6 lg:p-8">
            
            {{-- Header Title Banner --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 mb-6 border-b border-[var(--border-light)]">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 via-violet-600 to-purple-600 text-white flex items-center justify-center shadow-md shadow-indigo-500/20 shrink-0">
                        <span class="material-symbols-outlined text-2xl">admin_panel_settings</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                                Roles & Permissions
                            </h1>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/60">
                                {{ $roles->total() }} Roles Configured
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                            Configure user access control levels, manage permission sets, and define security groups
                        </p>
                    </div>
                </div>

                @can('create_roles')
                    <button id="openModal" 
                            class="inline-flex items-center justify-center gap-2 h-11 px-5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-500 active:scale-95 shadow-md shadow-emerald-600/20 transition-all cursor-pointer">
                        <span class="material-symbols-outlined text-xl">add_moderator</span>
                        <span>Add Role</span>
                    </button>
                @endcan
            </div>

            {{-- Table Container --}}
            <div class="overflow-x-auto rounded-2xl border border-[var(--border-light)] bg-[var(--card-bg)] shadow-xs">
                <table class="w-full min-w-[700px] text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/60 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">
                            <th class="py-3.5 px-4 sm:px-6 w-1/4">Role Name</th>
                            <th class="py-3.5 px-4 sm:px-6">Assigned Permissions</th>
                            @if(auth()->user()->can('edit_roles') || auth()->user()->can('delete_roles'))
                                <th class="py-3.5 px-4 sm:px-6 text-center">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border-subtle)]">
                        @forelse ($roles as $role)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                
                                {{-- Role Name --}}
                                <td class="py-4 px-4 sm:px-6 align-top">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-base">shield_person</span>
                                        </div>
                                        <div>
                                            <span class="font-bold text-[var(--text-dark)] text-sm sm:text-base block">
                                                {{ $role->name }}
                                            </span>
                                            <span class="text-xs text-[var(--text-muted)]">ID: #{{ $role->id }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Permissions List --}}
                                <td class="py-4 px-4 sm:px-6 align-top">
                                    @if($role->permissions->isNotEmpty())
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($role->permissions as $permission)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60">
                                                    {{ $permission->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-[var(--text-muted)] italic">No permissions assigned to this role</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                @if(auth()->user()->can('edit_roles') || auth()->user()->can('delete_roles'))
                                <td class="py-4 px-4 sm:px-6 align-top text-center">
                                    <div class="inline-flex items-center justify-center">
                                        
                                        {{-- Edit Button --}}
                                        @can('edit_roles')
                                            <button type="button" title="Edit"
                                                class="editBtn inline-flex items-center h-8 px-3 text-xs font-semibold text-blue-600 dark:text-blue-400 active:scale-95 transition-all cursor-pointer"
                                                data-id="{{ $role->id }}"
                                                data-name="{{ $role->name }}"
                                                data-permissions='@json($role->permissions->pluck("id"))'>
                                                <span class="material-symbols-outlined text-sm">edit</span>
                                            </button>
                                        @endcan

                                        {{-- Delete Button --}}
                                        @can('delete_roles')
                                            <form id="delete-form-{{ $role->id }}" action="{{ route('roles.destroy', $role->id) }}" method="POST" class="m-0 inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" title="Delete"
                                                        class="delete-btn inline-flex items-center h-8 px-3 text-xs font-semibold text-rose-600 dark:text-rose-400 active:scale-95 transition-all cursor-pointer" 
                                                        data-id="{{ $role->id }}">
                                                    <span class="material-symbols-outlined text-sm">delete</span>
                                                </button>
                                            </form>
                                        @endcan

                                    </div>
                                </td>
                                @endif

                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-12 px-4 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                            <span class="material-symbols-outlined text-2xl">shield</span>
                                        </div>
                                        <p class="text-sm font-semibold text-[var(--text-dark)] m-0">No Roles Found</p>
                                        <p class="text-xs text-[var(--text-muted)] m-0">Create a new role to establish permission sets for system users</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Wrapper --}}
            <div class="mt-6 pt-4 border-t border-[var(--border-light)]">
                {{ $roles->links() }}
            </div>
            
        </div>
    </div>

    {{-- Modal: Add Role --}}
    <div id="permissionModal" 
         class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden [&:not(.hidden)]:flex items-center justify-center p-4 transition-all duration-300">
        <div id="permissionModalContent" 
             class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-[var(--card-bg)] border border-[var(--border-light)] rounded-3xl shadow-2xl p-6 sm:p-8 transition-all">
            
            <button id="closeModal" 
                    class="absolute top-5 right-5 w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 flex items-center justify-center transition-all cursor-pointer" 
                    aria-label="Close Modal">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>

            <div class="flex items-center gap-3 pb-5 mb-6 border-b border-[var(--border-light)] pr-10">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-xl">add_moderator</span>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-[var(--text-dark)] m-0">
                        Create New Role
                    </h2>
                    <p class="text-xs text-[var(--text-muted)] m-0 font-medium">Define a role title and assign authorized capabilities</p>
                </div>
            </div>

            <form action="{{ route('roles.store') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                        Role Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required autocomplete="off" placeholder="e.g., Regional ICT Officer / System Auditor"
                           class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                </div>

                {{-- Permissions Checkbox Grid --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                        Assign Permissions <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 p-3 rounded-2xl border border-[var(--border-light)] bg-slate-50/50 dark:bg-slate-800/30 max-h-60 overflow-y-auto">
                        @foreach ($permissions as $permission)
                            <label class="flex items-center gap-2.5 p-2 rounded-xl bg-white dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/60 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors cursor-pointer text-xs font-medium text-[var(--text-dark)]" for="add_perm_{{ $permission->id }}">
                                <input type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $permission->id }}" 
                                       id="add_perm_{{ $permission->id }}" 
                                       class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-600 cursor-pointer">
                                <span>{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-[var(--border-light)]">
                    <button type="button" id="cancelAddModal" 
                            class="w-full sm:w-auto h-11 px-5 rounded-xl text-xs sm:text-sm font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="w-full sm:w-auto h-11 px-6 rounded-xl text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md shadow-indigo-600/20 flex items-center justify-center gap-2 transition-all cursor-pointer">
                        <span>Save Role</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Edit Role --}}
    <div id="editModal" 
         class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden [&:not(.hidden)]:flex items-center justify-center p-4 transition-all duration-300">
        <div id="editModalContent" 
             class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-[var(--card-bg)] border border-[var(--border-light)] rounded-3xl shadow-2xl p-6 sm:p-8 transition-all">
            
            <button id="closeEditModal" 
                    class="absolute top-5 right-5 w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 flex items-center justify-center transition-all cursor-pointer" 
                    aria-label="Close Modal">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>

            <div class="flex items-center gap-3 pb-5 mb-6 border-b border-[var(--border-light)] pr-10">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-xl">security</span>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-[var(--text-dark)] m-0">
                        Edit Role Details
                    </h2>
                    <p class="text-xs text-[var(--text-muted)] m-0 font-medium">Update role identifier and modify permission grants</p>
                </div>
            </div>

            <form id="editForm" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="edit_name" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                        Role Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="edit_name" required autocomplete="off"
                           class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                </div>

                {{-- Edit Permissions Checkbox Grid --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                        Update Permissions <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 p-3 rounded-2xl border border-[var(--border-light)] bg-slate-50/50 dark:bg-slate-800/30 max-h-60 overflow-y-auto">
                        @foreach ($permissions as $permission)
                            <label class="flex items-center gap-2.5 p-2 rounded-xl bg-white dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/60 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors cursor-pointer text-xs font-medium text-[var(--text-dark)]" for="edit_perm_{{ $permission->id }}">
                                <input type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $permission->id }}" 
                                       id="edit_perm_{{ $permission->id }}" 
                                       class="edit-permission w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-600 cursor-pointer">
                                <span>{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-[var(--border-light)]">
                    <button type="button" id="cancelEditModal" 
                            class="w-full sm:w-auto h-11 px-5 rounded-xl text-xs sm:text-sm font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="w-full sm:w-auto h-11 px-6 rounded-xl text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md shadow-indigo-600/20 flex items-center justify-center gap-2 transition-all cursor-pointer">
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // --- LOADING OVERLAY HELPER UTILITIES ---
            function showLoading() {
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) overlay.classList.add('active');
            }

            function hideLoading() {
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) overlay.classList.remove('active');
            }

            // Helper to get CSS variable colors for SweetAlert Dark Mode
            const getComputedColor = (cssVar) => getComputedStyle(document.body).getPropertyValue(cssVar).trim();

            // --- SWEETALERT NOTIFICATIONS ---
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{!! addslashes(session("success")) !!}',
                    timer: 2500,
                    showConfirmButton: false,
                    background: getComputedColor('--card-bg'),
                    color: getComputedColor('--text-dark')
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Notice!',
                    text: '{!! addslashes(session("error")) !!}',
                    timer: 3000,
                    showConfirmButton: false,
                    background: getComputedColor('--card-bg'),
                    color: getComputedColor('--text-dark')
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    showConfirmButton: true,
                    confirmButtonColor: '#4f46e5',
                    background: getComputedColor('--card-bg'),
                    color: getComputedColor('--text-dark')
                });
            @endif

            // Modal Helpers
            const body = document.body;

            function openModal(modal) {
                if (!modal) return;
                modal.classList.remove('hidden');
                body.classList.add('overflow-hidden');
            }

            function closeModal(modal) {
                if (!modal) return;
                modal.classList.add('hidden');
                body.classList.remove('overflow-hidden');
            }

            // --- ADD ROLE MODAL TOGGLES ---
            const addModal = document.getElementById("permissionModal");
            const openAddBtn = document.getElementById("openModal");
            const closeAddBtn = document.getElementById("closeModal");
            const cancelAddBtn = document.getElementById("cancelAddModal");

            if (openAddBtn && addModal) {
                openAddBtn.addEventListener("click", () => openModal(addModal));
            }

            const closeAddModalFunc = () => closeModal(addModal);

            if (closeAddBtn) closeAddBtn.addEventListener("click", closeAddModalFunc);
            if (cancelAddBtn) cancelAddBtn.addEventListener("click", closeAddModalFunc);

            if (addModal) {
                addModal.addEventListener("click", (e) => {
                    if (e.target === addModal) closeAddModalFunc();
                });

                // Trigger loading spinner on Add Role Form submission
                const addForm = addModal.querySelector('form');
                if (addForm) {
                    addForm.addEventListener('submit', function () {
                        showLoading();
                    });
                }
            }

            // --- EDIT ROLE MODAL TOGGLES ---
            const editModal = document.getElementById("editModal");
            const closeEditBtn = document.getElementById("closeEditModal");
            const cancelEditBtn = document.getElementById("cancelEditModal");
            const editButtons = document.querySelectorAll(".editBtn");
            const editForm = document.getElementById("editForm");
            const editName = document.getElementById("edit_name");

            editButtons.forEach(button => {
                button.addEventListener("click", (e) => {
                    e.preventDefault();

                    const id = button.dataset.id;
                    const name = button.dataset.name;
                    const permissions = JSON.parse(button.dataset.permissions || "[]");

                    // Fill modal inputs
                    if (editName) editName.value = name || '';

                    // Update form action dynamically targeting role routes
                    if (editForm) editForm.action = `/roles/${id}`;

                    // Reset all permission checkboxes first
                    document.querySelectorAll(".edit-permission").forEach(cb => cb.checked = false);

                    // Check assigned permissions
                    permissions.forEach(pid => {
                        const cb = document.getElementById("edit_perm_" + pid);
                        if (cb) cb.checked = true;
                    });

                    openModal(editModal);
                });
            });

            const closeEditModalFunc = () => closeModal(editModal);

            if (closeEditBtn) closeEditBtn.addEventListener("click", closeEditModalFunc);
            if (cancelEditBtn) cancelEditBtn.addEventListener("click", closeEditModalFunc);

            if (editModal) {
                editModal.addEventListener("click", (e) => {
                    if (e.target === editModal) closeEditModalFunc();
                });
            }

            // Trigger loading spinner on Edit Role Form submission
            if (editForm) {
                editForm.addEventListener('submit', function () {
                    showLoading();
                });
            }

            // --- DELETE ROLE CONFIRMATION & LOADING ---
            document.querySelectorAll('.delete-btn').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Delete this Role?',
                        text: "This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel',
                        background: getComputedColor('--card-bg'),
                        color: getComputedColor('--text-dark')
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showLoading();
                            form.submit();
                        }
                    });
                });
            });

            // --- KEYBOARD ACCESSIBILITY (ESC KEY) ---
            document.addEventListener('keydown', function (event) {
                if (event.key === "Escape") {
                    closeAddModalFunc();
                    closeEditModalFunc();
                }
            });
        });
    </script>
</x-app-layout>
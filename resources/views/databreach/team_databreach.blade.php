<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>

    <div id="main-content" class="w-full">
        <div class="bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-4 sm:p-6 lg:p-8">

            {{-- Header Title Banner --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 mb-6 border-b border-[var(--border-light)]">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-rose-600 via-red-600 to-orange-500 text-white flex items-center justify-center shadow-md shadow-rose-500/20 shrink-0">
                        <span class="material-symbols-outlined text-2xl">group</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                                Data Breach Response Team
                            </h1>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 border border-rose-200/60 dark:border-rose-800/60">
                                {{ $dbrtTeam->total() }} Members
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                            Manage DBRT members, contact details, and regional assignments
                        </p>
                    </div>
                </div>

                @can('create_dbrt')
                    <button id="openAddModal"
                            class="inline-flex items-center justify-center gap-2 h-11 px-5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-500 active:scale-95 shadow-md shadow-emerald-600/20 transition-all cursor-pointer">
                        <span class="material-symbols-outlined text-xl">person_add</span>
                        <span>Add DBRT Member</span>
                    </button>
                @endcan
            </div>

            {{-- Table Container --}}
            <div class="overflow-x-auto rounded-2xl border border-[var(--border-light)] bg-[var(--card-bg)] shadow-xs">
                <table class="w-full min-w-[640px] text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/60 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">
                            <th class="py-3.5 px-4 sm:px-6">Full Name / Member</th>
                            <th class="py-3.5 px-4 sm:px-6">Email Address</th>
                            <th class="py-3.5 px-4 sm:px-6">Region Assignment</th>
                            @canany(['edit_dbrt', 'delete_dbrt'])
                                <th class="py-3.5 px-4 sm:px-6 text-center">Actions</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border-subtle)]">
                        @forelse($dbrtTeam as $team)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">

                                {{-- Name with Avatar Initial --}}
                                <td class="py-4 px-4 sm:px-6 align-middle">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 font-bold text-xs flex items-center justify-center shrink-0 border border-rose-200/60 dark:border-rose-800/60">
                                            {{ strtoupper(substr($team->firstname, 0, 1) . substr($team->lastname ?? '', 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-[var(--text-dark)] block">
                                                {{ $team->firstname }} {{ $team->middle_initial ?? '' }} {{ $team->lastname ?? '' }}
                                            </span>
                                            <span class="text-xs text-[var(--text-muted)]">DBRT Member</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="py-4 px-4 sm:px-6 align-middle">
                                    <div class="flex items-center gap-1.5 text-[var(--text-dark)]">
                                        <span class="material-symbols-outlined text-base text-[var(--text-muted)]">mail</span>
                                        <span class="font-mono text-xs">{{ $team->email ?? 'N/A' }}</span>
                                    </div>
                                </td>

                                {{-- Region --}}
                                <td class="py-4 px-4 sm:px-6 align-middle">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-[var(--text-dark)] border border-slate-200 dark:border-slate-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        {{ $team->region ?? 'N/A' }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                @canany(['edit_dbrt', 'delete_dbrt'])
                                <td class="py-4 px-4 sm:px-6 align-middle text-center">
                                    <div class="inline-flex items-center justify-center gap-1">

                                        @can('edit_dbrt')
                                            <button type="button" title="Edit"
                                                class="edit-btn inline-flex items-center h-8 px-3 text-xs font-semibold text-blue-600 dark:text-blue-400 active:scale-95 transition-all cursor-pointer"
                                                data-id="{{ $team->dbrt_id }}"
                                                data-firstname="{{ $team->firstname }}"
                                                data-middle_initial="{{ $team->middle_initial }}"
                                                data-lastname="{{ $team->lastname }}"
                                                data-email="{{ $team->email }}"
                                                data-region="{{ $team->region }}">
                                                <span class="material-symbols-outlined text-sm">edit</span>
                                            </button>
                                        @endcan

                                        @can('delete_dbrt')
                                            <form action="{{ route('databreach.team_databreach.destroy', $team->dbrt_id) }}" method="POST" class="delete-form m-0 inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" title="Delete"
                                                        class="delete-btn inline-flex items-center h-8 px-3 text-xs font-semibold text-rose-600 dark:text-rose-400 active:scale-95 transition-all cursor-pointer">
                                                    <span class="material-symbols-outlined text-sm">delete</span>
                                                </button>
                                            </form>
                                        @endcan

                                    </div>
                                </td>
                                @endcanany

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 px-4 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                            <span class="material-symbols-outlined text-2xl">group_off</span>
                                        </div>
                                        <p class="text-sm font-semibold text-[var(--text-dark)] m-0">No DBRT Members Found</p>
                                        <p class="text-xs text-[var(--text-muted)] m-0">Add a new member to the Data Breach Response Team</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6 pt-4 border-t border-[var(--border-light)]">
                {{ $dbrtTeam->links() }}
            </div>

        </div>
    </div>

    {{-- Modal: Add DBRT Member --}}
    <div id="addModal"
         class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden [&:not(.hidden)]:flex items-center justify-center p-4 transition-all duration-300">
        <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-[var(--card-bg)] border border-[var(--border-light)] rounded-3xl shadow-2xl p-6 sm:p-8 transition-all">

            <button id="closeAddModal"
                    class="absolute top-5 right-5 w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 flex items-center justify-center transition-all cursor-pointer"
                    aria-label="Close Modal">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 flex items-start gap-3">
                    <span class="material-symbols-outlined text-xl text-rose-600 shrink-0">error</span>
                    <div class="text-xs">
                        <h4 class="font-bold text-rose-900 dark:text-rose-100 mb-1">Please fix the following error(s):</h4>
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="flex items-center gap-3 pb-5 mb-6 border-b border-[var(--border-light)] pr-10">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-xl">person_add</span>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-[var(--text-dark)] m-0">
                        Add DBRT Member
                    </h2>
                    <p class="text-xs text-[var(--text-muted)] m-0 font-medium">Register a new member to the Data Breach Response Team</p>
                </div>
            </div>

            <form action="{{ route('databreach.team_databreach.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Names in 3-column grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="firstname" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                            First Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="firstname" id="firstname" placeholder="e.g., Juan" required autocomplete="off"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>
                    <div>
                        <label for="middle_initial" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                            Middle Initial
                        </label>
                        <input type="text" name="middle_initial" id="middle_initial" placeholder="e.g., A." autocomplete="off"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>
                    <div>
                        <label for="lastname" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                            Last Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="lastname" id="lastname" placeholder="e.g., Dela Cruz" required autocomplete="off"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" placeholder="e.g., j_delacruz@cda.gov.ph" required autocomplete="email"
                           class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-mono">
                </div>

                {{-- Region --}}
                <div>
                    <label for="region" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                        Region Assignment <span class="text-rose-500">*</span>
                    </label>
                    <select id="region" name="region" required
                            class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                        <option value="">-- Select Region --</option>
                        <option value="CDA HO">CDA HO</option>
                        <option value="CDA CAR">CDA CAR</option>
                        <option value="CDA NIR">CDA NIR</option>
                        <option value="CDA NCR">CDA NCR</option>
                        <option value="CDA Region I">CDA Region I</option>
                        <option value="CDA Region II">CDA Region II</option>
                        <option value="CDA Region III">CDA Region III</option>
                        <option value="CDA Region IV-A">CDA Region IV-A</option>
                        <option value="CDA Region IV-B">CDA Region IV-B</option>
                        <option value="CDA Region V">CDA Region V</option>
                        <option value="CDA Region VI">CDA Region VI</option>
                        <option value="CDA Region VII">CDA Region VII</option>
                        <option value="CDA Region VIII">CDA Region VIII</option>
                        <option value="CDA Region IX">CDA Region IX</option>
                        <option value="CDA Region X">CDA Region X</option>
                        <option value="CDA Region XI">CDA Region XI</option>
                        <option value="CDA Region XII">CDA Region XII</option>
                        <option value="CDA Region XIII">CDA Region XIII</option>
                    </select>
                </div>

                {{-- Modal Footer --}}
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-[var(--border-light)]">
                    <button type="button" id="cancelAddModalBtn"
                            class="w-full sm:w-auto h-11 px-5 rounded-xl text-xs sm:text-sm font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="w-full sm:w-auto h-11 px-6 rounded-xl text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md shadow-indigo-600/20 flex items-center justify-center gap-2 transition-all cursor-pointer">
                        <span>Submit Member</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Edit DBRT Member --}}
    <div id="editModal"
         class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden [&:not(.hidden)]:flex items-center justify-center p-4 transition-all duration-300">
        <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-[var(--card-bg)] border border-[var(--border-light)] rounded-3xl shadow-2xl p-6 sm:p-8 transition-all">

            <button id="closeEditModal"
                    class="absolute top-5 right-5 w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 flex items-center justify-center transition-all cursor-pointer"
                    aria-label="Close Modal">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 flex items-start gap-3">
                    <span class="material-symbols-outlined text-xl text-rose-600 shrink-0">error</span>
                    <div class="text-xs">
                        <h4 class="font-bold text-rose-900 dark:text-rose-100 mb-1">Please fix the following error(s):</h4>
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="flex items-center gap-3 pb-5 mb-6 border-b border-[var(--border-light)] pr-10">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-xl">edit_note</span>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-[var(--text-dark)] m-0">
                        Edit DBRT Member
                    </h2>
                    <p class="text-xs text-[var(--text-muted)] m-0 font-medium">Update member information and regional assignment</p>
                </div>
            </div>

            <form id="editForm" method="POST" action="#" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Names in 3-column grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="edit_firstname" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                            First Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="firstname" id="edit_firstname" required autocomplete="off"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>
                    <div>
                        <label for="edit_middle_initial" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                            Middle Initial
                        </label>
                        <input type="text" name="middle_initial" id="edit_middle_initial" autocomplete="off"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>
                    <div>
                        <label for="edit_lastname" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                            Last Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="lastname" id="edit_lastname" required autocomplete="off"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="edit_email" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" id="edit_email" required autocomplete="email"
                           class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-mono">
                </div>

                {{-- Region --}}
                <div>
                    <label for="edit_region" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                        Region Assignment <span class="text-rose-500">*</span>
                    </label>
                    <select id="edit_region" name="region" required
                            class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                        <option value="">-- Select Region --</option>
                        <option value="CDA HO">CDA HO</option>
                        <option value="CDA CAR">CDA CAR</option>
                        <option value="CDA NIR">CDA NIR</option>
                        <option value="CDA NCR">CDA NCR</option>
                        <option value="CDA Region I">CDA Region I</option>
                        <option value="CDA Region II">CDA Region II</option>
                        <option value="CDA Region III">CDA Region III</option>
                        <option value="CDA Region IV-A">CDA Region IV-A</option>
                        <option value="CDA Region IV-B">CDA Region IV-B</option>
                        <option value="CDA Region V">CDA Region V</option>
                        <option value="CDA Region VI">CDA Region VI</option>
                        <option value="CDA Region VII">CDA Region VII</option>
                        <option value="CDA Region VIII">CDA Region VIII</option>
                        <option value="CDA Region IX">CDA Region IX</option>
                        <option value="CDA Region X">CDA Region X</option>
                        <option value="CDA Region XI">CDA Region XI</option>
                        <option value="CDA Region XII">CDA Region XII</option>
                        <option value="CDA Region XIII">CDA Region XIII</option>
                    </select>
                </div>

                {{-- Modal Footer --}}
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-[var(--border-light)]">
                    <button type="button" id="cancelEditModalBtn"
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
        document.addEventListener('DOMContentLoaded', function () {

            // Helper to get CSS variable colors for SweetAlert Dark Mode
            const getComputedColor = (cssVar) => getComputedStyle(document.body).getPropertyValue(cssVar).trim();

            // SweetAlert Flash Notifications
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: @json(session('success')),
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
                    text: @json(session('error')),
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

            // Add Modal Logic
            const addModal    = document.getElementById('addModal');
            const openAddBtn  = document.getElementById('openAddModal');
            const closeAddBtn = document.getElementById('closeAddModal');
            const cancelAddBtn = document.getElementById('cancelAddModalBtn');

            if (openAddBtn)   openAddBtn.addEventListener('click', () => openModal(addModal));
            if (closeAddBtn)  closeAddBtn.addEventListener('click', () => closeModal(addModal));
            if (cancelAddBtn) cancelAddBtn.addEventListener('click', () => closeModal(addModal));
            if (addModal)     addModal.addEventListener('click', e => { if (e.target === addModal) closeModal(addModal); });

            // Edit Modal Logic
            const editModal    = document.getElementById('editModal');
            const closeEditBtn = document.getElementById('closeEditModal');
            const cancelEditBtn = document.getElementById('cancelEditModalBtn');
            const editForm     = document.getElementById('editForm');

            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const dbrtId = button.dataset.id;
                    document.getElementById('edit_firstname').value      = button.dataset.firstname      || '';
                    document.getElementById('edit_middle_initial').value = button.dataset.middle_initial || '';
                    document.getElementById('edit_lastname').value       = button.dataset.lastname       || '';
                    document.getElementById('edit_email').value          = button.dataset.email          || '';
                    document.getElementById('edit_region').value         = button.dataset.region         || '';

                    if (editForm) editForm.action = `/databreach/team_databreach/${dbrtId}`;
                    openModal(editModal);
                });
            });

            if (closeEditBtn)  closeEditBtn.addEventListener('click',  () => closeModal(editModal));
            if (cancelEditBtn) cancelEditBtn.addEventListener('click', () => closeModal(editModal));
            if (editModal)     editModal.addEventListener('click', e => { if (e.target === editModal) closeModal(editModal); });

            // Delete Confirmation
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Remove this Member?',
                        text: "This action will permanently remove this member from the team.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel',
                        background: getComputedColor('--card-bg'),
                        color: getComputedColor('--text-dark')
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // Escape Key Closes Modals
            document.addEventListener('keydown', function (event) {
                if (event.key === "Escape") {
                    closeModal(addModal);
                    closeModal(editModal);
                }
            });
        });
    </script>
</x-app-layout>
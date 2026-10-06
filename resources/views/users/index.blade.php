<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>

    <!-- Full-Screen Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="w-12 h-12 border-4 border-white/20 border-t-white rounded-full animate-spin mb-4"></div>
        <p class="text-base font-semibold tracking-wide text-white">Processing User Account, please wait...</p>
    </div>

    <div id="main-content" class="w-full">
        <div class="panel bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-4 sm:p-6 lg:p-8">
            
            {{-- Header Title Banner --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 mb-6 border-b border-[var(--border-light)]">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-violet-600 via-purple-600 to-indigo-600 text-white flex items-center justify-center shadow-md shadow-purple-500/20 shrink-0">
                        <span class="material-symbols-outlined text-2xl">manage_accounts</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                                User Accounts
                            </h1>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 border border-purple-200/60 dark:border-purple-800/60">
                                {{ $users->total() }} Registered Users
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                            Manage user profiles, regional office assignments, contact info, and security role permissions
                        </p>
                    </div>
                </div>

                @can('create_tech_users')
                    <button id="openModal" 
                            class="inline-flex items-center justify-center gap-2 h-11 px-5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-500 active:scale-95 shadow-md shadow-emerald-600/20 transition-all cursor-pointer">
                        <span class="material-symbols-outlined text-xl">person_add</span>
                        <span>Add User</span>
                    </button>
                @endcan
            </div>

            {{-- Action Toolbar: Search Form --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                <form action="{{ route('users.index') }}" method="GET" class="w-full sm:max-w-md m-0">
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 material-symbols-outlined text-xl pointer-events-none">search</span>
                        <input type="text" 
                               name="search_query" 
                               value="{{ request('search_query') }}" 
                               placeholder="Search users by name, email, or region..." 
                               autocomplete="off"
                               class="w-full h-11 pl-10 pr-24 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] placeholder-[var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <button type="submit" 
                                class="absolute right-1.5 h-8 px-3 rounded-lg text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white transition-colors cursor-pointer">
                            Search
                        </button>
                    </div>
                </form>

                @if(request('search_query'))
                    <a href="{{ route('users.index') }}" 
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--text-muted)] hover:text-[var(--text-dark)] transition-colors">
                        <span class="material-symbols-outlined text-base">close</span>
                        <span>Clear search filter</span>
                    </a>
                @endif
            </div>

            {{-- Table Container --}}
            <div class="overflow-x-auto rounded-2xl border border-[var(--border-light)] bg-[var(--card-bg)] shadow-xs">
                <table class="w-full min-w-[850px] text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/60 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">
                            <th class="py-3.5 px-4 sm:px-6">Full Name</th>
                            <th class="py-3.5 px-4 sm:px-6">Email Address</th>
                            <th class="py-3.5 px-4 sm:px-6">Region</th>
                            <th class="py-3.5 px-4 sm:px-6">Contact Number</th>
                            <th class="py-3.5 px-4 sm:px-6">System Role(s)</th>
                            @if(auth()->user()->can('edit_tech_users') || auth()->user()->can('delete_tech_users'))
                                <th class="py-3.5 px-4 sm:px-6 text-center">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border-subtle)]">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                
                                {{-- Name & Avatar Initial --}}
                                <td class="py-4 px-4 sm:px-6 align-middle">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 font-bold text-xs flex items-center justify-center shrink-0 border border-purple-200/60 dark:border-purple-800/60">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-[var(--text-dark)] block">
                                                {{ $user->name }}
                                            </span>
                                            <span class="text-xs text-[var(--text-muted)]">User ID: #{{ $user->id }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Email Address --}}
                                <td class="py-4 px-4 sm:px-6 align-middle">
                                    <div class="flex items-center gap-1.5 text-[var(--text-dark)]">
                                        <span class="material-symbols-outlined text-base text-[var(--text-muted)]">mail</span>
                                        <span class="font-mono text-xs">{{ $user->email }}</span>
                                    </div>
                                </td>

                                {{-- Region --}}
                                <td class="py-4 px-4 sm:px-6 align-middle">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-[var(--text-dark)] border border-slate-200 dark:border-slate-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                        {{ $user->region }}
                                    </span>
                                </td>

                                {{-- Contact Number --}}
                                <td class="py-4 px-4 sm:px-6 align-middle">
                                    @if($user->contact_number)
                                        <div class="flex items-center gap-1.5 text-xs text-[var(--text-dark)] font-mono">
                                            <span class="material-symbols-outlined text-base text-[var(--text-muted)]">call</span>
                                            <span>{{ $user->contact_number }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-[var(--text-muted)] italic">N/A</span>
                                    @endif
                                </td>

                                {{-- Roles --}}
                                <td class="py-4 px-4 sm:px-6 align-middle">
                                    <div class="flex flex-wrap gap-1.5">
                                        @if($user->roles->isNotEmpty())
                                            @foreach($user->roles as $role)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60">
                                                No Role Assigned
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Actions --}}
                                @if(auth()->user()->can('edit_tech_users') || auth()->user()->can('delete_tech_users'))
                                <td class="py-4 px-4 sm:px-6 align-middle text-center">
                                    <div class="inline-flex items-center justify-center">
                                        
                                        {{-- Edit Button --}}
                                        @can('edit_tech_users')
                                            <button type="button" title="Edit"
                                                class="editBtn inline-flex items-center h-8 px-3 text-xs font-semibold text-blue-600 dark:text-blue-400 active:scale-95 transition-all cursor-pointer"
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-region="{{ $user->region }}"
                                                data-contact-number="{{ $user->contact_number }}"
                                                data-role-ids="{{ $user->roles->pluck('id')->toJson() }}">
                                                <span class="material-symbols-outlined text-sm">edit</span>
                                            </button>
                                        @endcan

                                        {{-- Delete Button --}}
                                        @can('delete_tech_users')
                                            <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="m-0 inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" title="Delete"
                                                        class="delete-btn inline-flex items-center h-8 px-3 text-xs font-semibold text-rose-600 dark:text-rose-400 active:scale-95 transition-all cursor-pointer" 
                                                        data-id="{{ $user->id }}">
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
                                <td colspan="6" class="py-12 px-4 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                            <span class="material-symbols-outlined text-2xl">person_off</span>
                                        </div>
                                        <p class="text-sm font-semibold text-[var(--text-dark)] m-0">No Users Found</p>
                                        <p class="text-xs text-[var(--text-muted)] m-0">Try changing your search keywords or register a new user</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Wrapper --}}
            <div class="mt-6 pt-4 border-t border-[var(--border-light)]">
                {{ $users->links() }}
            </div>
            
        </div>
    </div>

    {{-- Modal: Add User --}}
    <div id="userModal" 
         class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden [&:not(.hidden)]:flex items-center justify-center p-4 transition-all duration-300">
        <div id="userModalContent" 
             class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-[var(--card-bg)] border border-[var(--border-light)] rounded-3xl shadow-2xl p-6 sm:p-8 transition-all">
            
            <button id="closeModal" 
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
                        Add New User
                    </h2>
                    <p class="text-xs text-[var(--text-muted)] m-0 font-medium">Create a user account with assigned regional scope and permissions</p>
                </div>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
                @csrf
                
                {{-- Name & Email --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                            Full Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required placeholder="e.g., Juan A. Dela Cruz" value="{{ old('name') }}" autocomplete="name"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                            Email Address <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" required placeholder="e.g., j_delacruz@cda.gov.ph" value="{{ old('email') }}" autocomplete="email"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-mono">
                    </div>
                </div>

                {{-- Region & Contact Number --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="region" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                            Region Assignment <span class="text-rose-500">*</span>
                        </label>
                        <select name="region" id="region" required 
                                class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                            <option value="" disabled {{ old('region') ? '' : 'selected' }}>Select Region</option>
                            @foreach ($region as $area)
                                <option value="{{ $area }}" {{ old('region') == $area ? 'selected' : '' }}>{{ $area }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="contact_number" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                            Contact Number
                        </label>
                        <input type="text" name="contact_number" id="contact_number" placeholder="e.g., 09123456789" value="{{ old('contact_number') }}" autocomplete="tel"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-mono">
                    </div>
                </div>

                {{-- Password Options --}}
                <div class="p-4 rounded-2xl border border-[var(--border-light)] bg-slate-50/50 dark:bg-slate-800/30 space-y-4">
                    <label class="flex items-center gap-2.5 cursor-pointer text-xs font-semibold text-[var(--text-dark)]" for="auto_generate_password">
                        <input type="checkbox" id="auto_generate_password" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-600 cursor-pointer">
                        <span>Generate Password Automatically (Strong 12-character string)</span>
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                                Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <input type="password" name="password" id="password" required autocomplete="new-password"
                                       class="w-full h-11 pl-3.5 pr-10 rounded-xl border border-[var(--border-light)] bg-white dark:bg-slate-800 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                                <button type="button" id="regenerateBtn" class="hidden absolute right-2 w-8 h-8 rounded-lg text-indigo-600 hover:bg-indigo-50 dark:hover:bg-slate-700 flex items-center justify-center transition-colors cursor-pointer" title="Regenerate Password">
                                    <span class="material-symbols-outlined text-lg">refresh</span>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                                Confirm Password <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                                   class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-white dark:bg-slate-800 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                        </div>
                    </div>
                </div>

                {{-- Roles Checkbox Grid --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                        Select System Role(s) <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 p-3 rounded-2xl border border-[var(--border-light)] bg-slate-50/50 dark:bg-slate-800/30 max-h-48 overflow-y-auto">
                        @foreach ($roles as $role)
                            <label class="flex items-center gap-2.5 p-2 rounded-xl bg-white dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/60 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors cursor-pointer text-xs font-medium text-[var(--text-dark)]">
                                <input type="checkbox" name="roles[]" id="add_role_{{ $loop->index }}" value="{{ $role->id }}" 
                                       class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-600 cursor-pointer"
                                       {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                <span>{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('roles')
                        <p class="text-xs font-medium text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="created_at" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">Date Added</label>
                    <input type="text" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" readonly 
                           class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-100 dark:bg-slate-800/40 text-sm text-[var(--text-muted)] cursor-not-allowed outline-none">
                </div>

                {{-- Modal Footer --}}
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-[var(--border-light)]">
                    <button type="button" id="cancelAddModal" 
                            class="w-full sm:w-auto h-11 px-5 rounded-xl text-xs sm:text-sm font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="w-full sm:w-auto h-11 px-6 rounded-xl text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md shadow-indigo-600/20 flex items-center justify-center gap-2 transition-all cursor-pointer">
                        <span>Register User</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Edit User --}}
    <div id="editModal" 
         class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden [&:not(.hidden)]:flex items-center justify-center p-4 transition-all duration-300">
        <div id="editModalContent" 
             class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-[var(--card-bg)] border border-[var(--border-light)] rounded-3xl shadow-2xl p-6 sm:p-8 transition-all">
            
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
                    <span class="material-symbols-outlined text-xl">manage_accounts</span>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-[var(--text-dark)] m-0">
                        Edit User Details
                    </h2>
                    <p class="text-xs text-[var(--text-muted)] m-0 font-medium">Update account information, regional scope, and security roles</p>
                </div>
            </div>

            <form id="editForm" method="POST" action="#" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_name" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                            Full Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="edit_name" value="" required autocomplete="name"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>

                    <div>
                        <label for="edit_email" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                            Email Address <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="edit_email" value="" required autocomplete="email"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_region" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                            Region Assignment <span class="text-rose-500">*</span>
                        </label>
                        <select name="region" id="edit_region" required 
                                class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                            <option value="" disabled selected>Select Region</option>
                            @foreach ($region as $area)
                                <option value="{{ $area }}">{{ $area }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="edit_contact_number" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                            Contact Number
                        </label>
                        <input type="text" name="contact_number" id="edit_contact_number" value="" autocomplete="tel"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-mono">
                    </div>
                </div>

                {{-- Edit Roles Checkbox Grid --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                        Select System Role(s) <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 p-3 rounded-2xl border border-[var(--border-light)] bg-slate-50/50 dark:bg-slate-800/30 max-h-48 overflow-y-auto">
                        @foreach ($roles as $role)
                            <label class="flex items-center gap-2.5 p-2 rounded-xl bg-white dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/60 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors cursor-pointer text-xs font-medium text-[var(--text-dark)]">
                                <input type="checkbox" name="roles[]" id="edit_role_{{ $loop->index }}" value="{{ $role->id }}" 
                                       class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-600 cursor-pointer">
                                <span>{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('roles')
                        <p class="text-xs font-medium text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">Last Updated</label>
                    <input type="text" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" readonly 
                           class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-100 dark:bg-slate-800/40 text-sm text-[var(--text-muted)] cursor-not-allowed outline-none">
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

            // --- ADD USER MODAL TOGGLES ---
            const addModal = document.getElementById("userModal");
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

                // Trigger loading spinner on Add User Form submission
                const addForm = addModal.querySelector('form');
                if (addForm) {
                    addForm.addEventListener('submit', function () {
                        showLoading();
                    });
                }
            }

            // --- PASSWORD AUTO-GENERATE & VISIBILITY LOGIC ---
            const autoGenCheckbox = document.getElementById('auto_generate_password');
            const passInput = document.getElementById('password');
            const passConfirmInput = document.getElementById('password_confirmation');
            const regenerateBtn = document.getElementById('regenerateBtn');

            function generateRandomPassword(length = 12) {
                const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
                let password = "";
                for (let i = 0; i < length; i++) {
                    password += charset.charAt(Math.floor(Math.random() * charset.length));
                }
                return password;
            }

            function applyRandomPassword() {
                const newPassword = generateRandomPassword(12);
                if (passInput) passInput.value = newPassword;
                if (passConfirmInput) passConfirmInput.value = newPassword;
            }

            if (autoGenCheckbox) {
                autoGenCheckbox.addEventListener('change', function () {
                    if (this.checked) {
                        if (passInput) {
                            passInput.type = "text";
                            passInput.readOnly = true;
                        }
                        if (passConfirmInput) {
                            passConfirmInput.type = "text";
                            passConfirmInput.readOnly = true;
                        }
                        if (regenerateBtn) regenerateBtn.classList.remove('hidden');

                        applyRandomPassword();
                    } else {
                        if (passInput) {
                            passInput.type = "password";
                            passInput.readOnly = false;
                            passInput.value = "";
                        }
                        if (passConfirmInput) {
                            passConfirmInput.type = "password";
                            passConfirmInput.readOnly = false;
                            passConfirmInput.value = "";
                        }
                        if (regenerateBtn) regenerateBtn.classList.add('hidden');
                    }
                });
            }

            if (regenerateBtn) {
                regenerateBtn.addEventListener('click', function () {
                    applyRandomPassword();
                });
            }

            // --- EDIT USER MODAL TOGGLES ---
            const editModal = document.getElementById("editModal");
            const closeEditBtn = document.getElementById("closeEditModal");
            const cancelEditBtn = document.getElementById("cancelEditModal");
            const editButtons = document.querySelectorAll(".editBtn");

            const editForm = document.getElementById("editForm");
            const editName = document.getElementById("edit_name");
            const editRegion = document.getElementById("edit_region");
            const editEmail = document.getElementById("edit_email");
            const editContactNumber = document.getElementById("edit_contact_number");

            editButtons.forEach(button => {
                button.addEventListener("click", (e) => {
                    e.preventDefault();

                    const id = button.dataset.id;
                    const name = button.dataset.name;
                    const email = button.dataset.email;
                    const region = button.dataset.region;
                    const contactNumber = button.dataset.contactNumber;

                    // Extract role IDs array from dataset
                    let existingRoleIds = [];
                    try {
                        existingRoleIds = JSON.parse(button.dataset.roleIds).map(String);
                    } catch (err) {
                        if (button.dataset.roleIds) {
                            existingRoleIds = String(button.dataset.roleIds).split(',').map(s => s.trim());
                        }
                    }

                    // Populate form fields
                    if (editName) editName.value = name || '';
                    if (editEmail) editEmail.value = email || '';
                    if (editRegion) editRegion.value = region || '';
                    if (editContactNumber) editContactNumber.value = contactNumber || '';

                    // Set dynamic action URL
                    if (editForm) editForm.action = `/users/${id}`;

                    // Check existing assigned roles in modal checkboxes
                    document.querySelectorAll('input[name="roles[]"][id^="edit_role_"]').forEach(checkbox => {
                        checkbox.checked = existingRoleIds.includes(String(checkbox.value));
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

            // Trigger loading spinner on Edit User Form submission
            if (editForm) {
                editForm.addEventListener('submit', function () {
                    showLoading();
                });
            }

            // --- DELETE USER CONFIRMATION & LOADING ---
            document.querySelectorAll('.delete-btn').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Delete this User?',
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
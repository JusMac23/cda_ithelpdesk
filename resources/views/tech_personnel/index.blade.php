<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>

    <!-- Full-Screen Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="w-12 h-12 border-4 border-white/20 border-t-white rounded-full animate-spin mb-4"></div>
        <p class="text-base font-semibold tracking-wide text-white">Processing Technical Personnel, please wait...</p>
    </div>

    <div id="main-content" class="w-full">
        <div class="panel bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-4 sm:p-6 lg:p-8">
            
            {{-- Header Title Banner --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 mb-6 border-b border-[var(--border-light)]">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 via-teal-600 to-cyan-600 text-white flex items-center justify-center shadow-md shadow-teal-500/20 shrink-0">
                        <span class="material-symbols-outlined text-2xl">badge</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                                Technical Personnel
                            </h1>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400 border border-teal-200/60 dark:border-teal-800/60">
                                {{ $technical_personnel->total() }} Registered
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                            Manage IT personnel regional assignments, contact credentials, and technical service competencies
                        </p>
                    </div>
                </div>

                @can('create_technical_personnel')
                    <button id="openModal" 
                            class="inline-flex items-center justify-center gap-2 h-11 px-5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-500 active:scale-95 shadow-md shadow-emerald-600/20 transition-all cursor-pointer">
                        <span class="material-symbols-outlined text-xl">person_add</span>
                        <span>Add Personnel</span>
                    </button>
                @endcan
            </div>

            {{-- Action Toolbar: Search Form --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                @can('search_technical_personnel')
                <form action="{{ route('tech_personnel.index') }}" method="GET" class="w-full sm:max-w-md m-0">
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 material-symbols-outlined text-xl pointer-events-none">search</span>
                        <input type="text" 
                               name="search_query" 
                               value="{{ request('search_query') }}" 
                               placeholder="Search personnel by name, email, or area..." 
                               autocomplete="off"
                               class="w-full h-11 pl-10 pr-24 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] placeholder-[var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <button type="submit" 
                                class="absolute right-1.5 h-8 px-3 rounded-lg text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white transition-colors cursor-pointer">
                            Search
                        </button>
                    </div>
                </form>
                @endcan

                @if(request('search_query'))
                    <a href="{{ route('tech_personnel.index') }}" 
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--text-muted)] hover:text-[var(--text-dark)] transition-colors">
                        <span class="material-symbols-outlined text-base">close</span>
                        <span>Clear search filter</span>
                    </a>
                @endif
            </div>

            {{-- Table Container --}}
            <div class="overflow-x-auto rounded-2xl border border-[var(--border-light)] bg-[var(--card-bg)] shadow-xs">
                <table class="w-full min-w-[760px] text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/60 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">
                            <th class="py-3.5 px-4 sm:px-6">Full Name / Personnel</th>
                            <th class="py-3.5 px-4 sm:px-6">Email Address</th>
                            <th class="py-3.5 px-4 sm:px-6">Region Assignment</th>
                            <th class="py-3.5 px-4 sm:px-6">Technical Services Category</th>
                            @if(auth()->user()->can('edit_technical_personnel') || auth()->user()->can('delete_technical_personnel'))
                                <th class="py-3.5 px-4 sm:px-6 text-center">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border-subtle)]">
                        @forelse ($technical_personnel as $tech_personnel)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                
                                {{-- Name with Avatar Initial --}}
                                <td class="py-4 px-4 sm:px-6 align-middle">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold text-xs flex items-center justify-center shrink-0 border border-indigo-200/60 dark:border-indigo-800/60">
                                            {{ strtoupper(substr($tech_personnel->firstname, 0, 1) . substr($tech_personnel->lastname, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-[var(--text-dark)] block">
                                                {{ $tech_personnel->firstname }} {{ $tech_personnel->middle_initial }} {{ $tech_personnel->lastname }}
                                            </span>
                                            <span class="text-xs text-[var(--text-muted)]">ID: #{{ $tech_personnel->id }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="py-4 px-4 sm:px-6 align-middle">
                                    <div class="flex items-center gap-1.5 text-[var(--text-dark)]">
                                        <span class="material-symbols-outlined text-base text-[var(--text-muted)]">mail</span>
                                        <span class="font-mono text-xs">{{ $tech_personnel->it_email }}</span>
                                    </div>
                                </td>

                                {{-- Region --}}
                                <td class="py-4 px-4 sm:px-6 align-middle">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-[var(--text-dark)] border border-slate-200 dark:border-slate-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                                        {{ $tech_personnel->it_area }}
                                    </span>
                                </td>

                                {{-- Services Category Badges --}}
                                <td class="py-4 px-4 sm:px-6 align-middle">
                                    <div class="flex flex-wrap gap-1.5 max-w-sm">
                                        @if (!empty($tech_personnel->tech_services_category))
                                            @foreach (explode(',', $tech_personnel->tech_services_category) as $service)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60">
                                                    {{ trim($service) }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-xs text-[var(--text-muted)] italic">No categories assigned</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Actions --}}
                                @if(auth()->user()->can('edit_technical_personnel') || auth()->user()->can('delete_technical_personnel'))
                                <td class="py-4 px-4 sm:px-6 align-middle text-center">
                                    <div class="inline-flex items-center justify-center">
                                        
                                        {{-- Edit Button --}}
                                        @can('edit_technical_personnel')
                                            <button type="button" title="Edit"
                                                class="editBtn inline-flex items-center h-8 px-3 text-xs font-semibold text-blue-600 dark:text-blue-400 active:scale-95 transition-all cursor-pointer"
                                                data-id="{{ $tech_personnel->id }}"
                                                data-firstname="{{ $tech_personnel->firstname }}"
                                                data-middle_initial="{{ $tech_personnel->middle_initial }}"
                                                data-lastname="{{ $tech_personnel->lastname }}"
                                                data-it_email="{{ $tech_personnel->it_email }}"
                                                data-it_area="{{ $tech_personnel->it_area }}"
                                                data-tech_services="{{ $tech_personnel->tech_services_category }}">
                                                <span class="material-symbols-outlined text-sm">edit</span>
                                            </button>
                                        @endcan

                                        {{-- Delete Button --}}
                                        @can('delete_technical_personnel')
                                            <form id="delete-form-{{ $tech_personnel->id }}" action="{{ route('tech_personnel.destroy', $tech_personnel->id) }}" method="POST" class="m-0 inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" title="Delete"
                                                        class="delete-btn inline-flex items-center h-8 px-3 text-xs font-semibold text-rose-600 dark:text-rose-400 active:scale-95 transition-all cursor-pointer" 
                                                        data-id="{{ $tech_personnel->id }}">
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
                                <td colspan="5" class="py-12 px-4 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                            <span class="material-symbols-outlined text-2xl">person_off</span>
                                        </div>
                                        <p class="text-sm font-semibold text-[var(--text-dark)] m-0">No Technical Personnel Found</p>
                                        <p class="text-xs text-[var(--text-muted)] m-0">Try changing your search terms or add a new personnel record</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Wrapper --}}
            <div class="mt-6 pt-4 border-t border-[var(--border-light)]">
                {{ $technical_personnel->links() }}
            </div>
            
        </div>
    </div>

    {{-- Modal: Add Personnel --}}
    <div id="personnelModal" 
         class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden [&:not(.hidden)]:flex items-center justify-center p-4 transition-all duration-300">
        <div id="personnelModalContent" 
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
                        Create Technical Personnel
                    </h2>
                    <p class="text-xs text-[var(--text-muted)] m-0 font-medium">Add a new IT staff member and configure service competencies</p>
                </div>
            </div>

            <form action="{{ route('tech_personnel.store') }}" method="POST" class="space-y-5">
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

                {{-- Email Address --}}
                <div>
                    <label for="it_email" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="it_email" id="it_email" placeholder="e.g., j_delacruz@cda.gov.ph" required autocomplete="email"
                           class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-mono">
                </div>

                {{-- IT Area / Region --}}
                <div>
                    <label for="it_area" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                        IT Area / Region <span class="text-rose-500">*</span>
                    </label>
                    <select name="it_area" id="it_area" required 
                            class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                        <option value="" disabled selected>Select Region</option>
                        @foreach ($region as $area)
                            <option value="{{ $area }}">{{ $area }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Checkboxes for Technical Services Category --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                        Technical Services Category <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 p-3 rounded-2xl border border-[var(--border-light)] bg-slate-50/50 dark:bg-slate-800/30 max-h-48 overflow-y-auto">
                        @foreach($services as $service)
                            <label class="flex items-center gap-2.5 p-2 rounded-xl bg-white dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/60 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors cursor-pointer text-xs font-medium text-[var(--text-dark)]">
                                <input type="checkbox" name="tech_services_category[]" value="{{ $service }}" 
                                       class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-600 cursor-pointer">
                                <span>{{ $service }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Date Added --}}
                <div>
                    <label for="date_added" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">Date Added</label>
                    <input type="text" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" readonly 
                           class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-100 dark:bg-slate-800/40 text-sm text-[var(--text-muted)] cursor-not-allowed outline-none">
                    <input type="hidden" name="date_added" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d') }}">
                </div>

                {{-- Modal Footer --}}
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-[var(--border-light)]">
                    <button type="button" id="cancelAddModal" 
                            class="w-full sm:w-auto h-11 px-5 rounded-xl text-xs sm:text-sm font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="w-full sm:w-auto h-11 px-6 rounded-xl text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md shadow-indigo-600/20 flex items-center justify-center gap-2 transition-all cursor-pointer">
                        <span>Submit Personnel</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Edit Personnel --}}
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
                    <span class="material-symbols-outlined text-xl">edit_note</span>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-[var(--text-dark)] m-0">
                        Edit Technical Personnel
                    </h2>
                    <p class="text-xs text-[var(--text-muted)] m-0 font-medium">Update technical personnel information and service skills</p>
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

                {{-- Email Address --}}
                <div>
                    <label for="edit_it_email" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="it_email" id="edit_it_email" required autocomplete="email"
                           class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-mono">
                </div>

                {{-- IT Area / Region --}}
                <div>
                    <label for="edit_it_area" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                        IT Area / Region <span class="text-rose-500">*</span>
                    </label>
                    <select name="it_area" id="edit_it_area" required 
                            class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                        <option value="" disabled>Select Region</option>
                        @foreach ($region as $area)
                            <option value="{{ $area }}">{{ $area }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Checkboxes for Technical Services Category --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-2">
                        Technical Services Category <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 p-3 rounded-2xl border border-[var(--border-light)] bg-slate-50/50 dark:bg-slate-800/30 max-h-48 overflow-y-auto">
                        @foreach($services as $service)
                            <label class="flex items-center gap-2.5 p-2 rounded-xl bg-white dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/60 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors cursor-pointer text-xs font-medium text-[var(--text-dark)]">
                                <input type="checkbox" name="tech_services_category[]" value="{{ $service }}" 
                                       class="edit-tech-service-checkbox w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-600 cursor-pointer">
                                <span>{{ $service }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Date Updated --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">Date Updated</label>
                    <input type="text" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" readonly 
                           class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-100 dark:bg-slate-800/40 text-sm text-[var(--text-muted)] cursor-not-allowed outline-none">
                    <input type="hidden" name="date_updated" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d') }}">
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

            // Loading Helper Utilities
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

            // SweetAlert Flash & Validation Notifications
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: @json(session('success')),
                    timer: 2000,
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
                    timer: 2000,
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

            // Add Personnel Modal Logic
            const addModal = document.getElementById("personnelModal");
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

                // Trigger loading on Add form submission
                const addForm = addModal.querySelector('form');
                if (addForm) {
                    addForm.addEventListener('submit', function () {
                        showLoading();
                    });
                }
            }

            // Edit Personnel Modal Toggles
            const editModal = document.getElementById("editModal");
            const closeEditBtn = document.getElementById("closeEditModal");
            const cancelEditBtn = document.getElementById("cancelEditModal");
            const editButtons = document.querySelectorAll(".editBtn");
            const editForm = document.getElementById("editForm");

            const editFirstname = document.getElementById("edit_firstname");
            const editMiddleInitial = document.getElementById("edit_middle_initial");
            const editLastname = document.getElementById("edit_lastname");
            const editEmail = document.getElementById("edit_it_email");
            const editArea = document.getElementById("edit_it_area");
            const editCheckboxes = document.querySelectorAll(".edit-tech-service-checkbox");

            editButtons.forEach(button => {
                button.addEventListener("click", (e) => {
                    e.preventDefault();

                    const id = button.dataset.id;
                    if (editFirstname) editFirstname.value = button.dataset.firstname || '';
                    if (editMiddleInitial) editMiddleInitial.value = button.dataset.middle_initial || '';
                    if (editLastname) editLastname.value = button.dataset.lastname || '';
                    if (editEmail) editEmail.value = button.dataset.it_email || '';
                    if (editArea) editArea.value = button.dataset.it_area || '';

                    // Check selected service checkboxes
                    const techServicesStr = button.dataset.tech_services || '';
                    const selectedServices = techServicesStr.split(',').map(item => item.trim());

                    editCheckboxes.forEach(checkbox => {
                        checkbox.checked = selectedServices.includes(checkbox.value);
                    });

                    // Update action route dynamically
                    if (editForm) editForm.action = `/tech_personnel/${id}`;

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

            // Trigger loading on Edit form submission
            if (editForm) {
                editForm.addEventListener('submit', function () {
                    showLoading();
                });
            }

            // Delete Confirmation Logic
            document.querySelectorAll('.delete-btn').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Delete this Personnel?',
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

            // Close Modals with Escape Key
            document.addEventListener('keydown', function (event) {
                if (event.key === "Escape") {
                    closeAddModalFunc();
                    closeEditModalFunc();
                }
            });
        });
    </script>
</x-app-layout>
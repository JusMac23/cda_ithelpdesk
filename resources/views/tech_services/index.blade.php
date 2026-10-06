<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>

    <!-- Full-Screen Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="w-12 h-12 border-4 border-white/20 border-t-white rounded-full animate-spin mb-4"></div>
        <p class="text-base font-semibold tracking-wide text-white">Processing Technical Services, please wait...</p>
    </div>

    <div id="main-content" class="w-full">
        <div class="panel bg-[var(--card-bg)] border border-[var(--border-light)] rounded-2xl shadow-xs transition-colors duration-300 p-4 sm:p-6 lg:p-8">
            
            {{-- Header Title Banner --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 mb-6 border-b border-[var(--border-light)]">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-600 text-white flex items-center justify-center shadow-md shadow-indigo-500/20 shrink-0">
                        <span class="material-symbols-outlined text-2xl">design_services</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-dark)] m-0 leading-tight">
                                Technical Services & SLA
                            </h1>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/60">
                                {{ $technical_services->total() }} Services Catalog
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-[var(--text-muted)] m-0 mt-0.5 font-medium">
                            Service Level Agreement (SLA) turnaround targets and technical assistance service categories
                        </p>
                    </div>
                </div>

                @can('create_technical_services')
                    <button id="openModal" 
                            class="inline-flex items-center justify-center gap-2 h-11 px-5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-500 active:scale-95 shadow-md shadow-emerald-600/20 transition-all cursor-pointer">
                        <span class="material-symbols-outlined text-xl">add_circle</span>
                        <span>Add Service</span>
                    </button>
                @endcan
            </div>

            {{-- Action Toolbar: Search Form & Guidelines --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                @can('search_technical_services')
                <form action="{{ route('tech_services.index') }}" method="GET" class="w-full sm:max-w-md m-0">
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 material-symbols-outlined text-xl pointer-events-none">search</span>
                        <input type="text" 
                               name="search_query" 
                               value="{{ request('search_query') }}" 
                               placeholder="Search technical services description..." 
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
                    <a href="{{ route('tech_services.index') }}" 
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--text-muted)] hover:text-[var(--text-dark)] transition-colors">
                        <span class="material-symbols-outlined text-base">close</span>
                        <span>Clear search filter</span>
                    </a>
                @endif
            </div>

            @php
                $canManage = auth()->user()->can('edit_technical_services') || auth()->user()->can('delete_technical_services');
            @endphp

            {{-- Table Container --}}
            <div class="overflow-x-auto rounded-2xl border border-[var(--border-light)] bg-[var(--card-bg)] shadow-xs">
                <table class="w-full min-w-[700px] text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/60 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">
                            <th class="py-3.5 px-4 sm:px-6">Technical Services Description</th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">
                                <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Low SLA
                                </span>
                            </th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">
                                <span class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Medium SLA
                                </span>
                            </th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">
                                <span class="inline-flex items-center gap-1 text-orange-600 dark:text-orange-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> High SLA
                                </span>
                            </th>
                            <th class="py-3.5 px-4 sm:px-6 text-center">
                                <span class="inline-flex items-center gap-1 text-rose-600 dark:text-rose-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Critical SLA
                                </span>
                            </th>
                            @if($canManage)
                                <th class="py-3.5 px-4 sm:px-6 text-center">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border-subtle)]">
                        @forelse ($technical_services as $tech_services)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                
                                {{-- Service Description --}}
                                <td class="py-4 px-4 sm:px-6 align-middle font-semibold text-[var(--text-dark)]">
                                    <div class="flex items-center gap-2.5">
                                        <span class="material-symbols-outlined text-indigo-500 text-lg">build</span>
                                        <span>{{ $tech_services->technical_services }}</span>
                                    </div>
                                </td>

                                {{-- Low SLA --}}
                                <td class="py-4 px-4 sm:px-6 align-middle text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800">
                                        {{ $tech_services->low ?: 'N/A' }}
                                    </span>
                                </td>

                                {{-- Medium SLA --}}
                                <td class="py-4 px-4 sm:px-6 align-middle text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800">
                                        {{ $tech_services->medium ?: 'N/A' }}
                                    </span>
                                </td>

                                {{-- High SLA --}}
                                <td class="py-4 px-4 sm:px-6 align-middle text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-200 dark:bg-orange-950/40 dark:text-orange-300 dark:border-orange-800">
                                        {{ $tech_services->high ?: 'N/A' }}
                                    </span>
                                </td>

                                {{-- Critical SLA --}}
                                <td class="py-4 px-4 sm:px-6 align-middle text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800">
                                        {{ $tech_services->critical ?: 'N/A' }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                @if($canManage)
                                <td class="py-4 px-4 sm:px-6 align-middle text-center">
                                    <div class="inline-flex items-center justify-center">
                                        
                                        {{-- Edit Button --}}
                                        @can('edit_technical_services')
                                            <button type="button" title="Edit"
                                                class="editBtn inline-flex items-center h-8 px-3 text-xs font-semibold text-blue-600 dark:text-blue-400 active:scale-95 transition-all cursor-pointer"
                                                data-id="{{ $tech_services->id }}"
                                                data-technical_services="{{ $tech_services->technical_services }}"
                                                data-low="{{ $tech_services->low }}"
                                                data-medium="{{ $tech_services->medium }}"
                                                data-high="{{ $tech_services->high }}"
                                                data-critical="{{ $tech_services->critical }}">
                                                <span class="material-symbols-outlined text-sm">edit</span>
                                            </button>
                                        @endcan

                                        {{-- Delete Button --}}
                                        @can('delete_technical_services')
                                            <form id="delete-form-{{ $tech_services->id }}" action="{{ route('tech_services.destroy', $tech_services->id) }}" method="POST" class="m-0 inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" title="Delete"
                                                        class="delete-btn inline-flex items-center h-8 px-3 text-xs font-semibold text-rose-600 dark:text-rose-400 active:scale-95 transition-all cursor-pointer" 
                                                        data-id="{{ $tech_services->id }}">
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
                                <td colspan="{{ $canManage ? 6 : 5 }}" class="py-12 px-4 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                            <span class="material-symbols-outlined text-2xl">home_repair_service</span>
                                        </div>
                                        <p class="text-sm font-semibold text-[var(--text-dark)] m-0">No Technical Services Found</p>
                                        <p class="text-xs text-[var(--text-muted)] m-0">Try changing your search terms or add a new technical service</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Wrapper --}}
            <div class="mt-6 pt-4 border-t border-[var(--border-light)]">
                {{ $technical_services->links() }}
            </div>
            
        </div>
    </div>

    {{-- Modal: Add Service --}}
    <div id="servicesModal" 
         class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden [&:not(.hidden)]:flex items-center justify-center p-4 transition-all duration-300">
        <div id="servicesModalContent" 
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
                    <span class="material-symbols-outlined text-xl">add_circle</span>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-[var(--text-dark)] m-0">
                        Add Technical Service
                    </h2>
                    <p class="text-xs text-[var(--text-muted)] m-0 font-medium">Create a new service catalog item with SLA turnaround standards</p>
                </div>
            </div>

            <form action="{{ route('tech_services.store') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="technical_services" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                        Technical Services Description <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="technical_services" id="technical_services" required autocomplete="off" 
                           placeholder="e.g., Cybersecurity Incident Management / Software Installation"
                           class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                </div>

                {{-- SLA Turnaround Fields Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="low" class="block text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1.5">
                            Low Level SLA
                        </label>
                        <input type="text" name="low" id="low" autocomplete="off" placeholder="e.g. 1 day 30 mins or N/A"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>

                    <div>
                        <label for="medium" class="block text-xs font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400 mb-1.5">
                            Medium Level SLA
                        </label>
                        <input type="text" name="medium" id="medium" autocomplete="off" placeholder="e.g. 4 hours 30 mins"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>

                    <div>
                        <label for="high" class="block text-xs font-semibold uppercase tracking-wider text-orange-600 dark:text-orange-400 mb-1.5">
                            High Level SLA
                        </label>
                        <input type="text" name="high" id="high" autocomplete="off" placeholder="e.g. 2 hours 30 mins"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>

                    <div>
                        <label for="critical" class="block text-xs font-semibold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-1.5">
                            Critical Level SLA
                        </label>
                        <input type="text" name="critical" id="critical" autocomplete="off" placeholder="e.g. 1 hour 30 mins"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">Date Added</label>
                    <input type="text" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" readonly 
                           class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-100 dark:bg-slate-800/40 text-sm text-[var(--text-muted)] cursor-not-allowed outline-none">
                    <input type="hidden" name="added_at" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s') }}">
                </div>

                {{-- Modal Footer --}}
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-[var(--border-light)]">
                    <button type="button" id="cancelAddModal" 
                            class="w-full sm:w-auto h-11 px-5 rounded-xl text-xs sm:text-sm font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="w-full sm:w-auto h-11 px-6 rounded-xl text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md shadow-indigo-600/20 flex items-center justify-center gap-2 transition-all cursor-pointer">
                        <span>Submit Service</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Edit Service --}}
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
                        Edit Technical Service
                    </h2>
                    <p class="text-xs text-[var(--text-muted)] m-0 font-medium">Update description and SLA targets for this service</p>
                </div>
            </div>

            <form id="editForm" method="POST" action="#" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="edit_technical_services" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">
                        Technical Services Description <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="technical_services" id="edit_technical_services" required autocomplete="off"
                           class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                </div>

                {{-- SLA Turnaround Fields Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_low" class="block text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1.5">
                            Low Level SLA
                        </label>
                        <input type="text" name="low" id="edit_low" autocomplete="off"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>

                    <div>
                        <label for="edit_medium" class="block text-xs font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400 mb-1.5">
                            Medium Level SLA
                        </label>
                        <input type="text" name="medium" id="edit_medium" autocomplete="off"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>

                    <div>
                        <label for="edit_high" class="block text-xs font-semibold uppercase tracking-wider text-orange-600 dark:text-orange-400 mb-1.5">
                            High Level SLA
                        </label>
                        <input type="text" name="high" id="edit_high" autocomplete="off"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>

                    <div>
                        <label for="edit_critical" class="block text-xs font-semibold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-1.5">
                            Critical Level SLA
                        </label>
                        <input type="text" name="critical" id="edit_critical" autocomplete="off"
                               class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-50 dark:bg-slate-800/50 text-sm text-[var(--text-dark)] focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)] mb-1.5">Date Updated</label>
                    <input type="text" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('F j, Y h:i A') }}" readonly 
                           class="w-full h-11 px-3.5 rounded-xl border border-[var(--border-light)] bg-slate-100 dark:bg-slate-800/40 text-sm text-[var(--text-muted)] cursor-not-allowed outline-none">
                    <input type="hidden" name="updated_at" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s') }}">
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
                    html: @json($errors->all()).join('<br>'),
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

            // --- ADD SERVICES MODAL TOGGLES ---
            const addModal = document.getElementById("servicesModal");
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

                // Trigger loading spinner on Add Form submission
                const addForm = addModal.querySelector('form');
                if (addForm) {
                    addForm.addEventListener('submit', function () {
                        showLoading();
                    });
                }
            }

            // --- EDIT SERVICES MODAL TOGGLES ---
            const editModal = document.getElementById("editModal");
            const closeEditBtn = document.getElementById("closeEditModal");
            const cancelEditBtn = document.getElementById("cancelEditModal");
            const editButtons = document.querySelectorAll(".editBtn");
            const editForm = document.getElementById("editForm");

            // Input references for Edit Modal
            const editTechnicalServices = document.getElementById("edit_technical_services");
            const editLowRes = document.getElementById("edit_low");
            const editMediumRes = document.getElementById("edit_medium");
            const editHighRes = document.getElementById("edit_high");
            const editCriticalRes = document.getElementById("edit_critical");

            editButtons.forEach(button => {
                button.addEventListener("click", (e) => {
                    e.preventDefault();

                    const id = button.dataset.id;

                    // Populate modal input fields from button dataset attributes
                    if (editTechnicalServices) editTechnicalServices.value = button.dataset.technical_services || '';
                    if (editLowRes) editLowRes.value = button.dataset.low || '';
                    if (editMediumRes) editMediumRes.value = button.dataset.medium || '';
                    if (editHighRes) editHighRes.value = button.dataset.high || '';
                    if (editCriticalRes) editCriticalRes.value = button.dataset.critical || '';

                    // Dynamic route assignment for Technical Services endpoint
                    if (editForm) editForm.action = `/tech_services/${id}`;

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

            // Trigger loading spinner on Edit Form submission
            if (editForm) {
                editForm.addEventListener('submit', function () {
                    showLoading();
                });
            }

            // --- DELETE CONFIRMATION & LOADING ---
            document.querySelectorAll('.delete-btn').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Delete this Service?',
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
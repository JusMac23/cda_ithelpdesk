@php
    $year = now()->year;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CDA-ICT Helpdesk - IT Personnel Signature</title>
    <link rel="icon" href="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="/assets/js/sweetalert2.min.js"></script>
</head>

<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-['Inter',sans-serif] antialiased min-h-screen flex flex-col transition-colors duration-300">

<!-- Header Navigation Bar -->
<header class="sticky top-0 z-40 bg-slate-900/90 backdrop-blur-md border-b border-white/10 shadow-sm">
    <div class="h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-rose-500"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        
        {{-- Branding --}}
        <a href="{{ url('/') }}" class="group flex items-center gap-3 text-white font-extrabold text-lg sm:text-xl tracking-tight no-underline">
            <img src="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" alt="CDA Seal" class="w-10 h-10 object-contain transition-transform duration-300 group-hover:scale-105" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Cooperative_Development_Authority_%28CDA%29.svg/1200px-Cooperative_Development_Authority_%28CDA%29.svg.png'">
            <span class="bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent">CDA-ICT Helpdesk</span>
        </a>

        <a href="{{ route('tickets.index') }}" 
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-200 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 transition-all">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            <span class="hidden sm:inline">Back to Tickets</span>
        </a>
    </div>
</header>

<!-- Main Page Content -->
<main class="flex-1 w-full max-w-xl mx-auto px-4 sm:px-6 py-8 sm:py-16 flex flex-col justify-center">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 flex items-start gap-3.5">
            <span class="material-symbols-outlined text-2xl text-emerald-600 shrink-0">check_circle</span>
            <p class="text-sm font-medium m-0">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 flex items-start gap-3.5">
            <span class="material-symbols-outlined text-2xl text-rose-600 shrink-0">error</span>
            <div class="text-sm">
                <h4 class="font-bold text-rose-900 dark:text-rose-100 mb-1">Please fix the following:</h4>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Upload Form Card --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-xl p-6 sm:p-8 transition-colors duration-300">
        
        <div class="flex items-center gap-3.5 pb-5 mb-6 border-b border-slate-200 dark:border-slate-800">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-600 text-white flex items-center justify-center shadow-md shadow-indigo-500/20 shrink-0">
                <span class="material-symbols-outlined text-2xl">verified_user</span>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white m-0">
                    Upload IT Personnel Signature
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 m-0 mt-0.5 font-medium">
                    Upload your digital signature or sign-off proof for ticket resolution
                </p>
            </div>
        </div>

        <form action="{{ route('tickets.savePersonnelSignature', $ticket->ticket_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label for="personnel_signature" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">
                    Signature File <span class="text-rose-500">*</span>
                </label>
                <div class="p-4 rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/30 flex flex-col items-center justify-center gap-3 text-center">
                    <span class="material-symbols-outlined text-4xl text-indigo-500">cloud_upload</span>
                    <div>
                        <input type="file" id="personnel_signature" name="personnel_signature" accept="image/*" required 
                               class="w-full text-xs sm:text-sm text-slate-600 dark:text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-950 dark:file:text-indigo-300 cursor-pointer">
                    </div>
                    <span class="text-2xs text-slate-400">Accepts image files (PNG, JPG, JPEG, WEBP)</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2">
                <a href="{{ route('tickets.index') }}" 
                   class="w-full sm:w-auto h-11 px-5 rounded-xl text-xs sm:text-sm font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-all cursor-pointer">
                    Cancel
                </a>

                <button type="submit" 
                        class="w-full sm:w-auto h-11 px-6 rounded-xl text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md shadow-indigo-600/20 flex items-center justify-center gap-2 transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-lg">upload</span>
                    <span>Upload Signature</span>
                </button>
            </div>
        </form>
    </div>

</main>

<footer class="mt-auto py-6 border-t border-slate-200 dark:border-slate-800/60 bg-white/50 dark:bg-slate-900/50 text-center text-xs text-slate-400">
    <p class="m-0">&copy; {{ $year }} Cooperative Development Authority - Information & Communications Technology Division. All rights reserved.</p>
</footer>

</body>
</html>
@php
    $year = now()->year;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CDA-Helpdesk</title>
    <link rel="icon" href="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" />

    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/assets/js/sweetalert2.min.js"></script>

    <style>
        body {
            font-family: 'Figtree', sans-serif !important;
        }
        @keyframes fade-in-down {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down {
            animation: fade-in-down 0.9s ease-out both;
        }
        .interactive-link {
            transition: all 0.3s ease-in-out;
        }
        .interactive-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-figtree antialiased">

<header class="bg-white shadow-lg sticky top-0 z-50 border-b border-gray-200">
    <div class="h-1 bg-gradient-to-r from-blue-600 via-indigo-500 to-purple-500"></div>
    
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <!-- Logo & Title -->
        <h1 class="text-2xl lg:text-3xl font-bold text-blue-800 flex items-center gap-3">
            <img src="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" 
                 alt="Cooperative Development Authority Seal" 
                 class="w-12 h-12 object-contain drop-shadow-sm transition-transform duration-300 hover:scale-105"/>
            <span class="tracking-tight">CDA-Helpdesk System</span>
        </h1>
    </div>
</header>

<section class="p-8 max-w-6xl mx-auto bg-gray-200 rounded-2xl shadow-xl mt-10 mb-16 animate-fade-in-down">

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('myrequested_tickets.index') }}" 
           class="inline-flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-600 hover:shadow-lg transition duration-300">
            <i class="fas fa-arrow-left"></i> Back to Tickets
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-800 px-6 py-4 mb-6 rounded-lg">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i>
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-800 px-6 py-4 mb-6 rounded-lg">
            <div class="flex">
                <i class="fas fa-exclamation-circle text-xl mt-1 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-sm mb-1">Please fix the following:</h4>
                    <ul class="list-disc pl-5 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Upload Your Signature</h2>
        <p class="mb-4">Please upload your e-signature for ticket confirmation.</p>

        <form action="{{ route('tickets.saveClientSignature', $ticket->ticket_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="client_signature" accept="image/*" required 
                class="mb-4 border p-2 w-full rounded">

            <button type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow-md hover:shadow-lg transition duration-300 flex items-center gap-2">
                <i class="fas fa-upload"></i> 
                Upload Signature
            </button>
        </form>
    </div>
</section>
</body>
</html>

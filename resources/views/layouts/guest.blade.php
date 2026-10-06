<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="CDA-ICT Helpdesk – Secure IT Support Portal Login">

    <title>{{ config('app.name', 'CDA-ICT Helpdesk') }}</title>
    <link rel="icon" href="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased" style="font-family:'Inter',system-ui,sans-serif; background:linear-gradient(135deg,#e0e7ff 0%,#f0f9ff 40%,#dbeafe 80%,#ede9fe 100%); overflow:auto;">

    {{-- Full-page centered layout --}}
    <div class="min-h-screen flex items-center justify-center px-4 py-10 sm:py-16">

        {{-- Auth Card --}}
        <div class="w-full max-w-md animate-card-fade"
             style="background:rgba(255,255,255,0.96);
                    border-radius:1.5rem;
                    padding:2.75rem 2.5rem;
                    box-shadow:0 25px 50px -12px rgba(0,0,0,0.1),0 0 0 1px rgba(0,0,0,0.04);
                    backdrop-filter:blur(12px);">

            {{-- Logo --}}
            <div class="flex justify-center mb-7">
                <a href="{{ route('login') }}"
                   class="block transition-transform duration-300 ease-out hover:-translate-y-1 hover:scale-105 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-4 rounded-xl">
                    <img src="{{ asset('images/CDA-logo-RA11364-PNG.png') }}"
                         alt="Cooperative Development Authority Seal"
                         class="w-24 h-24 object-contain"
                         style="filter:drop-shadow(0 6px 12px rgba(0,0,0,0.10));">
                </a>
            </div>

            {{-- Heading --}}
            <div class="text-center mb-8">
                @php $route = Route::currentRouteName(); @endphp

                @if ($route === 'login')
                    <h1 class="text-xl font-bold text-blue-600 leading-tight mb-1">
                        Welcome to CDA-ICT Helpdesk!
                    </h1>
                    <p class="text-sm text-slate-500 font-normal leading-relaxed">
                        A few more clicks to sign in to your account.
                    </p>
                @elseif ($route === 'register')
                    <h1 class="text-xl font-bold text-blue-600">Sign Up User</h1>
                @elseif ($route === 'forgot-password')
                    <h1 class="text-xl font-bold text-blue-600">Forgot Password</h1>
                @elseif ($route === 'reset-password')
                    <h1 class="text-xl font-bold text-blue-600">Reset Password</h1>
                @endif
            </div>

            {{-- Slot for inner forms --}}
            {{ $slot }}

        </div>
    </div>

</body>
</html>
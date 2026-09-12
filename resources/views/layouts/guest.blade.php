<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CDA-ICT Helpdesk') }}</title>
        <link rel="icon" href="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" type="image/png">

        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

        <style>
            /* base resets & typography */
            body{ margin:0;font-family:'Figtree',ui-sans-serif,system-ui,-apple-system,sans-serif;color:#111827;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;background-color:#f3f4f6; }
            *{ box-sizing:border-box;}

            /* layout containers */
            .page-wrapper{ min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1rem; }
            .auth-card{ width:100%;max-width:28rem;padding:2rem;background-color:#ffffff;box-shadow:0 10px 15px -3px rgba(0,0,0,0.1),0 4px 6 -2 rgba(0,0,0,0.05);border-radius:.75rem; }

            /* logo styles */
            .logo-container{ display:flex;justify-content:center; margin-bottom:.5rem; }
            .logo-link{ display:block;}
            .logo-img{ width:5.5rem;height:5.5rem;object-fit:contain; }

            /* typography */
            .auth-heading{ text-align:center;margin-bottom:2rem; }
            /* title */
            .login-title{ font-size:1.5rem;font-weight:700;color:#1f2937;margin:0; }
            .login-subtitle{ font-size:.875rem;color:#6b7280;margin-top:.5rem;margin-bottom:0; }
        </style>
    </head>
    <body>
        <div class="page-wrapper">
            <div class="auth-card">

                <div class="logo-container">
                    <a href="{{ route('login') }}" class="logo-link">
                        <img src="{{ asset('images/CDA-logo-RA11364-PNG.png') }}" alt="Cooperative Development Authority Seal" class="logo-img" />
                    </a>
                </div>

                <div class="auth-heading">
                    @php
                        $route = Route::currentRouteName();
                    @endphp

                    @if ($route === 'login')
                        <div class="login-header">
                            <h2 class="login-title">Welcome Back</h2>
                            <p class="login-subtitle">A few more clicks to sign in to your account.</p>
                        </div>
                    @elseif ($route === 'register')
                        <h2 class="login-title">Sign Up User</h2>
                    @elseif ($route === 'forgot-password')
                        <h2 class="login-title">Forgot Password</h2>
                    @elseif ($route === 'reset-password')
                        <h2 class="login-title">Reset Password</h2>
                    @endif
                </div>

                {{-- Slot for the inner forms --}}
                {{ $slot }}
                
            </div>
        </div>
    </body>
</html>
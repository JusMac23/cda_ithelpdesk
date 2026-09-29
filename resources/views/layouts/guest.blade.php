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
            /* CSS Variables & Base Resets */
            :root { 
                --primary-color: #2563eb; 
                --bg-gradient: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%); 
                --card-bg: rgba(255, 255, 255, 0.95); 
                --text-main: #0f172a; 
                --text-muted: #64748b; 
                --border-light: rgba(0, 0, 0, 0.05); 
            }
            body { margin: 0; font-family: 'Inter', system-ui, -apple-system, sans-serif; color: var(--text-main); -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; background: var(--bg-gradient); min-height: 100vh; }
            * { box-sizing: border-box; }

            /* Layout & Animations */
            @keyframes cardFadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
            .page-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; }
            .auth-card { width: 100%; max-width: 28rem; padding: 3rem 2.5rem; background-color: var(--card-bg); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.01), 0 0 0 1px var(--border-light); border-radius: 1.25rem; backdrop-filter: blur(10px); animation: cardFadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

            /* Logo Styles */
            .logo-container { display: flex; justify-content: center; margin-bottom: 2rem; transition: transform 0.3s ease; }
            .logo-container:hover { transform: translateY(-3px) scale(1.02); }
            .logo-link { display: block; outline: none; }
            .logo-img { width: 6.5rem; height: 6.5rem; object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.06)); }

            /* Typography & Headings */
            .auth-heading { text-align: center; margin-bottom: 2.25rem; }
            .login-header { display: flex; flex-direction: column; gap: 0.35rem; }
            .login-title { font-size: 1.25rem; font-weight: 700; color: var(--primary-color); margin: 0; line-height: 1.2; }
            .login-subtitle { font-size: 0.95rem; color: var(--text-muted); margin: 0; font-weight: 400; line-height: 1.5; }
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
                            <h2 class="login-title">Welcome to CDA-ICT Helpdesk !</h2>
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
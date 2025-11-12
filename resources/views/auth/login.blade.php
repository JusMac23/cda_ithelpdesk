<x-guest-layout>

    <style>
        .cda-button {
            background-color: #2563eb;
            color: white;
            border: 1px solid #ccc;
            border-radius: .8rem;
            padding: 0.5rem 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.2s;
            width: 100%;
        }

        .cda-button:hover {
            background-color: #1e4fd1; 
            border-color: #6366f1; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        @media (min-width: 640px) {
            .cda-button {
                width: auto;
            }
        }
        .divider {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 20px;
        }

        .divider hr {
        flex: 1;
        border: none;
        border-top: 1px solid #ccc; /* Line color */
        }

        .divider span {
        margin: 0 10px;
        color: #555;
        font-size: 14px;
        font-weight: 500;
        }
    </style>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Login Form --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mb-2" 
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Remember Me + Forgot Password --}}
        <div class="flex items-center justify-between text-sm mt-2">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        name="remember">
                <span class="ms-2 text-gray-700">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" 
                class="text-indigo-600 hover:text-indigo-800 font-medium transition">
                {{ __('Forgot password?') }}
            </a>
            @endif
        </div>

        {{-- reCAPTCHA --}}
        <div class="w-full mt-4 mb-2">
            <div class="g-recaptcha"
                data-sitekey="{{ config('services.recaptcha.site_key') }}"
                data-callback="enableLoginButton"></div>

            @if ($errors->has('g-recaptcha-response'))
                <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2" />
            @endif
        </div>

        {{-- Login Button --}}
        <div>
            <x-primary-button id="login-button"
                class="w-full justify-center text-center py-3 rounded-xl transition duration-200"
                style="background-color: #cbd5e1; cursor: not-allowed; pointer-events: none; text-transform: none; font-size: 1rem;">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    {{-- Divider --}}
    <div class="divider">
        <hr>
            <span>OR</span>
        <hr>
    </div>

    {{-- Social Logins --}}
    <div class="space-y-3 mt-6">

        <div class="flex flex-col sm:flex-row justify-center">
            {{-- Authentik --}}
            <a href="{{ route('auth.authentik') }}" class="cda-button mb-2">
                <span class="font-medium">
                <i class="fa-solid fa-building-columns mr-2"></i> Continue with CDAOauth
                </span>
            </a>
            {{-- Google
            <a href="{{ route('auth.google') }}"
            class="w-full sm:w-auto flex items-center justify-center border border-gray-300 rounded-xl px-4 py-3 bg-white hover:border-indigo-400 hover:shadow-md transition duration-200">
                <img src="{{ asset('images/google-logo.png') }}" alt="Google Logo" class="h-4">
            </a>
            --}}
        </div>
    </div>

    {{-- Footer --}}
    <p class="text-center text-xs text-gray-500 mt-4">
        &copy; {{ date('Y') }} CDA ICTD. All rights reserved.
    </p>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#2563eb',
                    timer: 3000
                });
            });
        @endif

        @if(session('error'))
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Authentication Error',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#2563eb',
                });
            });
        @endif

        function enableLoginButton() {
            const button = document.getElementById('login-button');
            if (button) {
                button.style.backgroundColor = '#2563eb';
                button.style.cursor = 'pointer';
                button.style.pointerEvents = 'auto';
            }
        }
    </script>
</x-guest-layout>

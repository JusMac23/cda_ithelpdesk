<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* layout */
        .login-form-container { width: 100%; background: #ffffff; border-radius: 0.5rem; }

        /* standard inputs and labels */
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; color: #374151; font-size: 0.95rem; font-weight: 500; }
        .custom-input { width: 100%; padding: 0.85rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; display: block; box-sizing: border-box; font-family: inherit; font-size: 0.95rem; transition: all 0.2s ease; background-color: transparent; color: #374151; }
        .custom-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 1px #2563eb; }
        .custom-input::placeholder { color: #9ca3af; }

        /* password toggle styles */
        .password-wrapper { position: relative; display: block; }
        .password-wrapper .custom-input { padding-right: 2.5rem; /* make room for the icon */ }
        .toggle-password { position: absolute; top: 50%; right: 0.85rem; transform: translateY(-50%); cursor: pointer; color: #6b7280; font-size: 1rem; transition: color 0.2s; }
        .toggle-password:hover { color: #374151; }

        /* helpers */
        .flex-between{ display:flex;align-items:center;justify-content:space-between;font-size:.875rem;margin:.5rem 0 1.5rem; }
        .remember-wrap{ display:inline-flex;align-items:center;cursor:pointer; }
        .remember-wrap input[type=checkbox]{ border-radius:4px;border:1px solid #d1d5db;width:1rem;height:1rem;cursor:pointer; }

        /* links */
        .text-link{ color:#2563eb;text-decoration:none;font-weight:500;transition:color .2s ease; }
        .text-link:hover{ color:#1d4ed8;text-decoration:underline;}

        /* agreement text */
        .terms-text { font-size: .8rem; color: #6b7280; text-align: center; margin-bottom: 1.25rem; line-height: 1.5; }

        /* divider */
        .divider{ display:flex;align-items:center;text-align:center;margin:1.5rem 0;color:#9ca3af; }
        .divider hr{ flex:1;border:0;border-top:1px solid #e5e7eb; }
        .divider span{ padding:0 10px;font-size:.75rem;font-weight:600; }

        /* buttons */
        .btn-primary{ width:100%;padding:.75rem;border-radius:.5rem;border:none;color:#fff;font-size:1rem;font-weight:600;font-family:inherit;transition:all .2s;display:flex;justify-content:center;align-items:center; }
        .btn-primary:not(:disabled){ background:#2563eb;cursor:pointer;box-shadow:0 4px 6px -1px rgba(37,99,235,.2);}
        .btn-primary:not(:disabled):hover{ background:#1d4ed8;}
        .btn-primary:disabled{ background:#94a3b8;cursor:not-allowed;opacity:.7;}

        /* cda */
        .cda-button{ display:flex;align-items:center;justify-content:center;width:100%;padding:.75rem; margin-bottom:1rem;background:#fff;border:1px solid #d1d5db;border-radius:.5rem;text-decoration:none;color:#374151;font-weight:600;font-size:.95rem;transition:all .2s;box-shadow:0 1px 2px rgba(0,0,0,.05); }
        .cda-button:hover{ background:#f9fafb;border-color:#9ca3af;color:#111827; }

        /* misc */
        .error-text{ color:#ef4444;font-size:.875rem;margin-top:.5rem;display:block; }
        .footer-text{ text-align:center;font-size:.75rem;color:#6b7280;margin:2rem 0 0;}
    </style>

    <div class="login-form-container">
       
        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input id="email" class="custom-input" 
                    type="email" name="email" value="{{ old('email') }}" 
                    required autofocus autocomplete="username" 
                    placeholder="Enter your email" />
                
                @if ($errors->has('email'))
                    <span class="error-text">{{ $errors->first('email') }}</span>
                @endif
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="password-wrapper">
                    <input id="password" class="custom-input"
                        type="password" name="password" required autocomplete="current-password" 
                        placeholder="Enter your password" />
                    
                    {{-- Eye Icon Toggle --}}
                    <i class="fa-regular fa-eye toggle-password" id="togglePassword"></i>
                </div>

                @if ($errors->has('password'))
                    <span class="error-text">{{ $errors->first('password') }}</span>
                @endif
            </div>

            {{-- Remember Me + Forgot Password --}}
            <div class="flex-between">
                <label for="remember_me" class="remember-wrap">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span style="margin-left: 0.5rem; color: #4b5563;">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-link">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            {{-- Terms and Privacy Policy Agreement --}}
            <p class="terms-text">
                By signing in, you agree to our 
                <a href="https://cda.gov.ph/cda-privacy-policy/" class="text-link" target="_blank">Terms and Conditions</a> & 
                <a href="https://cda.gov.ph/cda-privacy-policy/" class="text-link" target="_blank">Privacy Policy</a>.
            </p>

            {{-- Sign in Button --}}
            <div>
                <button type="submit" id="login-button" class="btn-primary">
                    {{ __('Sign in') }}
                </button>
            </div>
        </form>

        {{-- Divider --}}
        <div class="divider">
            <hr>
            <span>OR</span>
            <hr>
        </div>

        {{-- CDAOauth Sign in --}}
        <div>
            <a href="{{ route('auth.authentik') }}" class="cda-button">
                Sign in with CDAOauth
            </a>
        </div>

        {{-- Google Sign in --}}
        <div>
            <a href="{{ route('auth.google') }}" class="cda-button">
                Sign in with Google
            </a>
        </div>

        {{-- Footer --}}
        <p class="footer-text">
            &copy; {{ date('Y') }} CDA-ICT Helpdesk. All rights reserved.
        </p>
    </div>

    <script src="/assets/js/sweetalert2.min.js"></script>
    
    <script>
        // Password Toggle Logic
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');

            if (togglePassword && password) {
                togglePassword.addEventListener('click', function () {
                    // Toggle the type attribute
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    
                    // Toggle the eye / eye-slash icon
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }
        });

        // SweetAlert Logic
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

        @if(session('error_swal'))
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'User not found',
                    text: '{{ session("error_message") }}',
                    confirmButtonColor: '#2563eb',
                });
            });
        @endif
    </script>
</x-guest-layout>
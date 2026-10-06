<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Session Status --}}
    @if (session('status'))
        <div class="mb-5 px-4 py-3 rounded-xl text-sm text-center font-medium"
             style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46;">
            {{ session('status') }}
        </div>
    @endif

    {{-- Username and Password Login Form --}}
    <form method="POST" action="{{ route('login') }}" class="mb-5">
        @csrf

        {{-- Username or Email --}}
        <div class="mb-4">
            <label for="username" class="block text-sm font-semibold text-slate-700 mb-1.5 tracking-[0.01em]">
                {{ __('Username') }}
            </label>
            <div class="relative flex items-center w-full">
                <i class="fa-regular fa-user absolute left-4 text-slate-400 text-sm pointer-events-none z-10
                           peer-focus:text-blue-600 transition-colors duration-200"></i>
                <input id="username"
                    type="text"
                    name="username"
                    value="{{ old('username', old('email')) }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Enter your username or email"
                    class="peer w-full pl-11 pr-4 py-2.5 border rounded-xl text-sm font-normal
                           text-slate-800 placeholder-slate-400 outline-none transition-all duration-200
                           bg-slate-50 border-slate-200
                           focus:bg-white focus:border-blue-500 focus:ring-3 focus:ring-blue-500/15
                           @if($errors->has('username') || $errors->has('email'))
                               border-red-400 bg-red-50 focus:ring-red-400/15 focus:border-red-400
                           @endif" />
            </div>
            @if ($errors->has('username'))
                <span class="flex items-center gap-1.5 mt-1.5 text-xs font-medium text-red-500">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $errors->first('username') }}
                </span>
            @elseif ($errors->has('email'))
                <span class="flex items-center gap-1.5 mt-1.5 text-xs font-medium text-red-500">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $errors->first('email') }}
                </span>
            @endif
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5 tracking-[0.01em]">
                {{ __('Password') }}
            </label>
            <div class="relative flex items-center w-full">
                <i class="fa-solid fa-lock absolute left-4 text-slate-400 text-sm pointer-events-none z-10"></i>
                <input id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your password"
                    class="peer w-full pl-11 pr-11 py-2.5 border rounded-xl text-sm font-normal
                           text-slate-800 placeholder-slate-400 outline-none transition-all duration-200
                           bg-slate-50 border-slate-200
                           focus:bg-white focus:border-blue-500 focus:ring-3 focus:ring-blue-500/15
                           @error('password') border-red-400 bg-red-50 focus:ring-red-400/15 focus:border-red-400 @enderror" />
                <button type="button"
                        id="togglePassword"
                        aria-label="Toggle password visibility"
                        class="absolute right-3 flex items-center justify-center p-1.5
                               text-slate-400 hover:text-slate-600 transition-colors duration-200 z-10
                               bg-transparent border-none cursor-pointer">
                    <i class="fa-regular fa-eye text-base" id="togglePasswordIcon"></i>
                </button>
            </div>
            @error('password')
                <span class="flex items-center gap-1.5 mt-1.5 text-xs font-medium text-red-500">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $message }}
                </span>
            @enderror
        </div>

        {{-- Remember Me & Forgot Password --}}
        <div class="flex justify-between items-center mt-2 mb-5 text-sm">
            <label for="remember_me" class="flex items-center gap-2 text-slate-500 cursor-pointer select-none font-medium">
                <input id="remember_me" type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-slate-300 accent-blue-600 cursor-pointer">
                <span>{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-blue-600 font-semibold text-[0.825rem] no-underline
                          transition-colors duration-200 hover:text-blue-800 hover:underline">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        {{-- Sign in Button --}}
        <button type="submit"
                id="login-button"
                class="flex items-center justify-center gap-2 w-full py-2.5 px-5 rounded-xl
                       text-white text-[0.95rem] font-semibold tracking-[0.01em] cursor-pointer
                       border border-white/10 transition-all duration-200 ease-out
                       focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                style="background:linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%);
                       box-shadow:0 4px 12px rgba(37,99,235,0.28);
                       font-family:inherit;"
                onmouseover="this.style.background='linear-gradient(135deg,#1d4ed8 0%,#1e40af 100%)';this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 16px rgba(37,99,235,0.38)';"
                onmouseout="this.style.background='linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%)';this.style.transform='';this.style.boxShadow='0 4px 12px rgba(37,99,235,0.28)';"
                onmousedown="this.style.transform='scale(0.98)';"
                onmouseup="this.style.transform='';">
            <span>{{ __('Sign in') }}</span>
            <i class="fa-solid fa-arrow-right-to-bracket"></i>
        </button>
    </form>

    {{-- Modern Divider Before SSO --}}
    <div class="flex items-center text-center my-5 text-slate-400 text-xs font-bold tracking-[0.08em] uppercase
                before:content-[''] before:flex-1 before:border-b before:border-slate-200
                after:content-['']  after:flex-1  after:border-b  after:border-slate-200">
        <span class="px-3">or sign in with</span>
    </div>

    {{-- SSO Action Buttons --}}
    <div class="flex flex-col gap-3 mb-7">

        {{-- CDAOauth Sign in --}}
        <a href="{{ route('auth.authentik') }}"
           class="flex items-center justify-center gap-3 w-full py-2.5 px-5 rounded-xl
                  font-semibold text-[0.95rem] text-white no-underline tracking-[0.01em]
                  transition-all duration-200 ease-out active:scale-[0.98]"
           style="background:linear-gradient(135deg,#1e40af 0%,#1d4ed8 100%);
                  border:1px solid rgba(255,255,255,0.1);
                  box-shadow:0 4px 12px rgba(30,64,175,0.25);"
           onmouseover="this.style.background='linear-gradient(135deg,#1e3a8a 0%,#1e40af 100%)';this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 16px rgba(30,64,175,0.38)';"
           onmouseout="this.style.background='linear-gradient(135deg,#1e40af 0%,#1d4ed8 100%)';this.style.transform='';this.style.boxShadow='0 4px 12px rgba(30,64,175,0.25)';">
            <i class="fa-solid fa-shield-halved"></i>
            <span>Sign in with CDAOauth</span>
        </a>

        {{-- Google Sign in --}}
        <a href="{{ route('auth.google') }}"
           class="flex items-center justify-center gap-3 w-full py-2.5 px-5 rounded-xl
                  font-semibold text-[0.95rem] text-slate-700 no-underline tracking-[0.01em]
                  border border-slate-200 transition-all duration-200 ease-out active:scale-[0.98]"
           style="background:#ffffff; box-shadow:0 2px 4px rgba(0,0,0,0.05);"
           onmouseover="this.style.background='#f8fafc';this.style.borderColor='#cbd5e1';this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 12px rgba(0,0,0,0.09)';"
           onmouseout="this.style.background='#ffffff';this.style.borderColor='';this.style.transform='';this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';">
            <i class="fa-brands fa-google text-lg" style="color:#ea4335;"></i>
            <span>Sign in with Google</span>
        </a>
    </div>

    {{-- Terms and Privacy Policy Agreement --}}
    <p class="text-[0.825rem] text-slate-500 leading-relaxed text-center mb-6">
        By signing in, you agree to our
        <a href="https://cda.gov.ph/cda-privacy-policy/"
           class="text-blue-600 font-semibold no-underline border-b border-transparent
                  transition-all duration-200 hover:text-blue-800 hover:border-blue-800"
           target="_blank">Terms and Conditions</a> &amp;
        <a href="https://cda.gov.ph/cda-privacy-policy/"
           class="text-blue-600 font-semibold no-underline border-b border-transparent
                  transition-all duration-200 hover:text-blue-800 hover:border-blue-800"
           target="_blank">Privacy Policy</a>.
    </p>

    {{-- Footer --}}
    <p class="text-xs text-center text-slate-400 font-medium m-0">
        &copy; {{ date('Y') }} CDA-ICT Helpdesk. All rights reserved.
    </p>

    <script src="/assets/js/sweetalert2.min.js"></script>

    <script>
        // Toggle Password Visibility
        document.addEventListener('DOMContentLoaded', function () {
            const togglePasswordBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            if (togglePasswordBtn && passwordInput && toggleIcon) {
                togglePasswordBtn.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    toggleIcon.classList.toggle('fa-eye', !isPassword);
                    toggleIcon.classList.toggle('fa-eye-slash', isPassword);
                });
            }
        });

        // SweetAlert Logic for OAuth feedbacks
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
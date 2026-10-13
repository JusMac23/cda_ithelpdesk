<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Session Status --}}
    @if (session('status'))
        <div class="mb-5 px-4 py-3 rounded-xl text-sm text-center font-medium"
             style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46;">
            {{ session('status') }}
        </div>
    @endif

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

        {{-- Separator --}}
        <div class="flex items-center text-center my-1 text-slate-400 text-xs font-bold tracking-[0.08em] uppercase
                    before:content-[''] before:flex-1 before:border-b before:border-slate-200
                    after:content-[''] after:flex-1 after:border-b after:border-slate-200">
            <span class="px-3">or</span>
        </div>

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
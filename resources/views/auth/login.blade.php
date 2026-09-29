<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    
    <style>
        /* SSO Action Container */
        .auth-actions { display: flex; flex-direction: column; gap: 0.85rem; margin-bottom: 1.75rem; }

        /* Modern SSO Buttons Base */
        .btn-sso { display: flex; align-items: center; justify-content: center; gap: 0.75rem; width: 100%; padding: 0.575rem 1.25rem; border-radius: 0.75rem; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; letter-spacing: 0.01em; }
        .btn-sso:active { transform: scale(0.98); }

        /* Primary SSO Button (CDAOauth) */
        .btn-cda { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25); }
        .btn-cda:hover { background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%); transform: translateY(-2px); box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35); }

        /* Secondary SSO Button (Google) */
        .btn-google { background: #ffffff; color: #374151; border: 1px solid #e5e7eb; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04); }
        .btn-google:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
        .btn-google .google-icon { color: #ea4335; font-size: 1.1rem; }

        /* Modern Divider Style */
        .auth-divider { display: flex; align-items: center; text-align: center; margin: 0.5rem 0; color: #94a3b8; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }
        .auth-divider::before, .auth-divider::after { content: ''; flex: 1; border-bottom: 1px solid #e2e8f0; }
        .auth-divider span { padding: 0 0.75rem; }

        /* Terms & Legal Links */
        .terms-text { font-size: 0.825rem; color: #64748b; line-height: 1.5; text-align: center; margin-bottom: 1.75rem; }
        .text-link { color: #2563eb; text-decoration: none; font-weight: 600; transition: color 0.2s ease; border-bottom: 1px solid transparent; }
        .text-link:hover { color: #1d4ed8; border-bottom-color: #1d4ed8; text-decoration: none; }

        /* Footer Copyright */
        .footer-text { font-size: 0.75rem; text-align: center; color: #94a3b8; margin: 0; font-weight: 500; }
    </style>

    {{-- SSO Action Buttons --}}
    <div class="auth-actions">
        {{-- CDAOauth Sign in --}}
        <a href="{{ route('auth.authentik') }}" class="btn-sso btn-cda">
            <i class="fa-solid fa-shield-halved"></i>
            <span>Sign in with CDAOauth</span>
        </a>

        <div class="auth-divider">
            <span>or</span>
        </div>

        {{-- Google Sign in --}}
        <a href="{{ route('auth.google') }}" class="btn-sso btn-google">
            <i class="fa-brands fa-google google-icon"></i>
            <span>Sign in with Google</span>
        </a>
    </div>

    {{-- Terms and Privacy Policy Agreement --}}
    <p class="terms-text">
        By signing in, you agree to our
        <a href="https://cda.gov.ph/cda-privacy-policy/" class="text-link" target="_blank">Terms and Conditions</a> & 
        <a href="https://cda.gov.ph/cda-privacy-policy/" class="text-link" target="_blank">Privacy Policy</a>.
    </p>

    {{-- Footer --}}
    <p class="footer-text">
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
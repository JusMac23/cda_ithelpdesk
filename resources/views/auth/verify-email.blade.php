<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* layout */
        .verify-email-container{width:100%;}

        /* text */
        .instruction-text{font-size:.875rem;color:#4b5563;margin-bottom:1.5rem;line-height:1.6;}

        /* alerts */
        .success-alert{font-size:.875rem;font-weight:500;color:#059669;margin-bottom:1.5rem;padding:.75rem 1rem;background:#ecfdf5;border-radius:.5rem;border:1px solid #a7f3d0;display:flex;align-items:flex-start;gap:.5rem;}

        /* actions */
        .action-buttons{display:flex;align-items:center;justify-content:space-between;margin-top:2rem;flex-wrap:wrap;gap:1rem;}

        /* buttons */
        .btn-primary{padding:.65rem 1.25rem;border-radius:.5rem;border:none;color:#fff;font-size:.875rem;font-weight:600;font-family:inherit;transition:all .2s;cursor:pointer;background:#2563eb;display:inline-flex;align-items:center;box-shadow:0 4px 6px -1px rgba(37,99,235,.2);}
        .btn-primary:hover{background:#1d4ed8;}
        .btn-link{background:none;border:none;padding:0;font-size:.875rem;color:#4b5563;text-decoration:underline;cursor:pointer;font-family:inherit;transition:color .2s;}
        .btn-link:hover{color:#111827;}
    </style>

    <div class="verify-email-container">
        
        <p class="instruction-text">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="success-alert">
                <i class="fa-solid fa-circle-check" style="margin-top: 0.15rem;"></i>
                <div>
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            </div>
        @endif

        <div class="action-buttons">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-primary">
                    {{ __('Resend Verification Email') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-link">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
        
    </div>
</x-guest-layout>
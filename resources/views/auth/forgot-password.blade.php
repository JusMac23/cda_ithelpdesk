<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* layout */
        .forgot-password-container{width:100%;}

        /* text */
        .instruction-text{font-size:.875rem;color:#4b5563;margin-bottom:1.5rem;line-height:1.5;text-align:center;}
        .error-text{color:#ef4444;font-size:.875rem;margin-top:.5rem;display:block;}

        /* form */
        .form-group{margin-bottom:1.25rem;}
        .form-group label{display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:.35rem;}
        .custom-input{width:100%;padding:.65rem .75rem;border:1px solid #d1d5db;border-radius:.5rem;display:block;box-sizing:border-box;font-family:inherit;font-size:.95rem;transition:all .2s ease;}
        .custom-input:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.2);}

        /* buttons */
        .btn-primary{width:100%;padding:.75rem;border-radius:.5rem;border:none;color:#fff;font-size:1rem;font-weight:600;font-family:inherit;transition:all .2s;cursor:pointer;background:#2563eb;margin-top:1rem;display:flex;justify-content:center;align-items:center;box-shadow:0 4px 6px -1px rgba(37,99,235,.2);}
        .btn-primary:hover{background:#1d4ed8;}

        /* links */
        .back-link-container{text-align:center;margin-top:1.5rem;}
        .text-link{color:#2563eb;text-decoration:none;font-size:.875rem;font-weight:500;transition:color .2s ease;}
        .text-link:hover{color:#1d4ed8;text-decoration:underline;}
    </style>

    <div class="forgot-password-container">
        
        <p class="instruction-text">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </p>

        <x-auth-session-status class="mb-4" style="color: #059669; font-size: 0.875rem; text-align: center; margin-bottom: 1rem;" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <x-input-label for="email" :value="__('Email')" />
                <input id="email" class="custom-input" 
                    type="email" name="email" value="{{ old('email') }}" 
                    required autofocus placeholder="Enter your email address" />
                
                @if ($errors->has('email'))
                    <span class="error-text">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div>
                <button type="submit" class="btn-primary">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>
            
            <div class="back-link-container">
                <a href="{{ route('login') }}" class="text-link">
                    <i class="fa-solid fa-arrow-left" style="margin-right: 0.25rem;"></i> Back to Login
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
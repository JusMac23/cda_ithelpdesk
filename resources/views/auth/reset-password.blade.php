<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* layout */
        .reset-password-container{width:100%;}

        /* form */
        .form-group{margin-bottom:1.25rem;}
        .form-group label{display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:.35rem;}
        .custom-input{width:100%;padding:.65rem .75rem;border:1px solid #d1d5db;border-radius:.5rem;display:block;box-sizing:border-box;font-family:inherit;font-size:.95rem;transition:all .2s ease;}
        .custom-input:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.2);}

        /* buttons */
        .btn-primary{width:100%;padding:.75rem;border-radius:.5rem;border:none;color:#fff;font-size:1rem;font-weight:600;font-family:inherit;transition:all .2s;cursor:pointer;background:#2563eb;margin-top:1.5rem;display:flex;justify-content:center;align-items:center;box-shadow:0 4px 6px -1px rgba(37,99,235,.2);}
        .btn-primary:hover{background:#1d4ed8;}

        /* misc */
        .error-text{color:#ef4444;font-size:.875rem;margin-top:.5rem;display:block;}
    </style>

    <div class="reset-password-container">
        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group">
                <x-input-label for="email" :value="__('Email')" />
                <input id="email" class="custom-input" 
                    type="email" name="email" value="{{ old('email', $request->email) }}" 
                    required autofocus autocomplete="username" />
                
                @if ($errors->has('email'))
                    <span class="error-text">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div class="form-group">
                <x-input-label for="password" :value="__('Password')" />
                <input id="password" class="custom-input" 
                    type="password" name="password" 
                    required autocomplete="new-password" placeholder="Enter new password" />
                
                @if ($errors->has('password'))
                    <span class="error-text">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="form-group">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <input id="password_confirmation" class="custom-input" 
                    type="password" name="password_confirmation" 
                    required autocomplete="new-password" placeholder="Confirm new password" />
                
                @if ($errors->has('password_confirmation'))
                    <span class="error-text">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </div>

            <div>
                <button type="submit" class="btn-primary">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
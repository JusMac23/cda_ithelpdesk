<section>
    <style>
        /* --- Theme Variables --- */
        :root {
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
            --input-text: #334155;
            --readonly-bg: #f8fafc;
            --text-dark: #0f172a;
            --text-muted: #475569;
        }

        body.dark {
            /* Elevated input backgrounds and brighter borders for visibility */
            --input-bg: #1e293b; 
            --input-border: #64748b; 
            --input-text: #f1f5f9;
            --readonly-bg: #0f172a;
            --text-dark: #f8fafc;
            --text-muted: #9ca3af;
        }

        /* Section Header */ 
        .section-header h2 { font-size: 1.25rem; font-weight: 700; color: var(--text-dark); margin-bottom: 0.5rem; transition: color 0.3s ease; }
        .section-header p { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0; font-weight: 500; transition: color 0.3s ease; }
        
        /* Form Spacing */ 
        .form-spacing { margin-top: 2rem; display: flex; flex-direction: column; gap: 1.5rem; }
        
        /* Form Group */ 
        .form-group { display: flex; flex-direction: column; }
        
        /* Form Label */ 
        .form-label { font-size: 0.875rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.5rem; display: block; transition: color 0.3s ease; }
        
        /* Form Input - Unified 44px Height & Dynamic Outline */ 
        .form-input { height: 44px; width: 100%; padding: 0 1rem; border: 1px solid var(--input-border); border-radius: 0.5rem; font-size: 0.95rem; color: var(--input-text); background-color: var(--input-bg); transition: all 0.2s ease; font-family: inherit; }
        
        /* Form Input Focus */ 
        .form-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }
        body.dark .form-input:focus { border-color: #818cf8; box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.25); }
        
        /* Readonly Input */ 
        .form-input[readonly] { background-color: var(--readonly-bg); color: var(--text-muted); cursor: not-allowed; border-color: var(--input-border); opacity: 0.7; }
        .form-input[readonly]:focus { box-shadow: none; border-color: var(--input-border); }
        
        /* Primary Button - Unified Styling */ 
        .btn-primary { display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 2rem; border-radius: 0.5rem; font-size: 0.95rem; font-weight: 600; cursor: pointer; border: none; color: white; background-color: #4f46e5; transition: all 0.2s ease; font-family: inherit; box-shadow: 0 1px 2px rgba(79, 70, 229, 0.2); }
        .btn-primary:hover { background-color: #4338ca; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
        .btn-primary:active { transform: translateY(0); box-shadow: 0 1px 2px rgba(79, 70, 229, 0.2); }
        
        /* Form Actions */ 
        .form-actions { display: flex; align-items: center; gap: 1rem; margin-top: 0.5rem; }
        
        /* Success Text */ 
        .text-success { font-size: 0.875rem; color: #10b981; font-weight: 600; margin-top: 0.75rem; transition: color 0.3s ease; }
        body.dark .text-success { color: #34d399; }
        
        /* Error Text */ 
        .text-error { font-size: 0.875rem; color: #ef4444; margin-top: 0.5rem; font-weight: 500; transition: color 0.3s ease; }
        body.dark .text-error { color: #f87171; }
        
        /* Saved Text */ 
        .text-saved { font-size: 0.9rem; font-weight: 500; color: var(--text-muted); transition: color 0.3s ease; }
        
        /* Link Text */ 
        .link-text { font-size: 0.875rem; color: #4f46e5; text-decoration: underline; cursor: pointer; background: none; border: none; padding: 0; margin-top: 0.5rem; font-weight: 500; transition: color 0.2s; }
        .link-text:hover { color: #3730a3; }
        body.dark .link-text { color: #818cf8; }
        body.dark .link-text:hover { color: #a5b4fc; }
        
        /* Mobile Button Stretch */ 
        @media (max-width: 640px) { 
            .form-actions { flex-direction: column; align-items: stretch; } 
            .btn-primary { width: 100%; } 
            .text-saved { text-align: center; }
        }
    </style>

    <header class="section-header">
        <h2>{{ __('Profile Information') }}</h2>
        <p>{{ __("Update your account's profile information and email address.") }}</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="form-spacing">
        @csrf
        @method('patch')

        <div class="form-group">
            <x-input-label for="name" :value="__('Name')" class="form-label" />
            <x-text-input id="name" name="name" type="text" class="form-input" 
                :value="old('name', $user->name)" 
                autofocus autocomplete="name" 
                :readonly="!auth()->user()->hasRole('Super Admin')" />
            <x-input-error class="text-error" :messages="$errors->get('name')" />
        </div>

        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" class="form-label" />
            <x-text-input id="email" name="email" type="email" class="form-input" 
                :value="old('email', $user->email)" 
                required autocomplete="username" 
                :readonly="!auth()->user()->hasRole('Super Admin')" /> 
            <x-input-error class="text-error" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2" style="color: var(--text-muted); font-weight: 500; transition: color 0.3s ease;">
                        {{ __('Your email address is unverified.') }}

                        {{-- Only show resend link if user is Super Admin --}}
                        @if(auth()->user()->hasRole('Super Admin'))
                        <button form="send-verification" class="link-text">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                        @endif
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="text-success">
                            <i class="fas fa-check-circle" style="margin-right: 0.25rem;"></i> {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
        
        <div class="form-actions">
            @can('edit_profile')
            <button type="submit" class="btn-primary">
                {{ __('Save Changes') }}
            </button>
            @endcan

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-saved"
                ><i class="fas fa-check" style="margin-right: 0.25rem; color: #10b981;"></i> {{ __('Saved successfully.') }}</p>
            @endif
        </div>
    </form>
</section>
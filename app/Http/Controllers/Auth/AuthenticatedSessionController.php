<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request with reCAPTCHA verification.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate credentials and reCAPTCHA
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                        'secret' => config('services.recaptcha.secret_key'),
                        'response' => $value,
                        'remoteip' => $request->ip(),
                    ]);

                    if (!($response->json()['success'] ?? false)) {
                        $fail('CAPTCHA verification failed.');
                    }
                },
            ],
        ]);

        // Attempt to authenticate the user
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Regenerate session on successful login
        $request->session()->regenerate();

        $user = Auth::user();

        // Role-based redirection
        if ($user->hasRole('Super Admin')) {

            return redirect()->route('dashboard');

        } elseif ($user->hasRole('Admin')) {

            return redirect()->route('assignedtome_tickets.index');

        } elseif ($user->hasRole('DPO')) {

            return redirect()->route('databreach.index');

        } elseif ($user->hasRole('DBRT')) {

            return redirect()->route('databreach.index');
        }

        // Default fallback
        return redirect()->route('login');
    }

    /**
     * Destroy an authenticated session (logout).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

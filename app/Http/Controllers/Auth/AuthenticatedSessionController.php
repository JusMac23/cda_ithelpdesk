<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
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
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // DESTROY ANY EXISTING SESSION BEFORE PROCESSING LOGIN
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Support either 'username' or 'email' input
        $loginInput = trim($request->input('username') ?? $request->input('email') ?? '');

        // Validate credentials
        $request->validate([
            'username' => ['nullable', 'string'],
            'email'    => ['nullable', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (empty($loginInput)) {
            throw ValidationException::withMessages([
                'username' => 'The username or email field is required.',
            ]);
        }

        // Determine if input is email format or name/username
        $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL);
        $primaryField = $isEmail ? 'email' : 'name';
        $secondaryField = $isEmail ? 'name' : 'email';
        $remember = $request->boolean('remember');

        // First attempt with the inferred field
        $authenticated = Auth::attempt([$primaryField => $loginInput, 'password' => $request->password], $remember);

        // If not authenticated, attempt with the fallback field
        if (!$authenticated) {
            $authenticated = Auth::attempt([$secondaryField => $loginInput, 'password' => $request->password], $remember);
        }

        // If database table has a username column, check it as well
        if (!$authenticated && Schema::hasColumn('users', 'username')) {
            $authenticated = Auth::attempt(['username' => $loginInput, 'password' => $request->password], $remember);
        }

        if (!$authenticated) {
            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
                'email'    => __('auth.failed'),
            ]);
        }

        // REGENERATE SESSION ONCE AUTHENTICATED
        $request->session()->regenerate();

        $user = Auth::user();

        // Role-based redirection
        if ($user->hasRole('Super Admin')) {
            return redirect()->route('overview_tickets.index');
        } elseif ($user->hasRole('Client')) {
            return redirect()->route('myrequested_tickets.index');
        } elseif ($user->hasAnyRole(['ICTD', 'ICTS', 'ICTS Admin'])) {
            return redirect()->route('tickets.index');    
        } elseif ($user->hasAnyRole(['DPO', 'DBRT'])) {
            return redirect()->route('databreach.index');
        }

        // Default fallback (Avoid redirecting authenticated users to 'login')
        return redirect('/');
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
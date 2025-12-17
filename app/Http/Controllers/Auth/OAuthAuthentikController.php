<?php  

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Schema;

class OAuthAuthentikController extends Controller
{
    // Redirect to Authentik OAuth
    public function redirectToAuthentik()
    {
        return Socialite::driver('authentik')->redirect();
    }

    // Handle Authentik callback
    public function handleAuthentikCallback(Request $request)
    {
        try {
            $authentikUser = Socialite::driver('authentik')->user();

            // Log received data
            \Log::info('Authentik User Data:', [
                'id'       => $authentikUser->getId(),
                'name'     => $authentikUser->getName(),
                'email'    => $authentikUser->getEmail(),
                'nickname' => $authentikUser->getNickname(),
            ]);

            // -----------------------------
            // FIND EXISTING USER ONLY
            // -----------------------------
            $user = null;

            // Check authentik_id
            if (Schema::hasColumn('users', 'authentik_id')) {
                $user = User::where('authentik_id', $authentikUser->getId())->first();
            }

            // Check by email if not found
            if (!$user && $authentikUser->getEmail()) {
                $user = User::where('email', $authentikUser->getEmail())->first();
            }

            // ----------------------------------------------------
            // BLOCK LOGIN → USER MUST BE CREATED BY SUPER ADMIN
            // ----------------------------------------------------
            if (!$user) {
                \Log::warning("Authentik Login Failed: NO USER FOUND → " . $authentikUser->getEmail());

                return redirect('/login')->with([
                    'error_swal' => true,
                    'error_message' => 'No user found. Please contact the administrator.'
                ]);
            }

            // ----------------------------------------------------
            // EXISTING USER → UPDATE BASIC INFO ONLY
            // ----------------------------------------------------
            $needsSave = false;

            if (Schema::hasColumn('users', 'authentik_id') && !$user->authentik_id) {
                $user->authentik_id = $authentikUser->getId();
                $needsSave = true;
            }

            if ($authentikUser->getName() && $user->name !== $authentikUser->getName()) {
                $user->name = $authentikUser->getName();
                $needsSave = true;
            }

            if ($needsSave) {
                $user->save();
                \Log::info("Updated user information for " . $user->email);
            }

            // ----------------------------------------------------
            // LOGIN USER
            // ----------------------------------------------------
            Auth::guard('web')->login($user);
            $request->session()->regenerate();

            $roles = $user->getRoleNames()->implode(', ');
            \Log::info("User {$user->email} logged in with roles: {$roles}");

            // ----------------------------------------------------
            // ROLE-BASED REDIRECTION
            // ----------------------------------------------------
            if ($user->hasRole('Super Admin')) {
                return redirect()->route('dashboard');
            }

            if ($user->hasRole('User')) {
                return redirect()->route('assignedtome_tickets.index');
            }

            if ($user->hasRole('DPO') || $user->hasRole('DBRT')) {
                return redirect()->route('databreach.index');
            }

            // No valid role
            \Log::warning("User {$user->email} has no valid role.");
            return redirect()->route('login')->with('error', 'No valid role assigned to this account.');

        } catch (\Exception $e) {
            \Log::error('Authentik authentication failed: ' . $e->getMessage());

            return redirect('/login')->with([
                'error_swal' => true,
                'error_message' => 'Authentik authentication failed: ' . $e->getMessage()
            ]);
        }
    }
}

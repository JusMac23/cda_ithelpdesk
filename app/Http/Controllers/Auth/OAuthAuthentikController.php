<?php 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role; 
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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

            // Debug: Log the Authentik user data
            \Log::info('Authentik User Data:', [
                'id' => $authentikUser->getId(),
                'name' => $authentikUser->getName(),
                'email' => $authentikUser->getEmail(),
                'nickname' => $authentikUser->getNickname(),
            ]);

            // Check if user exists by authentik_id first
            $user = null;
            $isExistingUser = false;
            if (Schema::hasColumn('users', 'authentik_id')) {
                $user = User::where('authentik_id', $authentikUser->getId())->first();
            }

            // If not found by authentik_id, check by email
            if (!$user && $authentikUser->getEmail()) {
                $user = User::where('email', $authentikUser->getEmail())->first();
                if ($user) {
                    $isExistingUser = true;
                    \Log::info('Found EXISTING user by email: ' . $user->email . ' with roles: ' . $user->getRoleNames()->implode(', '));
                }
            }

            // Get the default "Admin" role for new users only
            try {
                $defaultUserRole = Role::where('name', 'Admin')->first();
                
                if (!$defaultUserRole) {
                    $defaultUserRole = Role::create(['name' => 'Admin']);
                    \Log::info('Created new User role with ID: ' . $defaultUserRole->id);
                }
                
            } catch (\Exception $roleException) {
                \Log::error('Role handling failed: ' . $roleException->getMessage());
                return redirect('/login')->with('error', 'Role configuration error: ' . $roleException->getMessage());
            }

            if ($user) {
                // EXISTING USER: Preserve existing roles only
                $existingRoles = $user->getRoleNames();
                \Log::info('Processing EXISTING user: ' . $user->email . ' with current roles: ' . $existingRoles->implode(', '));
                
                // Update user information if needed
                $needsSave = false;
                if (Schema::hasColumn('users', 'authentik_id') && !$user->authentik_id) {
                    $user->authentik_id = $authentikUser->getId();
                    $needsSave = true;
                    \Log::info('Updated authentik_id for existing user');
                }
                
                // Update name if it's different
                if ($authentikUser->getName() && $user->name !== $authentikUser->getName()) {
                    $user->name = $authentikUser->getName();
                    $needsSave = true;
                    \Log::info('Updated name for existing user');
                }
                
                if ($needsSave) {
                    $user->save();
                }
                
                // CRITICAL: For existing users, REMOVE any default User role if they already have other roles
                if ($existingRoles->count() > 0 && $existingRoles->contains('Admin')) {
                    // If user has both Admin and User roles, remove the User role
                    if ($existingRoles->count() > 1) {
                        try {
                            $user->removeRole($defaultUserRole);
                            \Log::info('REMOVED User role from existing user to prevent duplication: ' . $user->email);
                        } catch (\Exception $e) {
                            \Log::error('Failed to remove User role: ' . $e->getMessage());
                        }
                    }
                }
                
            } else {
                // NEW USER: Create and assign default User role
                $email = $authentikUser->getEmail();
                
                // If no email from Authentik, create a placeholder email using username
                if (!$email) {
                    $username = $authentikUser->getNickname() ?: $authentikUser->getName() ?: Str::slug($authentikUser->getId());
                    $email = $username . '@authentik.local';
                }

                // Prepare user data
                $userData = [
                    'name' => $authentikUser->getName() ?: $authentikUser->getNickname() ?: 'Authentik User',
                    'email' => $email,
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(),
                ];
                
                // Add authentik_id if the column exists
                if (Schema::hasColumn('users', 'authentik_id')) {
                    $userData['authentik_id'] = $authentikUser->getId();
                }
                
                // Add role value if the column exists and is required
                if (Schema::hasColumn('users', 'role')) {
                    $userData['role'] = $defaultUserRole->id;
                    \Log::info('Added role to user data for database column');
                }
                
                // Create new user
                $user = User::create($userData);
                \Log::info('Created NEW user: ' . $user->email);
                
                // Assign default User role to NEW users only
                try {
                    $user->assignRole($defaultUserRole);
                    \Log::info('Assigned default User role to NEW Authentik user');
                } catch (\Exception $assignException) {
                    \Log::error('Role assignment failed for new user: ' . $assignException->getMessage());
                }
            }
            
            // FINAL ROLE CHECK: Ensure no role duplication
            $finalRoles = $user->getRoleNames();
            \Log::info('FINAL role check for ' . $user->email . ': ' . $finalRoles->implode(', '));
            
            // If user has multiple roles including User, remove the User role
            if ($finalRoles->count() > 1 && $finalRoles->contains('Admin')) {
                try {
                    $user->removeRole($defaultUserRole);
                    \Log::info('REMOVED User role due to multiple roles detected');
                    $finalRoles = $user->getRoleNames();
                    \Log::info('Roles after cleanup: ' . $finalRoles->implode(', '));
                } catch (\Exception $e) {
                    \Log::error('Failed to remove User role during cleanup: ' . $e->getMessage());
                }
            }

            // Login and regenerate session
            Auth::guard('web')->login($user);
            $request->session()->regenerate();

            // Get current roles for final logging
            $userRoles = $user->getRoleNames();
            \Log::info('Admin ' . $user->email . ' logged in with FINAL roles: ' . $userRoles->implode(', '));

            // Redirect based on user's actual roles
            if ($user->hasRole('Super Admin')) {

            return redirect()->route('dashboard');
                \Log::info('Redirecting to Super Admin');

            } elseif ($user->hasRole('Admin')) {
                \Log::info('Redirecting to Assigned Tickets');
                return redirect()->route('assignedtome_tickets.index');

            }elseif ($user->hasRole('DPO')) {
                \Log::info('Redirecting to Assigned Tickets');
                return redirect()->route('databreach.index');

            }elseif ($user->hasRole('DBRT')) {
                \Log::info('Redirecting to Assigned Tickets');
                return redirect()->route('databreach.index');
            }

            // Fallback if no recognizable role
            \Log::warning('User has no recognizable role: ' . $user->email);
            return redirect()->route('login')->with('error', 'No valid role assigned to this account.');

        } catch (\Exception $e) {
            \Log::error('Authentik authentication failed: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Authentik authentication failed: ' . $e->getMessage());
        }
    }
}
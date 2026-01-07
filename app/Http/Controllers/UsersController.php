<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use App\Models\User;

class UsersController extends Controller
{
    // Display All User
    public function index(Request $request)
    {
        $query = User::query()->with(['roles.permissions'])
            ->select('users.*')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id');

        if ($request->filled('search_query')) {
            $search = $request->search_query;

            $query->where(function ($q) use ($search) {
                $q->where('users.id', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhere('users.contact_number', 'like', "%{$search}%")
                    ->orWhere('users.created_at', 'like', "%{$search}%")
                    ->orWhere('users.updated_at', 'like', "%{$search}%");
            })
            ->orWhereHas('roles', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $query->orderBy('roles.id', 'asc');

        if (auth()->user()->hasRole('Super Admin')) {
            $roles = Role::all(); 
        } else {
            $roles = Role::where('name', '!=', 'Super Admin')->get(); 
        }

        $users = $query->paginate(20)->appends($request->only('search_query'));

        return view('users.index', compact('users', 'roles'));
    }

    // Add New User
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'contact_number' => 'required|string|max:15|unique:users,contact_number',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($validated['role']);

        $superAdminExists = User::whereHas('roles', function ($q) {
            $q->where('name', 'Super Admin');
        })->exists();

        if ($role->name === 'Super Admin' && $superAdminExists) {
            return back()->withErrors(['role' => 'A Super Admin already exists.']);
        }

        if ($role->name === 'Super Admin' && !auth()->user()->hasRole('Super Admin')) {
            return back()->withErrors(['role' => 'You are not authorized to assign Super Admin role.']);
        }

        $user = User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'contact_number' => $validated['contact_number'],
            'password'   => bcrypt($validated['password']),
            'role'       => $role->id,
            'created_at' => Carbon::now('Asia/Manila'),
        ]);

        $user->syncRoles([$role->name]);

        return redirect()->route('users.index')->with('success', 'User successfully added.');
    }

    // Update User
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
            return back()->withErrors(['error' => 'You are not authorized to edit the Super Admin.']);
        }

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id),
            ],
            'contact_number' => [
                'required',
                'string',
                'max:15',
                Rule::unique('users')->ignore($id),
            ],
            'role'  => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($validated['role']);

        $anotherSuperAdminExists = User::where('id', '!=', $id)
            ->whereHas('roles', function ($q) {
                $q->where('name', 'Super Admin');
            })
            ->exists();

        if ($role->name === 'Super Admin' && $anotherSuperAdminExists) {
            return back()->withErrors(['role' => 'Only one Super Admin can exist.']);
        }

        if ($role->name === 'Super Admin' && !auth()->user()->hasRole('Super Admin')) {
            return back()->withErrors(['role' => 'You are not authorized to assign Super Admin role.']);
        }

        $user->update([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'contact_number' => $validated['contact_number'],
            'role'       => $role->id, 
            'updated_at' => Carbon::now('Asia/Manila'),
        ]);

        $user->syncRoles([$role->name]);

        return redirect()->route('users.index')->with('success', 'User successfully updated.');
    }

    // Delete User
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('Super Admin')) {
            return back()->withErrors(['error' => 'Super Admin cannot be deleted.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}

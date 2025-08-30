<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleManagementController extends Controller
{
    /**
     * Display role management dashboard
     */
    public function index()
    {
        $roles = Role::with('permissions')->get(); // Fetch roles with their permissions
        $permissions = Permission::all();
        $users = User::with('roles')->get(); // Fetch users with their roles
        
        return view('admin.role-management.index', compact('roles', 'permissions', 'users')); // 
    }

    /**
     * Show form to create a new role
     */
    public function createRole()
    {
        $permissions = Permission::all();
        return view('admin.role-management.create-role', compact('permissions'));
    }

    /**
     * Store a new role
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.role-management.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Show form to edit a role
     */
    public function editRole(Role $role)
    {
        $permissions = Permission::all();
        return view('admin.role-management.edit-role', compact('role', 'permissions'));
    }

    /**
     * Update a role
     */
    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.role-management.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Delete a role
     */
    public function deleteRole(Role $role)
    {
        if ($role->name === 'admin') {
            return redirect()->route('admin.role-management.index')
                ->with('error', 'Cannot delete admin role.');
        }

        $role->delete();

        return redirect()->route('admin.role-management.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Show form to assign roles to users
     */
    public function assignRoles()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        
        return view('admin.role-management.assign-roles', compact('users', 'roles'));
    }

    /**
     * Update user roles
     */
    public function updateUserRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array',
        ]);

        $user->syncRoles($request->roles ?? []);

        return redirect()->route('admin.role-management.assign-roles')
            ->with('success', 'User roles updated successfully.');
    }
}

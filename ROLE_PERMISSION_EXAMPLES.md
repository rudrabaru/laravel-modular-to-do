# Laravel Spatie Roles & Permissions Implementation

This document provides examples and usage patterns for the enhanced Laravel 12 application with Spatie Roles & Permissions.

## Overview

The application now supports three main roles:
- **Admin**: Full system access with role and permission management
- **Manager**: Limited admin access for task and user management
- **User**: Basic access for personal task management

## Database Seeder Example

```php
// database/seeders/RolesAndPermissionsSeeder.php

// Create permissions
$permissions = [
    'view-admin-dashboard',
    'view-manager-dashboard', 
    'view-user-dashboard',
    'view-users',
    'create-users',
    'edit-users',
    'delete-users',
    'assign-roles',
    'manage-roles',
    'manage-permissions',
    'view-all-tasks',
    'view-own-tasks',
    'create-tasks',
    'edit-tasks',
    'delete-tasks',
    'assign-tasks',
    'complete-tasks',
    // ... more permissions
];

// Create roles with specific permissions
$adminRole = Role::create(['name' => 'admin']);
$adminRole->givePermissionTo(Permission::all()); // Admin gets all permissions

$managerRole = Role::create(['name' => 'manager']);
$managerRole->givePermissionTo([
    'view-manager-dashboard',
    'view-users',
    'view-all-tasks',
    'create-tasks',
    'edit-tasks',
    'delete-tasks',
    'assign-tasks',
    'complete-tasks',
    // ... limited permissions
]);

$userRole = Role::create(['name' => 'user']);
$userRole->givePermissionTo([
    'view-user-dashboard',
    'view-own-tasks',
    'create-tasks',
    'edit-tasks',
    'complete-tasks',
    // ... basic permissions
]);
```

## Route Examples with Role-Based Access

```php
// routes/web.php

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard.index');
});

// Manager routes  
Route::middleware(['auth', 'verified', 'role:manager'])->group(function () {
    Route::get('/manager/dashboard', [ManagerDashboardController::class, 'index'])->name('manager.dashboard.index');
});

// User routes
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard.index');
});

// Role Management Routes (Admin only)
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/role-management', [RoleManagementController::class, 'index'])->name('admin.role-management.index');
    Route::get('/admin/role-management/create', [RoleManagementController::class, 'createRole'])->name('admin.role-management.create');
    Route::post('/admin/role-management', [RoleManagementController::class, 'storeRole'])->name('admin.role-management.store');
    Route::get('/admin/role-management/{role}/edit', [RoleManagementController::class, 'editRole'])->name('admin.role-management.edit');
    Route::put('/admin/role-management/{role}', [RoleManagementController::class, 'updateRole'])->name('admin.role-management.update');
    Route::delete('/admin/role-management/{role}', [RoleManagementController::class, 'deleteRole'])->name('admin.role-management.delete');
    Route::get('/admin/role-management/assign-roles', [RoleManagementController::class, 'assignRoles'])->name('admin.role-management.assign-roles');
    Route::put('/admin/role-management/users/{user}/roles', [RoleManagementController::class, 'updateUserRoles'])->name('admin.role-management.update-user-roles');
});
```

## Blade Template Examples with Spatie Directives

### Admin Dashboard Example
```blade
{{-- resources/views/admin/dashboard.blade.php --}}

<!-- Admin Actions Section -->
@role('admin')
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Admin Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @can('manage-roles')
            <a href="{{ route('admin.role-management.index') }}" class="bg-purple-500 hover:bg-purple-700 text-black font-bold py-2 px-4 rounded text-center">
                Manage Roles
            </a>
        @endcan
        
        @can('assign-roles')
            <a href="{{ route('admin.role-management.assign-roles') }}" class="bg-indigo-500 hover:bg-indigo-700 text-black font-bold py-2 px-4 rounded text-center">
                Assign Roles
            </a>
        @endcan
        
        @can('view-all-tasks')
            <a href="{{ route('admin.tasks.index') }}" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded text-center">
                View All Tasks
            </a>
        @endcan
    </div>
</div>
@endrole

<!-- Users Table with Role Display -->
<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th>User</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>
                @foreach($user->roles as $role)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($role->name === 'admin') bg-red-100 text-red-800
                        @elseif($role->name === 'manager') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800
                        @endif">
                        {{ ucfirst($role->name) }}
                    </span>
                @endforeach
            </td>
            <td>
                <div class="flex space-x-2">
                    @can('view-all-tasks')
                        <a href="{{ route('admin.tasks.index') }}?user={{ $user->id }}" class="text-indigo-600 hover:text-indigo-900">View Tasks</a>
                    @endcan
                    @can('assign-roles')
                        <a href="{{ route('admin.role-management.assign-roles') }}#user-{{ $user->id }}" class="text-green-600 hover:text-green-900">Manage Roles</a>
                    @endcan
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
```

### Manager Dashboard Example
```blade
{{-- resources/views/manager/dashboard.blade.php --}}

<!-- Manager Actions Section -->
<div class="bg-white rounded-lg shadow p-6 mt-8">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Manager Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @can('view-all-tasks')
            <a href="{{ route('admin.tasks.index') }}" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded text-center">
                View All Tasks
            </a>
        @endcan
        
        @can('create-tasks')
            <a href="{{ route('admin.tasks.create') }}" class="bg-green-500 hover:bg-green-700 text-black font-bold py-2 px-4 rounded text-center">
                Create Task
            </a>
        @endcan
        
        @can('view-users')
            <a href="{{ route('admin.tasks.index') }}" class="bg-purple-500 hover:bg-purple-700 text-black font-bold py-2 px-4 rounded text-center">
                Manage Tasks
            </a>
        @endcan
    </div>
    
    <!-- Note about restricted access -->
    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Manager Access</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>As a Manager, you have limited access compared to Administrators. You cannot manage roles, permissions, or view admin users.</p>
                </div>
            </div>
        </div>
    </div>
</div>
```

### User Dashboard Example
```blade
{{-- resources/views/user/dashboard.blade.php --}}

<!-- User Actions Section -->
@role('user')
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @can('create-tasks')
            <a href="{{ route('user.tasks.create') }}" class="bg-green-500 hover:bg-green-700 text-black font-bold py-2 px-4 rounded text-center">
                Create New Task
            </a>
        @endcan
        
        @can('view-own-tasks')
            <a href="{{ route('user.tasks.index') }}" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded text-center">
                View My Tasks
            </a>
        @endcan
        
        @can('create-reminders')
            <a href="{{ route('user.reminders.create') }}" class="bg-purple-500 hover:bg-purple-700 text-black font-bold py-2 px-4 rounded text-center">
                Set Reminder
            </a>
        @endcan
    </div>
</div>
@endrole

<!-- Task Lists with Permission Checks -->
@can('view-own-tasks')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Pending Tasks</h2>
    </div>
    <div class="p-6">
        @foreach($pendingTasks as $task)
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-gray-900">{{ $task->title }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $task->description }}</p>
                </div>
                <div class="flex space-x-2">
                    @can('view-own-tasks')
                        <a href="{{ route('user.tasks.show', $task) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View</a>
                    @endcan
                    @can('edit-tasks')
                        <a href="{{ route('user.tasks.edit', $task) }}" class="text-green-600 hover:text-green-900 text-sm font-medium">Edit</a>
                    @endcan
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endcan

<!-- Permission Notice -->
@role('user')
<div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">User Access</h3>
            <div class="mt-2 text-sm text-blue-700">
                <p>You have access to manage your own tasks and reminders. Contact an administrator if you need additional permissions.</p>
            </div>
        </div>
    </div>
</div>
@endrole
```

## Controller Examples

### Role Management Controller
```php
// app/Http/Controllers/RoleManagementController.php

class RoleManagementController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        $users = User::with('roles')->get();
        
        return view('admin.role-management.index', compact('roles', 'permissions', 'users'));
    }

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
```

### Manager Dashboard Controller
```php
// Modules/Dashboard/app/Http/Controllers/Manager/DashboardController.php

class DashboardController extends Controller
{
    public function index()
    {
        // Managers can see users but with limited information
        $users = User::withCount(['tasks', 'pendingTasks', 'completedTasks'])
            ->whereDoesntHave('roles', function($query) {
                $query->where('name', 'admin'); // Managers cannot see admin users
            })
            ->orderBy('name')
            ->get();
        
        $totalTasks = Task::count();
        $pendingTasks = Task::where('status', 'pending')->count();
        $completedTasks = Task::where('status', 'completed')->count();
        $overdueTasks = Task::where('status', 'pending')
            ->where('due_date', '<', now())
            ->count();
        
        return view('dashboard::manager.index', compact(
            'users', 
            'totalTasks', 
            'pendingTasks', 
            'completedTasks', 
            'overdueTasks'
        ));
    }
}
```

## Key Features Implemented

### 1. Role Hierarchy
- **Admin**: Full system access with role and permission management
- **Manager**: Limited admin access (cannot manage roles/permissions or see admin users)
- **User**: Basic access for personal task management

### 2. Permission-Based Access Control
- Dashboard access control (`view-admin-dashboard`, `view-manager-dashboard`, `view-user-dashboard`)
- Task management permissions (`view-all-tasks`, `view-own-tasks`, `create-tasks`, etc.)
- User management permissions (`view-users`, `create-users`, `edit-users`, etc.)
- Role and permission management (`manage-roles`, `manage-permissions`, `assign-roles`)

### 3. UI Components
- Role management interface for admins
- User role assignment interface
- Conditional rendering based on permissions
- Visual role indicators and access notices

### 4. Security Features
- Route-level protection with middleware
- Controller-level permission checks
- Blade template conditional rendering
- Protected admin role (cannot be deleted)

## Usage Instructions

1. **Run the seeder** to create roles and permissions:
   ```bash
   php artisan db:seed --class=RolesAndPermissionsSeeder
   ```

2. **Assign roles to users** through the admin interface at `/admin/role-management/assign-roles`

3. **Manage roles and permissions** through the admin interface at `/admin/role-management`

4. **Use Spatie directives** in your blade templates:
   - `@role('admin')` - Check if user has admin role
   - `@can('view-all-tasks')` - Check if user has specific permission
   - `@hasrole('manager')` - Alternative role check
   - `@hasanyrole(['admin', 'manager'])` - Check for any of multiple roles

5. **Use middleware** in routes for protection:
   - `role:admin` - Require admin role
   - `permission:manage-roles` - Require specific permission

This implementation provides a robust, scalable role and permission system that can be easily extended for future requirements.

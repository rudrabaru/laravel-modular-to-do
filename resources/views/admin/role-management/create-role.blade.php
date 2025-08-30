@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Create New Role</h1>
        <p class="text-gray-600 mt-2">Create a new role and assign permissions</p>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Role Details</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.role-management.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Role Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter role name (e.g., editor, moderator)">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-4">Permissions</label>
                    
                    @foreach($permissions->groupBy(function($permission) {
                        return explode('-', $permission->name)[0];
                    }) as $group => $groupPermissions)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">{{ ucfirst($group) }} Permissions</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($groupPermissions as $permission)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                               {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.role-management.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-black font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                        Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Permission Groups Information -->
    <div class="bg-white rounded-lg shadow mt-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Permission Groups</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Dashboard Permissions</h3>
                    <p class="text-sm text-gray-600 mb-3">Control access to different dashboard types</p>
                    <ul class="text-xs text-gray-500 space-y-1">
                        <li>• view-admin-dashboard: Access to admin dashboard</li>
                        <li>• view-manager-dashboard: Access to manager dashboard</li>
                        <li>• view-user-dashboard: Access to user dashboard</li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">User Management</h3>
                    <p class="text-sm text-gray-600 mb-3">Control user-related operations</p>
                    <ul class="text-xs text-gray-500 space-y-1">
                        <li>• view-users: View user list</li>
                        <li>• create-users: Create new users</li>
                        <li>• edit-users: Edit user information</li>
                        <li>• delete-users: Delete users</li>
                        <li>• assign-roles: Assign roles to users</li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Role & Permission Management</h3>
                    <p class="text-sm text-gray-600 mb-3">Control role and permission management</p>
                    <ul class="text-xs text-gray-500 space-y-1">
                        <li>• manage-roles: Create, edit, delete roles</li>
                        <li>• manage-permissions: Manage system permissions</li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Task Management</h3>
                    <p class="text-sm text-gray-600 mb-3">Control task-related operations</p>
                    <ul class="text-xs text-gray-500 space-y-1">
                        <li>• view-all-tasks: View all tasks in system</li>
                        <li>• view-own-tasks: View only own tasks</li>
                        <li>• create-tasks: Create new tasks</li>
                        <li>• edit-tasks: Edit existing tasks</li>
                        <li>• delete-tasks: Delete tasks</li>
                        <li>• assign-tasks: Assign tasks to users</li>
                        <li>• complete-tasks: Mark tasks as complete</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

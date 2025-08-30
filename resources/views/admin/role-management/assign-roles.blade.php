@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Assign Roles to Users</h1>
        <p class="text-gray-600 mt-2">Manage user role assignments</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">User Role Assignments</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Roles</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assign Roles</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr id="user-{{ $user->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($role->name === 'admin') bg-red-100 text-red-800
                                        @elseif($role->name === 'manager') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                                @if($user->roles->count() === 0)
                                    <span class="text-sm text-gray-500">No roles assigned</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.role-management.update-user-roles', $user) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                @method('PUT')
                                <select name="roles[]" multiple class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" 
                                            {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-black text-sm font-bold py-2 px-4 rounded">
                                    Update
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Role Information -->
    <div class="bg-white rounded-lg shadow mt-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Role Information</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                    <h3 class="text-lg font-medium text-red-900 mb-2">Admin Role</h3>
                    <p class="text-sm text-red-700 mb-3">Full system access with all permissions including role and permission management.</p>
                    <div class="text-xs text-red-600">
                        <strong>Key permissions:</strong> manage-roles, manage-permissions, view-all-tasks, assign-roles
                    </div>
                </div>
                
                <div class="border border-yellow-200 rounded-lg p-4 bg-yellow-50">
                    <h3 class="text-lg font-medium text-yellow-900 mb-2">Manager Role</h3>
                    <p class="text-sm text-yellow-700 mb-3">Limited admin access for task and user management, but cannot manage roles or permissions.</p>
                    <div class="text-xs text-yellow-600">
                        <strong>Key permissions:</strong> view-users, view-all-tasks, create-tasks, edit-tasks
                    </div>
                </div>
                
                <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                    <h3 class="text-lg font-medium text-green-900 mb-2">User Role</h3>
                    <p class="text-sm text-green-700 mb-3">Basic access for personal task management and viewing own data.</p>
                    <div class="text-xs text-green-600">
                        <strong>Key permissions:</strong> view-own-tasks, create-tasks, edit-tasks, complete-tasks
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

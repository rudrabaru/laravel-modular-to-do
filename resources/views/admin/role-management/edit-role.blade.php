@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Role: {{ ucfirst($role->name) }}</h1>
        <p class="text-gray-600 mt-2">Modify role permissions and settings</p>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Role Details</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.role-management.update', $role) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Role Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter role name">
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
                                               {{ in_array($permission->name, old('permissions', $role->permissions->pluck('name')->toArray())) ? 'checked' : '' }}>
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
                        Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Current Role Information -->
    <div class="bg-white rounded-lg shadow mt-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Current Role Information</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Role Details</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-600">Name:</span>
                            <span class="text-sm text-gray-900">{{ ucfirst($role->name) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-600">Created:</span>
                            <span class="text-sm text-gray-900">{{ $role->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-600">Last Updated:</span>
                            <span class="text-sm text-gray-900">{{ $role->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-600">Total Permissions:</span>
                            <span class="text-sm text-gray-900">{{ $role->permissions->count() }}</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Current Permissions</h3>
                    <div class="space-y-1">
                        @foreach($role->permissions->take(10) as $permission)
                            <div class="text-sm text-gray-600">• {{ $permission->name }}</div>
                        @endforeach
                        @if($role->permissions->count() > 10)
                            <div class="text-sm text-gray-500">... and {{ $role->permissions->count() - 10 }} more</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

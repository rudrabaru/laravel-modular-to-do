@extends('layouts.app')

@section('content')
<!-- Notification Box -->
@if(count($notifications) > 0)
    <x-notification-box :notifications="$notifications" />
@endif

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Dashboard</h1>
        <p class="text-gray-600 mt-2">Manage your tasks and track your progress</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Tasks</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalTasks }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $pendingCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $completedCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Overdue</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $overdueCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Tasks Alert -->
    @if($overdueTasks->count() > 0)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Overdue Tasks</h3>
                <div class="mt-2 text-sm text-red-700">
                    <p>You have {{ $overdueTasks->count() }} overdue task(s). Please complete them as soon as possible.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Pending Tasks -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Pending Tasks</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $pendingTasks->count() }} tasks</p>
            </div>
            <div class="p-6">
                @if($pendingTasks->count() > 0)
                    <div class="space-y-4">
                        @foreach($pendingTasks as $task)
                        <div class="border border-gray-200 rounded-lg p-4 {{ $task->isOverdue() ? 'border-red-300 bg-red-50' : '' }}">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900 {{ $task->isOverdue() ? 'text-red-800' : '' }}">
                                        {{ $task->title }}
                                    </h3>
                                    @if($task->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $task->description }}</p>
                                    @endif
                                    @if($task->due_date)
                                        <p class="text-sm {{ $task->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }} mt-2">
                                            Due: {{ $task->due_date->format('M d, Y') }}
                                            @if($task->isOverdue())
                                                (Overdue by {{ $task->due_date->diffForHumans() }})
                                            @endif
                                        </p>
                                    @endif
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('user.tasks.show', $task) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No pending tasks. Great job!</p>
                @endif
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Recently Completed</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $completedTasks->count() }} tasks</p>
            </div>
            <div class="p-6">
                @if($completedTasks->count() > 0)
                    <div class="space-y-4">
                        @foreach($completedTasks as $task)
                        <div class="border border-gray-200 rounded-lg p-4 bg-green-50">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900 line-through">{{ $task->title }}</h3>
                                    @if($task->description)
                                        <p class="text-sm text-gray-600 mt-1 line-through">{{ $task->description }}</p>
                                    @endif
                                    <p class="text-sm text-green-600 mt-2">Completed {{ $task->updated_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('user.tasks.show', $task) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No completed tasks yet.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Note: User task management is accessible from the navbar (Manage Tasks) -->
</div>
@endsection

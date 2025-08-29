@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Task Details</h1>
            <p class="text-gray-600 mt-2">View your task information</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-6">
                <div class="flex justify-between items-start">
                    <h2 class="text-2xl font-semibold text-gray-900 {{ $task->isOverdue() ? 'text-red-800' : '' }}">
                        {{ $task->title }}
                    </h2>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                        {{ $task->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($task->status) }}
                    </span>
                </div>
            </div>

            @if($task->description)
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $task->description }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Due Date</h3>
                    <p class="text-gray-700">
                        @if($task->due_date)
                            <span class="{{ $task->isOverdue() ? 'text-red-600 font-medium' : '' }}">
                                {{ $task->due_date->format('F d, Y') }}
                                @if($task->isOverdue())
                                    <br><span class="text-sm text-red-500">Overdue by {{ $task->due_date->diffForHumans() }}</span>
                                @endif
                            </span>
                        @else
                            <span class="text-gray-400">No due date set</span>
                        @endif
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Created</h3>
                    <p class="text-gray-700">{{ $task->created_at->format('F d, Y \a\t g:i A') }}</p>
                </div>

                @if($task->updated_at != $task->created_at)
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Last Updated</h3>
                    <p class="text-gray-700">{{ $task->updated_at->format('F d, Y \a\t g:i A') }}</p>
                </div>
                @endif
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('user.tasks.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black font-medium py-2 px-4 rounded-lg transition duration-200">
                    Back to Tasks
                </a>
                <a href="{{ route('user.tasks.edit', $task) }}" class="bg-blue-600 hover:bg-blue-700 text-black font-medium py-2 px-4 rounded-lg transition duration-200">
                    Edit Task
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

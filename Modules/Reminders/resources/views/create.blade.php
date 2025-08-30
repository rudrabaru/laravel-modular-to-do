@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Create Reminder</h1>
            <p class="text-gray-600 mt-2">Set a reminder for your task</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('user.reminders.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="task_id" class="block text-sm font-medium text-gray-700 mb-2">Select Task</label>
                    <select name="task_id" id="task_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select a task</option>
                        @foreach($tasks as $task)
                            <option value="{{ $task->id }}" {{ old('task_id') == $task->id ? 'selected' : '' }}>
                                {{ $task->title }} (Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }})
                            </option>
                        @endforeach
                    </select>
                    @error('task_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="remind_at" class="block text-sm font-medium text-gray-700 mb-2">Remind At</label>
                    <input type="datetime-local" name="remind_at" id="remind_at" value="{{ old('remind_at') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    @error('remind_at')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message (Optional)</label>
                    <textarea name="message" id="message" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Add a custom message for this reminder">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('user.dashboard.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-black rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        Create Reminder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

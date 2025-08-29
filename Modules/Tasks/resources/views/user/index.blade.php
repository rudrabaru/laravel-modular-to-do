@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Tasks</h1>
            <p class="text-gray-600 mt-2">Manage your tasks</p>
        </div>
        <a href="{{ route('user.tasks.create') }}" class="bg-green-600 hover:bg-green-700 text-black  font-medium py-2 px-4 rounded-lg transition duration-200">
            + Create New Task
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form method="GET" action="{{ route('user.tasks.index') }}" class="flex flex-wrap gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-black font-medium py-2 px-4 rounded-md transition duration-200">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Tasks Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tasks as $task)
        <div class="bg-white rounded-lg shadow border relative {{ $task->isOverdue() ? 'border-red-300' : 'border-gray-200' }}">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4 relative">
                    <h3 class="text-lg font-medium text-gray-900 {{ $task->isOverdue() ? 'text-red-800' : '' }}">
                        {{ $task->title }}
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $task->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($task->status) }}
                        </span>
                        <!-- Kebab menu (top-right, next to status) -->
                        <div class="relative">
                            <button type="button" class="p-2 rounded-full hover:bg-gray-100 js-kebab" aria-haspopup="true" aria-expanded="false">
                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </button>
                            <div class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-md shadow-lg z-20 js-menu">
                                <button type="button" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 js-open-edit" data-id="{{ $task->id }}">Edit</button>
                                <button type="button" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 js-open-delete" data-id="{{ $task->id }}">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($task->description)
                    <p class="text-sm text-gray-600 mb-4">{{ Str::limit($task->description, 150) }}</p>
                @endif
                
                @if($task->due_date)
                    <p class="text-sm {{ $task->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }} mb-4">
                        Due: {{ $task->due_date->format('M d, Y') }}
                        @if($task->isOverdue())
                            <br><span class="text-xs text-red-500">Overdue by {{ $task->due_date->diffForHumans() }}</span>
                        @endif
                    </p>
                @endif
                
                <!-- View button kept inline for quick access -->
                <div class="flex justify-end">
                    <button type="button" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium js-open-view" data-id="{{ $task->id }}">View</button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new task.</p>
                <div class="mt-6">
                    <a href="{{ route('user.tasks.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-black bg-indigo-600 hover:bg-indigo-700">
                        Create Task
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($tasks->hasPages())
    <div class="mt-8">
        {{ $tasks->links() }}
    </div>
    @endif

    <!-- Back to Dashboard -->
    <div class="mt-8">
        <a href="{{ route('user.dashboard.index') }}" class="bg-gray-600 hover:bg-gray-700 text-black  font-medium py-2 px-4 rounded-lg transition duration-200">
            Back to Dashboard
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    // Kebab toggle logic (no Alpine)
    $(document).on('click', '.js-kebab', function(e){
        e.stopPropagation();
        const $menu = $(this).closest('div.relative').find('.js-menu');
        $('.js-menu').not($menu).addClass('hidden');
        $menu.toggleClass('hidden');
    });
    $(document).on('click', function(){
        $('.js-menu').addClass('hidden');
    });

    function openModal(title, bodyHtml, onConfirm) {
        Swal.fire({
            title: title,
            html: bodyHtml,
            showCancelButton: true,
            confirmButtonText: 'Save',
            focusConfirm: false,
            didOpen: () => {
                $("form.swal2-form").on('submit', function(e){ e.preventDefault(); })
            }
        }).then((result) => {
            if (result.isConfirmed && typeof onConfirm === 'function') {
                onConfirm();
            }
        });
    }

    $('.js-open-view').on('click', function(){
        const id = $(this).data('id');
        $.get(`/user/tasks/${id}?partial=1`, function(markup){
            Swal.fire({ title: 'Task Details', html: markup, showConfirmButton: false, showCancelButton: true, cancelButtonText: 'Close' });
        });
    });

    $('.js-open-edit').on('click', function(){
        const id = $(this).data('id');
        $.get(`/user/tasks/${id}/edit?partial=1`, function(markup){
            openModal('Edit Task', `${markup}`, function(){
                const form = $('#task-form');
                const data = form.serialize();
                $.ajax({
                    url: `/user/tasks/${id}`,
                    method: 'POST',
                    data: data + '&_method=PUT&_token={{ csrf_token() }}',
                }).done(function(){
                    Swal.fire('Updated', 'Task updated successfully', 'success').then(()=>location.reload());
                }).fail(function(xhr){
                    Swal.fire('Error', xhr.responseJSON?.message || 'Validation failed', 'error');
                });
            });
        });
    });

    $('.js-open-delete').on('click', function(){
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete task?',
            text: 'This action cannot be undone',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete'
        }).then((result)=>{
            if(result.isConfirmed){
                $.post(`/user/tasks/${id}`, {_method:'DELETE', _token:'{{ csrf_token() }}' })
                 .done(()=> Swal.fire('Deleted', 'Task deleted', 'success').then(()=>location.reload()))
                 .fail(()=> Swal.fire('Error', 'Failed to delete', 'error'));
            }
        });
    });

    // Create via modal when clicking the Create button (prevent navigating page)
    $(document).on('click', "a[href='{{ route('user.tasks.create') }}']", function(e){
        e.preventDefault();
        $.get(`{{ route('user.tasks.create') }}?partial=1`, function(markup){
            openModal('Create Task', `${markup}`, function(){
                const form = $('#task-form');
                const data = form.serialize();
                $.post(`/user/tasks`, data + '&_token={{ csrf_token() }}')
                 .done(()=> Swal.fire('Created', 'Task created successfully', 'success').then(()=>location.reload()))
                 .fail((xhr)=> Swal.fire('Error', xhr.responseJSON?.message || 'Validation failed', 'error'));
            });
        });
    });
});
</script>
@endpush

<div class="text-start">
    <h5 class="fw-bold mb-2">{{ $task->title }}</h5>
    @if($task->description)
        <p class="mb-2">{{ $task->description }}</p>
    @endif
    <p class="mb-1"><strong>Due:</strong> {{ optional($task->due_date)->format('M d, Y') }}</p>
    <p class="mb-0"><strong>Status:</strong> {{ ucfirst($task->status) }}</p>
</div>


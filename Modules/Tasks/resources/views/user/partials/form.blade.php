<form class="swal2-form" id="task-form">
    @csrf
    @if(($mode ?? 'create') === 'edit')
        @method('PUT')
    @endif
    <div class="mb-3 text-start">
        <label class="form-label">Task Title *</label>
        <input name="title" type="text" class="form-control" value="{{ old('title', $task->title ?? '') }}" required>
    </div>
    <div class="mb-3 text-start">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $task->description ?? '') }}</textarea>
    </div>
    <div class="mb-3 text-start">
        <label class="form-label">Due Date *</label>
        <input name="due_date" type="date" class="form-control" value="{{ old('due_date', isset($task->due_date) ? $task->due_date->format('Y-m-d') : '') }}" required>
    </div>
    <div class="mb-3 text-start">
        <label class="form-label">Remind At (optional, must be before due date)</label>
        <input name="remind_at" type="datetime-local" class="form-control" value="{{ old('remind_at', optional($task->reminders()->first())->remind_at ? optional($task->reminders()->first()->remind_at)->format('Y-m-d\TH:i') : '') }}">
    </div>
    <div class="mb-3 text-start">
        <label class="form-label">Status *</label>
        <select name="status" class="form-select" required>
            <option value="pending" @selected(old('status', $task->status ?? 'pending')==='pending')>Pending</option>
            <option value="completed" @selected(old('status', $task->status ?? '')==='completed')>Completed</option>
        </select>
    </div>
</form>


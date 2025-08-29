<?php

namespace Modules\Tasks\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Tasks\Models\Task;
use Illuminate\Support\Facades\Auth;
use Modules\Reminders\Models\Reminder;

class TasksController extends Controller
{
    /**
     * Display a listing of user's own tasks
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->tasks()->orderBy('created_at', 'desc');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $tasks = $query->paginate(10)->appends($request->query());
        
        // Get notifications
        $notifications = $this->getNotifications($user);
        
        return view('tasks::user.index', compact('tasks', 'notifications'));
    }

    /**
     * Show the form for creating a new task
     */
    public function create()
    {
        $task = new Task();
        if (request()->ajax() || request()->boolean('partial')) {
            return view('tasks::user.partials.form', ['task' => $task, 'mode' => 'create']);
        }
        return view('tasks::user.create', compact('task'));
    }

    /**
     * Store a newly created task
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after:today',
            'remind_at' => 'nullable|date',
            'status' => 'required|in:pending,completed'
        ]);

        $validated['user_id'] = Auth::id();
        
        $task = Task::create($validated);

        // Validate remind_at < due_date
        if (!empty($request->remind_at) && !empty($request->due_date) && 
            now()->parse($request->remind_at)->greaterThan(now()->parse($request->due_date))) {
            return response()->json(['message' => 'Reminder cannot be after due date'], 422);
        }

        // Create reminder and dispatch delayed job
        if (!empty($request->remind_at)) {
            $reminder = \Modules\Reminders\Models\Reminder::create([
                'task_id' => $task->id,
                'remind_at' => $request->remind_at,
            ]);
            \Modules\Reminders\Jobs\SendReminderJob::dispatch($reminder->id)->delay(now()->parse($request->remind_at)); // Dispatch delayed job
        }
        if ($request->ajax()) {
            return response()->json(['message' => 'Task created successfully!', 'id' => $task->id]);
        }
        return redirect()->route('user.tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * Show the specified task
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        if (request()->ajax() || request()->boolean('partial')) {
            return view('tasks::user.partials.show', compact('task'));
        }
        return view('tasks::user.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        if (request()->ajax() || request()->boolean('partial')) {
            return view('tasks::user.partials.form', ['task' => $task, 'mode' => 'edit']);
        }
        return view('tasks::user.edit', compact('task'));
    }

    /**
     * Update the specified task
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'remind_at' => 'nullable|date',
            'status' => 'required|in:pending,completed'
        ]);

        $task->update($validated);

        // Validate remind_at < due_date
        if (!empty($request->remind_at) && !empty($request->due_date) && 
            now()->parse($request->remind_at)->greaterThan(now()->parse($request->due_date))) {
            return response()->json(['message' => 'Reminder cannot be after due date'], 422);
        }

        if (!empty($request->remind_at)) {
            $reminder = \Modules\Reminders\Models\Reminder::updateOrCreate(
                ['task_id' => $task->id],
                ['remind_at' => $request->remind_at]
            );
            \Modules\Reminders\Jobs\SendReminderJob::dispatch($reminder->id)->delay(now()->parse($request->remind_at));
        }
        if ($request->ajax()) {
            return response()->json(['message' => 'Task updated successfully!']);
        }
        return redirect()->route('user.tasks.index')->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified task
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        
        $task->delete();
        if (request()->ajax()) {
            return response()->json(['message' => 'Task deleted successfully!']);
        }
        return redirect()->route('user.tasks.index')->with('success', 'Task deleted successfully!');
    }
    
    /**
     * Get notifications for the user based on their reminders
     */
    private function getNotifications($user)
    {
        $notifications = [];
        
        // Get unread reminders that are due soon (within next 24 hours)
        $dueSoonReminders = Reminder::whereHas('task', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('remind_at', '>=', now())
        ->where('remind_at', '<=', now()->addDay())
        ->whereNull('read_at')
        ->with('task')
        ->orderBy('remind_at', 'asc')
        ->get();
        
        foreach ($dueSoonReminders as $reminder) {
            $notifications[] = [
                'id' => $reminder->id,
                'title' => 'Task Reminder',
                'message' => "Task '{$reminder->task->title}' is due soon",
                'time' => $reminder->remind_at->diffForHumans(),
                'type' => 'reminder'
            ];
        }
        
        // Get overdue task notifications (only for unread reminders)
        $overdueTasks = $user->tasks()
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->whereHas('reminders', function($query) {
                $query->whereNull('read_at');
            })
            ->get();
            
        foreach ($overdueTasks as $task) {
            $notifications[] = [
                'id' => 'overdue_' . $task->id,
                'title' => 'Overdue Task',
                'message' => "Task '{$task->title}' is overdue",
                'time' => $task->due_date->diffForHumans(),
                'type' => 'overdue'
            ];
        }
        
        return $notifications;
    }
}

<?php

namespace Modules\Tasks\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Tasks\Models\Task;
use App\Models\User;

class TasksController extends Controller
{
    /**
     * Display a listing of all tasks (read-only for admin)
     */
    public function index(Request $request)
    {
        $query = Task::with('user')->orderBy('created_at', 'desc');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by user
        if ($request->filled('user')) {
            $query->where('user_id', $request->user);
        }
        
        $tasks = $query->paginate(20)->appends($request->query());
        return view('tasks::admin.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('tasks::admin.create', compact('users'));
    }

    /**
     * Store a newly created task
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'nullable|date|after:today',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'due_date' => $request->due_date,
            'priority' => $request->priority ?? 'medium',
            'status' => 'pending',
        ]);

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified task
     */
    public function show(Task $task)
    {
        $task->load('user');
        return view('tasks::admin.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task
     */
    public function edit(Task $task)
    {
        $users = User::orderBy('name')->get();
        return view('tasks::admin.edit', compact('task', 'users'));
    }

    /**
     * Update the specified task
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high',
            'status' => 'required|in:pending,completed',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'due_date' => $request->due_date,
            'priority' => $request->priority ?? 'medium',
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    /**
     * Display all users with their total task counts
     */
    public function users()
    {
        $users = User::withCount(['tasks', 'pendingTasks', 'completedTasks'])
            ->orderBy('name')
            ->get();
        
        return view('tasks::admin.users', compact('users'));
    }
}

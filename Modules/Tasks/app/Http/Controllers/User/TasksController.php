<?php

namespace Modules\Tasks\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Tasks\Models\Task;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    /**
     * Display a listing of user's own tasks
     */
    public function index(Request $request)
    {
        $query = Auth::user()->tasks()->orderBy('created_at', 'desc');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $tasks = $query->paginate(10)->appends($request->query());
        return view('tasks::user.index', compact('tasks'));
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
            'status' => 'required|in:pending,completed'
        ]);

        $validated['user_id'] = Auth::id();
        
        $task = Task::create($validated);
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
            'status' => 'required|in:pending,completed'
        ]);

        $task->update($validated);
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
}

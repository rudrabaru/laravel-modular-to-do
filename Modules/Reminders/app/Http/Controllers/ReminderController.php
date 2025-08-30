<?php

namespace Modules\Reminders\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Reminders\Models\Reminder;
use Modules\Tasks\Models\Task;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    /**
     * Show the form for creating a new reminder
     */
    public function create()
    {
        $user = Auth::user();
        $tasks = $user->tasks()->where('status', 'pending')->get();
        return view('reminders::create', compact('tasks'));
    }

    /**
     * Store a newly created reminder
     */
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'remind_at' => 'required|date|after:now',
            'message' => 'nullable|string|max:255',
        ]);

        // Ensure the task belongs to the authenticated user
        $task = Task::where('id', $request->task_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        Reminder::create([
            'task_id' => $request->task_id,
            'remind_at' => $request->remind_at,
            'message' => $request->message,
        ]);

        return redirect()->route('user.dashboard.index')
            ->with('success', 'Reminder created successfully.');
    }

    /**
     * Display the specified reminder
     */
    public function show(Reminder $reminder)
    {
        // Ensure the reminder belongs to the authenticated user
        if ($reminder->task->user_id !== Auth::id()) {
            abort(403);
        }

        return view('reminders::show', compact('reminder'));
    }

    /**
     * Show the form for editing the specified reminder
     */
    public function edit(Reminder $reminder)
    {
        // Ensure the reminder belongs to the authenticated user
        if ($reminder->task->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        $tasks = $user->tasks()->where('status', 'pending')->get();
        return view('reminders::edit', compact('reminder', 'tasks'));
    }

    /**
     * Update the specified reminder
     */
    public function update(Request $request, Reminder $reminder)
    {
        // Ensure the reminder belongs to the authenticated user
        if ($reminder->task->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'remind_at' => 'required|date',
            'message' => 'nullable|string|max:255',
        ]);

        // Ensure the task belongs to the authenticated user
        $task = Task::where('id', $request->task_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $reminder->update([
            'task_id' => $request->task_id,
            'remind_at' => $request->remind_at,
            'message' => $request->message,
        ]);

        return redirect()->route('user.dashboard.index')
            ->with('success', 'Reminder updated successfully.');
    }

    /**
     * Remove the specified reminder
     */
    public function destroy(Reminder $reminder)
    {
        // Ensure the reminder belongs to the authenticated user
        if ($reminder->task->user_id !== Auth::id()) {
            abort(403);
        }

        $reminder->delete();

        return redirect()->route('user.dashboard.index')
            ->with('success', 'Reminder deleted successfully.');
    }
}

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

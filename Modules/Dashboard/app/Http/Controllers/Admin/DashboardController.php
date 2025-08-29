<?php

namespace Modules\Dashboard\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Modules\Tasks\Models\Task;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard with user task counts
     */
    public function index()
    {
        $users = User::withCount(['tasks', 'pendingTasks', 'completedTasks'])
            ->orderBy('name')
            ->get();
        
        $totalTasks = Task::count();
        $pendingTasks = Task::where('status', 'pending')->count();
        $completedTasks = Task::where('status', 'completed')->count();
        $overdueTasks = Task::where('status', 'pending')
            ->where('due_date', '<', now())
            ->count();
        
        return view('dashboard::admin.index', compact(
            'users', 
            'totalTasks', 
            'pendingTasks', 
            'completedTasks', 
            'overdueTasks'
        ));
    }
}

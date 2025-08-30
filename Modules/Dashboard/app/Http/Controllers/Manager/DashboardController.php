<?php

namespace Modules\Dashboard\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Modules\Tasks\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display manager dashboard with user task counts (restricted view)
     */
    public function index()
    {
        // Managers can see users but with limited information
        $users = User::withCount(['tasks', 'pendingTasks', 'completedTasks'])
            ->whereDoesntHave('roles', function($query) {
                $query->where('name', 'admin'); // Managers cannot see admin users
            })
            ->orderBy('name')
            ->get();
        
        $totalTasks = Task::count();
        $pendingTasks = Task::where('status', 'pending')->count();
        $completedTasks = Task::where('status', 'completed')->count();
        $overdueTasks = Task::where('status', 'pending')
            ->where('due_date', '<', now())
            ->count();
        
        return view('dashboard::manager.index', compact(
            'users', 
            'totalTasks', 
            'pendingTasks', 
            'completedTasks', 
            'overdueTasks'
        ));
    }
}

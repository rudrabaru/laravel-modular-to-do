<?php

namespace Modules\Dashboard\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display user dashboard with tasks grouped by status
     */
    public function index()
    {
        $user = Auth::user();
        
        $pendingTasks = $user->tasks()
            ->where('status', 'pending')
            ->orderBy('due_date', 'asc')
            ->get();
            
        $completedTasks = $user->tasks()
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
            
        $overdueTasks = $user->tasks()
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->orderBy('due_date', 'asc')
            ->get();
            
        $totalTasks = $user->tasks()->count();
        $pendingCount = $user->tasks()->where('status', 'pending')->count();
        $completedCount = $user->tasks()->where('status', 'completed')->count();
        $overdueCount = $overdueTasks->count();
        
        return view('dashboard::user.index', compact(
            'pendingTasks',
            'completedTasks', 
            'overdueTasks',
            'totalTasks',
            'pendingCount',
            'completedCount',
            'overdueCount'
        ));
    }
}

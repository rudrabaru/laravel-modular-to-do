<?php

namespace Modules\Dashboard\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Reminders\Models\Reminder;

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
        
        // Get notifications from reminders
        $notifications = $this->getNotifications($user);
        
        return view('dashboard::user.index', compact(
            'pendingTasks',
            'completedTasks', 
            'overdueTasks',
            'totalTasks',
            'pendingCount',
            'completedCount',
            'overdueCount',
            'notifications'
        ));
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

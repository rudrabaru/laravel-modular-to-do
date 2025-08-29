<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Reminders\Models\Reminder;

class NotificationController extends Controller
{
    /**
     * Mark a specific notification as read
     */
    public function markAsRead(Request $request)
    {
        $notificationId = $request->input('notification_id');
        
        // Handle reminder notifications
        if (is_numeric($notificationId)) {
            $reminder = Reminder::whereHas('task', function($query) {
                $query->where('user_id', Auth::id());
            })->find($notificationId);
            
            if ($reminder) {
                $reminder->update(['read_at' => now()]);
            }
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        
        // Mark all unread reminders as read
        Reminder::whereHas('task', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereNull('read_at')
        ->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }
}

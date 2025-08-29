<?php

namespace Modules\Reminders\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Modules\Reminders\Models\Reminder;

class SendReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $reminderId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $reminderId)
    {
        $this->reminderId = $reminderId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $reminder = Reminder::with('task.user')->find($this->reminderId); // Assuming Reminder has a relationship with Task and User
        if (!$reminder || !$reminder->task || !$reminder->task->user) {
            return;
        }
        $task = $reminder->task; // Assuming Reminder has a 'task' relationship
        $user = $task->user; // Assuming Task has a 'user' relationship
        Log::info("Reminder: Task {$task->title} for user {$user->name} is due soon.");
    }
}

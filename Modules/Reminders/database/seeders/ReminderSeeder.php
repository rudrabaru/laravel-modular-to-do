<?php

namespace Modules\Reminders\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Reminders\Models\Reminder;
use Modules\Tasks\Models\Task;
use App\Models\User;

class ReminderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing tasks to create reminders for
        $tasks = Task::where('status', 'pending')->take(3)->get();
        
        foreach ($tasks as $task) {
            // Create a reminder that's due soon (within next 24 hours)
            Reminder::firstOrCreate(
                ['task_id' => $task->id],
                [
                    'remind_at' => now()->addHours(rand(1, 12)),
                ]
            );
        }
        
        // Create a reminder for a task due tomorrow
        $futureTask = Task::where('status', 'pending')
            ->where('due_date', '>', now()->addDay())
            ->first();
            
        if ($futureTask) {
            Reminder::firstOrCreate(
                ['task_id' => $futureTask->id],
                [
                    'remind_at' => now()->addHours(rand(13, 24)),
                ]
            );
        }
    }
}

<?php

namespace Modules\Reminders\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Reminders\Models\Reminder;
use Modules\Tasks\Models\Task;
use Illuminate\Support\Carbon;

class ReminderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $task = Task::query()->first();
        if (!$task) {
            return;
        }

        if (!Reminder::query()->exists()) {
            Reminder::query()->create([
                'task_id' => $task->id,
                'remind_at' => Carbon::now()->addDays(6)->toDateTimeString(), // 1 day before the task due date
            ]);
        }
    }
}

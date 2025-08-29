<?php

namespace Modules\Tasks\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Tasks\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->where('email', 'user@example.com')->first();
        if (!$user) return;

        // Mirror the root DatabaseSeeder tasks here using firstOrCreate
        Task::firstOrCreate(
            ['user_id' => $user->id, 'title' => 'Test Task 1'],
            ['description' => 'This is a test task', 'due_date' => Carbon::now()->addDays(5), 'status' => 'pending']
        );

        Task::firstOrCreate(
            ['user_id' => $user->id, 'title' => 'Test Task 2'],
            ['description' => 'This is another test task', 'due_date' => Carbon::now()->subDays(2), 'status' => 'completed']
        );

        Task::firstOrCreate(
            ['user_id' => $user->id, 'title' => 'Test Task 3'],
            ['description' => 'This is a third test task', 'due_date' => Carbon::now()->addDays(1), 'status' => 'pending']
        );

        Task::firstOrCreate(
            ['user_id' => $user->id, 'title' => 'Overdue Task'],
            ['description' => 'This task is overdue', 'due_date' => Carbon::now()->subDays(3), 'status' => 'pending']
        );

        Task::firstOrCreate(
            ['user_id' => $user->id, 'title' => 'Laravel Task'],
            ['description' => 'Modular to-do', 'due_date' => Carbon::now()->addDays(3), 'status' => 'pending']
        );

        // Pick the target user by email
        $target = User::where('email', 'manan@gmail.com')->first();

        if ($target) {
        Task::firstOrCreate(
            ['user_id' => $target->id, 'title' => 'Prepare weekly report'],
            [
            'description' => 'Compile KPIs and blockers',
            'due_date' => Carbon::now()->addDays(3),
            'status' => 'pending',
            ]
        );
        }
    }
}

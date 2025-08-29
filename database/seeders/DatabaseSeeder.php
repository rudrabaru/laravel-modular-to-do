<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Modules\Tasks\Models\Task;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user (if not exists)
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Create regular user (if not exists)
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]
        );

        // Create some test tasks for the regular user only
        Task::firstOrCreate(
            [
                'user_id' => $user->id,
                'title' => 'Test Task 1'
            ],
            [
                'description' => 'This is a test task',
                'due_date' => now()->addDays(5),
                'status' => 'pending',
            ]
        );

        Task::firstOrCreate(
            [
                'user_id' => $user->id,
                'title' => 'Test Task 2'
            ],
            [
                'description' => 'This is another test task',
                'due_date' => now()->subDays(2),
                'status' => 'completed',
            ]
        );

        Task::firstOrCreate(
            [
                'user_id' => $user->id,
                'title' => 'Test Task 3'
            ],
            [
                'description' => 'This is a third test task',
                'due_date' => now()->addDays(1),
                'status' => 'pending',
            ]
        );

        Task::firstOrCreate(
            [
                'user_id' => $user->id,
                'title' => 'Overdue Task'
            ],
            [
                'description' => 'This task is overdue',
                'due_date' => now()->subDays(3),
                'status' => 'pending',
            ]
        );
    }
}

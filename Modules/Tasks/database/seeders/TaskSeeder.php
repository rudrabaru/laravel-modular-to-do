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
        $user = User::query()->where('email', 'user@example.com')->first() ?? User::query()->first();
        if (!$user) {
            return;
        }

        if (!Task::query()->exists()) {
            Task::query()->create([
                'user_id' => $user->id,
                'title' => 'Sample Task',
                'description' => 'This is a sample task for seeding.',
                'due_date' => Carbon::now()->addDays(7)->toDateString(),
                'status' => 'pending',
            ]);
        }
    }
}

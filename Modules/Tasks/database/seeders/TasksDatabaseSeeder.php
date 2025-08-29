<?php

namespace Modules\Tasks\Database\Seeders;

use Illuminate\Database\Seeder;

class TasksDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TaskSeeder::class,
            \Modules\Reminders\Database\Seeders\ReminderSeeder::class,
        ]);
    }
}

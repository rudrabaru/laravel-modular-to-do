<?php

namespace Modules\Reminders\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Reminders\Database\Factories\ReminderFactory;

class Reminder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'task_id',
        'remind_at',
    ];

    // protected static function newFactory(): ReminderFactory
    // {
    //     // return ReminderFactory::new();
    // }
}

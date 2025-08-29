<?php

namespace Modules\Reminders\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reminder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'task_id',
        'remind_at',
        'read_at',
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(\Modules\Tasks\Models\Task::class);
    }
}

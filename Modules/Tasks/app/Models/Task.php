<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function reminders()
    {
        return $this->hasMany(\Modules\Reminders\Models\Reminder::class);
    }

    /**
     * Get the user that owns the task
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Check if task is overdue
     */
    public function isOverdue()
    {
        return $this->status === 'pending' && $this->due_date && $this->due_date < now();
    }
}

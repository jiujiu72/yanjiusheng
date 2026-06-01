<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestReminderSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_reminder_minutes', 'second_reminder_minutes', 'snooze_minutes', 'enabled'
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}

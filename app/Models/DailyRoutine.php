<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyRoutine extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'wake_time', 'sleep_time', 'study_hours', 'exercise_minutes', 'mood', 'summary'
    ];

    protected $casts = [
        'date' => 'date',
    ];
}

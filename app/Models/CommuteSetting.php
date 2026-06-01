<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommuteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'city', 'morning_commute', 'evening_commute', 'reminder_minutes_before',
        'rain_alert', 'heat_alert', 'cold_alert', 'heat_threshold', 'cold_threshold',
        'notes', 'enabled',
    ];

    protected $casts = [
        'rain_alert' => 'boolean',
        'heat_alert' => 'boolean',
        'cold_alert' => 'boolean',
        'enabled' => 'boolean',
    ];
}

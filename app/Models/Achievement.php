<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'name', 'description', 'icon', 'category', 'threshold', 'unlocked', 'unlocked_at'
    ];

    protected $casts = [
        'unlocked' => 'boolean',
        'unlocked_at' => 'datetime',
    ];
}

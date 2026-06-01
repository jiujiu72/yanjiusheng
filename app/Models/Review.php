<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'title', 'period_start', 'period_end',
        'achievements', 'problems', 'next_plan', 'content', 'rating'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
    ];
}

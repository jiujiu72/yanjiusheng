<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestroomLog extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'time', 'duration_minutes', 'note'];

    protected $casts = [
        'date' => 'date',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'type', 'platform', 'episode', 'watch_date', 'rating', 'notes', 'source'
    ];

    protected $casts = [
        'watch_date' => 'date',
    ];
}

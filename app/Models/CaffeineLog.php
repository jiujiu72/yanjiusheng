<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaffeineLog extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'time', 'type', 'name', 'caffeine_mg', 'price', 'note'];

    protected $casts = [
        'date' => 'date',
    ];
}

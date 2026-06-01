<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrinkWaterLog extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'time', 'amount', 'type'];

    protected $casts = [
        'date' => 'date',
    ];
}

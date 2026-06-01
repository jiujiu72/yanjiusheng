<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemBorrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name', 'borrower', 'borrow_date', 'expected_return_date',
        'actual_return_date', 'status', 'notes',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'expected_return_date' => 'date',
        'actual_return_date' => 'date',
    ];
}

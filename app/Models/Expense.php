<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'category', 'description', 'amount',
        'is_reimbursed', 'reimbursed_amount', 'reimbursed_at', 'receipt_note'
    ];

    protected $casts = [
        'date' => 'date',
        'reimbursed_at' => 'date',
        'is_reimbursed' => 'boolean',
    ];
}

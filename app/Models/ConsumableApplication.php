<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumableApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'quantity', 'unit', 'unit_price', 'total_cost',
        'applied_at', 'purpose', 'expense_id',
    ];

    protected $casts = [
        'applied_at' => 'date',
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}

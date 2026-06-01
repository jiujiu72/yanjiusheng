<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'status', 'progress', 'start_date', 'due_date'
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
    ];

    public function meetingMinutes()
    {
        return $this->hasMany(MeetingMinute::class);
    }
}

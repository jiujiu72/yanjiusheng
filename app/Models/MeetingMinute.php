<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingMinute extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'topic', 'attendees', 'action_items', 'conclusions',
        'notes', 'research_project_id', 'meeting_time', 'duration_minutes',
    ];

    protected $casts = [
        'meeting_time' => 'datetime',
    ];

    public function researchProject()
    {
        return $this->belongsTo(ResearchProject::class);
    }
}

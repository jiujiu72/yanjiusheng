<?php

namespace App\Http\Controllers;

use App\Models\DailyRoutine;
use App\Models\Pomodoro;
use App\Models\Todo;
use App\Models\Literature;
use App\Models\ResearchProject;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        $days = 30;
        $startDate = Carbon::now()->subDays($days);

        $routines = DailyRoutine::where('date', '>=', $startDate)
            ->orderBy('date')
            ->get();

        $studyData = $routines->pluck('study_hours', 'date')->toArray();
        $moodData = $routines->pluck('mood', 'date')->toArray();
        $exerciseData = $routines->pluck('exercise_minutes', 'date')->toArray();

        $totalStudyHours = $routines->sum('study_hours');
        $avgStudyHours = $routines->count() > 0 ? round($routines->avg('study_hours'), 1) : 0;
        $avgMood = $routines->count() > 0 ? round($routines->avg('mood'), 1) : 0;

        $pomodorosByWeek = Pomodoro::where('date', '>=', $startDate)
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->date)->startOfWeek()->format('m/d');
            })
            ->map->count();

        $todoStats = [
            'completed' => Todo::where('completed', true)->count(),
            'pending' => Todo::where('completed', false)->count(),
        ];

        $litStats = [
            'unread' => Literature::where('status', 'unread')->count(),
            'reading' => Literature::where('status', 'reading')->count(),
            'finished' => Literature::where('status', 'finished')->count(),
        ];

        $projectStats = [
            'planning' => ResearchProject::where('status', 'planning')->count(),
            'in_progress' => ResearchProject::where('status', 'in_progress')->count(),
            'review' => ResearchProject::where('status', 'review')->count(),
            'completed' => ResearchProject::where('status', 'completed')->count(),
        ];

        return view('statistics.index', compact(
            'studyData', 'moodData', 'exerciseData',
            'totalStudyHours', 'avgStudyHours', 'avgMood',
            'pomodorosByWeek', 'todoStats', 'litStats', 'projectStats'
        ));
    }
}

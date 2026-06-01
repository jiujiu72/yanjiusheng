<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\DailyRoutine;
use App\Models\DrinkWaterLog;
use App\Models\Literature;
use App\Models\Pomodoro;
use App\Models\ResearchProject;
use App\Models\Review;
use App\Models\Todo;
use App\Models\WatchLog;
use Carbon\Carbon;

class AchievementService
{
    public function checkAll(): array
    {
        $newlyUnlocked = [];
        $locked = Achievement::where('unlocked', false)->get();

        foreach ($locked as $achievement) {
            if ($this->evaluate($achievement->key)) {
                $achievement->update(['unlocked' => true, 'unlocked_at' => now()]);
                $newlyUnlocked[] = [
                    'name' => $achievement->name,
                    'description' => $achievement->description,
                    'icon' => $achievement->icon,
                ];
            }
        }

        if (!empty($newlyUnlocked)) {
            session()->flash('achievement_unlocked', $newlyUnlocked);
        }

        return $newlyUnlocked;
    }

    public function getProgress(string $key): array
    {
        $progressMap = [
            'routine_streak_7' => fn() => ['current' => $this->getConsecutiveRoutineDays(), 'target' => 7],
            'routine_streak_30' => fn() => ['current' => $this->getConsecutiveRoutineDays(), 'target' => 30],
            'study_total_100' => fn() => ['current' => (int) DailyRoutine::sum('study_hours'), 'target' => 100],
            'study_total_500' => fn() => ['current' => (int) DailyRoutine::sum('study_hours'), 'target' => 500],
            'pomodoro_50' => fn() => ['current' => Pomodoro::count(), 'target' => 50],
            'pomodoro_200' => fn() => ['current' => Pomodoro::count(), 'target' => 200],
            'literature_10' => fn() => ['current' => Literature::where('status', 'finished')->count(), 'target' => 10],
            'literature_50' => fn() => ['current' => Literature::where('status', 'finished')->count(), 'target' => 50],
            'review_4' => fn() => ['current' => Review::count(), 'target' => 4],
            'water_streak_7' => fn() => ['current' => $this->getConsecutiveWaterDays(), 'target' => 7],
            'watch_20' => fn() => ['current' => WatchLog::count(), 'target' => 20],
            'todo_complete_50' => fn() => ['current' => Todo::where('completed', true)->count(), 'target' => 50],
            'project_complete_1' => fn() => ['current' => ResearchProject::where('status', 'completed')->count(), 'target' => 1],
        ];

        if (isset($progressMap[$key])) {
            return $progressMap[$key]();
        }

        return ['current' => 0, 'target' => 1];
    }

    private function evaluate(string $key): bool
    {
        $conditions = [
            'routine_streak_7' => fn() => $this->getConsecutiveRoutineDays() >= 7,
            'routine_streak_30' => fn() => $this->getConsecutiveRoutineDays() >= 30,
            'study_total_100' => fn() => DailyRoutine::sum('study_hours') >= 100,
            'study_total_500' => fn() => DailyRoutine::sum('study_hours') >= 500,
            'pomodoro_50' => fn() => Pomodoro::count() >= 50,
            'pomodoro_200' => fn() => Pomodoro::count() >= 200,
            'literature_10' => fn() => Literature::where('status', 'finished')->count() >= 10,
            'literature_50' => fn() => Literature::where('status', 'finished')->count() >= 50,
            'review_4' => fn() => Review::count() >= 4,
            'water_streak_7' => fn() => $this->getConsecutiveWaterDays() >= 7,
            'watch_20' => fn() => WatchLog::count() >= 20,
            'todo_complete_50' => fn() => Todo::where('completed', true)->count() >= 50,
            'project_complete_1' => fn() => ResearchProject::where('status', 'completed')->count() >= 1,
        ];

        if (isset($conditions[$key])) {
            return $conditions[$key]();
        }

        return false;
    }

    private function getConsecutiveRoutineDays(): int
    {
        $dates = DailyRoutine::orderBy('date', 'desc')->pluck('date');
        if ($dates->isEmpty()) return 0;

        $streak = 0;
        $expected = Carbon::today();

        foreach ($dates as $date) {
            $d = Carbon::parse($date);
            if ($d->equalTo($expected)) {
                $streak++;
                $expected = $expected->subDay();
            } elseif ($d->lt($expected)) {
                break;
            }
        }

        return $streak;
    }

    private function getConsecutiveWaterDays(): int
    {
        $dates = DrinkWaterLog::selectRaw('date, SUM(amount) as total')
            ->groupBy('date')
            ->having('total', '>=', 2000)
            ->orderBy('date', 'desc')
            ->pluck('date');

        if ($dates->isEmpty()) return 0;

        $streak = 0;
        $expected = Carbon::today();

        foreach ($dates as $date) {
            $d = Carbon::parse($date);
            if ($d->equalTo($expected)) {
                $streak++;
                $expected = $expected->subDay();
            } elseif ($d->lt($expected)) {
                break;
            }
        }

        return $streak;
    }
}

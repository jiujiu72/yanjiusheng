<?php

namespace App\Http\Controllers;

use App\Models\ResearchProject;
use App\Models\Literature;
use App\Models\Todo;
use App\Models\Note;
use App\Models\DailyRoutine;
use App\Models\Pomodoro;
use App\Models\DrinkWaterLog;
use App\Models\Expense;
use App\Models\Achievement;
use App\Models\ItemBorrowing;
use App\Models\ConsumableApplication;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'projects_count' => ResearchProject::count(),
            'active_projects' => ResearchProject::where('status', 'in_progress')->count(),
            'literatures_count' => Literature::count(),
            'unread_literatures' => Literature::where('status', 'unread')->count(),
            'pending_todos' => Todo::where('completed', false)->count(),
            'overdue_todos' => Todo::where('completed', false)->where('due_date', '<', now())->count(),
            'notes_count' => Note::count(),
            'today_study_hours' => DailyRoutine::where('date', today())->value('study_hours') ?? 0,
            'week_pomodoros' => Pomodoro::where('date', '>=', now()->startOfWeek())->count(),
            'today_water' => DrinkWaterLog::where('date', today())->sum('amount'),
            'pending_reimburse' => Expense::where('is_reimbursed', false)->sum('amount'),
            'unlocked_achievements' => Achievement::where('unlocked', true)->count(),
            'overdue_borrowings' => ItemBorrowing::where('status', 'overdue')->count()
                + ItemBorrowing::where('status', 'borrowed')->where('expected_return_date', '<', today())->count(),
            'consumable_month_cost' => ConsumableApplication::whereMonth('applied_at', now()->month)
                ->whereYear('applied_at', now()->year)->sum('total_cost'),
        ];

        $recent_todos = Todo::where('completed', false)->orderBy('due_date')->limit(5)->get();
        $recent_projects = ResearchProject::orderBy('updated_at', 'desc')->limit(3)->get();

        $daily_quote = $this->getDailyQuote();
        $countdown = $this->getCountdown();

        return view('dashboard', compact('stats', 'recent_todos', 'recent_projects', 'daily_quote', 'countdown'));
    }

    private function getDailyQuote()
    {
        $quotes = [
            ['text' => '学而不思则罔，思而不学则殆。', 'author' => '孔子'],
            ['text' => '路漫漫其修远兮，吾将上下而求索。', 'author' => '屈原'],
            ['text' => '不积跬步，无以至千里；不积小流，无以成江海。', 'author' => '荀子'],
            ['text' => '业精于勤，荒于嬉；行成于思，毁于随。', 'author' => '韩愈'],
            ['text' => '纸上得来终觉浅，绝知此事要躬行。', 'author' => '陆游'],
            ['text' => '博观而约取，厚积而薄发。', 'author' => '苏轼'],
            ['text' => '千里之行，始于足下。', 'author' => '老子'],
            ['text' => 'The only way to do great work is to love what you do.', 'author' => 'Steve Jobs'],
            ['text' => 'Science is organized knowledge. Wisdom is organized life.', 'author' => 'Immanuel Kant'],
            ['text' => 'Research is what I\'m doing when I don\'t know what I\'m doing.', 'author' => 'Wernher von Braun'],
        ];
        return $quotes[array_rand($quotes)];
    }

    private function getCountdown()
    {
        $events = [
            ['name' => '论文初稿截止', 'date' => Carbon::now()->addMonths(3)],
            ['name' => '中期答辩', 'date' => Carbon::now()->addMonths(6)],
        ];

        $projects = ResearchProject::where('due_date', '>=', today())
            ->orderBy('due_date')
            ->limit(3)
            ->get();

        foreach ($projects->reverse() as $project) {
            array_unshift($events, [
                'name' => mb_strlen($project->title) > 15
                    ? mb_substr($project->title, 0, 15) . '…'
                    : $project->title,
                'full_name' => $project->title,
                'date' => $project->due_date,
            ]);
        }

        foreach ($events as &$event) {
            $event['days_left'] = (int) Carbon::now()->diffInDays($event['date'], false);
            if (!isset($event['full_name'])) {
                $event['full_name'] = $event['name'];
            }
        }

        return array_slice($events, 0, 5);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DrinkWaterLog;
use App\Services\AchievementService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DrinkWaterController extends Controller
{
    public function index()
    {
        $today = today();
        $todayLogs = DrinkWaterLog::where('date', $today)->orderBy('time', 'desc')->get();
        $todayTotal = $todayLogs->sum('amount');
        $target = 2000;

        $weekData = DrinkWaterLog::where('date', '>=', now()->subDays(7))
            ->selectRaw('date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $recentLogs = DrinkWaterLog::where('date', '<', $today)
            ->selectRaw('date, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(14)
            ->get();

        return view('drink-water.index', compact('todayLogs', 'todayTotal', 'target', 'weekData', 'recentLogs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|integer|min:50|max:2000',
            'type' => 'required|in:warm_water,cold_water,other',
        ]);

        DrinkWaterLog::create([
            'date' => today(),
            'time' => now()->format('H:i:s'),
            'amount' => $validated['amount'],
            'type' => $validated['type'],
        ]);

        app(AchievementService::class)->checkAll();

        return redirect()->route('drink-water.index')->with('success', '饮水已记录 +' . $validated['amount'] . 'ml');
    }

    public function destroy(DrinkWaterLog $drinkWater)
    {
        $drinkWater->delete();
        return redirect()->route('drink-water.index')->with('success', '记录已删除');
    }
}

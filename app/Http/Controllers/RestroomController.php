<?php

namespace App\Http\Controllers;

use App\Models\RestroomLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RestroomController extends Controller
{
    public function index()
    {
        $today = today();
        $todayLogs = RestroomLog::where('date', $today)->orderBy('time', 'desc')->get();
        $todayCount = $todayLogs->count();

        $weekStats = RestroomLog::where('date', '>=', now()->subDays(7))
            ->selectRaw('date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $recentLogs = RestroomLog::orderBy('date', 'desc')->orderBy('time', 'desc')->limit(50)->get();

        return view('restroom.index', compact('todayLogs', 'todayCount', 'weekStats', 'recentLogs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'duration_minutes' => 'nullable|integer|min:1|max:120',
            'note' => 'nullable|string|max:100',
        ]);

        RestroomLog::create([
            'date' => today(),
            'time' => now()->format('H:i:s'),
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()->route('restroom.index')->with('success', '已打卡');
    }

    public function destroy(RestroomLog $restroom)
    {
        $restroom->delete();
        return redirect()->route('restroom.index')->with('success', '记录已删除');
    }
}

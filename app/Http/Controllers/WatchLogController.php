<?php

namespace App\Http\Controllers;

use App\Models\WatchLog;
use App\Services\AchievementService;
use Illuminate\Http\Request;

class WatchLogController extends Controller
{
    public function index(Request $request)
    {
        $query = WatchLog::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('platform')) {
            $query->where('platform', 'like', '%' . $request->platform . '%');
        }

        $logs = $query->orderBy('watch_date', 'desc')->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => WatchLog::count(),
            'this_month' => WatchLog::whereMonth('watch_date', now()->month)
                ->whereYear('watch_date', now()->year)->count(),
            'tv' => WatchLog::where('type', 'tv')->count(),
            'movie' => WatchLog::where('type', 'movie')->count(),
            'anime' => WatchLog::where('type', 'anime')->count(),
        ];

        return view('watch-logs.index', compact('logs', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'type' => 'required|in:tv,movie,anime',
            'platform' => 'nullable|string|max:50',
            'episode' => 'nullable|string|max:50',
            'watch_date' => 'required|date|before_or_equal:today',
            'rating' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string|max:500',
        ], [
            'title.required' => '请输入影视名称',
            'title.max' => '名称不能超过200个字符',
            'type.required' => '请选择类型',
            'type.in' => '类型无效',
            'watch_date.required' => '请选择观看日期',
            'watch_date.before_or_equal' => '观看日期不能超过今天',
            'rating.min' => '评分最低1分',
            'rating.max' => '评分最高5分',
        ]);

        $validated['source'] = 'manual';
        WatchLog::create($validated);

        app(AchievementService::class)->checkAll();

        return redirect()->route('watch-logs.index')->with('success', '观看记录已添加！');
    }

    public function import(Request $request)
    {
        $request->validate([
            'records' => 'required|array|min:1',
            'records.*.title' => 'required|string|max:200',
            'records.*.platform' => 'nullable|string|max:50',
            'records.*.watch_date' => 'required|date|before_or_equal:today',
            'records.*.type' => 'nullable|in:tv,movie,anime',
        ]);

        $imported = 0;
        foreach ($request->records as $record) {
            $exists = WatchLog::where('title', $record['title'])
                ->where('watch_date', $record['watch_date'])
                ->exists();

            if (!$exists) {
                WatchLog::create([
                    'title' => $record['title'],
                    'type' => $record['type'] ?? 'tv',
                    'platform' => $record['platform'] ?? null,
                    'watch_date' => $record['watch_date'],
                    'source' => 'import',
                ]);
                $imported++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "成功导入 {$imported} 条观看记录！",
            'imported' => $imported,
        ]);
    }

    public function destroy(WatchLog $watchLog)
    {
        $watchLog->delete();
        return redirect()->route('watch-logs.index')->with('success', '记录已删除');
    }
}

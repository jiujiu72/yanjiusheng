<?php

namespace App\Http\Controllers;

use App\Models\DailyRoutine;
use App\Models\Pomodoro;
use App\Services\AchievementService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoutineController extends Controller
{
    public function index()
    {
        $routines = DailyRoutine::orderBy('date', 'desc')->limit(30)->get();
        $today = DailyRoutine::where('date', today())->first();

        return view('routines.index', compact('routines', 'today'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'wake_time' => 'nullable|date_format:H:i',
            'sleep_time' => 'nullable|date_format:H:i',
            'study_hours' => 'nullable|numeric|min:0|max:24',
            'exercise_minutes' => 'nullable|numeric|min:0|max:600',
            'mood' => 'required|integer|min:1|max:5',
            'summary' => 'nullable|string|max:500',
        ], [
            'date.required' => '日期不能为空',
            'date.date' => '日期格式无效',
            'date.before_or_equal' => '不能记录未来日期',
            'wake_time.date_format' => '起床时间格式无效',
            'sleep_time.date_format' => '就寝时间格式无效',
            'study_hours.numeric' => '学习时长必须为数字',
            'study_hours.min' => '学习时长不能为负数',
            'study_hours.max' => '学习时长不能超过24小时',
            'exercise_minutes.numeric' => '运动时长必须为数字',
            'exercise_minutes.min' => '运动时长不能为负数',
            'exercise_minutes.max' => '运动时长不能超过600分钟',
            'mood.required' => '请选择今日心情',
            'mood.min' => '心情值最低为1',
            'mood.max' => '心情值最高为5',
            'summary.max' => '今日总结不能超过500个字符',
        ]);

        DailyRoutine::updateOrCreate(
            ['date' => $validated['date']],
            $validated
        );

        app(AchievementService::class)->checkAll();

        return redirect()->route('routines.index')->with('success', '作息已记录');
    }

    public function destroy(DailyRoutine $routine)
    {
        $routine->delete();
        return redirect()->route('routines.index')->with('success', '记录已删除');
    }
}

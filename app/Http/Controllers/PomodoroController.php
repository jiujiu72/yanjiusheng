<?php

namespace App\Http\Controllers;

use App\Models\Pomodoro;
use App\Services\AchievementService;
use Illuminate\Http\Request;

class PomodoroController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task' => 'nullable|string|max:255',
            'duration' => 'required|integer|min:1|max:120',
        ], [
            'task.max' => '任务名称不能超过255个字符',
            'duration.required' => '请选择时长',
            'duration.integer' => '时长必须为整数',
            'duration.min' => '时长最少为1分钟',
            'duration.max' => '时长最多为120分钟',
        ]);

        Pomodoro::create([
            'date' => today(),
            'task' => $validated['task'],
            'duration' => $validated['duration'],
            'completed' => true,
        ]);

        app(AchievementService::class)->checkAll();

        return redirect()->back()->with('success', '番茄钟已记录！');
    }
}

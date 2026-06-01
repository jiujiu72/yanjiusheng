<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Services\AchievementService;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index(AchievementService $service)
    {
        $achievements = Achievement::orderBy('category')->orderBy('id')->get();

        $categoryNames = [
            'study' => '学习成就',
            'checkin' => '打卡成就',
            'review' => '复盘成就',
            'life' => '生活成就',
        ];

        $grouped = $achievements->groupBy('category');

        $progress = [];
        foreach ($achievements as $a) {
            $progress[$a->key] = $service->getProgress($a->key);
        }

        return view('achievements.index', compact('grouped', 'categoryNames', 'progress'));
    }

    public function check(AchievementService $service)
    {
        $newlyUnlocked = $service->checkAll();

        if (count($newlyUnlocked) > 0) {
            $names = array_map(fn($a) => $a['name'], $newlyUnlocked);
            return redirect()->route('achievements.index')
                ->with('success', '🎉 恭喜解锁新成就：' . implode('、', $names));
        }

        return redirect()->route('achievements.index')
            ->with('success', '已检查所有成就条件，暂无新成就解锁');
    }
}

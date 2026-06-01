<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run()
    {
        $achievements = [
            [
                'key' => 'routine_streak_7',
                'name' => '连续打卡7天',
                'description' => '连续7天记录作息数据',
                'icon' => 'bi-calendar-check',
                'category' => 'checkin',
                'threshold' => 7,
            ],
            [
                'key' => 'routine_streak_30',
                'name' => '坚持一个月',
                'description' => '连续30天记录作息数据',
                'icon' => 'bi-calendar-heart',
                'category' => 'checkin',
                'threshold' => 30,
            ],
            [
                'key' => 'study_total_100',
                'name' => '学习百小时',
                'description' => '累计学习时长达到100小时',
                'icon' => 'bi-book',
                'category' => 'study',
                'threshold' => 100,
            ],
            [
                'key' => 'study_total_500',
                'name' => '学术达人',
                'description' => '累计学习时长达到500小时',
                'icon' => 'bi-mortarboard',
                'category' => 'study',
                'threshold' => 500,
            ],
            [
                'key' => 'pomodoro_50',
                'name' => '番茄大师',
                'description' => '完成50个番茄钟',
                'icon' => 'bi-alarm',
                'category' => 'study',
                'threshold' => 50,
            ],
            [
                'key' => 'pomodoro_200',
                'name' => '番茄传奇',
                'description' => '完成200个番茄钟',
                'icon' => 'bi-fire',
                'category' => 'study',
                'threshold' => 200,
            ],
            [
                'key' => 'literature_10',
                'name' => '初涉学海',
                'description' => '完成阅读10篇文献',
                'icon' => 'bi-journal-bookmark',
                'category' => 'study',
                'threshold' => 10,
            ],
            [
                'key' => 'literature_50',
                'name' => '博览群书',
                'description' => '完成阅读50篇文献',
                'icon' => 'bi-book-half',
                'category' => 'study',
                'threshold' => 50,
            ],
            [
                'key' => 'review_4',
                'name' => '复盘达人',
                'description' => '完成4次周/月复盘',
                'icon' => 'bi-arrow-repeat',
                'category' => 'review',
                'threshold' => 4,
            ],
            [
                'key' => 'water_streak_7',
                'name' => '饮水健康',
                'description' => '连续7天每日饮水达2000ml',
                'icon' => 'bi-droplet-fill',
                'category' => 'life',
                'threshold' => 7,
            ],
            [
                'key' => 'watch_20',
                'name' => '追剧达人',
                'description' => '累计观看记录达20条',
                'icon' => 'bi-film',
                'category' => 'life',
                'threshold' => 20,
            ],
            [
                'key' => 'todo_complete_50',
                'name' => '效率之星',
                'description' => '累计完成50个待办事项',
                'icon' => 'bi-check2-all',
                'category' => 'study',
                'threshold' => 50,
            ],
            [
                'key' => 'project_complete_1',
                'name' => '课题结题',
                'description' => '完成至少1个研究课题',
                'icon' => 'bi-trophy',
                'category' => 'study',
                'threshold' => 1,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(
                ['key' => $achievement['key']],
                $achievement
            );
        }
    }
}

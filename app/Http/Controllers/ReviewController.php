<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::orderBy('period_end', 'desc')->paginate(15);
        return view('reviews.index', compact('reviews'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'weekly');

        if ($type === 'weekly') {
            $periodStart = now()->startOfWeek();
            $periodEnd = now()->endOfWeek();
            $title = $periodStart->format('Y-m-d') . ' ~ ' . $periodEnd->format('Y-m-d') . ' 周复盘';
            $template = [
                'achievements' => "1. \n2. \n3. ",
                'problems' => "1. \n2. ",
                'next_plan' => "1. \n2. \n3. ",
                'content' => "【本周学习时长】\n\n【心得体会】\n",
            ];
        } else {
            $periodStart = now()->startOfMonth();
            $periodEnd = now()->endOfMonth();
            $title = now()->format('Y年m月') . ' 月度复盘';
            $template = [
                'achievements' => "【本月目标回顾】\n\n【完成情况】\n1. \n2. \n3. ",
                'problems' => "【未完成原因】\n\n【需要改进的地方】\n",
                'next_plan' => "【下月目标】\n1. \n2. \n3. \n\n【课题进展计划】\n",
                'content' => "【自我评价】\n\n【其他备注】\n",
            ];
        }

        return view('reviews.create', compact('type', 'periodStart', 'periodEnd', 'title', 'template'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:weekly,monthly',
            'title' => 'required|string|max:100',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'achievements' => 'nullable|string|max:2000',
            'problems' => 'nullable|string|max:2000',
            'next_plan' => 'nullable|string|max:2000',
            'content' => 'nullable|string|max:3000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        Review::create($validated);

        return redirect()->route('reviews.index')->with('success', '复盘已保存');
    }

    public function show(Review $review)
    {
        return view('reviews.show', compact('review'));
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('reviews.index')->with('success', '复盘已删除');
    }
}

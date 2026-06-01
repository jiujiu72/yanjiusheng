<?php

namespace App\Http\Controllers;

use App\Models\CaffeineLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CaffeineController extends Controller
{
    public function index()
    {
        $logs = CaffeineLog::orderBy('date', 'desc')->orderBy('time', 'desc')->limit(50)->get();

        $monthStart = now()->startOfMonth();
        $monthStats = [
            'count' => CaffeineLog::where('date', '>=', $monthStart)->count(),
            'total_price' => CaffeineLog::where('date', '>=', $monthStart)->sum('price'),
            'total_caffeine' => CaffeineLog::where('date', '>=', $monthStart)->sum('caffeine_mg'),
        ];

        $typeLabels = [
            'coffee' => '咖啡',
            'black_tea' => '红茶',
            'green_tea' => '绿茶',
            'milk_tea' => '奶茶',
            'other' => '其他',
        ];

        return view('caffeine.index', compact('logs', 'monthStats', 'typeLabels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:coffee,black_tea,green_tea,milk_tea,other',
            'name' => 'required|string|min:1|max:100',
            'caffeine_mg' => 'nullable|integer|min:0|max:1000',
            'price' => 'nullable|numeric|min:0|max:9999.99',
            'note' => 'nullable|string|max:200',
        ], [
            'type.required' => '请选择饮品类型',
            'type.in' => '饮品类型无效，请从列表中选择',
            'name.required' => '请填写饮品名称或点击快捷选项',
            'name.min' => '饮品名称不能为空',
            'name.max' => '饮品名称不能超过100个字符',
            'caffeine_mg.integer' => '咖啡因含量必须为整数',
            'caffeine_mg.min' => '咖啡因含量不能为负数',
            'caffeine_mg.max' => '咖啡因含量不能超过1000mg',
            'price.numeric' => '价格必须为数字',
            'price.min' => '价格不能为负数',
            'price.max' => '价格不能超过9999.99元',
            'note.max' => '备注不能超过200个字符',
        ]);

        CaffeineLog::create(array_merge($validated, [
            'date' => today(),
            'time' => now()->format('H:i:s'),
        ]));

        return redirect()->route('caffeine.index')->with('success', '饮品已记录');
    }

    public function destroy(CaffeineLog $caffeine)
    {
        $caffeine->delete();
        return redirect()->route('caffeine.index')->with('success', '记录已删除');
    }
}

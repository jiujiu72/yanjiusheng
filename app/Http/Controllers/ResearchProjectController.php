<?php

namespace App\Http\Controllers;

use App\Models\ResearchProject;
use Illuminate\Http\Request;

class ResearchProjectController extends Controller
{
    public function index()
    {
        $projects = ResearchProject::orderBy('updated_at', 'desc')->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:planning,in_progress,review,completed',
            'progress' => 'required|integer|min:0|max:100',
            'start_date' => 'nullable|date|before_or_equal:due_date',
            'due_date' => 'nullable|date',
        ], [
            'title.required' => '课题名称不能为空',
            'title.max' => '课题名称不能超过255个字符',
            'description.max' => '描述不能超过1000个字符',
            'status.required' => '请选择课题状态',
            'status.in' => '课题状态无效',
            'progress.required' => '请填写进度',
            'progress.integer' => '进度必须为整数',
            'progress.min' => '进度不能小于0',
            'progress.max' => '进度不能超过100',
            'start_date.date' => '开始日期格式无效',
            'start_date.before_or_equal' => '开始日期不能晚于截止日期',
            'due_date.date' => '截止日期格式无效',
        ]);

        ResearchProject::create($validated);
        return redirect()->route('projects.index')->with('success', '课题已创建');
    }

    public function edit(ResearchProject $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, ResearchProject $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:planning,in_progress,review,completed',
            'progress' => 'required|integer|min:0|max:100',
            'start_date' => 'nullable|date|before_or_equal:due_date',
            'due_date' => 'nullable|date',
        ], [
            'title.required' => '课题名称不能为空',
            'title.max' => '课题名称不能超过255个字符',
            'description.max' => '描述不能超过1000个字符',
            'status.required' => '请选择课题状态',
            'status.in' => '课题状态无效',
            'progress.required' => '请填写进度',
            'progress.integer' => '进度必须为整数',
            'progress.min' => '进度不能小于0',
            'progress.max' => '进度不能超过100',
            'start_date.date' => '开始日期格式无效',
            'start_date.before_or_equal' => '开始日期不能晚于截止日期',
            'due_date.date' => '截止日期格式无效',
        ]);

        $project->update($validated);
        return redirect()->route('projects.index')->with('success', '课题已更新');
    }

    public function destroy(ResearchProject $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', '课题已删除');
    }
}

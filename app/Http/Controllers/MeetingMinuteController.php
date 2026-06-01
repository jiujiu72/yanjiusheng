<?php

namespace App\Http\Controllers;

use App\Models\MeetingMinute;
use App\Models\ResearchProject;
use Illuminate\Http\Request;

class MeetingMinuteController extends Controller
{
    public function index(Request $request)
    {
        $query = MeetingMinute::with('researchProject');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('project_id')) {
            $query->where('research_project_id', $request->project_id);
        }
        if ($request->filled('date_from')) {
            $query->where('meeting_time', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('meeting_time', '<=', $request->date_to . ' 23:59:59');
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('topic', 'like', "%{$search}%")
                  ->orWhere('attendees', 'like', "%{$search}%")
                  ->orWhere('conclusions', 'like', "%{$search}%")
                  ->orWhere('action_items', 'like', "%{$search}%");
            });
        }

        $meetings = $query->orderBy('meeting_time', 'desc')->paginate(15);
        $projects = ResearchProject::orderBy('title')->get();

        return view('meetings.index', compact('meetings', 'projects'));
    }

    public function create()
    {
        $projects = ResearchProject::orderBy('title')->get();
        return view('meetings.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());
        MeetingMinute::create($validated);
        return redirect()->route('meetings.index')->with('success', '会议纪要已创建');
    }

    public function show(MeetingMinute $meeting)
    {
        $meeting->load('researchProject');
        return view('meetings.show', compact('meeting'));
    }

    public function edit(MeetingMinute $meeting)
    {
        $projects = ResearchProject::orderBy('title')->get();
        return view('meetings.edit', compact('meeting', 'projects'));
    }

    public function update(Request $request, MeetingMinute $meeting)
    {
        $validated = $request->validate($this->rules(), $this->messages());
        $meeting->update($validated);
        return redirect()->route('meetings.index')->with('success', '会议纪要已更新');
    }

    public function destroy(MeetingMinute $meeting)
    {
        $meeting->delete();
        return redirect()->route('meetings.index')->with('success', '会议纪要已删除');
    }

    private function rules()
    {
        return [
            'type' => 'required|in:online_academic,offline_academic,group_meeting',
            'topic' => 'required|string|max:255',
            'attendees' => 'nullable|string',
            'action_items' => 'nullable|string',
            'conclusions' => 'nullable|string',
            'notes' => 'nullable|string',
            'research_project_id' => 'nullable|exists:research_projects,id',
            'meeting_time' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:1',
        ];
    }

    private function messages()
    {
        return [
            'type.required' => '请选择会议类型',
            'type.in' => '会议类型不正确',
            'topic.required' => '会议主题不能为空',
            'topic.max' => '会议主题不能超过255个字符',
            'meeting_time.required' => '请填写会议时间',
            'meeting_time.date' => '会议时间格式不正确',
            'duration_minutes.integer' => '会议时长必须为整数',
            'duration_minutes.min' => '会议时长至少为1分钟',
            'research_project_id.exists' => '所选课题不存在',
        ];
    }
}

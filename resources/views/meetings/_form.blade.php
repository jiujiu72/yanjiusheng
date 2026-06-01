@php
    $typeLabels = [
        'online_academic' => '线上学术会议',
        'offline_academic' => '线下学术会议',
        'group_meeting' => '组会',
    ];
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">会议类型 <span class="text-danger">*</span></label>
        <select name="type" class="form-select @error('type') is-invalid @enderror">
            <option value="">请选择类型</option>
            @foreach($typeLabels as $value => $label)
            <option value="{{ $value }}" {{ old('type', $meeting->type ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">关联课题</label>
        <select name="research_project_id" class="form-select @error('research_project_id') is-invalid @enderror">
            <option value="">不关联课题</option>
            @foreach($projects as $project)
            <option value="{{ $project->id }}" {{ old('research_project_id', $meeting->research_project_id ?? '') == $project->id ? 'selected' : '' }}>{{ $project->title }}</option>
            @endforeach
        </select>
        @error('research_project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">会议主题 <span class="text-danger">*</span></label>
    <input type="text" name="topic" class="form-control @error('topic') is-invalid @enderror"
           value="{{ old('topic', $meeting->topic ?? '') }}" placeholder="输入会议主题">
    @error('topic')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">会议时间 <span class="text-danger">*</span></label>
        <input type="datetime-local" name="meeting_time" class="form-control @error('meeting_time') is-invalid @enderror"
               value="{{ old('meeting_time', isset($meeting) ? $meeting->meeting_time->format('Y-m-d\TH:i') : '') }}">
        @error('meeting_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">会议时长（分钟）</label>
        <input type="number" name="duration_minutes" class="form-control @error('duration_minutes') is-invalid @enderror"
               value="{{ old('duration_minutes', $meeting->duration_minutes ?? '') }}" min="1" placeholder="如：60">
        @error('duration_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">参会人员</label>
    <textarea name="attendees" class="form-control @error('attendees') is-invalid @enderror"
              rows="2" placeholder="多人可用逗号或换行分隔">{{ old('attendees', $meeting->attendees ?? '') }}</textarea>
    @error('attendees')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">待办任务</label>
    <textarea name="action_items" class="form-control @error('action_items') is-invalid @enderror"
              rows="3" placeholder="会议中产生的待办事项...">{{ old('action_items', $meeting->action_items ?? '') }}</textarea>
    @error('action_items')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">会议结论</label>
    <textarea name="conclusions" class="form-control @error('conclusions') is-invalid @enderror"
              rows="3" placeholder="会议得出的结论或决定...">{{ old('conclusions', $meeting->conclusions ?? '') }}</textarea>
    @error('conclusions')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">补充备注</label>
    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
              rows="2" placeholder="其他备注信息...">{{ old('notes', $meeting->notes ?? '') }}</textarea>
    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

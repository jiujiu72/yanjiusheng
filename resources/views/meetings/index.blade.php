@extends('layouts.app')

@section('title', '会议纪要 - 研究生自我管理系统')

@section('content')
@php
    $typeLabels = [
        'online_academic' => '线上学术会议',
        'offline_academic' => '线下学术会议',
        'group_meeting' => '组会',
    ];
    $typeBadges = [
        'online_academic' => 'bg-info',
        'offline_academic' => 'bg-success',
        'group_meeting' => 'bg-primary',
    ];
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-people-fill"></i> 会议纪要</h4>
    <a href="{{ route('meetings.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> 新建纪要
    </a>
</div>

{{-- 筛选栏 --}}
<div class="card mb-4">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('meetings.index') }}" class="row g-2 align-items-center">
            <div class="col-md-2">
                <select name="type" class="form-select form-select-sm">
                    <option value="">全部类型</option>
                    @foreach($typeLabels as $value => $label)
                    <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="project_id" class="form-select form-select-sm">
                    <option value="">全部课题</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control form-control-sm"
                       value="{{ request('date_from') }}" placeholder="开始日期">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control form-control-sm"
                       value="{{ request('date_to') }}" placeholder="结束日期">
            </div>
            <div class="col-md-2">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="搜索主题/人员..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bi bi-search"></i></button>
                <a href="{{ route('meetings.index') }}" class="btn btn-outline-secondary btn-sm">重置</a>
            </div>
        </form>
    </div>
</div>

{{-- 会议列表 --}}
@if($meetings->isEmpty())
<div class="text-center text-muted py-5">
    <i class="bi bi-people-fill" style="font-size:3rem;"></i>
    <p class="mt-3">还没有会议纪要，点击"新建纪要"开始记录吧！</p>
</div>
@else
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>类型</th>
                    <th>会议主题</th>
                    <th>关联课题</th>
                    <th>会议时间</th>
                    <th>时长</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($meetings as $meeting)
                <tr>
                    <td>
                        <span class="badge {{ $typeBadges[$meeting->type] ?? 'bg-secondary' }}">
                            {{ $typeLabels[$meeting->type] ?? $meeting->type }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('meetings.show', $meeting) }}" class="text-decoration-none">
                            {{ Str::limit($meeting->topic, 30) }}
                        </a>
                    </td>
                    <td>
                        @if($meeting->researchProject)
                        <span class="badge bg-light text-dark">{{ Str::limit($meeting->researchProject->title, 15) }}</span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="small">{{ $meeting->meeting_time->format('Y-m-d H:i') }}</td>
                    <td class="small">{{ $meeting->duration_minutes ? $meeting->duration_minutes . '分钟' : '-' }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-outline-info btn-sm" title="查看">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('meetings.edit', $meeting) }}" class="btn btn-outline-primary btn-sm" title="编辑">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('meetings.destroy', $meeting) }}" method="POST"
                                  onsubmit="return confirm('确定删除该会议纪要？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="删除">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $meetings->withQueryString()->links() }}
</div>
@endif
@endsection

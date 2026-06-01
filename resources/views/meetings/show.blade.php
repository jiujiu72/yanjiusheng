@extends('layouts.app')

@section('title', '会议纪要详情 - 研究生自我管理系统')

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
    <h4 class="mb-0"><i class="bi bi-people-fill"></i> 会议纪要详情</h4>
    <div>
        <a href="{{ route('meetings.edit', $meeting) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-pencil"></i> 编辑
        </a>
        <a href="{{ route('meetings.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> 返回列表
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <span class="badge {{ $typeBadges[$meeting->type] ?? 'bg-secondary' }}">{{ $typeLabels[$meeting->type] ?? $meeting->type }}</span>
            <strong class="ms-2">{{ $meeting->topic }}</strong>
        </span>
        <span class="text-muted small">
            <i class="bi bi-clock"></i> {{ $meeting->meeting_time->format('Y-m-d H:i') }}
            @if($meeting->duration_minutes)
                · {{ $meeting->duration_minutes }}分钟
            @endif
        </span>
    </div>
    <div class="card-body">
        @if($meeting->researchProject)
        <div class="mb-3">
            <strong><i class="bi bi-kanban"></i> 关联课题：</strong>
            <span class="badge bg-light text-dark">{{ $meeting->researchProject->title }}</span>
        </div>
        @endif

        @if($meeting->attendees)
        <div class="mb-3">
            <strong><i class="bi bi-person-lines-fill"></i> 参会人员：</strong>
            <p class="mb-0 mt-1">{{ $meeting->attendees }}</p>
        </div>
        @endif

        @if($meeting->action_items)
        <div class="mb-3">
            <strong><i class="bi bi-list-check"></i> 待办任务：</strong>
            <div class="mt-1 p-3 bg-light rounded" style="white-space:pre-line;">{{ $meeting->action_items }}</div>
        </div>
        @endif

        @if($meeting->conclusions)
        <div class="mb-3">
            <strong><i class="bi bi-chat-square-text"></i> 会议结论：</strong>
            <div class="mt-1 p-3 bg-light rounded" style="white-space:pre-line;">{{ $meeting->conclusions }}</div>
        </div>
        @endif

        @if($meeting->notes)
        <div class="mb-3">
            <strong><i class="bi bi-sticky"></i> 补充备注：</strong>
            <div class="mt-1 p-3 bg-light rounded" style="white-space:pre-line;">{{ $meeting->notes }}</div>
        </div>
        @endif

        <div class="text-muted small mt-3">
            <i class="bi bi-calendar-plus"></i> 记录于 {{ $meeting->created_at->format('Y-m-d H:i') }}
            @if($meeting->updated_at->gt($meeting->created_at))
                · 最后编辑 {{ $meeting->updated_at->format('Y-m-d H:i') }}
            @endif
        </div>
    </div>
</div>
@endsection

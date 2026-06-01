@extends('layouts.app')

@section('title', '仪表盘 - 研究生自我管理系统')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-house-door"></i> 仪表盘</h4>
    <span class="text-muted">{{ now()->format('Y年m月d日 l') }}</span>
</div>

{{-- 每日一言 --}}
<div class="card mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="card-body text-white py-4">
        <blockquote class="mb-0">
            <p class="fs-5 mb-2"><i class="bi bi-quote"></i> {{ $daily_quote['text'] }}</p>
            <footer class="text-white-50">—— {{ $daily_quote['author'] }}</footer>
        </blockquote>
    </div>
</div>

{{-- 统计卡片 --}}
<div class="row g-3 mb-4">
    <div class="col-md-2 col-6">
        <div class="card stat-card" style="border-left-color: #4a6cf7;">
            <div class="card-body">
                <div class="stat-number" style="color:#4a6cf7;">{{ $stats['active_projects'] }}</div>
                <div class="text-muted small">进行中课题</div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card stat-card" style="border-left-color: #f5a623;">
            <div class="card-body">
                <div class="stat-number" style="color:#f5a623;">{{ $stats['pending_todos'] }}</div>
                <div class="text-muted small">待办事项</div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card stat-card" style="border-left-color: #50c878;">
            <div class="card-body">
                <div class="stat-number" style="color:#50c878;">{{ $stats['today_study_hours'] }}h</div>
                <div class="text-muted small">今日学习</div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card stat-card" style="border-left-color: #e74c3c;">
            <div class="card-body">
                <div class="stat-number" style="color:#e74c3c;">{{ $stats['unread_literatures'] }}</div>
                <div class="text-muted small">未读文献</div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card stat-card" style="border-left-color: #17a2b8;">
            <div class="card-body">
                <div class="stat-number" style="color:#17a2b8;">{{ $stats['today_water'] }}ml</div>
                <div class="text-muted small">今日饮水</div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card stat-card" style="border-left-color: #6a1b9a;">
            <div class="card-body">
                <div class="stat-number" style="color:#6a1b9a; font-size:1.5rem;">¥{{ number_format($stats['pending_reimburse'], 0) }}</div>
                <div class="text-muted small">待报销</div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card stat-card" style="border-left-color: #f5a623;">
            <div class="card-body">
                <div class="stat-number" style="color:#f5a623;">{{ $stats['unlocked_achievements'] }}</div>
                <div class="text-muted small">已解锁成就</div>
            </div>
        </div>
    </div>
    @if($stats['overdue_borrowings'] > 0)
    <div class="col-md-2 col-6">
        <div class="card stat-card" style="border-left-color: #dc3545;">
            <div class="card-body">
                <div class="stat-number" style="color:#dc3545;">{{ $stats['overdue_borrowings'] }}</div>
                <div class="text-muted small">逾期未还</div>
            </div>
        </div>
    </div>
    @endif
    @if($stats['consumable_month_cost'] > 0)
    <div class="col-md-2 col-6">
        <div class="card stat-card" style="border-left-color: #e67e22;">
            <div class="card-body">
                <div class="stat-number" style="color:#e67e22; font-size:1.5rem;">&yen;{{ number_format($stats['consumable_month_cost'], 0) }}</div>
                <div class="text-muted small">本月耗材</div>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="row g-4">
    {{-- 倒计时 --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-hourglass-split"></i> 重要倒计时</div>
            <div class="card-body">
                @foreach($countdown as $event)
                    <div class="d-flex align-items-center mb-3 pb-2 border-bottom gap-2">
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-bold text-truncate" title="{{ $event['full_name'] }}">{{ $event['name'] }}</div>
                            <small class="text-muted">{{ $event['date']->format('Y-m-d') }}</small>
                        </div>
                        <span class="badge {{ $event['days_left'] <= 7 ? 'bg-danger' : ($event['days_left'] <= 30 ? 'bg-warning' : 'bg-info') }} rounded-pill fs-6 flex-shrink-0">
                            {{ $event['days_left'] }}天
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 近期待办 --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-list-check"></i> 近期待办</div>
            <div class="card-body">
                @forelse($recent_todos as $todo)
                    <div class="d-flex align-items-center mb-2 gap-1">
                        <span class="badge badge-{{ $todo->priority }} flex-shrink-0">{{ $todo->priority }}</span>
                        <span class="flex-grow-1 text-truncate {{ $todo->completed ? 'text-decoration-line-through text-muted' : '' }}" title="{{ $todo->title }}">
                            {{ $todo->title }}
                        </span>
                        @if($todo->due_date)
                            <small class="text-muted flex-shrink-0 ms-1">{{ $todo->due_date->format('m/d') }}</small>
                        @endif
                    </div>
                @empty
                    <p class="text-muted text-center">暂无待办事项</p>
                @endforelse
                <a href="{{ route('todos.index') }}" class="btn btn-sm btn-outline-primary mt-2 w-100">查看全部</a>
            </div>
        </div>
    </div>

    {{-- 课题进度 --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-kanban"></i> 课题进度</div>
            <div class="card-body">
                @forelse($recent_projects as $project)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1 gap-1">
                            <span class="fw-bold small project-title flex-grow-1" title="{{ $project->title }}">{{ $project->title }}</span>
                            <span class="badge badge-{{ $project->status }} small flex-shrink-0">
                                {{ ['planning'=>'规划中','in_progress'=>'进行中','review'=>'审核中','completed'=>'已完成'][$project->status] }}
                            </span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $project->progress }}%; background: var(--primary-color)"></div>
                        </div>
                        <small class="text-muted">{{ $project->progress }}%</small>
                    </div>
                @empty
                    <p class="text-muted text-center">暂无课题</p>
                @endforelse
                <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-primary mt-2 w-100">查看全部</a>
            </div>
        </div>
    </div>
</div>

{{-- 番茄钟快速记录 --}}
<div class="card mt-4">
    <div class="card-header"><i class="bi bi-stopwatch"></i> 快速番茄钟 | 本周完成 {{ $stats['week_pomodoros'] }} 个</div>
    <div class="card-body">
        <form action="{{ route('pomodoros.store') }}" method="POST" class="row g-2 align-items-end">
            @csrf
            @submitToken
            <div class="col-md-5">
                <input type="text" name="task" class="form-control @error('task') is-invalid @enderror"
                       placeholder="专注任务（选填）" maxlength="255">
                @error('task')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <select name="duration" class="form-select">
                    <option value="25">25 分钟</option>
                    <option value="45">45 分钟</option>
                    <option value="60">60 分钟</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg"></i> 记录</button>
            </div>
        </form>
    </div>
</div>
@endsection

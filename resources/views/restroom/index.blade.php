@extends('layouts.app')

@section('title', '如厕打卡')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-door-open"></i> 如厕打卡</h4>
</div>

{{-- 快速打卡 --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <div style="font-size:2.5rem; font-weight:700; color:#6a1b9a;">{{ $todayCount }}</div>
                <div class="text-muted">今日次数</div>
            </div>
            <div class="col-md-9">
                <form action="{{ route('restroom.store') }}" method="POST">
                    @csrf
                    @submitToken
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">时长(分钟)</label>
                            <input type="number" name="duration_minutes" class="form-control"
                                   placeholder="可选" min="1" max="120">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">备注</label>
                            <input type="text" name="note" class="form-control" placeholder="可选">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-circle"></i> 打卡
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- 本周统计 --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-bar-chart"></i> 近7天统计</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>日期</th><th>次数</th></tr></thead>
                    <tbody>
                        @forelse($weekStats as $stat)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($stat->date)->format('m-d') }}</td>
                            <td>{{ $stat->count }} 次</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center text-muted py-3">暂无数据</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 今日记录 & 最近记录 --}}
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-clock-history"></i> 打卡记录</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>日期</th><th>时间</th><th>时长</th><th>备注</th><th>操作</th></tr></thead>
                    <tbody>
                        @forelse($recentLogs as $log)
                        <tr>
                            <td>{{ $log->date->format('m-d') }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->time)->format('H:i') }}</td>
                            <td>{{ $log->duration_minutes ? $log->duration_minutes.'分钟' : '-' }}</td>
                            <td class="small">{{ $log->note ?? '' }}</td>
                            <td>
                                <form action="{{ route('restroom.destroy', $log) }}" method="POST" onsubmit="return confirm('确定删除？')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">暂无记录</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

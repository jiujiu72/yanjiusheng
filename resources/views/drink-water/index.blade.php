@extends('layouts.app')

@section('title', '饮水打卡')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-droplet"></i> 饮水打卡</h4>
    <span class="text-muted">目标: {{ $target }}ml / 天</span>
</div>

{{-- 今日进度 --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-4 text-center">
                <div style="font-size:2.5rem; font-weight:700; color:{{ $todayTotal >= $target ? '#28a745' : '#4a6cf7' }};">
                    {{ $todayTotal }}ml
                </div>
                <div class="text-muted">今日已饮水</div>
                <div class="progress mt-2" style="height:12px;">
                    <div class="progress-bar {{ $todayTotal >= $target ? 'bg-success' : '' }}"
                         style="width:{{ min(100, round($todayTotal / $target * 100)) }}%; background:{{ $todayTotal >= $target ? '' : 'var(--primary-color)' }}">
                        {{ round($todayTotal / $target * 100) }}%
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <form action="{{ route('drink-water.store') }}" method="POST">
                    @csrf
                    @submitToken
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">水量</label>
                            <select name="amount" class="form-select">
                                <option value="200">200ml (小杯)</option>
                                <option value="250" selected>250ml (一杯)</option>
                                <option value="350">350ml (中杯)</option>
                                <option value="500">500ml (大杯)</option>
                                <option value="1000">1000ml (水壶)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">类型</label>
                            <select name="type" class="form-select">
                                <option value="warm_water">温水</option>
                                <option value="cold_water">凉水</option>
                                <option value="other">其他</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-droplet-fill"></i> 打卡
                            </button>
                        </div>
                    </div>
                </form>
                <div class="mt-3">
                    <span class="text-muted small">快捷打卡:</span>
                    <form action="{{ route('drink-water.store') }}" method="POST" class="d-inline">
                        @csrf
                        @submitToken
                        <input type="hidden" name="amount" value="250">
                        <input type="hidden" name="type" value="warm_water">
                        <button type="submit" class="btn btn-sm btn-outline-primary ms-2">+250ml</button>
                    </form>
                    <form action="{{ route('drink-water.store') }}" method="POST" class="d-inline">
                        @csrf
                        @submitToken
                        <input type="hidden" name="amount" value="500">
                        <input type="hidden" name="type" value="warm_water">
                        <button type="submit" class="btn btn-sm btn-outline-primary ms-1">+500ml</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- 今日记录 --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-clock-history"></i> 今日记录</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>时间</th><th>水量</th><th>类型</th><th>操作</th></tr></thead>
                    <tbody>
                        @forelse($todayLogs as $log)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($log->time)->format('H:i') }}</td>
                            <td>{{ $log->amount }}ml</td>
                            <td>{{ ['warm_water'=>'温水','cold_water'=>'凉水','other'=>'其他'][$log->type] }}</td>
                            <td>
                                <form action="{{ route('drink-water.destroy', $log) }}" method="POST" onsubmit="return confirm('确定删除？')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">今天还没喝水哦，快来打卡吧！</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 近期统计 --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-bar-chart"></i> 近期每日饮水量</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>日期</th><th>总量</th><th>次数</th><th>达标</th></tr></thead>
                    <tbody>
                        @forelse($recentLogs as $day)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($day->date)->format('m-d') }}</td>
                            <td>{{ $day->total }}ml</td>
                            <td>{{ $day->count }}次</td>
                            <td>{!! $day->total >= $target ? '<span class="text-success"><i class="bi bi-check-circle-fill"></i></span>' : '<span class="text-muted"><i class="bi bi-x-circle"></i></span>' !!}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">暂无历史记录</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

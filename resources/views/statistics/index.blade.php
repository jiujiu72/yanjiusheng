@extends('layouts.app')

@section('title', '数据统计')

@section('styles')
<style>
    .stat-overview .card-body { padding: 1.25rem 1rem; }
    .stat-overview .stat-value { font-size: 1.75rem; font-weight: 700; line-height: 1.2; }
    .stat-overview .stat-label { font-size: 0.8rem; margin-top: 0.25rem; }
    .chart-card { height: 100%; }
    .chart-card .card-header { padding: 0.75rem 1rem; font-size: 0.9rem; }
    .chart-card .card-body { padding: 1rem; display: flex; align-items: center; justify-content: center; }
    .chart-card canvas { width: 100% !important; max-height: 220px; }
    .chart-row { margin-bottom: 0; }
</style>
@endsection

@section('content')
<h4 class="mb-4"><i class="bi bi-graph-up"></i> 数据统计</h4>

{{-- 概览数据 --}}
<div class="row g-3 mb-4 stat-overview">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="stat-value text-primary">{{ $totalStudyHours }}h</div>
                <div class="stat-label text-muted">30天总学习</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="stat-value text-success">{{ $avgStudyHours }}h</div>
                <div class="stat-label text-muted">日均学习</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="stat-value text-warning">{{ $avgMood }}/5</div>
                <div class="stat-label text-muted">平均心情</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="stat-value text-info">{{ $todoStats['completed'] }}</div>
                <div class="stat-label text-muted">已完成待办</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="stat-value text-danger">{{ $todoStats['pending'] }}</div>
                <div class="stat-label text-muted">待完成</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="stat-value" style="color:#6a1b9a;">{{ $litStats['finished'] }}</div>
                <div class="stat-label text-muted">已读文献</div>
            </div>
        </div>
    </div>
</div>

{{-- 第一行：学习趋势（主图）+ 待办完成率 --}}
<div class="row g-3 mb-3 chart-row">
    <div class="col-lg-8">
        <div class="card chart-card">
            <div class="card-header"><i class="bi bi-bar-chart"></i> 学习时长趋势（近30天）</div>
            <div class="card-body">
                <canvas id="studyChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card chart-card">
            <div class="card-header"><i class="bi bi-pie-chart"></i> 待办完成率</div>
            <div class="card-body">
                <canvas id="todoChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- 第二行：心情趋势 + 番茄钟 --}}
<div class="row g-3 mb-3 chart-row">
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header"><i class="bi bi-emoji-smile"></i> 心情趋势</div>
            <div class="card-body">
                <canvas id="moodChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header"><i class="bi bi-stopwatch"></i> 每周番茄钟数</div>
            <div class="card-body">
                <canvas id="pomodoroChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- 第三行：文献进度 + 课题状态 --}}
<div class="row g-3 chart-row">
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header"><i class="bi bi-book"></i> 文献阅读进度</div>
            <div class="card-body">
                <canvas id="litChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header"><i class="bi bi-kanban"></i> 课题状态分布</div>
            <div class="card-body">
                <canvas id="projectChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const studyData = @json($studyData);
    const moodData = @json($moodData);
    const todoStats = @json($todoStats);
    const litStats = @json($litStats);
    const projectStats = @json($projectStats);
    const pomodorosByWeek = @json($pomodorosByWeek);

    var chartOpts = { responsive: true, maintainAspectRatio: false };

    new Chart(document.getElementById('studyChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(studyData).map(function(d){ return d.substring(5); }),
            datasets: [{
                label: '学习时长(h)',
                data: Object.values(studyData),
                backgroundColor: 'rgba(74, 108, 247, 0.6)',
                borderRadius: 4
            }]
        },
        options: Object.assign({}, chartOpts, { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } })
    });

    new Chart(document.getElementById('todoChart'), {
        type: 'doughnut',
        data: {
            labels: ['已完成', '未完成'],
            datasets: [{ data: [todoStats.completed, todoStats.pending], backgroundColor: ['#50c878', '#e74c3c'] }]
        },
        options: Object.assign({}, chartOpts, { plugins: { legend: { position: 'bottom' } } })
    });

    new Chart(document.getElementById('moodChart'), {
        type: 'line',
        data: {
            labels: Object.keys(moodData).map(function(d){ return d.substring(5); }),
            datasets: [{
                label: '心情指数',
                data: Object.values(moodData),
                borderColor: '#f5a623',
                backgroundColor: 'rgba(245, 166, 35, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: Object.assign({}, chartOpts, { scales: { y: { min: 1, max: 5 } } })
    });

    new Chart(document.getElementById('pomodoroChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(pomodorosByWeek),
            datasets: [{
                label: '番茄数',
                data: Object.values(pomodorosByWeek),
                backgroundColor: 'rgba(231, 76, 60, 0.6)',
                borderRadius: 4
            }]
        },
        options: Object.assign({}, chartOpts, { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } })
    });

    new Chart(document.getElementById('litChart'), {
        type: 'doughnut',
        data: {
            labels: ['未读', '在读', '已读'],
            datasets: [{ data: [litStats.unread, litStats.reading, litStats.finished], backgroundColor: ['#6c757d', '#17a2b8', '#28a745'] }]
        },
        options: Object.assign({}, chartOpts, { plugins: { legend: { position: 'bottom' } } })
    });

    new Chart(document.getElementById('projectChart'), {
        type: 'doughnut',
        data: {
            labels: ['规划中', '进行中', '审核中', '已完成'],
            datasets: [{ data: [projectStats.planning, projectStats.in_progress, projectStats.review, projectStats.completed], backgroundColor: ['#1565c0', '#e65100', '#6a1b9a', '#2e7d32'] }]
        },
        options: Object.assign({}, chartOpts, { plugins: { legend: { position: 'bottom' } } })
    });
</script>
@endsection

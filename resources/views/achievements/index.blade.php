@extends('layouts.app')

@section('title', '成就勋章 - 研究生自我管理系统')

@section('styles')
<style>
    .achievement-card {
        position: relative;
        border-radius: 16px;
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
    }
    .achievement-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
    .achievement-card.locked { opacity: 0.6; filter: grayscale(0.5); }
    .achievement-card.locked .achievement-icon { color: #adb5bd !important; }
    .achievement-icon { font-size: 2.5rem; }
    .achievement-unlocked-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #28a745;
        color: #fff;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
    }
    .progress-thin { height: 6px; border-radius: 3px; }
    .category-tab { cursor: pointer; }
    .category-study { color: #4a6cf7; }
    .category-checkin { color: #28a745; }
    .category-review { color: #6f42c1; }
    .category-life { color: #fd7e14; }
    .unlocked-time { font-size: 0.7rem; color: #6c757d; }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-trophy"></i> 成就勋章</h4>
    <form action="{{ route('achievements.check') }}" method="POST" class="d-inline">
        @csrf
        @submitToken
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="bi bi-arrow-clockwise"></i> 检查成就
        </button>
    </form>
</div>

{{-- 统计概览 --}}
@php
    $totalCount = 0;
    $unlockedCount = 0;
    foreach ($grouped as $achievements) {
        foreach ($achievements as $a) {
            $totalCount++;
            if ($a->unlocked) $unlockedCount++;
        }
    }
@endphp
<div class="card mb-4">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <span class="fs-5 fw-bold text-primary">{{ $unlockedCount }}</span>
                <span class="text-muted">/</span>
                <span>{{ $totalCount }}</span>
                <span class="text-muted ms-1">已解锁</span>
            </div>
            <div class="col">
                <div class="progress progress-thin">
                    <div class="progress-bar bg-primary" style="width: {{ $totalCount > 0 ? ($unlockedCount/$totalCount*100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 分类展示 --}}
@foreach($grouped as $category => $achievements)
<div class="mb-4">
    <h5 class="mb-3 category-{{ $category }}">
        <i class="bi bi-{{ $category == 'study' ? 'book' : ($category == 'checkin' ? 'calendar-check' : ($category == 'review' ? 'arrow-repeat' : 'heart')) }}"></i>
        {{ $categoryNames[$category] ?? $category }}
        <small class="text-muted fw-normal">({{ $achievements->where('unlocked', true)->count() }}/{{ $achievements->count() }})</small>
    </h5>
    <div class="row g-3">
        @foreach($achievements as $achievement)
        <div class="col-md-4 col-lg-3">
            <div class="card achievement-card {{ $achievement->unlocked ? 'unlocked' : 'locked' }}">
                @if($achievement->unlocked)
                    <div class="achievement-unlocked-badge"><i class="bi bi-check"></i></div>
                @endif
                <div class="card-body text-center py-4">
                    <div class="achievement-icon mb-2 category-{{ $achievement->category }}">
                        <i class="bi {{ $achievement->icon }}"></i>
                    </div>
                    <h6 class="mb-1">{{ $achievement->name }}</h6>
                    <small class="text-muted d-block mb-2">{{ $achievement->description }}</small>

                    @if($achievement->unlocked)
                        <span class="unlocked-time">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            {{ $achievement->unlocked_at->format('Y-m-d') }} 解锁
                        </span>
                    @else
                        @php $p = $progress[$achievement->key] ?? ['current' => 0, 'target' => 1]; @endphp
                        <div class="progress progress-thin mb-1">
                            <div class="progress-bar" style="width: {{ min(100, $p['target'] > 0 ? ($p['current']/$p['target']*100) : 0) }}%"></div>
                        </div>
                        <small class="text-muted">{{ $p['current'] }} / {{ $p['target'] }}</small>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach

@if($grouped->isEmpty())
<div class="text-center text-muted py-5">
    <i class="bi bi-trophy" style="font-size:3rem;"></i>
    <p class="mt-3">成就系统尚未初始化，请运行数据库种子</p>
</div>
@endif
@endsection

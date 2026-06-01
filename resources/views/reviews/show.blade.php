@extends('layouts.app')

@section('title', $review->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">
        <span class="badge {{ $review->type == 'weekly' ? 'bg-info' : 'bg-primary' }} me-2">
            {{ $review->type == 'weekly' ? '周复盘' : '月复盘' }}
        </span>
        {{ $review->title }}
    </h4>
    <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary btn-sm">返回列表</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row text-muted small">
            <div class="col-auto">周期: {{ $review->period_start->format('Y-m-d') }} ~ {{ $review->period_end->format('Y-m-d') }}</div>
            @if($review->rating)
            <div class="col-auto">自评: {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
            @endif
            <div class="col-auto">创建于: {{ $review->created_at->format('Y-m-d H:i') }}</div>
        </div>
    </div>
</div>

@if($review->achievements)
<div class="card mb-3">
    <div class="card-header"><i class="bi bi-trophy"></i> {{ $review->type == 'weekly' ? '本周成果' : '本月成果' }}</div>
    <div class="card-body">
        <div style="white-space:pre-wrap;">{{ $review->achievements }}</div>
    </div>
</div>
@endif

@if($review->problems)
<div class="card mb-3">
    <div class="card-header"><i class="bi bi-exclamation-triangle"></i> 问题与反思</div>
    <div class="card-body">
        <div style="white-space:pre-wrap;">{{ $review->problems }}</div>
    </div>
</div>
@endif

@if($review->next_plan)
<div class="card mb-3">
    <div class="card-header"><i class="bi bi-signpost-2"></i> {{ $review->type == 'weekly' ? '下周计划' : '下月计划' }}</div>
    <div class="card-body">
        <div style="white-space:pre-wrap;">{{ $review->next_plan }}</div>
    </div>
</div>
@endif

@if($review->content)
<div class="card mb-3">
    <div class="card-header"><i class="bi bi-chat-text"></i> 其他备注</div>
    <div class="card-body">
        <div style="white-space:pre-wrap;">{{ $review->content }}</div>
    </div>
</div>
@endif
@endsection

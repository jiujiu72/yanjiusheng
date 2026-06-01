@extends('layouts.app')

@section('title', '周月复盘')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-arrow-repeat"></i> 每周 / 月度复盘</h4>
    <div>
        <a href="{{ route('reviews.create', ['type' => 'weekly']) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> 周复盘
        </a>
        <a href="{{ route('reviews.create', ['type' => 'monthly']) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-plus-lg"></i> 月复盘
        </a>
    </div>
</div>

@forelse($reviews as $review)
<div class="card mb-3">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <span class="badge {{ $review->type == 'weekly' ? 'bg-info' : 'bg-primary' }} me-2">
                {{ $review->type == 'weekly' ? '周复盘' : '月复盘' }}
            </span>
            <a href="{{ route('reviews.show', $review) }}" class="fw-bold text-decoration-none">{{ $review->title }}</a>
            <div class="text-muted small mt-1">
                {{ $review->period_start->format('Y-m-d') }} ~ {{ $review->period_end->format('Y-m-d') }}
                @if($review->rating)
                    | 自评: {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reviews.show', $review) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
            <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('确定删除此复盘？')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
        </div>
    </div>
</div>
@empty
<div class="card">
    <div class="card-body text-center text-muted py-5">
        <i class="bi bi-arrow-repeat" style="font-size:3rem;"></i>
        <p class="mt-2">暂无复盘记录，点击上方按钮开始你的第一次复盘</p>
    </div>
</div>
@endforelse

@if($reviews->hasPages())
    <div class="mt-3">{{ $reviews->links() }}</div>
@endif
@endsection

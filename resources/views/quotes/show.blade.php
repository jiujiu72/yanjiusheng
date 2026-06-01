@extends('layouts.app')

@section('title', '短句详情 - 研究生自我管理系统')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-blockquote-left"></i> 短句详情</h4>
    <div>
        <a href="{{ route('quotes.edit', $quote) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-pencil"></i> 编辑
        </a>
        <a href="{{ route('quotes.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> 返回列表
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <blockquote class="blockquote mb-3">
            <p class="quote-content" style="font-size:1.1rem; line-height:1.8;">{{ $quote->content }}</p>
        </blockquote>

        @if($quote->source)
        <p class="text-muted mb-2"><i class="bi bi-book"></i> 来源：{{ $quote->source }}</p>
        @endif

        <div class="d-flex gap-2 flex-wrap mb-3">
            @if($quote->category)
            <span class="badge bg-primary">{{ $quote->category }}</span>
            @endif
            @if($quote->tags)
                @foreach(explode(',', $quote->tags) as $tag)
                <span class="badge bg-secondary">{{ trim($tag) }}</span>
                @endforeach
            @endif
            @if($quote->favorited)
            <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> 已收藏</span>
            @endif
        </div>

        <div class="text-muted small">
            <i class="bi bi-clock"></i> 收录于 {{ $quote->created_at->format('Y-m-d H:i') }}
        </div>

        <hr>
        <button class="btn btn-outline-success btn-sm" onclick="copyQuote(this)">
            <i class="bi bi-clipboard"></i> 一键复制
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
function copyQuote(btn) {
    var text = document.querySelector('.quote-content').textContent.trim();
    navigator.clipboard.writeText(text).then(function() {
        btn.innerHTML = '<i class="bi bi-check-lg"></i> 已复制';
        setTimeout(function() { btn.innerHTML = '<i class="bi bi-clipboard"></i> 一键复制'; }, 2000);
    });
}
</script>
@endsection

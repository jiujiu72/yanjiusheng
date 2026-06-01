@extends('layouts.app')

@section('title', '短句摘抄 - 研究生自我管理系统')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-blockquote-left"></i> 短句摘抄 / 金句库</h4>
    <a href="{{ route('quotes.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> 收录新句
    </a>
</div>

{{-- 筛选栏 --}}
<div class="card mb-4">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('quotes.index') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="category" class="form-select form-select-sm">
                    <option value="">全部分类</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="favorited" class="form-select form-select-sm">
                    <option value="">全部</option>
                    <option value="1" {{ request('favorited') == '1' ? 'selected' : '' }}>仅收藏</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="搜索内容、来源、标签..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bi bi-search"></i> 搜索</button>
                <a href="{{ route('quotes.index') }}" class="btn btn-outline-secondary btn-sm">重置</a>
            </div>
        </form>
    </div>
</div>

{{-- 短句列表 --}}
@if($quotes->isEmpty())
<div class="text-center text-muted py-5">
    <i class="bi bi-blockquote-left" style="font-size:3rem;"></i>
    <p class="mt-3">还没有收录任何短句，点击"收录新句"开始吧！</p>
</div>
@else
<div class="row">
    @foreach($quotes as $quote)
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <p class="quote-content mb-2" style="line-height:1.7;">{{ $quote->content }}</p>
                @if($quote->source)
                <p class="text-muted small mb-2"><i class="bi bi-book"></i> {{ $quote->source }}</p>
                @endif
                <div class="d-flex gap-1 flex-wrap mb-2">
                    @if($quote->category)
                    <span class="badge bg-primary">{{ $quote->category }}</span>
                    @endif
                    @if($quote->tags)
                        @foreach(explode(',', $quote->tags) as $tag)
                        <span class="badge bg-secondary">{{ trim($tag) }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                <div class="d-flex gap-1">
                    <button class="btn btn-outline-success btn-sm" onclick="copyQuote(this)" title="复制">
                        <i class="bi bi-clipboard"></i>
                    </button>
                    <form action="{{ route('quotes.favorite', $quote) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm {{ $quote->favorited ? 'btn-warning' : 'btn-outline-warning' }}" title="{{ $quote->favorited ? '取消收藏' : '收藏' }}">
                            <i class="bi bi-star{{ $quote->favorited ? '-fill' : '' }}"></i>
                        </button>
                    </form>
                    <a href="{{ route('quotes.show', $quote) }}" class="btn btn-outline-info btn-sm" title="详情">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('quotes.edit', $quote) }}" class="btn btn-outline-primary btn-sm" title="编辑">
                        <i class="bi bi-pencil"></i>
                    </a>
                </div>
                <form action="{{ route('quotes.destroy', $quote) }}" method="POST"
                      onsubmit="return confirm('确定删除这条短句？')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm" title="删除">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection

@section('scripts')
<script>
function copyQuote(btn) {
    var text = btn.closest('.card').querySelector('.quote-content').textContent.trim();
    navigator.clipboard.writeText(text).then(function() {
        var icon = btn.querySelector('i');
        icon.className = 'bi bi-check-lg';
        setTimeout(function() { icon.className = 'bi bi-clipboard'; }, 2000);
    });
}
</script>
@endsection

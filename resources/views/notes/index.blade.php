@extends('layouts.app')

@section('title', '笔记归档')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-journal-text"></i> 笔记归档</h4>
    <a href="{{ route('notes.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> 新建笔记</a>
</div>

{{-- 搜索 --}}
<div class="card mb-4">
    <div class="card-body py-2">
        <form class="row g-2 align-items-center" method="GET">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="搜索标题/内容/标签..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select form-select-sm">
                    <option value="">全部分类</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-outline-primary w-100">搜索</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3">
    @forelse($notes as $note)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 {{ $note->pinned ? 'border-warning' : '' }}">
                <div class="card-body">
                    @if($note->pinned)
                        <span class="badge bg-warning text-dark mb-2"><i class="bi bi-pin-fill"></i> 置顶</span>
                    @endif
                    <h6 class="card-title">{{ $note->title }}</h6>
                    <p class="card-text text-muted small">{{ Str::limit(strip_tags($note->content), 100) }}</p>
                    <div class="mt-2">
                        @if($note->category)
                            <span class="badge bg-light text-dark">{{ $note->category }}</span>
                        @endif
                        @if($note->tags)
                            @foreach(array_slice(explode(',', $note->tags), 0, 3) as $tag)
                                <span class="badge bg-info bg-opacity-10 text-info">{{ trim($tag) }}</span>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                    <small class="text-muted">{{ $note->updated_at->format('m-d H:i') }}</small>
                    <div>
                        <a href="{{ route('notes.show', $note) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('notes.edit', $note) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('notes.destroy', $note) }}" method="POST" class="d-inline" onsubmit="return confirm('确定删除?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card"><div class="card-body text-center py-5 text-muted">
                <i class="bi bi-journal fs-1"></i>
                <p class="mt-2">暂无笔记</p>
            </div></div>
        </div>
    @endforelse
</div>
@endsection

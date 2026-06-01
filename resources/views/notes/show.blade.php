@extends('layouts.app')
@section('title', $note->title)
@section('content')
<div class="mb-4">
    <a href="{{ route('notes.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> 返回</a>
</div>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <h5>{{ $note->title }}</h5>
            @if($note->pinned)
                <span class="badge bg-warning text-dark"><i class="bi bi-pin-fill"></i> 置顶</span>
            @endif
        </div>
        <div class="mb-3">
            @if($note->category)
                <span class="badge bg-primary">{{ $note->category }}</span>
            @endif
            @if($note->tags)
                @foreach(explode(',', $note->tags) as $tag)
                    <span class="badge bg-light text-dark">{{ trim($tag) }}</span>
                @endforeach
            @endif
        </div>
        <div class="border-top pt-3">
            {!! nl2br(e($note->content)) !!}
        </div>
        <div class="mt-4 pt-3 border-top text-muted small">
            创建于 {{ $note->created_at->format('Y-m-d H:i') }} | 更新于 {{ $note->updated_at->format('Y-m-d H:i') }}
        </div>
        <div class="mt-3">
            <a href="{{ route('notes.edit', $note) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> 编辑</a>
        </div>
    </div>
</div>
@endsection

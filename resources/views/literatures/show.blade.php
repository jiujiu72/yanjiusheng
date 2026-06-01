@extends('layouts.app')

@section('title', $literature->title)

@section('content')
<div class="mb-4">
    <a href="{{ route('literatures.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> 返回</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <h5>{{ $literature->title }}</h5>
            <span class="badge {{ $literature->status == 'unread' ? 'bg-secondary' : ($literature->status == 'reading' ? 'bg-info' : 'bg-success') }}">
                {{ ['unread'=>'未读','reading'=>'在读','finished'=>'已读'][$literature->status] }}
            </span>
        </div>

        <table class="table table-borderless">
            <tr><th width="100">作者</th><td>{{ $literature->authors ?: '-' }}</td></tr>
            <tr><th>期刊</th><td>{{ $literature->journal ?: '-' }}</td></tr>
            <tr><th>年份</th><td>{{ $literature->year ?: '-' }}</td></tr>
            <tr><th>DOI</th><td>{{ $literature->doi ?: '-' }}</td></tr>
            <tr>
                <th>评分</th>
                <td>
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= $literature->rating ? '-fill text-warning' : '' }}"></i>
                    @endfor
                </td>
            </tr>
            <tr><th>标签</th><td>
                @if($literature->tags)
                    @foreach(explode(',', $literature->tags) as $tag)
                        <span class="badge bg-light text-dark me-1">{{ trim($tag) }}</span>
                    @endforeach
                @else - @endif
            </td></tr>
        </table>

        @if($literature->abstract)
            <h6 class="mt-4">摘要</h6>
            <p class="text-muted">{{ $literature->abstract }}</p>
        @endif

        @if($literature->notes)
            <h6 class="mt-4">阅读笔记</h6>
            <div class="bg-light p-3 rounded">{!! nl2br(e($literature->notes)) !!}</div>
        @endif

        <div class="mt-4">
            <a href="{{ route('literatures.edit', $literature) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> 编辑</a>
        </div>
    </div>
</div>
@endsection

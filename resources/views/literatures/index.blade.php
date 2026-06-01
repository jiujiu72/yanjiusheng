@extends('layouts.app')

@section('title', '文献管理')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-book"></i> 文献管理</h4>
    <a href="{{ route('literatures.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> 添加文献</a>
</div>

{{-- 筛选 --}}
<div class="card mb-4">
    <div class="card-body py-2">
        <form class="row g-2 align-items-center" method="GET">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="搜索标题/作者/标签..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">全部状态</option>
                    <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>未读</option>
                    <option value="reading" {{ request('status') == 'reading' ? 'selected' : '' }}>在读</option>
                    <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>已读</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-outline-primary w-100">筛选</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>标题</th>
                    <th>作者</th>
                    <th>年份</th>
                    <th>状态</th>
                    <th>评分</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($literatures as $lit)
                    <tr>
                        <td>
                            <a href="{{ route('literatures.show', $lit) }}" class="text-decoration-none fw-bold">{{ Str::limit($lit->title, 40) }}</a>
                            @if($lit->tags)
                                <br><small class="text-muted">{{ $lit->tags }}</small>
                            @endif
                        </td>
                        <td class="small">{{ Str::limit($lit->authors, 30) }}</td>
                        <td>{{ $lit->year }}</td>
                        <td>
                            <span class="badge {{ $lit->status == 'unread' ? 'bg-secondary' : ($lit->status == 'reading' ? 'bg-info' : 'bg-success') }}">
                                {{ ['unread'=>'未读','reading'=>'在读','finished'=>'已读'][$lit->status] }}
                            </span>
                        </td>
                        <td>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $lit->rating ? '-fill text-warning' : '' }}"></i>
                            @endfor
                        </td>
                        <td>
                            <a href="{{ route('literatures.edit', $lit) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('literatures.destroy', $lit) }}" method="POST" class="d-inline" onsubmit="return confirm('确定删除?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">暂无文献记录</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

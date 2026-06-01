@extends('layouts.app')

@section('title', '课题进度')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-kanban"></i> 课题进度</h4>
    <a href="{{ route('projects.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> 新建课题</a>
</div>

<div class="row g-4">
    @forelse($projects as $project)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                        <h6 class="card-title mb-0 project-title flex-grow-1" title="{{ $project->title }}">{{ $project->title }}</h6>
                        <span class="badge badge-{{ $project->status }} flex-shrink-0">
                            {{ ['planning'=>'规划中','in_progress'=>'进行中','review'=>'审核中','completed'=>'已完成'][$project->status] }}
                        </span>
                    </div>
                    @if($project->description)
                        <p class="card-text text-muted small text-truncate-2">{{ $project->description }}</p>
                    @endif
                    <div class="mb-2">
                        <div class="progress">
                            <div class="progress-bar bg-primary" style="width: {{ $project->progress }}%"></div>
                        </div>
                        <small class="text-muted">进度: {{ $project->progress }}%</small>
                    </div>
                    <div class="small text-muted mb-3">
                        @if($project->start_date)
                            <i class="bi bi-calendar"></i> {{ $project->start_date->format('Y-m-d') }}
                        @endif
                        @if($project->due_date)
                            ~ {{ $project->due_date->format('Y-m-d') }}
                        @endif
                    </div>
                    <div class="d-flex gap-2 mt-auto">
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> 编辑
                        </a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('确定删除此课题？')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> 删除</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mt-2">暂无课题，点击右上角创建第一个课题吧！</p>
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection

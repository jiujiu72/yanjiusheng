@extends('layouts.app')

@section('title', '日程待办')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-check2-square"></i> 日程待办</h4>
    <a href="{{ route('todos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> 新建待办</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @forelse($todos as $todo)
                <div class="list-group-item d-flex align-items-center">
                    <form action="{{ route('todos.toggle', $todo) }}" method="POST" class="me-3">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm {{ $todo->completed ? 'btn-success' : 'btn-outline-secondary' }}" style="width:32px;height:32px;">
                            <i class="bi bi-check-lg"></i>
                        </button>
                    </form>
                    <div class="flex-grow-1">
                        <div class="{{ $todo->completed ? 'text-decoration-line-through text-muted' : '' }}">
                            <span class="badge badge-{{ $todo->priority }} me-2">
                                {{ ['low'=>'低','medium'=>'中','high'=>'高'][$todo->priority] }}
                            </span>
                            {{ $todo->title }}
                            @if($todo->category)
                                <span class="badge bg-light text-dark ms-2">{{ $todo->category }}</span>
                            @endif
                        </div>
                        @if($todo->due_date)
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i> {{ $todo->due_date->format('Y-m-d') }}
                                @if(!$todo->completed && $todo->due_date->isPast())
                                    <span class="text-danger">(已逾期)</span>
                                @endif
                            </small>
                        @endif
                    </div>
                    <div class="d-flex gap-1">
                        <a href="{{ route('todos.edit', $todo) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('todos.destroy', $todo) }}" method="POST" onsubmit="return confirm('确定删除?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="list-group-item text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mt-2">暂无待办事项</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

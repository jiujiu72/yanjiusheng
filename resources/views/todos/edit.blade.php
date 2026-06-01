@extends('layouts.app')

@section('title', '编辑待办')

@section('content')
<h4 class="mb-4"><i class="bi bi-pencil"></i> 编辑待办</h4>
<div class="card">
    <div class="card-body">
        <form action="{{ route('todos.update', $todo) }}" method="POST">
            @csrf @method('PUT')
            @submitToken
            @include('todos._form', ['todo' => $todo])
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> 保存</button>
                <a href="{{ route('todos.index') }}" class="btn btn-outline-secondary ms-2">取消</a>
            </div>
        </form>
    </div>
</div>
@endsection

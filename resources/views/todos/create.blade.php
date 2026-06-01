@extends('layouts.app')

@section('title', '新建待办')

@section('content')
<h4 class="mb-4"><i class="bi bi-plus-circle"></i> 新建待办</h4>
<div class="card">
    <div class="card-body">
        <form action="{{ route('todos.store') }}" method="POST">
            @csrf
            @submitToken
            @include('todos._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> 创建</button>
                <a href="{{ route('todos.index') }}" class="btn btn-outline-secondary ms-2">取消</a>
            </div>
        </form>
    </div>
</div>
@endsection

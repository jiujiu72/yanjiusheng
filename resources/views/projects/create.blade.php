@extends('layouts.app')

@section('title', '新建课题')

@section('content')
<div class="mb-4">
    <h4><i class="bi bi-plus-circle"></i> 新建课题</h4>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            @submitToken
            @include('projects._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> 创建</button>
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary ms-2">取消</a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', '编辑课题')

@section('content')
<div class="mb-4">
    <h4><i class="bi bi-pencil"></i> 编辑课题</h4>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('projects.update', $project) }}" method="POST">
            @csrf @method('PUT')
            @submitToken
            @include('projects._form', ['project' => $project])
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> 保存</button>
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary ms-2">取消</a>
            </div>
        </form>
    </div>
</div>
@endsection

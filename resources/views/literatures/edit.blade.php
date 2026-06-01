@extends('layouts.app')

@section('title', '编辑文献')

@section('content')
<h4 class="mb-4"><i class="bi bi-pencil"></i> 编辑文献</h4>
<div class="card">
    <div class="card-body">
        <form action="{{ route('literatures.update', $literature) }}" method="POST">
            @csrf @method('PUT')
            @submitToken
            @include('literatures._form', ['literature' => $literature])
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> 保存</button>
                <a href="{{ route('literatures.index') }}" class="btn btn-outline-secondary ms-2">取消</a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', '添加文献')

@section('content')
<h4 class="mb-4"><i class="bi bi-plus-circle"></i> 添加文献</h4>
<div class="card">
    <div class="card-body">
        <form action="{{ route('literatures.store') }}" method="POST">
            @csrf
            @submitToken
            @include('literatures._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> 添加</button>
                <a href="{{ route('literatures.index') }}" class="btn btn-outline-secondary ms-2">取消</a>
            </div>
        </form>
    </div>
</div>
@endsection

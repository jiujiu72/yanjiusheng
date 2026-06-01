@extends('layouts.app')

@section('title', '新增短句 - 研究生自我管理系统')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-blockquote-left"></i> 新增短句</h4>
    <a href="{{ route('quotes.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> 返回列表
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('quotes.store') }}" method="POST">
            @csrf
            @submitToken
            @include('quotes._form')
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> 收录短句
            </button>
        </form>
    </div>
</div>
@endsection

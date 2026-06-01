@extends('layouts.app')

@section('title', '新建会议纪要 - 研究生自我管理系统')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-people-fill"></i> 新建会议纪要</h4>
    <a href="{{ route('meetings.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> 返回列表
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('meetings.store') }}" method="POST">
            @csrf
            @submitToken
            @include('meetings._form')
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> 创建纪要
            </button>
        </form>
    </div>
</div>
@endsection

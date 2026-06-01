@extends('layouts.app')

@section('title', '编辑会议纪要 - 研究生自我管理系统')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-people-fill"></i> 编辑会议纪要</h4>
    <a href="{{ route('meetings.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> 返回列表
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('meetings.update', $meeting) }}" method="POST">
            @csrf
            @method('PUT')
            @submitToken
            @include('meetings._form')
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> 保存修改
            </button>
        </form>
    </div>
</div>
@endsection

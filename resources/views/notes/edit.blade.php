@extends('layouts.app')
@section('title', '编辑笔记')
@section('content')
<h4 class="mb-4"><i class="bi bi-pencil"></i> 编辑笔记</h4>
<div class="card"><div class="card-body">
    <form action="{{ route('notes.update', $note) }}" method="POST">
        @csrf @method('PUT')
        @submitToken
        @include('notes._form', ['note' => $note])
        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> 保存</button>
            <a href="{{ route('notes.index') }}" class="btn btn-outline-secondary ms-2">取消</a>
        </div>
    </form>
</div></div>
@endsection

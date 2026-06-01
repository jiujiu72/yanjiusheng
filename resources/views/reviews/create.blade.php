@extends('layouts.app')

@section('title', '新建复盘')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil-square"></i> 新建{{ $type == 'weekly' ? '周' : '月' }}复盘</h4>
    <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary btn-sm">返回列表</a>
</div>

<form action="{{ route('reviews.store') }}" method="POST">
    @csrf
    @submitToken
    <input type="hidden" name="type" value="{{ $type }}">

    <div class="card mb-4">
        <div class="card-header">基本信息</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">标题 <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', $title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">起始日期</label>
                    <input type="date" name="period_start" class="form-control @error('period_start') is-invalid @enderror"
                           value="{{ old('period_start', $periodStart->format('Y-m-d')) }}" required>
                    @error('period_start')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">结束日期</label>
                    <input type="date" name="period_end" class="form-control @error('period_end') is-invalid @enderror"
                           value="{{ old('period_end', $periodEnd->format('Y-m-d')) }}" required>
                    @error('period_end')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">{{ $type == 'weekly' ? '本周成果' : '本月成果' }}</div>
        <div class="card-body">
            <textarea name="achievements" class="form-control @error('achievements') is-invalid @enderror"
                      rows="5" placeholder="记录你的成果和完成的事项...">{{ old('achievements', $template['achievements']) }}</textarea>
            @error('achievements')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">{{ $type == 'weekly' ? '遇到的问题' : '问题与反思' }}</div>
        <div class="card-body">
            <textarea name="problems" class="form-control @error('problems') is-invalid @enderror"
                      rows="4" placeholder="遇到了哪些困难和问题？">{{ old('problems', $template['problems']) }}</textarea>
            @error('problems')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">{{ $type == 'weekly' ? '下周计划' : '下月计划' }}</div>
        <div class="card-body">
            <textarea name="next_plan" class="form-control @error('next_plan') is-invalid @enderror"
                      rows="4" placeholder="接下来的计划和目标...">{{ old('next_plan', $template['next_plan']) }}</textarea>
            @error('next_plan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">其他备注 / 心得体会</div>
        <div class="card-body">
            <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                      rows="4" placeholder="自由记录...">{{ old('content', $template['content']) }}</textarea>
            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">自我评价</div>
        <div class="card-body">
            <label class="form-label">本{{ $type == 'weekly' ? '周' : '月' }}自评（1-5分）</label>
            <select name="rating" class="form-select w-auto @error('rating') is-invalid @enderror">
                <option value="">不评分</option>
                <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 - 很不满意</option>
                <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 - 不太满意</option>
                <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 - 一般</option>
                <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 - 比较满意</option>
                <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>5 - 非常满意</option>
            </select>
            @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> 保存复盘</button>
        <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">取消</a>
    </div>
</form>
@endsection

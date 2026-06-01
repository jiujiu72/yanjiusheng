@extends('layouts.app')

@section('title', '经费记账')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-wallet2"></i> 经费与报销记账</h4>
</div>

{{-- 汇总卡片 --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:1.75rem; font-weight:700; color:#e74c3c;">¥{{ number_format($summary['total'], 2) }}</div>
                <div class="text-muted small">累计支出</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:1.75rem; font-weight:700; color:#28a745;">¥{{ number_format($summary['reimbursed'], 2) }}</div>
                <div class="text-muted small">已报销</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:1.75rem; font-weight:700; color:#f5a623;">¥{{ number_format($summary['pending'], 2) }}</div>
                <div class="text-muted small">待报销</div>
            </div>
        </div>
    </div>
</div>

{{-- 新增记录 --}}
<div class="card mb-4">
    <div class="card-header"><i class="bi bi-plus-circle"></i> 添加支出</div>
    <div class="card-body">
        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf
            @submitToken
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">日期 <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                           value="{{ old('date', today()->format('Y-m-d')) }}" required>
                    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">类别 <span class="text-danger">*</span></label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                        @foreach($categoryLabels as $val => $label)
                            <option value="{{ $val }}" {{ old('category') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">描述 <span class="text-danger">*</span></label>
                    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                           placeholder="支出说明" value="{{ old('description') }}" required>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">金额 <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror"
                           placeholder="0.00" min="0.01" value="{{ old('amount') }}" required>
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">票据备注</label>
                    <input type="text" name="receipt_note" class="form-control" placeholder="发票号等" value="{{ old('receipt_note') }}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- 筛选 --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-auto">
                <select name="category" class="form-select form-select-sm">
                    <option value="">全部类别</option>
                    @foreach($categoryLabels as $val => $label)
                        <option value="{{ $val }}" {{ request('category') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">全部状态</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>待报销</option>
                    <option value="reimbursed" {{ request('status') == 'reimbursed' ? 'selected' : '' }}>已报销</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-outline-primary">筛选</button>
                <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-outline-secondary">重置</a>
            </div>
        </form>
    </div>
</div>

{{-- 记录列表 --}}
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>日期</th><th>类别</th><th>描述</th><th>金额</th><th>报销状态</th><th>备注</th><th>操作</th></tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                <tr>
                    <td>{{ $expense->date->format('m-d') }}</td>
                    <td><span class="badge bg-secondary">{{ $categoryLabels[$expense->category] ?? $expense->category }}</span></td>
                    <td>{{ $expense->description }}</td>
                    <td class="fw-bold">¥{{ number_format($expense->amount, 2) }}</td>
                    <td>
                        @if($expense->is_reimbursed)
                            <span class="badge bg-success">已报销 ¥{{ number_format($expense->reimbursed_amount, 2) }}</span>
                        @else
                            <span class="badge bg-warning text-dark">待报销</span>
                        @endif
                    </td>
                    <td class="small">{{ $expense->receipt_note }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <form action="{{ route('expenses.toggle', $expense) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="reimbursed_amount" value="{{ $expense->amount }}">
                                <button class="btn btn-sm {{ $expense->is_reimbursed ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $expense->is_reimbursed ? '取消报销' : '标记已报销' }}">
                                    <i class="bi bi-{{ $expense->is_reimbursed ? 'arrow-counterclockwise' : 'check-circle' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('确定删除？')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-3">暂无记录</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($expenses->hasPages())
        <div class="card-body">{{ $expenses->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

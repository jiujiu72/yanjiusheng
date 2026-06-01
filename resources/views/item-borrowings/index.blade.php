@extends('layouts.app')

@section('title', '物品借用登记')

@section('styles')
<style>
    .overdue-row { background: #fff5f5 !important; }
    .badge-borrowed { background: #0d6efd; color: #fff; }
    .badge-overdue { background: #dc3545; color: #fff; }
    .badge-returned { background: #198754; color: #fff; }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-box-arrow-up-right"></i> 物品借用登记</h4>
</div>

{{-- 逾期提醒 --}}
@if($overdueItems->count() > 0)
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong><i class="bi bi-exclamation-triangle-fill"></i> 逾期提醒：</strong>
    您有 {{ $overdueItems->count() }} 件物品已超过预计归还日期！
    <ul class="mb-0 mt-1">
        @foreach($overdueItems->take(5) as $item)
            <li>「{{ $item->item_name }}」借自 {{ $item->borrower }}，预计 {{ $item->expected_return_date->format('Y-m-d') }} 归还</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- 汇总卡片 --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:1.75rem; font-weight:700; color:var(--primary-color);">{{ $summary['total'] }}</div>
                <div class="text-muted small">总借用记录</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:1.75rem; font-weight:700; color:#0d6efd;">{{ $summary['active'] }}</div>
                <div class="text-muted small">当前借用中</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:1.75rem; font-weight:700; color:#dc3545;">{{ $summary['overdue'] }}</div>
                <div class="text-muted small">逾期未还</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:1.75rem; font-weight:700; color:#198754;">{{ $summary['returned'] }}</div>
                <div class="text-muted small">已归还</div>
            </div>
        </div>
    </div>
</div>

{{-- 新增记录 --}}
<div class="card mb-4">
    <div class="card-header"><i class="bi bi-plus-circle"></i> 登记借用</div>
    <div class="card-body">
        <form action="{{ route('item-borrowings.store') }}" method="POST">
            @csrf
            @submitToken
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">物品名称 <span class="text-danger">*</span></label>
                    <input type="text" name="item_name" class="form-control @error('item_name') is-invalid @enderror"
                           placeholder="如：万用表" value="{{ old('item_name') }}" required maxlength="255" minlength="1">
                    @error('item_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">借用对象 <span class="text-danger">*</span></label>
                    <input type="text" name="borrower" class="form-control @error('borrower') is-invalid @enderror"
                           placeholder="借自谁/哪里" value="{{ old('borrower') }}" required maxlength="255" minlength="1">
                    @error('borrower')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">借用日期 <span class="text-danger">*</span></label>
                    <input type="date" name="borrow_date" id="borrowDate" class="form-control @error('borrow_date') is-invalid @enderror"
                           value="{{ old('borrow_date', today()->format('Y-m-d')) }}" required>
                    @error('borrow_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">预计归还 <span class="text-danger">*</span></label>
                    <input type="date" name="expected_return_date" id="expectedReturnDate" class="form-control @error('expected_return_date') is-invalid @enderror"
                           value="{{ old('expected_return_date') }}" required>
                    @error('expected_return_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">备注</label>
                    <input type="text" name="notes" class="form-control" placeholder="可选备注" value="{{ old('notes') }}" maxlength="500">
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
                <select name="status" class="form-select form-select-sm">
                    <option value="">全部状态</option>
                    <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>借用中</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>逾期未还</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>已归还</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-outline-primary">筛选</button>
                <a href="{{ route('item-borrowings.index') }}" class="btn btn-sm btn-outline-secondary">重置</a>
            </div>
        </form>
    </div>
</div>

{{-- 记录列表 --}}
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>物品名称</th><th>借用对象</th><th>借用日期</th><th>预计归还</th><th>状态</th><th>实际归还</th><th>备注</th><th>操作</th></tr>
            </thead>
            <tbody>
                @forelse($borrowings as $item)
                <tr class="{{ $item->status === 'overdue' ? 'overdue-row' : '' }}">
                    <td class="fw-bold">{{ $item->item_name }}</td>
                    <td>{{ $item->borrower }}</td>
                    <td>{{ $item->borrow_date->format('m-d') }}</td>
                    <td>{{ $item->expected_return_date->format('m-d') }}</td>
                    <td>
                        @if($item->status === 'borrowed')
                            <span class="badge badge-borrowed">借用中</span>
                        @elseif($item->status === 'overdue')
                            <span class="badge badge-overdue">逾期</span>
                        @else
                            <span class="badge badge-returned">已归还</span>
                        @endif
                    </td>
                    <td>{{ $item->actual_return_date ? $item->actual_return_date->format('m-d') : '-' }}</td>
                    <td class="small">{{ $item->notes }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            @if($item->status !== 'returned')
                            <form action="{{ route('item-borrowings.return', $item) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-success" title="标记归还">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                            </form>
                            @endif
                            <form action="{{ route('item-borrowings.destroy', $item) }}" method="POST" onsubmit="return confirm('确定删除？')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-3">暂无借用记录</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($borrowings->hasPages())
        <div class="card-body">{{ $borrowings->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

@section('scripts')
<script>
(function() {
    var borrowDate = document.getElementById('borrowDate');
    var returnDate = document.getElementById('expectedReturnDate');

    function syncMinReturn() {
        if (borrowDate.value) {
            returnDate.min = borrowDate.value;
            if (returnDate.value && returnDate.value < borrowDate.value) {
                returnDate.value = '';
            }
        }
    }

    borrowDate.addEventListener('change', syncMinReturn);
    syncMinReturn();

    var form = borrowDate.closest('form');
    form.addEventListener('submit', function(e) {
        var itemName = form.querySelector('[name="item_name"]');
        var borrower = form.querySelector('[name="borrower"]');

        if (itemName.value.trim().length === 0) {
            e.preventDefault();
            itemName.classList.add('is-invalid');
            itemName.focus();
            return;
        }
        itemName.classList.remove('is-invalid');

        if (borrower.value.trim().length === 0) {
            e.preventDefault();
            borrower.classList.add('is-invalid');
            borrower.focus();
            return;
        }
        borrower.classList.remove('is-invalid');

        if (!borrowDate.value) {
            e.preventDefault();
            borrowDate.classList.add('is-invalid');
            borrowDate.focus();
            return;
        }

        if (!returnDate.value) {
            e.preventDefault();
            returnDate.classList.add('is-invalid');
            returnDate.focus();
            return;
        }

        if (returnDate.value < borrowDate.value) {
            e.preventDefault();
            returnDate.classList.add('is-invalid');
            alert('归还日期不能早于借用日期');
            returnDate.focus();
            return;
        }
        returnDate.classList.remove('is-invalid');
    });
})();
</script>
@endsection

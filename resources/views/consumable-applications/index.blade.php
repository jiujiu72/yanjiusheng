@extends('layouts.app')

@section('title', '实验耗材申领')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-box-seam"></i> 实验耗材申领</h4>
</div>

{{-- 汇总卡片 --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:1.75rem; font-weight:700; color:#e74c3c;">&yen;{{ number_format($summary['total_cost'], 2) }}</div>
                <div class="text-muted small">耗材总支出</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:1.75rem; font-weight:700; color:#28a745;">&yen;{{ number_format($summary['linked_cost'], 2) }}</div>
                <div class="text-muted small">已记入经费</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:1.75rem; font-weight:700; color:#f5a623;">&yen;{{ number_format($summary['pending_cost'], 2) }}</div>
                <div class="text-muted small">待记入经费</div>
            </div>
        </div>
    </div>
</div>

{{-- 新增记录 --}}
<div class="card mb-4">
    <div class="card-header"><i class="bi bi-plus-circle"></i> 申领登记</div>
    <div class="card-body">
        <form action="{{ route('consumable-applications.store') }}" method="POST">
            @csrf
            @submitToken
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">耗材名称 <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           placeholder="如：移液器吸头" value="{{ old('name') }}" required maxlength="255" minlength="1">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-1">
                    <label class="form-label">数量 <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" id="consumableQty" class="form-control @error('quantity') is-invalid @enderror"
                           min="1" max="99999" value="{{ old('quantity', 1) }}" required>
                    @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-1">
                    <label class="form-label">单位 <span class="text-danger">*</span></label>
                    <select name="unit" class="form-select @error('unit') is-invalid @enderror" required>
                        <option value="个" {{ old('unit') == '个' ? 'selected' : '' }}>个</option>
                        <option value="盒" {{ old('unit') == '盒' ? 'selected' : '' }}>盒</option>
                        <option value="瓶" {{ old('unit') == '瓶' ? 'selected' : '' }}>瓶</option>
                        <option value="包" {{ old('unit') == '包' ? 'selected' : '' }}>包</option>
                        <option value="支" {{ old('unit') == '支' ? 'selected' : '' }}>支</option>
                        <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>ml</option>
                        <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>g</option>
                        <option value="套" {{ old('unit') == '套' ? 'selected' : '' }}>套</option>
                    </select>
                    @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">单价(&yen;) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="unit_price" id="consumablePrice" class="form-control @error('unit_price') is-invalid @enderror"
                           min="0.01" max="999999" placeholder="0.00" value="{{ old('unit_price') }}" required>
                    @error('unit_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">申领日期 <span class="text-danger">*</span></label>
                    <input type="date" name="applied_at" class="form-control @error('applied_at') is-invalid @enderror"
                           value="{{ old('applied_at', today()->format('Y-m-d')) }}" required>
                    @error('applied_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">用途说明</label>
                    <input type="text" name="purpose" class="form-control" placeholder="实验用途" value="{{ old('purpose') }}" maxlength="500">
                </div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" name="create_expense" class="form-check-input" id="createExpense"
                               {{ old('create_expense') ? 'checked' : 'checked' }}>
                        <label class="form-check-label" for="createExpense">同时记入经费记账（类别：设备耗材）</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small">预计费用：<strong id="costPreview">&yen;0.00</strong></span>
                </div>
                <div class="col-md-4 text-end">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> 提交申领</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- 记录列表 --}}
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>耗材名称</th><th>数量</th><th>单价</th><th>总费用</th><th>申领日期</th><th>用途</th><th>经费状态</th><th>操作</th></tr>
            </thead>
            <tbody>
                @forelse($consumables as $item)
                <tr>
                    <td class="fw-bold">{{ $item->name }}</td>
                    <td>{{ $item->quantity }} {{ $item->unit }}</td>
                    <td>&yen;{{ number_format($item->unit_price, 2) }}</td>
                    <td class="fw-bold">&yen;{{ number_format($item->total_cost, 2) }}</td>
                    <td>{{ $item->applied_at->format('m-d') }}</td>
                    <td class="small">{{ $item->purpose ?? '-' }}</td>
                    <td>
                        @if($item->expense_id)
                            <span class="badge bg-success">已记入经费</span>
                        @else
                            <span class="badge bg-warning text-dark">未记入</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            @if(!$item->expense_id)
                            <form action="{{ route('consumable-applications.link-expense', $item) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-success" title="记入经费">
                                    <i class="bi bi-wallet2"></i>
                                </button>
                            </form>
                            @endif
                            <form action="{{ route('consumable-applications.destroy', $item) }}" method="POST" onsubmit="return confirm('确定删除？')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-3">暂无申领记录</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($consumables->hasPages())
        <div class="card-body">{{ $consumables->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

@section('scripts')
<script>
(function() {
    var qtyInput = document.getElementById('consumableQty');
    var priceInput = document.getElementById('consumablePrice');
    var preview = document.getElementById('costPreview');

    function updatePreview() {
        var qty = parseFloat(qtyInput.value) || 0;
        var price = parseFloat(priceInput.value) || 0;
        var total = (qty * price).toFixed(2);
        preview.textContent = '¥' + total;
    }

    qtyInput.addEventListener('input', updatePreview);
    priceInput.addEventListener('input', updatePreview);
    updatePreview();

    var form = qtyInput.closest('form');
    form.addEventListener('submit', function(e) {
        var nameInput = form.querySelector('[name="name"]');
        var appliedAt = form.querySelector('[name="applied_at"]');

        if (nameInput.value.trim().length === 0) {
            e.preventDefault();
            nameInput.classList.add('is-invalid');
            nameInput.focus();
            return;
        }
        nameInput.classList.remove('is-invalid');

        var qty = parseInt(qtyInput.value);
        if (isNaN(qty) || qty < 1 || qty > 99999) {
            e.preventDefault();
            qtyInput.classList.add('is-invalid');
            alert('数量必须在 1 ~ 99999 之间');
            qtyInput.focus();
            return;
        }
        qtyInput.classList.remove('is-invalid');

        var price = parseFloat(priceInput.value);
        if (isNaN(price) || price < 0.01 || price > 999999) {
            e.preventDefault();
            priceInput.classList.add('is-invalid');
            alert('单价必须在 0.01 ~ 999999 之间');
            priceInput.focus();
            return;
        }
        priceInput.classList.remove('is-invalid');

        if (!appliedAt.value) {
            e.preventDefault();
            appliedAt.classList.add('is-invalid');
            appliedAt.focus();
            return;
        }
        appliedAt.classList.remove('is-invalid');
    });
})();
</script>
@endsection

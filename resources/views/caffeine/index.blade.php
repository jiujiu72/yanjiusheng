@extends('layouts.app')

@section('title', '咖啡茶饮记录')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-cup-hot"></i> 咖啡茶饮记录</h4>
</div>

{{-- 本月统计 --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:1.75rem; font-weight:700; color:#6f4e37;">{{ $monthStats['count'] }}</div>
                <div class="text-muted small">本月杯数</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:1.75rem; font-weight:700; color:#e65100;">¥{{ number_format($monthStats['total_price'], 1) }}</div>
                <div class="text-muted small">本月花费</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:1.75rem; font-weight:700; color:#2e7d32;">{{ $monthStats['total_caffeine'] }}mg</div>
                <div class="text-muted small">本月咖啡因摄入</div>
            </div>
        </div>
    </div>
</div>

{{-- 快速记录 --}}
<div class="card mb-4">
    <div class="card-header"><i class="bi bi-plus-circle"></i> 记录饮品</div>
    <div class="card-body">
        <form action="{{ route('caffeine.store') }}" method="POST" id="caffeineForm">
            @csrf
            @submitToken
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">类型 <span class="text-danger">*</span></label>
                    <select name="type" id="caffeineType" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="" disabled {{ old('type') ? '' : 'selected' }}>请选择</option>
                        @foreach($typeLabels as $val => $label)
                            <option value="{{ $val }}" {{ old('type') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">名称 <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="caffeineName" class="form-control @error('name') is-invalid @enderror"
                           placeholder="请填写或点击下方快捷选项" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">咖啡因(mg)</label>
                    <input type="number" name="caffeine_mg" id="caffeineMg" class="form-control @error('caffeine_mg') is-invalid @enderror"
                           placeholder="可选" min="0" max="1000" value="{{ old('caffeine_mg') }}">
                    @error('caffeine_mg')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">价格(元)</label>
                    <input type="number" step="0.1" name="price" id="caffeinePrice" class="form-control @error('price') is-invalid @enderror"
                           placeholder="可选" min="0" max="9999.99" value="{{ old('price') }}">
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">备注</label>
                    <input type="text" name="note" class="form-control @error('note') is-invalid @enderror" placeholder="可选" maxlength="200" value="{{ old('note') }}">
                    @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg"></i></button>
                </div>
            </div>

            {{-- 常用饮品快捷选项 --}}
            <div class="mt-3">
                <span class="text-muted small me-2">快捷选择:</span>
                <span class="quick-drink-btn badge rounded-pill bg-light text-dark border me-1 mb-1" style="cursor:pointer;"
                      data-type="coffee" data-name="美式" data-caffeine="150">美式</span>
                <span class="quick-drink-btn badge rounded-pill bg-light text-dark border me-1 mb-1" style="cursor:pointer;"
                      data-type="coffee" data-name="拿铁" data-caffeine="75">拿铁</span>
                <span class="quick-drink-btn badge rounded-pill bg-light text-dark border me-1 mb-1" style="cursor:pointer;"
                      data-type="coffee" data-name="卡布奇诺" data-caffeine="75">卡布奇诺</span>
                <span class="quick-drink-btn badge rounded-pill bg-light text-dark border me-1 mb-1" style="cursor:pointer;"
                      data-type="coffee" data-name="摩卡" data-caffeine="95">摩卡</span>
                <span class="quick-drink-btn badge rounded-pill bg-light text-dark border me-1 mb-1" style="cursor:pointer;"
                      data-type="coffee" data-name="冷萃" data-caffeine="200">冷萃</span>
                <span class="quick-drink-btn badge rounded-pill bg-light text-dark border me-1 mb-1" style="cursor:pointer;"
                      data-type="coffee" data-name="浓缩" data-caffeine="63">浓缩</span>
                <span class="quick-drink-btn badge rounded-pill bg-light text-dark border me-1 mb-1" style="cursor:pointer;"
                      data-type="milk_tea" data-name="奶茶" data-caffeine="30">奶茶</span>
                <span class="quick-drink-btn badge rounded-pill bg-light text-dark border me-1 mb-1" style="cursor:pointer;"
                      data-type="milk_tea" data-name="果茶" data-caffeine="15">果茶</span>
                <span class="quick-drink-btn badge rounded-pill bg-light text-dark border me-1 mb-1" style="cursor:pointer;"
                      data-type="green_tea" data-name="龙井" data-caffeine="35">龙井</span>
                <span class="quick-drink-btn badge rounded-pill bg-light text-dark border me-1 mb-1" style="cursor:pointer;"
                      data-type="green_tea" data-name="碧螺春" data-caffeine="30">碧螺春</span>
                <span class="quick-drink-btn badge rounded-pill bg-light text-dark border me-1 mb-1" style="cursor:pointer;"
                      data-type="black_tea" data-name="红茶" data-caffeine="45">红茶</span>
                <span class="quick-drink-btn badge rounded-pill bg-light text-dark border me-1 mb-1" style="cursor:pointer;"
                      data-type="black_tea" data-name="伯爵茶" data-caffeine="40">伯爵茶</span>
                <span class="quick-drink-btn badge rounded-pill bg-light text-dark border me-1 mb-1" style="cursor:pointer;"
                      data-type="other" data-name="可可" data-caffeine="5">可可</span>
                <span class="quick-drink-btn badge rounded-pill bg-light text-dark border me-1 mb-1" style="cursor:pointer;"
                      data-type="other" data-name="柠檬水" data-caffeine="0">柠檬水</span>
            </div>
        </form>
    </div>
</div>

{{-- 历史记录 --}}
<div class="card">
    <div class="card-header">饮品记录</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>日期</th><th>时间</th><th>类型</th><th>名称</th><th>咖啡因</th><th>价格</th><th>备注</th><th>操作</th></tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->date->format('m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->time)->format('H:i') }}</td>
                    <td><span class="badge bg-secondary">{{ $typeLabels[$log->type] ?? $log->type }}</span></td>
                    <td>{{ $log->name ?? '-' }}</td>
                    <td>{{ $log->caffeine_mg ? $log->caffeine_mg.'mg' : '-' }}</td>
                    <td>{{ $log->price ? '¥'.$log->price : '-' }}</td>
                    <td class="small">{{ $log->note ?? '' }}</td>
                    <td>
                        <form action="{{ route('caffeine.destroy', $log) }}" method="POST" onsubmit="return confirm('确定删除？')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-3">暂无记录</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('styles')
<style>
    .quick-drink-btn { transition: all 0.2s; padding: 0.35rem 0.65rem !important; font-size: 0.8rem; }
    .quick-drink-btn:hover { background-color: var(--primary-color) !important; color: #fff !important; border-color: var(--primary-color) !important; }
    .quick-drink-btn.active { background-color: var(--primary-color) !important; color: #fff !important; border-color: var(--primary-color) !important; }
</style>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.quick-drink-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.quick-drink-btn').forEach(function(b) { b.classList.remove('active'); });
        this.classList.add('active');

        var typeSelect = document.getElementById('caffeineType');
        var nameInput = document.getElementById('caffeineName');
        var caffeineInput = document.getElementById('caffeineMg');

        typeSelect.value = this.getAttribute('data-type');
        nameInput.value = this.getAttribute('data-name');
        caffeineInput.value = this.getAttribute('data-caffeine');
        nameInput.focus();
    });
});
</script>
@endsection

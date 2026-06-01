@extends('layouts.app')

@section('title', '通讯录')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-people"></i> 导师及同门通讯录</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addContactForm">
        <i class="bi bi-plus-lg"></i> 添加联系人
    </button>
</div>

{{-- 新增表单 --}}
<div class="collapse mb-4" id="addContactForm">
    <div class="card">
        <div class="card-header"><i class="bi bi-person-plus"></i> 添加联系人</div>
        <div class="card-body">
            <form action="{{ route('contacts.store') }}" method="POST">
                @csrf
                @submitToken
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">姓名 <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">分组 <span class="text-danger">*</span></label>
                        <select name="group" class="form-select @error('group') is-invalid @enderror" required>
                            @foreach($groupLabels as $val => $label)
                                <option value="{{ $val }}" {{ old('group') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">手机</label>
                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}" placeholder="13812345678" maxlength="11"
                               pattern="1[3-9]\d{9}" title="请输入11位有效手机号">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text" style="font-size:0.7rem;">11位手机号，1开头</div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">邮箱</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="example@mail.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">微信</label>
                        <input type="text" name="wechat" class="form-control @error('wechat') is-invalid @enderror"
                               value="{{ old('wechat') }}" placeholder="字母数字下划线">
                        @error('wechat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">研究方向</label>
                        <input type="text" name="research_direction" class="form-control" value="{{ old('research_direction') }}">
                    </div>
                    <div class="col-md-10">
                        <label class="form-label">备注</label>
                        <input type="text" name="note" class="form-control" placeholder="如: 办公室位置、常用联系时间等" value="{{ old('note') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg"></i> 保存</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 分组展示 --}}
@foreach($groupLabels as $groupKey => $groupLabel)
    @if(isset($orderedContacts[$groupKey]) && $orderedContacts[$groupKey]->count() > 0)
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-{{ $groupKey == 'advisor' ? 'mortarboard' : 'person' }}"></i> {{ $groupLabel }}</span>
            <span class="badge bg-primary rounded-pill">{{ $orderedContacts[$groupKey]->count() }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>姓名</th><th>手机</th><th>邮箱</th><th>微信</th><th>研究方向</th><th>备注</th><th>操作</th></tr>
                </thead>
                <tbody>
                    @foreach($orderedContacts[$groupKey] as $contact)
                    <tr>
                        <td class="fw-bold">{{ $contact->name }}</td>
                        <td>{{ $contact->phone ?? '-' }}</td>
                        <td>{{ $contact->email ?? '-' }}</td>
                        <td>{{ $contact->wechat ?? '-' }}</td>
                        <td class="small">{{ $contact->research_direction ?? '-' }}</td>
                        <td class="small">{{ $contact->note ?? '' }}</td>
                        <td>
                            <form action="{{ route('contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('确定删除此联系人？')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endforeach

@if($orderedContacts->isEmpty())
<div class="card">
    <div class="card-body text-center text-muted py-5">
        <i class="bi bi-people" style="font-size:3rem;"></i>
        <p class="mt-2">暂无联系人，点击上方按钮添加</p>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
// 有校验错误时自动展开添加表单
@if($errors->any())
    var formCollapse = document.getElementById('addContactForm');
    if (formCollapse) {
        formCollapse.classList.add('show');
    }
@endif

// 手机号输入限制：仅允许数字，最多11位
var phoneInput = document.querySelector('input[name="phone"]');
if (phoneInput) {
    phoneInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^\d]/g, '').substring(0, 11);
    });
    phoneInput.addEventListener('paste', function(e) {
        var self = this;
        setTimeout(function() {
            self.value = self.value.replace(/[^\d]/g, '').substring(0, 11);
        }, 0);
    });
}
</script>
@endsection

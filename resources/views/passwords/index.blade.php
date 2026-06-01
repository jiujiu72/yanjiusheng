@extends('layouts.app')

@section('title', '密码备忘录 - 研究生自我管理系统')

@section('styles')
<style>
    .password-field { font-family: monospace; letter-spacing: 2px; }
    .category-academic { background: #e3f2fd; color: #1565c0; }
    .category-social { background: #fce4ec; color: #c62828; }
    .category-finance { background: #fff8e1; color: #f57f17; }
    .category-email { background: #e8f5e9; color: #2e7d32; }
    .category-other { background: #f3e5f5; color: #6a1b9a; }
    .pw-mask { color: #999; user-select: none; }
    .pw-revealed { color: #333; font-weight: 500; }
    .security-note { background: #f8f9fa; border-left: 3px solid #4a6cf7; }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-shield-lock"></i> 密码备忘录</h4>
    <span class="badge bg-success"><i class="bi bi-lock"></i> AES-256加密保护</span>
</div>

<div class="security-note p-3 rounded mb-4">
    <small class="text-muted">
        <i class="bi bi-info-circle"></i> 所有密码均使用 AES-256-CBC 加密存储在本地数据库中，明文密码不会出现在页面源码中。
        点击"查看"按钮可临时显示密码（5秒后自动隐藏）。
    </small>
</div>

{{-- 添加密码表单 --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-plus-circle"></i> 添加密码记录</span>
        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#addPasswordForm">
            <i class="bi bi-chevron-down"></i>
        </button>
    </div>
    <div class="collapse" id="addPasswordForm">
        <div class="card-body">
            <form action="{{ route('passwords.store') }}" method="POST">
                @csrf
                @submitToken
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">网站/应用名称 <span class="text-danger">*</span></label>
                        <input type="text" name="site_name" class="form-control @error('site_name') is-invalid @enderror"
                               value="{{ old('site_name') }}" placeholder="例：知网CNKI" required>
                        @error('site_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">网址URL</label>
                        <input type="text" name="url" class="form-control @error('url') is-invalid @enderror"
                               value="{{ old('url') }}" placeholder="https://...">
                        @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">分类 <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="academic" {{ old('category') == 'academic' ? 'selected' : '' }}>学术平台</option>
                            <option value="social" {{ old('category') == 'social' ? 'selected' : '' }}>社交媒体</option>
                            <option value="finance" {{ old('category') == 'finance' ? 'selected' : '' }}>金融支付</option>
                            <option value="email" {{ old('category') == 'email' ? 'selected' : '' }}>邮箱通讯</option>
                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>其他</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">用户名/账号 <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username') }}" placeholder="输入账号" required>
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">密码 <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password" id="newPassword"
                                   class="form-control @error('password') is-invalid @enderror" placeholder="输入密码" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('newPassword', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">备注</label>
                        <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="可选备注信息">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-lock"></i> 安全保存</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 密码列表 --}}
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>网站/应用</th>
                    <th>分类</th>
                    <th>用户名</th>
                    <th>密码</th>
                    <th>备注</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($passwords as $pw)
                <tr>
                    <td>
                        <strong>{{ $pw->site_name }}</strong>
                        @if($pw->url)
                            <br><a href="{{ $pw->url }}" target="_blank" class="small text-muted">{{ Str::limit($pw->url, 30) }}</a>
                        @endif
                    </td>
                    <td><span class="badge category-{{ $pw->category }}">{{ $categories[$pw->category] ?? $pw->category }}</span></td>
                    <td><code>{{ $pw->username }}</code></td>
                    <td>
                        <span id="pw-{{ $pw->id }}" class="password-field pw-mask">••••••••</span>
                    </td>
                    <td><small class="text-muted">{{ $pw->notes ? Str::limit($pw->notes, 20) : '-' }}</small></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" onclick="revealPassword({{ $pw->id }})" title="查看">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-outline-success" onclick="copyPassword({{ $pw->id }})" title="复制">
                                <i class="bi bi-clipboard"></i>
                            </button>
                            <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $pw->id }}" title="编辑">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('passwords.destroy', $pw) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('确定删除该密码记录？')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="删除"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- 编辑模态框 --}}
                <div class="modal fade" id="editModal{{ $pw->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('passwords.update', $pw) }}" method="POST">
                                @csrf @method('PUT')
                                @submitToken
                                <div class="modal-header">
                                    <h5 class="modal-title">编辑 - {{ $pw->site_name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">网站/应用名称</label>
                                        <input type="text" name="site_name" class="form-control" value="{{ $pw->site_name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">网址URL</label>
                                        <input type="text" name="url" class="form-control" value="{{ $pw->url }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">分类</label>
                                        <select name="category" class="form-select" required>
                                            @foreach($categories as $key => $label)
                                                <option value="{{ $key }}" {{ $pw->category == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">用户名/账号</label>
                                        <input type="text" name="username" class="form-control" value="{{ $pw->username }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">新密码 <small class="text-muted">（留空则不修改）</small></label>
                                        <input type="password" name="password" class="form-control" placeholder="输入新密码...">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">备注</label>
                                        <input type="text" name="notes" class="form-control" value="{{ $pw->notes }}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                    <button type="submit" class="btn btn-primary">保存修改</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">暂无密码记录，点击上方添加你的第一条记录</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
function revealPassword(id) {
    const span = document.getElementById('pw-' + id);
    fetch('/passwords/' + id + '/reveal', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        }
    })
    .then(r => r.json())
    .then(data => {
        span.textContent = data.password;
        span.classList.remove('pw-mask');
        span.classList.add('pw-revealed');
        setTimeout(function() {
            span.textContent = '••••••••';
            span.classList.remove('pw-revealed');
            span.classList.add('pw-mask');
        }, 5000);
    })
    .catch(() => alert('获取密码失败'));
}

function copyPassword(id) {
    fetch('/passwords/' + id + '/reveal', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        }
    })
    .then(r => r.json())
    .then(data => {
        navigator.clipboard.writeText(data.password).then(function() {
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check"></i>';
            btn.classList.add('btn-success');
            btn.classList.remove('btn-outline-success');
            setTimeout(function() {
                btn.innerHTML = originalHTML;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-success');
            }, 2000);
        });
    })
    .catch(() => alert('复制失败'));
}

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

(function() {
    var form = document.getElementById('addPasswordForm').querySelector('form');
    if (!form) return;

    var rules = {
        site_name: { required: true, maxLength: 100, label: '网站/应用名称' },
        username: { required: true, maxLength: 100, label: '用户名/账号' },
        password: { required: true, minLength: 1, maxLength: 500, label: '密码' },
        url: { required: false, maxLength: 500, pattern: /^(https?:\/\/.+)?$/, patternMsg: '请输入有效的URL地址', label: '网址' },
        category: { required: true, label: '分类' },
    };

    function validateField(input) {
        var name = input.name;
        var rule = rules[name];
        if (!rule) return true;

        var value = input.value.trim();
        var error = '';

        if (rule.required && !value) {
            error = '请输入' + rule.label;
        } else if (value && rule.maxLength && value.length > rule.maxLength) {
            error = rule.label + '不能超过' + rule.maxLength + '个字符';
        } else if (value && rule.minLength && value.length < rule.minLength) {
            error = rule.label + '不能少于' + rule.minLength + '个字符';
        } else if (value && rule.pattern && !rule.pattern.test(value)) {
            error = rule.patternMsg || rule.label + '格式不正确';
        }

        showFieldError(input, error);
        return !error;
    }

    function showFieldError(input, error) {
        input.classList.remove('is-invalid', 'is-valid');
        var feedback = input.parentElement.querySelector('.live-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'live-feedback invalid-feedback';
            if (input.parentElement.classList.contains('input-group')) {
                input.parentElement.parentElement.appendChild(feedback);
            } else {
                input.parentElement.appendChild(feedback);
            }
        }

        if (error) {
            input.classList.add('is-invalid');
            feedback.textContent = error;
            feedback.style.display = 'block';
        } else if (input.value.trim()) {
            input.classList.add('is-valid');
            feedback.style.display = 'none';
        } else {
            feedback.style.display = 'none';
        }
    }

    var inputs = form.querySelectorAll('input[name], select[name]');
    inputs.forEach(function(input) {
        if (!rules[input.name]) return;

        input.addEventListener('blur', function() { validateField(this); });
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });

    form.addEventListener('submit', function(e) {
        var allValid = true;
        inputs.forEach(function(input) {
            if (rules[input.name]) {
                if (!validateField(input)) allValid = false;
            }
        });

        if (!allValid) {
            e.preventDefault();
            var firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) firstInvalid.focus();
        }
    });
})();
</script>
@endsection

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '研究生自我管理系统')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a6cf7;
            --sidebar-width: 260px;
        }
        body { background: #f5f7fb; min-height: 100vh; }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 100%);
            padding: 1.5rem 0;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s;
        }
        .sidebar .brand {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }
        .sidebar .brand small { display: block; font-size: 0.75rem; opacity: 0.7; font-weight: normal; }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.7rem 1.5rem;
            font-size: 0.9rem;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.1);
            border-left-color: var(--primary-color);
        }
        .sidebar .nav-link i { width: 24px; margin-right: 8px; }
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
        }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .card-header { background: transparent; border-bottom: 1px solid #eee; font-weight: 600; }
        .stat-card { border-left: 4px solid var(--primary-color); }
        .stat-card .stat-number { font-size: 2rem; font-weight: 700; color: var(--primary-color); }
        .badge-planning { background: #e3f2fd; color: #1565c0; }
        .badge-in_progress { background: #fff3e0; color: #e65100; }
        .badge-review { background: #f3e5f5; color: #6a1b9a; }
        .badge-completed { background: #e8f5e9; color: #2e7d32; }
        .badge-high { background: #ffebee; color: #c62828; }
        .badge-medium { background: #fff8e1; color: #f57f17; }
        .badge-low { background: #e8f5e9; color: #2e7d32; }
        .progress { height: 8px; border-radius: 4px; }
        .mood-emoji { font-size: 1.5rem; }
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            word-break: break-all;
        }
        .project-title {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100%;
            display: block;
        }
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #dc3545;
            background-image: none;
        }
        .invalid-feedback { font-size: 0.8rem; }
        .quick-phrase-btn {
            font-size: 0.75rem;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #dee2e6;
            background: #f8f9fa;
            color: #495057;
        }
        .quick-phrase-btn:hover {
            background: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
        }
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1100;
            background: var(--primary-color);
            border: none;
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 8px;
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 1rem; padding-top: 4rem; }
            .mobile-toggle { display: flex; align-items: center; justify-content: center; }
        }
        .achievement-toast-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            pointer-events: none;
        }
        .achievement-toast {
            pointer-events: auto;
            min-width: 320px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.4);
            padding: 1rem 1.25rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: achievementSlideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            opacity: 0;
            transform: translateX(100%);
        }
        .achievement-toast.hiding {
            animation: achievementSlideOut 0.4s ease-in forwards;
        }
        .achievement-toast-icon {
            font-size: 2rem;
            flex-shrink: 0;
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .achievement-toast-body { flex: 1; }
        .achievement-toast-title { font-weight: 700; font-size: 0.95rem; }
        .achievement-toast-desc { font-size: 0.8rem; opacity: 0.85; margin-top: 2px; }
        .achievement-toast-close {
            background: none; border: none; color: rgba(255,255,255,0.7);
            font-size: 1.2rem; cursor: pointer; padding: 0; line-height: 1;
        }
        .achievement-toast-close:hover { color: #fff; }
        @keyframes achievementSlideIn {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes achievementSlideOut {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(100%); }
        }
    </style>
    @yield('styles')
</head>
<body>
    <button class="mobile-toggle" onclick="document.querySelector('.sidebar').classList.toggle('show')">
        <i class="bi bi-list"></i>
    </button>

    <nav class="sidebar">
        <div class="brand">
            <i class="bi bi-mortarboard-fill"></i> 研究生管理
            <small>Graduate Self-Management</small>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-house-door"></i> 仪表盘
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">
                    <i class="bi bi-kanban"></i> 课题进度
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('literatures.*') ? 'active' : '' }}" href="{{ route('literatures.index') }}">
                    <i class="bi bi-book"></i> 文献管理
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('todos.*') ? 'active' : '' }}" href="{{ route('todos.index') }}">
                    <i class="bi bi-check2-square"></i> 日程待办
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('notes.*') ? 'active' : '' }}" href="{{ route('notes.index') }}">
                    <i class="bi bi-journal-text"></i> 笔记归档
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('meetings.*') ? 'active' : '' }}" href="{{ route('meetings.index') }}">
                    <i class="bi bi-people-fill"></i> 会议纪要
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('routines.*') ? 'active' : '' }}" href="{{ route('routines.index') }}">
                    <i class="bi bi-alarm"></i> 作息记录
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('statistics.*') ? 'active' : '' }}" href="{{ route('statistics.index') }}">
                    <i class="bi bi-graph-up"></i> 数据统计
                </a>
            </li>
            <li class="nav-item mt-2" style="padding:0 1.5rem;"><small class="text-white-50">生活记录</small></li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('weather-commute.*') ? 'active' : '' }}" href="{{ route('weather-commute.index') }}">
                    <i class="bi bi-cloud-sun"></i> 天气通勤
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('drink-water.*') ? 'active' : '' }}" href="{{ route('drink-water.index') }}">
                    <i class="bi bi-droplet"></i> 饮水打卡
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('caffeine.*') ? 'active' : '' }}" href="{{ route('caffeine.index') }}">
                    <i class="bi bi-cup-hot"></i> 咖啡茶饮
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('restroom.*') ? 'active' : '' }}" href="{{ route('restroom.index') }}">
                    <i class="bi bi-door-open"></i> 如厕记录
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('watch-logs.*') ? 'active' : '' }}" href="{{ route('watch-logs.index') }}">
                    <i class="bi bi-film"></i> 追剧追番
                </a>
            </li>
            <li class="nav-item mt-2" style="padding:0 1.5rem;"><small class="text-white-50">管理工具</small></li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                    <i class="bi bi-wallet2"></i> 经费记账
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('item-borrowings.*') ? 'active' : '' }}" href="{{ route('item-borrowings.index') }}">
                    <i class="bi bi-box-arrow-up-right"></i> 物品借用
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('consumable-applications.*') ? 'active' : '' }}" href="{{ route('consumable-applications.index') }}">
                    <i class="bi bi-box-seam"></i> 耗材申领
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('contacts.*') ? 'active' : '' }}" href="{{ route('contacts.index') }}">
                    <i class="bi bi-people"></i> 通讯录
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('quotes.*') ? 'active' : '' }}" href="{{ route('quotes.index') }}">
                    <i class="bi bi-blockquote-left"></i> 短句摘抄
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reviews.*') ? 'active' : '' }}" href="{{ route('reviews.index') }}">
                    <i class="bi bi-arrow-repeat"></i> 周月复盘
                </a>
            </li>
            <li class="nav-item mt-2" style="padding:0 1.5rem;"><small class="text-white-50">个人工具</small></li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('achievements.*') ? 'active' : '' }}" href="{{ route('achievements.index') }}">
                    <i class="bi bi-trophy"></i> 成就勋章
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('passwords.*') ? 'active' : '' }}" href="{{ route('passwords.index') }}">
                    <i class="bi bi-shield-lock"></i> 密码备忘
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#restReminderSettingsModal">
                    <i class="bi bi-bell"></i> 休息提醒
                </a>
            </li>
        </ul>
        <div class="mt-auto px-3 pt-4" style="border-top:1px solid rgba(255,255,255,0.1); margin-top:2rem;">
            <div class="text-white-50 small text-center">
                <i class="bi bi-clock"></i> {{ now()->format('Y-m-d H:i') }}
            </div>
        </div>
    </nav>

    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- 全局表单防重复提交 --}}
    <script>
    (function() {
        document.addEventListener('submit', function(e) {
            var form = e.target;
            if (!form || form.tagName !== 'FORM') return;
            if (form.method.toUpperCase() === 'GET') return;
            if (form.dataset.noGuard) return;

            if (form.dataset.submitting === 'true') {
                e.preventDefault();
                return;
            }

            form.dataset.submitting = 'true';

            var btns = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            btns.forEach(function(btn) {
                btn.disabled = true;
                if (btn.tagName === 'BUTTON') {
                    btn.dataset.originalHtml = btn.innerHTML;
                    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> 提交中...';
                }
            });

            setTimeout(function() {
                form.dataset.submitting = '';
                btns.forEach(function(btn) {
                    btn.disabled = false;
                    if (btn.tagName === 'BUTTON' && btn.dataset.originalHtml) {
                        btn.innerHTML = btn.dataset.originalHtml;
                    }
                });
            }, 8000);
        });
    })();
    </script>

    {{-- 成就解锁 Toast 通知 --}}
    <div class="achievement-toast-container" id="achievementToastContainer"></div>
    @if(session('achievement_unlocked'))
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var achievements = @json(session('achievement_unlocked'));
        achievements.forEach(function(a, idx) {
            setTimeout(function() {
                showAchievementToast(a.name, a.description, a.icon);
            }, idx * 600);
        });
    });
    </script>
    @endif
    <script>
    function showAchievementToast(name, description, icon) {
        var container = document.getElementById('achievementToastContainer');
        var toast = document.createElement('div');
        toast.className = 'achievement-toast';
        toast.innerHTML = '<div class="achievement-toast-icon"><i class="bi ' + (icon || 'bi-trophy-fill') + '"></i></div>' +
            '<div class="achievement-toast-body"><div class="achievement-toast-title">🎉 成就解锁!</div>' +
            '<div class="achievement-toast-desc">' + name + (description ? ' — ' + description : '') + '</div></div>' +
            '<button class="achievement-toast-close" onclick="dismissAchievementToast(this)">&times;</button>';
        container.appendChild(toast);
        setTimeout(function() { dismissAchievementToast(toast.querySelector('.achievement-toast-close')); }, 6000);
    }
    function dismissAchievementToast(btn) {
        var toast = btn.closest('.achievement-toast');
        if (!toast || toast.classList.contains('hiding')) return;
        toast.classList.add('hiding');
        setTimeout(function() { toast.remove(); }, 400);
    }
    </script>

    @include('partials.rest-reminder')
    @yield('scripts')
</body>
</html>

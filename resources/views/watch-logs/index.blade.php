@extends('layouts.app')

@section('title', '追剧追番打卡 - 研究生自我管理系统')

@section('styles')
<style>
    .rating-stars { color: #ffc107; }
    .rating-stars .empty { color: #dee2e6; }
    .type-badge-tv { background: #e3f2fd; color: #1565c0; }
    .type-badge-movie { background: #fce4ec; color: #c62828; }
    .type-badge-anime { background: #f3e5f5; color: #6a1b9a; }
    .source-badge-manual { background: #e8f5e9; color: #2e7d32; }
    .source-badge-import { background: #fff3e0; color: #e65100; }
    .import-preview { max-height: 300px; overflow-y: auto; }
    .stat-icon { font-size: 1.5rem; }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-film"></i> 追剧追番打卡</h4>
</div>

{{-- 统计卡片 --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center py-3">
                <div class="stat-icon text-primary"><i class="bi bi-collection-play"></i></div>
                <div class="stat-number fs-4">{{ $stats['total'] }}</div>
                <div class="text-muted small">累计观看</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center py-3">
                <div class="stat-icon text-success"><i class="bi bi-calendar-check"></i></div>
                <div class="stat-number fs-4">{{ $stats['this_month'] }}</div>
                <div class="text-muted small">本月观看</div>
            </div>
        </div>
    </div>
    <div class="col-4 col-md-2">
        <div class="card">
            <div class="card-body text-center py-3">
                <div class="fw-bold text-primary">{{ $stats['tv'] }}</div>
                <div class="text-muted small">电视剧</div>
            </div>
        </div>
    </div>
    <div class="col-4 col-md-2">
        <div class="card">
            <div class="card-body text-center py-3">
                <div class="fw-bold text-danger">{{ $stats['movie'] }}</div>
                <div class="text-muted small">电影</div>
            </div>
        </div>
    </div>
    <div class="col-4 col-md-2">
        <div class="card">
            <div class="card-body text-center py-3">
                <div class="fw-bold text-purple" style="color:#6a1b9a;">{{ $stats['anime'] }}</div>
                <div class="text-muted small">动漫</div>
            </div>
        </div>
    </div>
</div>

{{-- 手动录入表单 --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-plus-circle"></i> 添加观看记录</span>
        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#addForm">
            <i class="bi bi-chevron-down"></i>
        </button>
    </div>
    <div class="collapse show" id="addForm">
        <div class="card-body">
            <form action="{{ route('watch-logs.store') }}" method="POST" id="watchLogForm">
                @csrf
                @submitToken
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">影视名称 <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="例：进击的巨人" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">类型 <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="tv" {{ old('type') == 'tv' ? 'selected' : '' }}>电视剧</option>
                            <option value="movie" {{ old('type') == 'movie' ? 'selected' : '' }}>电影</option>
                            <option value="anime" {{ old('type') == 'anime' ? 'selected' : '' }}>动漫</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">平台</label>
                        <input type="text" name="platform" class="form-control" value="{{ old('platform') }}" placeholder="例：B站、Netflix">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">集数/进度</label>
                        <input type="text" name="episode" class="form-control" value="{{ old('episode') }}" placeholder="例：第5集、S01E03">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">观看日期 <span class="text-danger">*</span></label>
                        <input type="date" name="watch_date" class="form-control @error('watch_date') is-invalid @enderror"
                               value="{{ old('watch_date', date('Y-m-d')) }}" required>
                        @error('watch_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">评分</label>
                        <select name="rating" class="form-select">
                            <option value="">不评分</option>
                            <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>★★★★★</option>
                            <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>★★★★☆</option>
                            <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>★★★☆☆</option>
                            <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>★★☆☆☆</option>
                            <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>★☆☆☆☆</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">备注</label>
                        <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="简短感想...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100" id="watchLogSubmitBtn"><i class="bi bi-plus-lg"></i> 添加</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 浏览器历史导入 --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-cloud-upload"></i> 从浏览器历史导入</span>
        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#importSection">
            <i class="bi bi-chevron-down"></i>
        </button>
    </div>
    <div class="collapse" id="importSection">
        <div class="card-body">
            <p class="text-muted small mb-3">
                支持导入 Chrome/Edge 浏览器历史记录文件（JSON格式）。可使用浏览器扩展"Export Chrome History"导出历史记录，
                系统将自动识别B站、爱奇艺、优酷、腾讯视频、Netflix等平台的观看记录。
            </p>
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">选择历史记录文件</label>
                    <input type="file" id="historyFile" class="form-control" accept=".json,.csv">
                </div>
                <div class="col-md-3">
                    <button type="button" id="parseBtn" class="btn btn-outline-primary w-100" disabled>
                        <i class="bi bi-search"></i> 解析文件
                    </button>
                </div>
                <div class="col-md-3">
                    <button type="button" id="importBtn" class="btn btn-success w-100" style="display:none;">
                        <i class="bi bi-cloud-upload"></i> 确认导入
                    </button>
                </div>
            </div>
            <div id="importPreview" class="mt-3" style="display:none;">
                <h6>识别到以下观看记录：</h6>
                <div class="import-preview">
                    <table class="table table-sm table-hover mb-0">
                        <thead><tr><th><input type="checkbox" id="selectAll" checked></th><th>标题</th><th>平台</th><th>日期</th></tr></thead>
                        <tbody id="importTableBody"></tbody>
                    </table>
                </div>
                <div class="mt-2 text-muted small" id="importCount"></div>
            </div>
            <div id="importResult" class="mt-3" style="display:none;"></div>
        </div>
    </div>
</div>

{{-- 筛选 --}}
<div class="card mb-4">
    <div class="card-body py-2">
        <form class="row g-2 align-items-center" method="GET">
            <div class="col-auto">
                <select name="type" class="form-select form-select-sm">
                    <option value="">全部类型</option>
                    <option value="tv" {{ request('type') == 'tv' ? 'selected' : '' }}>电视剧</option>
                    <option value="movie" {{ request('type') == 'movie' ? 'selected' : '' }}>电影</option>
                    <option value="anime" {{ request('type') == 'anime' ? 'selected' : '' }}>动漫</option>
                </select>
            </div>
            <div class="col-auto">
                <input type="text" name="platform" class="form-control form-control-sm" placeholder="搜索平台"
                       value="{{ request('platform') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-funnel"></i> 筛选</button>
                <a href="{{ route('watch-logs.index') }}" class="btn btn-sm btn-outline-secondary">重置</a>
            </div>
        </form>
    </div>
</div>

{{-- 观看记录列表 --}}
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>影视名称</th>
                    <th>类型</th>
                    <th>平台</th>
                    <th>集数</th>
                    <th>日期</th>
                    <th>评分</th>
                    <th>来源</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>
                        <strong>{{ $log->title }}</strong>
                        @if($log->notes)<br><small class="text-muted">{{ Str::limit($log->notes, 30) }}</small>@endif
                    </td>
                    <td>
                        <span class="badge type-badge-{{ $log->type }}">
                            {{ ['tv' => '电视剧', 'movie' => '电影', 'anime' => '动漫'][$log->type] }}
                        </span>
                    </td>
                    <td>{{ $log->platform ?: '-' }}</td>
                    <td>{{ $log->episode ?: '-' }}</td>
                    <td>{{ $log->watch_date->format('m-d') }}</td>
                    <td>
                        @if($log->rating)
                            <span class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $log->rating ? '-fill' : '' }} {{ $i > $log->rating ? 'empty' : '' }}"></i>
                                @endfor
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge source-badge-{{ $log->source }}">
                            {{ $log->source == 'manual' ? '手动' : '导入' }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('watch-logs.destroy', $log) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('确定删除这条记录吗？')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">暂无观看记录，开始记录你的追剧生活吧！</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="card-footer">{{ $logs->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

@section('scripts')
<script>
(function() {
    const fileInput = document.getElementById('historyFile');
    const parseBtn = document.getElementById('parseBtn');
    const importBtn = document.getElementById('importBtn');
    const preview = document.getElementById('importPreview');
    const tableBody = document.getElementById('importTableBody');
    const importCount = document.getElementById('importCount');
    const importResult = document.getElementById('importResult');
    const selectAll = document.getElementById('selectAll');

    let parsedRecords = [];

    const platforms = {
        'bilibili.com': 'Bilibili',
        'iqiyi.com': '爱奇艺',
        'youku.com': '优酷',
        'v.qq.com': '腾讯视频',
        'netflix.com': 'Netflix',
        'crunchyroll.com': 'Crunchyroll',
        'mgtv.com': '芒果TV',
        'douban.com/movie': '豆瓣',
    };

    fileInput.addEventListener('change', function() {
        parseBtn.disabled = !this.files.length;
        preview.style.display = 'none';
        importBtn.style.display = 'none';
        importResult.style.display = 'none';
    });

    parseBtn.addEventListener('click', function() {
        const file = fileInput.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                let data;
                if (file.name.endsWith('.json')) {
                    data = JSON.parse(e.target.result);
                } else {
                    data = parseCSV(e.target.result);
                }

                parsedRecords = [];
                const seen = new Set();

                (Array.isArray(data) ? data : [data]).forEach(function(item) {
                    const url = item.url || item.URL || '';
                    const title = item.title || item.Title || '';

                    for (const [domain, platform] of Object.entries(platforms)) {
                        if (url.includes(domain)) {
                            let cleanTitle = title
                                .replace(/[-_|].*?(bilibili|爱奇艺|优酷|腾讯视频|Netflix|芒果TV).*$/i, '')
                                .replace(/^\s+|\s+$/g, '');

                            if (!cleanTitle || cleanTitle.length < 2) break;

                            const visitTime = item.visitTime || item.visit_time || item.date || item.Date || new Date().toISOString();
                            const dateStr = new Date(typeof visitTime === 'number' ? visitTime / 1000 : visitTime)
                                .toISOString().split('T')[0];
                            const key = cleanTitle + '|' + dateStr;

                            if (!seen.has(key)) {
                                seen.add(key);
                                parsedRecords.push({
                                    title: cleanTitle,
                                    platform: platform,
                                    watch_date: dateStr,
                                    type: 'tv',
                                    selected: true,
                                });
                            }
                            break;
                        }
                    }
                });

                renderPreview();
            } catch (err) {
                importResult.style.display = 'block';
                importResult.innerHTML = '<div class="alert alert-danger">文件解析失败：' + err.message + '</div>';
            }
        };
        reader.readAsText(file);
    });

    function parseCSV(text) {
        const lines = text.trim().split('\n');
        if (lines.length < 2) return [];
        const headers = lines[0].split(',').map(h => h.trim().replace(/"/g, ''));
        return lines.slice(1).map(line => {
            const values = line.split(',').map(v => v.trim().replace(/"/g, ''));
            const obj = {};
            headers.forEach((h, i) => obj[h] = values[i] || '');
            return obj;
        });
    }

    function renderPreview() {
        if (parsedRecords.length === 0) {
            importResult.style.display = 'block';
            importResult.innerHTML = '<div class="alert alert-warning">未在文件中识别到影视平台的观看记录。</div>';
            preview.style.display = 'none';
            importBtn.style.display = 'none';
            return;
        }

        tableBody.innerHTML = '';
        parsedRecords.forEach(function(r, i) {
            const tr = document.createElement('tr');
            tr.innerHTML = '<td><input type="checkbox" class="record-check" data-idx="' + i + '" checked></td>' +
                '<td>' + r.title + '</td><td>' + r.platform + '</td><td>' + r.watch_date + '</td>';
            tableBody.appendChild(tr);
        });

        importCount.textContent = '共识别 ' + parsedRecords.length + ' 条记录';
        preview.style.display = 'block';
        importBtn.style.display = 'block';
        importResult.style.display = 'none';
    }

    selectAll.addEventListener('change', function() {
        document.querySelectorAll('.record-check').forEach(cb => {
            cb.checked = selectAll.checked;
            parsedRecords[cb.dataset.idx].selected = selectAll.checked;
        });
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('record-check')) {
            parsedRecords[e.target.dataset.idx].selected = e.target.checked;
        }
    });

    importBtn.addEventListener('click', function() {
        const selected = parsedRecords.filter(r => r.selected);
        if (selected.length === 0) {
            alert('请至少选择一条记录');
            return;
        }

        importBtn.disabled = true;
        importBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> 导入中...';

        fetch('{{ route("watch-logs.import") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ records: selected }),
        })
        .then(r => r.json())
        .then(data => {
            importResult.style.display = 'block';
            importResult.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
            importBtn.style.display = 'none';
            preview.style.display = 'none';
            if (data.imported > 0) {
                setTimeout(() => location.reload(), 1500);
            }
        })
        .catch(err => {
            importResult.style.display = 'block';
            importResult.innerHTML = '<div class="alert alert-danger">导入失败，请重试</div>';
        })
        .finally(() => {
            importBtn.disabled = false;
            importBtn.innerHTML = '<i class="bi bi-cloud-upload"></i> 确认导入';
        });
    });
})();
</script>
@endsection

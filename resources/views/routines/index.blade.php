@extends('layouts.app')

@section('title', '作息记录')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-alarm"></i> 作息记录</h4>
</div>

{{-- 快速记录 --}}
<div class="card mb-4">
    <div class="card-header"><i class="bi bi-plus-circle"></i> 今日记录</div>
    <div class="card-body">
        <form action="{{ route('routines.store') }}" method="POST">
            @csrf
            @submitToken
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">日期 <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                           value="{{ old('date', isset($today) && $today ? $today->date->format('Y-m-d') : today()->format('Y-m-d')) }}" required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">起床时间</label>
                    <input type="time" name="wake_time" class="form-control @error('wake_time') is-invalid @enderror"
                           value="{{ old('wake_time', $today->wake_time ?? '') }}">
                    @error('wake_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">就寝时间</label>
                    <input type="time" name="sleep_time" class="form-control @error('sleep_time') is-invalid @enderror"
                           value="{{ old('sleep_time', $today->sleep_time ?? '') }}">
                    @error('sleep_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">学习(小时)</label>
                    <input type="number" step="0.5" name="study_hours" class="form-control @error('study_hours') is-invalid @enderror"
                           min="0" max="24" value="{{ old('study_hours', $today->study_hours ?? '') }}">
                    @error('study_hours')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">运动(分钟)</label>
                    <input type="number" name="exercise_minutes" class="form-control @error('exercise_minutes') is-invalid @enderror"
                           min="0" max="600" value="{{ old('exercise_minutes', $today->exercise_minutes ?? '') }}">
                    @error('exercise_minutes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">心情 <span class="text-danger">*</span></label>
                    <select name="mood" class="form-select @error('mood') is-invalid @enderror" required>
                        <option value="1" {{ old('mood', $today->mood ?? 3) == 1 ? 'selected' : '' }}>1 😫</option>
                        <option value="2" {{ old('mood', $today->mood ?? 3) == 2 ? 'selected' : '' }}>2 😟</option>
                        <option value="3" {{ old('mood', $today->mood ?? 3) == 3 ? 'selected' : '' }}>3 😐</option>
                        <option value="4" {{ old('mood', $today->mood ?? 3) == 4 ? 'selected' : '' }}>4 😊</option>
                        <option value="5" {{ old('mood', $today->mood ?? 3) == 5 ? 'selected' : '' }}>5 🥳</option>
                    </select>
                    @error('mood')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-10">
                    <label class="form-label">今日总结</label>
                    <input type="text" id="summaryInput" name="summary"
                           class="form-control @error('summary') is-invalid @enderror"
                           maxlength="500" placeholder="今天做了什么？点击下方快捷用语或自行输入"
                           value="{{ old('summary', $today->summary ?? '') }}">
                    @error('summary')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="mt-2 d-flex flex-wrap gap-1">
                        <span class="quick-phrase-btn" data-phrase="写论文，进展顺利">写论文</span>
                        <span class="quick-phrase-btn" data-phrase="读文献，整理笔记">读文献</span>
                        <span class="quick-phrase-btn" data-phrase="跑实验，调参优化">跑实验</span>
                        <span class="quick-phrase-btn" data-phrase="写代码，调试bug">写代码</span>
                        <span class="quick-phrase-btn" data-phrase="组会汇报">组会汇报</span>
                        <span class="quick-phrase-btn" data-phrase="和导师讨论">导师讨论</span>
                        <span class="quick-phrase-btn" data-phrase="整理数据，分析结果">整理数据</span>
                        <span class="quick-phrase-btn" data-phrase="准备PPT/材料">准备材料</span>
                        <span class="quick-phrase-btn" data-phrase="去图书馆学习">图书馆</span>
                        <span class="quick-phrase-btn" data-phrase="复习课程内容">复习课程</span>
                        <span class="quick-phrase-btn" data-phrase="效率一般，需要调整">效率一般</span>
                        <span class="quick-phrase-btn" data-phrase="状态很好，高效产出">状态很好</span>
                        <span class="quick-phrase-btn" data-phrase="休息日，适当放松">休息放松</span>
                        <span class="quick-phrase-btn" data-phrase="运动健身，保持活力">运动健身</span>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg"></i> 保存</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- 历史记录 --}}
<div class="card">
    <div class="card-header">近期作息记录</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>日期</th>
                    <th>起床</th>
                    <th>就寝</th>
                    <th>学习</th>
                    <th>运动</th>
                    <th>心情</th>
                    <th>总结</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($routines as $routine)
                    <tr>
                        <td>{{ $routine->date->format('m-d') }}</td>
                        <td>{{ $routine->wake_time ?? '-' }}</td>
                        <td>{{ $routine->sleep_time ?? '-' }}</td>
                        <td>{{ $routine->study_hours }}h</td>
                        <td>{{ $routine->exercise_minutes }}min</td>
                        <td class="mood-emoji">{{ ['','😫','😟','😐','😊','🥳'][$routine->mood] }}</td>
                        <td class="small" style="max-width:200px;" title="{{ $routine->summary }}">{{ Str::limit($routine->summary, 30) }}</td>
                        <td>
                            <form action="{{ route('routines.destroy', $routine) }}" method="POST" onsubmit="return confirm('确定删除此记录？')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">暂无记录</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.quick-phrase-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var input = document.getElementById('summaryInput');
        var phrase = this.getAttribute('data-phrase');
        if (input.value && !input.value.endsWith('，') && !input.value.endsWith(',')) {
            input.value += '，' + phrase;
        } else {
            input.value += phrase;
        }
        input.focus();
    });
});
</script>
@endsection

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">课题名称 <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $project->title ?? '') }}" required maxlength="255"
               placeholder="请输入课题名称（最多255字符）">
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">状态 <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="planning" {{ old('status', $project->status ?? '') == 'planning' ? 'selected' : '' }}>规划中</option>
            <option value="in_progress" {{ old('status', $project->status ?? '') == 'in_progress' ? 'selected' : '' }}>进行中</option>
            <option value="review" {{ old('status', $project->status ?? '') == 'review' ? 'selected' : '' }}>审核中</option>
            <option value="completed" {{ old('status', $project->status ?? '') == 'completed' ? 'selected' : '' }}>已完成</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label">描述</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3"
                  maxlength="1000" placeholder="课题描述（选填，最多1000字符）">{{ old('description', $project->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">进度 (%) <span class="text-danger">*</span></label>
        <input type="number" name="progress" class="form-control @error('progress') is-invalid @enderror"
               min="0" max="100" value="{{ old('progress', $project->progress ?? 0) }}" required>
        @error('progress')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">开始日期</label>
        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
               value="{{ old('start_date', isset($project) && $project->start_date ? $project->start_date->format('Y-m-d') : '') }}">
        @error('start_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">截止日期</label>
        <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror"
               value="{{ old('due_date', isset($project) && $project->due_date ? $project->due_date->format('Y-m-d') : '') }}">
        @error('due_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

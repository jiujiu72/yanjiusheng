<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">待办事项 <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $todo->title ?? '') }}" required maxlength="255"
               placeholder="请输入待办事项">
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">优先级 <span class="text-danger">*</span></label>
        <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
            <option value="low" {{ old('priority', $todo->priority ?? '') == 'low' ? 'selected' : '' }}>低</option>
            <option value="medium" {{ old('priority', $todo->priority ?? 'medium') == 'medium' ? 'selected' : '' }}>中</option>
            <option value="high" {{ old('priority', $todo->priority ?? '') == 'high' ? 'selected' : '' }}>高</option>
        </select>
        @error('priority')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label">描述</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                  rows="2" maxlength="1000" placeholder="补充说明（选填）">{{ old('description', $todo->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">截止日期</label>
        <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror"
               value="{{ old('due_date', isset($todo) && $todo->due_date ? $todo->due_date->format('Y-m-d') : '') }}">
        @error('due_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">分类</label>
        <input type="text" name="category" class="form-control @error('category') is-invalid @enderror"
               maxlength="50" placeholder="如：论文、课程、生活"
               value="{{ old('category', $todo->category ?? '') }}">
        @error('category')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

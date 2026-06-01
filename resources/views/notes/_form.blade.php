<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">标题 <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $note->title ?? '') }}" required maxlength="255"
               placeholder="请输入笔记标题">
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">分类</label>
        <input type="text" name="category" class="form-control @error('category') is-invalid @enderror"
               maxlength="50" placeholder="如：论文笔记、课程笔记"
               value="{{ old('category', $note->category ?? '') }}">
        @error('category')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label">内容 <span class="text-danger">*</span></label>
        <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                  rows="10" required placeholder="请输入笔记内容">{{ old('content', $note->content ?? '') }}</textarea>
        @error('content')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-8">
        <label class="form-label">标签</label>
        <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror"
               maxlength="255" placeholder="用逗号分隔"
               value="{{ old('tags', $note->tags ?? '') }}">
        @error('tags')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pinned" value="1" {{ old('pinned', $note->pinned ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">置顶</label>
        </div>
    </div>
</div>

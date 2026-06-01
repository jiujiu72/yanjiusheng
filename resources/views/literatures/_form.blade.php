<div class="row g-3">
    <div class="col-12">
        <label class="form-label">论文标题 <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $literature->title ?? '') }}" required maxlength="255"
               placeholder="请输入论文完整标题">
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">作者</label>
        <input type="text" name="authors" class="form-control @error('authors') is-invalid @enderror"
               placeholder="多个作者用逗号分隔" maxlength="500"
               value="{{ old('authors', $literature->authors ?? '') }}">
        @error('authors')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">期刊/会议</label>
        <input type="text" name="journal" class="form-control @error('journal') is-invalid @enderror"
               maxlength="255" value="{{ old('journal', $literature->journal ?? '') }}">
        @error('journal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2">
        <label class="form-label">年份</label>
        <input type="number" name="year" class="form-control @error('year') is-invalid @enderror"
               min="1900" max="2100" placeholder="如 2024"
               value="{{ old('year', $literature->year ?? '') }}">
        @error('year')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">DOI</label>
        <input type="text" name="doi" class="form-control @error('doi') is-invalid @enderror"
               maxlength="255" placeholder="如 10.xxxx/xxxxx"
               value="{{ old('doi', $literature->doi ?? '') }}">
        @error('doi')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">阅读状态 <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="unread" {{ old('status', $literature->status ?? '') == 'unread' ? 'selected' : '' }}>未读</option>
            <option value="reading" {{ old('status', $literature->status ?? '') == 'reading' ? 'selected' : '' }}>在读</option>
            <option value="finished" {{ old('status', $literature->status ?? '') == 'finished' ? 'selected' : '' }}>已读</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">评分 (0-5) <span class="text-danger">*</span></label>
        <input type="number" name="rating" class="form-control @error('rating') is-invalid @enderror"
               min="0" max="5" required value="{{ old('rating', $literature->rating ?? 0) }}">
        @error('rating')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label">摘要</label>
        <textarea name="abstract" class="form-control @error('abstract') is-invalid @enderror"
                  rows="3" maxlength="2000" placeholder="论文摘要（选填）">{{ old('abstract', $literature->abstract ?? '') }}</textarea>
        @error('abstract')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label">阅读笔记</label>
        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                  rows="4" maxlength="5000" placeholder="阅读心得与笔记（选填）">{{ old('notes', $literature->notes ?? '') }}</textarea>
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label">标签</label>
        <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror"
               maxlength="255" placeholder="用逗号分隔，如：深度学习,NLP,Transformer"
               value="{{ old('tags', $literature->tags ?? '') }}">
        @error('tags')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

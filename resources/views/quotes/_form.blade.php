<div class="mb-3">
    <label class="form-label">短句内容 <span class="text-danger">*</span></label>
    <textarea name="content" class="form-control @error('content') is-invalid @enderror"
              rows="4" placeholder="输入短句、金句或学术语句...">{{ old('content', $quote->content ?? '') }}</textarea>
    @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">来源</label>
    <input type="text" name="source" class="form-control @error('source') is-invalid @enderror"
           value="{{ old('source', $quote->source ?? '') }}" placeholder="论文、书籍、作者等">
    @error('source')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">分类</label>
        <input type="text" name="category" class="form-control @error('category') is-invalid @enderror"
               value="{{ old('category', $quote->category ?? '') }}" placeholder="如：学术句式、优美文笔、名人名言">
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">标签</label>
        <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror"
               value="{{ old('tags', $quote->tags ?? '') }}" placeholder="多个标签用逗号分隔">
        @error('tags')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-group mb-3">
    <label class="control-label">Title</label>
    <input name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control">
</div>

<div class="form-group mb-3">
    <label class="control-label">Description</label>
    <textarea name="description" data-shortcode-attribute="content" class="form-control" rows="3">{{ $content }}</textarea>
</div>

<div class="form-group mb-3">
    <label class="control-label">Limit</label>
    <input name="limit" class="form-control" value="{{ Arr::get($attributes, 'limit', 6) }}">
</div>

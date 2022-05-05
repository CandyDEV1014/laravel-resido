<div class="form-group mb-3">
    <label class="control-label">{{ __('Title') }}</label>
    <input name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control">
</div>

<div class="form-group mb-3">
    <label class="control-label">{{ __('Description') }}</label>
    <textarea name="description" data-shortcode-attribute="content" class="form-control" rows="3">{{ $content }}</textarea>
</div>

<div class="form-group mb-3">
    <label class="control-label">{{ __('Limit') }}</label>
    <input name="title" class="form-control" value="{{ Arr::get($attributes, 'limit', 6) }}">
</div>

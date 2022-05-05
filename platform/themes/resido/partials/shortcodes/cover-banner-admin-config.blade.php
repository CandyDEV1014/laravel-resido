<div class="form-group mb-3">
    <label class="control-label">{{ __('Title') }}</label>
    <input name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control" placeholder="Search Perfect Place In Your City">
</div>
<div class="form-group mb-3">
    <label class="control-label">{{ __('Description') }}</label>
    <textarea name="description" data-shortcode-attribute="content" class="form-control" rows="3">{{ $content }}</textarea>
</div>
<div class="form-group mb-3">
    <label class="control-label">{{ __('Background') }}</label>
    {!! Form::mediaImage('bg', Arr::get($attributes, 'bg')) !!}
</div>
<div class="form-group mb-3">
    <label class="control-label">{{ __('Text Button') }}</label>
    <input name="btntext" value="{{ Arr::get($attributes, 'btntext') }}" class="form-control" placeholder="Explore More Property">
</div>
<div class="form-group mb-3">
    <label class="control-label">{{ __('Link Button') }}</label>
    <input name="btnlink" value="{{ Arr::get($attributes, 'btnlink') }}" class="form-control" placeholder="https://abc.com">
</div>

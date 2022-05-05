@php
$types = app(Botble\RealEstate\Repositories\Interfaces\TypeInterface::class)->all();
@endphp

<div class="form-group mb-3">
    <label class="control-label">{{ __('Title') }}</label>
    <input name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control">
</div>

<div class="form-group mb-3">
    <label class="control-label">{{ __('Description') }}</label>
    <textarea name="description" data-shortcode-attribute="content" class="form-control" rows="3">{{ $content }}</textarea>
</div>

<div class="form-group mb-3">
    <label class="control-label">{{ __('Property Type') }}</label>
    <select name="type" id="type" class="form-control">
        <option value="" @if (Arr::get($attributes, 'type') == "") selected @endif>{{ __('-----') }}</option>
        @foreach($types as $key => $type)
            <option value="{{ $type->id }}" @if (Arr::get($attributes, 'type') == $type->slug) selected @endif>{{ $type->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group mb-3">
    <label class="control-label">{{ __('Limit') }}</label>
    <input name="limit" class="form-control" value="{{ Arr::get($attributes, 'limit', 6) }}">
</div>

<div class="form-group mb-3">
    <label class="control-label">{{ __('Style') }}</label>
    <select name="style" class="form-control">
        <option value="1" @if (Arr::get($attributes, 'style') == 1) selected @endif>Style 1</option>
        <option value="2" @if (Arr::get($attributes, 'style') == 2) selected @endif>Style 2</option>
    </select>
</div>

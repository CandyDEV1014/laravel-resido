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

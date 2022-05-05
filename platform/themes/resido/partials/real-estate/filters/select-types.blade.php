<select id="select-type" data-placeholder="{{ __('Type') }}" class="form-control" name="type">
    <option value="">{{ __('-- Select --') }}</option>
    @foreach (app(Botble\RealEstate\Repositories\Interfaces\TypeInterface::class)->all() as $key => $type)
        <option value="{{ $type->slug }}" @if (request()->input('type') == $type->slug) selected @endif>{{ $type->name }}
        </option>
    @endforeach
</select>

<select data-placeholder="{{ __('Country') }}" class="form-control select2" name="country_id"
        id="filter_country_id">
    <option value="">{{ __('Country') }}</option>
    @foreach(get_countries() as $country)
        <option value="{{ $country->id }}" @if (request()->input('country_id') == $country->id) selected @endif>
            {{ $country->name }}
        </option>
    @endforeach
</select>

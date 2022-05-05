<select data-placeholder="{{ __('City') }}" class="form-control select2" name="city_id" id="filter_city_id"
        data-url="{{ route('public.ajax.cities-by-state') }}">
    <option value="">{{ __('City') }}</option>
    @if(!empty(request()->input('state_id')))
        @foreach(get_cities_by_state(request()->input('state_id')) as $city)
            <option value="{{ $city->id }}"
                    @if (request()->input('city_id') == $city->id) selected @endif>
                {{ $city->name }}
            </option>
        @endforeach
    @endif
</select>

<select data-placeholder="{{ __('State') }}" class="form-control select2" name="state_id" id="filter_state_id"
        data-url="{{ route('public.ajax.stateses-by-country') }}">
    <option value="">{{ __('State') }}</option>
    @if(!empty(request()->input('country_id')))
        @foreach(get_states_by_country(request()->input('country_id')) as $state)
            <option value="{{ $state->id }}" @if (request()->input('state_id') == $state->id) selected @endif>
                {{ $state->name }}
            </option>
        @endforeach
    @endif
</select>



<select data-placeholder="{{ __('Country') }}" class="form-control select2" name="country_id"
        id="filter_country_id">
    <option value="">{{ __('Country') }}</option>
    @foreach(get_countries() as $country)
        <option value="{{ $country->id }}" @if (request()->input('country_id') == $country->id) selected @endif>
            {{ $country->name }}
        </option>
    @endforeach
</select>

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

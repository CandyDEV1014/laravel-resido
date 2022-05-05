<select data-placeholder="{{ __('Country') }}, {{ __('State') }}, {{ __('City') }}" class="form-control city_id" data-url="{{ route('public.ajax.cities') }}" name="city_id" id="city_id">
    @if(!empty(request()->input('city_id')))
        <option value="{{ request()->input('city_id') }}" selected>
            {{ Location::getCityNameById(request()->input('city_id')) }}
        </option>
    @endif
</select>

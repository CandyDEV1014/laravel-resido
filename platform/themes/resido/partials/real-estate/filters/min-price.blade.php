<select id="minprice" data-placeholder="{{ __('No Min') }}" name="min_price" class="form-control">
    <option value="">&nbsp;</option>
    @if (theme_option('min_price'))
        @foreach (get_repeat_field('min_price') as $item)
            @if (count($item) > 1)
                <option value="{{ $item[0]['value'] }}" @if (request()->input('min_price') == $item[0]['value']) selected @endif>
                    {{ $item[1]['value'] }}
                </option>
            @endif
        @endforeach
    @endif
</select>

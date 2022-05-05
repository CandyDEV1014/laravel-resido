<select id="select-bathroom" data-placeholder="{{ __('Bathroom') }}" name="bathroom" class="form-control">
    <option value="">&nbsp;</option>
    @if (theme_option('bathroom'))
        @foreach (get_repeat_field('bathroom') as $i => $item)
            @if (count($item) > 1)
                <option value="{{ $item[1]['value'] }}" @if (request()->input('bathroom') == $item[1]['value']) selected @endif>
                    {{ $item[0]['value'] }}
                </option>
            @endif
        @endforeach
    @endif
</select>

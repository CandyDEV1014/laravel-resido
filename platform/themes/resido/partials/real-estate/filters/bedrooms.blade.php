<select id="select-bedroom" data-placeholder="{{ __('Bedroom') }}" name="bedroom" class="form-control">
    <option value="">&nbsp;</option>
    @if (theme_option('bedroom'))
        @foreach (get_repeat_field('bedroom') as $i => $item)
            @if (count($item) > 1)
                <option value="{{ $item[1]['value'] }}" @if (request()->input('bedroom') == $item[1]['value']) selected @endif>
                    {{ $item[0]['value'] }}
                </option>
            @endif
        @endforeach
    @endif
</select>

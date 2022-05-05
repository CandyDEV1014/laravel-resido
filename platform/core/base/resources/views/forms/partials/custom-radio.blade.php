@php
/**
 * @var string $name
 * @var array $values
 * @var string $selected
 */
$values = (array)$values;
@endphp
@if (count($values) > 1) <div class="mt-radio-list"> @endif
    @foreach($values as $line)
        @php
            $value = isset($line[0]) ? $line[0] : '';
            $label = isset($line[1]) ? $line[1] : '';
            $disabled = isset($line[2]) ? $line[2] : '';
        @endphp
        <label class="me-2">
            <input type="radio"
                   value="{{ $value }}"
                   {{ (string)$selected === (string)$value ? 'checked' : '' }}
                   name="{{ $name }}" {{ $disabled ? 'disabled' : '' }}>
            {{ $label }}
        </label>
    @endforeach
@if (count($values) > 1) </div> @endif

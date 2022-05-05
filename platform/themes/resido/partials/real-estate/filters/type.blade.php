@php
$types = app(Botble\RealEstate\Repositories\Interfaces\TypeInterface::class)->all();
@endphp

@if(count($types))
@php
$type_choice = request()->input('type', $types->first()->slug);
@endphp
<ul>
    @foreach ($types as $key => $type)
        <li>
            <input id="cp-{{ $type->slug }}" value="{{ $type->slug }}" class="checkbox-custom" name="type"
                type="radio" @if ($type_choice == $type->slug) checked @endif>
            <label for="cp-{{ $type->slug }}" class="checkbox-custom-label">{{ $type->name }}</label>
        </li>
    @endforeach
</ul>
@endif
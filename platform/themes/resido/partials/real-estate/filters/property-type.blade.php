@php 
    $categories = app(\Botble\RealEstate\Repositories\Interfaces\CategoryInterface::class)->allBy(['status' => \Botble\Base\Enums\BaseStatusEnum::PUBLISHED]);
@endphp

<select id="minprice" data-placeholder="{{ __('Property Type') }}" name="category_id" class="form-control">
    <option value="">&nbsp;</option>
    @if (theme_option('min_price'))
        @foreach ($categories as $key => $value)
            <option value="{{$value->id}}">{{$value->name}}</option>
        @endforeach
    @endif
</select>

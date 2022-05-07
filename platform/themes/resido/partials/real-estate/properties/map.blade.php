@php
    $details = $property->details()
        ->where('status', Botble\Base\Enums\BaseStatusEnum::PUBLISHED)
        ->where('is_featured', 1)
        ->orderBy('order', 'DESC')
        ->limit(3)
        ->get();
@endphp
<div class="d-flex">
    <div class="blii">
        <img class="lazy" src="{{ get_image_loading() }}" data-src="{{ $property->image_thumb }}" height="100" width="100" alt="{{ $property->name }}">
    </div>
    <div class="infomarker">
        <h4><a href="{{ $property->url }}" target="_blank">{{ $property->name }}</a></h4>
        <div class="mb-1"><span>{{ $property->city_name }}</span></div>
        <div>
            @foreach($details as $detail)
                <span class="detail">
                    <i class="{{ $detail->icon }}"></i>
                    <i class="vti">{{ $detail->pivot->value }}</i>
                </span>
            @endforeach
        </div>
        <div class="lists_property_price">
            <div class="lists_property_price_value">
                <h5>{{ $property->price_html }}</h5>
            </div>
        </div>

    </div>
</div>

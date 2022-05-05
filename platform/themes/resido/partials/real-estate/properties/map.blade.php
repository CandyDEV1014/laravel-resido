<div class="d-flex">
    <div class="blii">
        <img class="lazy" src="{{ get_image_loading() }}" data-src="{{ $property->image_thumb }}" height="100" width="100" alt="{{ $property->name }}">
    </div>
    <div class="infomarker">
        <h4><a href="{{ $property->url }}" target="_blank">{{ $property->name }}</a></h4>
        <div class="mb-1"><span>{{ $property->city_name }}</span></div>
        <div>
            <span> <i class="ti-location-pin"></i> {{ $property->square_text }}</span> &nbsp; &nbsp;
            <span>
                <i><img src="{{ Theme::asset()->url('img/bed.svg') }}" alt="icon" width="13"></i>
                <i class="vti">{{ $property->number_bedroom }}</i></span> &nbsp; &nbsp; <span>
                <i><img src="{{ Theme::asset()->url('img/bathtub.svg') }}" alt="icon" width="13"></i>
                <i class="vti">{{ $property->number_bathroom }}</i>
            </span>
        </div>
        <div class="lists_property_price">
            <div class="lists_property_price_value">
                <h5>{{ $property->price_html }}</h5>
            </div>
        </div>

    </div>
</div>

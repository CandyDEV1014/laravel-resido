<div class="property-listing property-2">
    <div class="listing-img-wrapper">
        <div class="list-single-img">
            <a href="{{ $property->url }}">
                <img src="{{ $property->image_thumb }}" class="img-fluid mx-auto" alt="{{ $property->name }}">
            </a>
        </div>
        <span class="prt-types {{ $property->type_slug }}">{{ $property->type_name }}</span>
    </div>
    <div class="listing-detail-wrapper pb-0">
        <div class="listing-short-detail">
            <h4 class="listing-name">
                <a href="{{ $property->url }}" title="{{ $property->name }}">{{ $property->name }}</a>
                <i class="list-status ti-check"></i>
            </h4>
        </div>
    </div>
    <div class="price-features-wrapper">
        <div class="listing-price-fx">
            <h6 class="listing-card-info-price price-prefix">{{ $property->price_html }}</span></h6>
        </div>
    </div>
</div>

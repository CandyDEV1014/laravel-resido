<!-- ============================ Hero Banner  Start================================== -->
<div class="featured_slick_gallery gray">
    {!! Theme::partial('real-estate.properties.slick-gallery', compact('property')) !!}
</div>
<!-- ============================ Hero Banner End ================================== -->

<!-- ============================ Property Header Info Start================================== -->
<section class="gray-simple rtl p-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-md-12">

                <div class="property_block_wrap style-3">
                    @if(isset($property->author->avatar))
                    <div class="pbw-flex-1">
                        <div class="pbw-flex-thumb">
                            <img class="img-fluid" src="{{ RvMedia::getImageUrl($property->author->avatar->url, 'thumb') }}" alt="{{ $property->author->name }}">
                        </div>
                    </div>
                    @endif

                    <div class="pbw-flex">
                        <div class="prt-detail-title-desc">
                            <span class="prt-types {{ $property->type }}">{{ $property->type_name }}</span>
                            <h3>{{ $property->name }}</h3>
                            <span><i class="lni-map-marker"></i> {{ $property->location }}</span>
                            <h3 class="prt-price-fix">{{ $property->price_html }}</h3>
                            {!! Theme::partial('real-estate.elements.list-fx-features', compact('property')) !!}
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>
<!-- ============================ Property Header Info End ================================== -->

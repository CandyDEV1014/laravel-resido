@php
Theme::asset()
    ->usePath()
    ->add('leaflet-css', 'plugins/leaflet.css');
Theme::asset()
    ->container('footer')
    ->usePath()
    ->add('leaflet-js', 'plugins/leaflet.js');
Theme::asset()
    ->container('footer')
    ->usePath()
    ->add('leaflet.markercluster-src-js', 'plugins/leaflet.markercluster-src.js');
@endphp

<div class="home-map-banner full-wrapious">
    <div class="hm-map-container fw-map">
        <div id="map" data-type="{{ request()->input('type') }}"
            data-url="{{ route('public.ajax.properties.map') }}"
            data-center="{{ json_encode([43.615134, -76.393186]) }}"></div>
    </div>

    <!-- Advance Search -->
    <div class="advance-search-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <button data-bs-toggle="collapse" data-bs-target="#ad-search"
                        class="btn adv-btn">{{ __('Advanced Search') }}
                    </button>

                    <div id="ad-search" class="collapse">
                        <div class="map-search-box">

                            <div class="full-search-2 eclip-search italian-search hero-search-radius shadow-hard">
                                <div class="hero-search-content">
                                    <form action="{{ route('public.properties') }}" method="GET" id="frmhomesearch">
                                        <div class="row">

                                            <div class="col-lg-3 col-md-3 col-sm-12 b-r">
                                                <div class="form-group">
                                                    <div class="choose-property-type">
                                                        {!! Theme::partial('real-estate.filters.type') !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-7 col-md-6 col-sm-12 p-0 elio">
                                                <div class="form-group">
                                                    <div class="input-with-icon">
                                                        {!! Theme::partial('real-estate.filters.input-search') !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-2 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <button class="btn search-btn black"
                                                        type="submit">{{ __('Search') }}
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script id="traffic-popup-map-template" type="text/x-custom-template">
    {!! Theme::partial('real-estate.properties.map-popup', ['property' => get_object_property_map()]) !!}
</script>

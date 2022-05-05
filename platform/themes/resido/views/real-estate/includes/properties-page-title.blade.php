@php
    $layout = theme_option('properties_page_layout');

    $requestLayout = request()->input('layout');
    if ($requestLayout && in_array($requestLayout, array_keys(get_properties_page_layout()))) {
        $layout = $requestLayout;
    }

    $layout = ($layout && in_array($layout, array_keys(get_properties_page_layout()))) ? $layout : 'sidebar';
@endphp


@if($layout == 'full')
    <div class="page-title">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">

                    <div class="full-search-2 eclip-search italian-search hero-search-radius shadow-hard">
                        <div class="hero-search-content">
                            <div class="row">

                                <div class="col-lg-4 col-md-4 col-sm-12 b-r">
                                    <div class="form-group">
                                        <div class="choose-property-type">
                                            {!! Theme::partial('real-estate.filters.type') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-5 col-sm-12 p-0 elio">
                                    <div class="form-group">
                                        <div class="input-with-icon">
                                            {!! Theme::partial('real-estate.filters.input-search') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <a href="#" class="btn search-btn black">{{ __('Search') }}</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@elseif($layout == 'grid_map' || $layout == 'map')
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

    </div>
    <script id="traffic-popup-map-template" type="text/x-custom-template">
        {!! Theme::partial('real-estate.properties.map-popup', ['property' => get_object_property_map()]) !!}
    </script>
@else
    <div class="page-title">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">

                    <h1 class="ipt-title">{{ __('Properties') }}</h1>
                    <span class="ipn-subtitle">{{ theme_option('properties_description') }}</span>

                </div>
            </div>
        </div>
    </div>
@endif

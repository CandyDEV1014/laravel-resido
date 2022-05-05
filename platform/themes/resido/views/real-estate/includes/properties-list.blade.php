@php
    $layout = theme_option('properties_page_layout');
    $requestLayout = request()->input('layout');
    if ($requestLayout && in_array($requestLayout, array_keys(get_properties_page_layout()))) {
        $layout = $requestLayout;
    }

    $layout = ($layout && in_array($layout, array_keys(get_properties_page_layout()))) ? $layout : 'sidebar';
    $viewType = request()->input('view', 'grid');
    $gridClass = 'col-lg-12 col-md-12';
    $gridItemClass = 'col-lg-6 col-md-12';

    if ($layout !== 'full' && $layout !== 'grid_full') {
        $gridClass = 'col-lg-8 col-md-12';
    }

    switch ($layout) {
        case 'grid_sidebar':
        case 'grid_map':
        case 'sidebar':
        case 'map':
            if($viewType == 'list') {
                 $gridItemClass = 'col-lg-12 col-md-12';
            }
            break;

        case 'full':
            $viewType = 'list';
            break;

        case 'grid_full':
            if ($viewType == 'list') {
                $gridItemClass = 'col-lg-6 col-md-12';
            } else {
                $gridItemClass = 'col-lg-4 col-md-6 col-sm-12';
            }
            break;
    }
@endphp

@if ($layout == 'half_map')
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
    <div class="half-map container-fluid max-w-screen-2xl">
        <div class="fs-content">
            <form action="{{ route('public.properties') }}" method="get" id="ajax-filters-form">
                <input type="hidden" name="page" data-value="{{ $properties->currentPage() }}">
                <input type="hidden" name="layout" value="{{ request()->input('layout') }}">
                <div class="row">
                    <div class="fs-inner-container1 col-md-7" id="properties-list">
                        @include(Theme::getThemeNamespace('views.real-estate.includes.filters-halfmap'))
                        <div class="list-layout data-listing position-relative">
                            {!! Theme::partial('real-estate.properties.items', compact('properties')) !!}
                        </div>
                    </div>
                    <div class="fs-left-map-box1 col-md-5">
                        <div class="rightmap h-100">
                            <div id="map" data-type="{{ request()->input('type') }}"
                                 data-url="{{ route('public.ajax.properties.map') }}"
                                 data-center="{{ json_encode([43.615134, -76.393186]) }}"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="clearfix"></div>
@else
    <!-- ============================ All Property ================================== -->
    <section class="gray">
        <div class="container">

            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="filter_search_opt">
                        <a href="javascript:void(0);" class="open_search_menu">{{ __('Search Property') }}<i
                                class="ml-2 ti-menu"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                @if ($layout !== 'full' && $layout !== 'grid_full')
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="simple-sidebar sm-sidebar" id="filter_search" style="left: -310px;">
                            @include(Theme::getThemeNamespace('views.real-estate.includes.search-sidebar'))
                        </div>
                    </div>
                @endif

                <div class="{{ $gridClass }} list-layout">
                    <div class="row justify-content-center">
                        @include(Theme::getThemeNamespace('views.real-estate.includes.sorting-box'))
                    </div>

                    <div class="row">
                        @foreach ($properties as $property)
                            <div class="{{ $gridItemClass }}">
                                @if (strpos($viewType, 'grid') !== false)
                                    {!! Theme::partial('real-estate.properties.item-grid', compact('property')) !!}
                                @else
                                    {!! Theme::partial('real-estate.properties.item-list', compact('property')) !!}
                                @endif
                            </div>
                            <!-- End Single Property -->
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <nav class="d-flex justify-content-center pt-3" aria-label="Page navigation">
                                {!! $properties->withQueryString()->onEachSide(1)->links() !!}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

<script id="traffic-popup-map-template" type="text/x-custom-template">
    {!! Theme::partial('real-estate.properties.map-popup', ['property' => get_object_property_map()]) !!}
</script>

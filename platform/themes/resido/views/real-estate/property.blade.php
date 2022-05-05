@php
    Theme::asset()->usePath()->add('leaflet-css', 'plugins/leaflet.css');
    Theme::asset()->usePath()->add('jquery-bar-rating', 'plugins/jquery-bar-rating/themes/fontawesome-stars.css');
    Theme::asset()->container('footer')->usePath()->add('leaflet-js', 'plugins/leaflet.js');
    Theme::asset()->usePath()->add('magnific-css', 'plugins/magnific-popup.css');
    Theme::asset()->container('footer')->usePath()->add('magnific-js', 'plugins/jquery.magnific-popup.min.js');
    Theme::asset()->container('footer')->usePath()->add('property-js', 'js/property.js');
    Theme::asset()->container('footer')->usePath()->add('jquery-bar-rating-js', 'plugins/jquery-bar-rating/jquery.barrating.min.js');
    Theme::asset()->container('footer')->usePath()->add('wishlist', 'js/wishlist.js', [], []);
    $headerLayout = MetaBox::getMetaData($property, 'header_layout', true);
    $headerLayout = !empty($headerLayout) ? $headerLayout : theme_option('property_header_layout', 'layout-1');
@endphp
@php $img = $property->images && isset($property->images[1]) ? $property->images[1] : ($property->images && isset($property->images[0]) ? $property->images[0] : 'img-loading.jpg') @endphp

<section class="image-cover"
    style="background:url({{ RvMedia::getImageUrl($img, null, false, RvMedia::getDefaultImage()) }}) no-repeat;"
    data-overlay="5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-8">
                <div class="caption-wrap-content text-center">
                    <h2>{{ __('Properties') }}</h2>
                    <p class="mb-5"><a href="{{url('/')}}" class="property-detail-breadcrumb-custom">{{ __('Home') }}</a> - <a href="{{url('/properties')}}" class="property-detail-breadcrumb-custom">{{ __('Properties') }}</a> - {{ $property->name }}</p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ============================ Property Detail Start ================================== -->
<section class="property-detail gray-simple">
    <div data-property-id="{{ $property->id }}"></div>
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-12 col-md-12 col-sm-12">
                {!! Theme::partial('real-estate.properties.headers.' . $headerLayout, compact('property')) !!}
            </div>
        </div>
        <div class="row">
            <!-- property main detail -->
            <div class="col-lg-8 col-md-12 col-sm-12">

                @if('layout-1' === $headerLayout)
                    <div class="property_block_wrap style-2 p-4">
                        <div class="prt-detail-title-desc">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <span class="prt-types {{ $property->type->slug }}">{{ $property->type_name }}</span>
                                    @if($property->is_urgent == true)
                                    <span class="prt-types urgent">{{ __('Urgent') }}</span>
                                    @endif
                                    @if($property->status == 'sold')
                                    <span class="prt-types sold">{{ __('SOLD') }}</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="prt-types">{{ __('Created Date') }} {{date("d/m/Y",strtotime($property->created_at))}}</span>
                                    <span class="prt-types">{{ __('Last Edit Date') }} {{date("d/m/Y",strtotime($property->updated_at))}}</span>
                                </div>
                            </div>
                            <h3>{{ $property->name }}</h3>
                            <span><i class="lni-map-marker"></i> {{ $property->location . ', ' . $property->city_name }}</span>
                            <h3 class="prt-price-fix">{{ $property->price_html }}</h3>
                            <div class="d-flex justify-content-between">
                                <div>
                                    @if($property->is_featured == true)
                                    <span class="prt-types" title="<?php echo e(__('Featured Property')); ?>"><i class="fas fa-check"></i>&nbsp;&nbsp;{{ __('Featured') }}</span>
                                    @endif
                                    @if($property->is_top_property == true)
                                    <span class="prt-types" title="<?php echo e(__('Top Property')); ?>"><i class="fas fa-check"></i>&nbsp;&nbsp;{{ __('Top Property') }}</span>
                                    @endif
                                    <span class="prt-types" title="<?php echo e(__('Total Views')); ?>"><i class="fa fa-eye"></i>&nbsp;&nbsp;{{ number_format($property->views) }}</span>
                                    <span class="prt-types" title="<?php echo e(__('Add Review')); ?>"><i class="fa fa-comment-dots"></i>&nbsp;&nbsp;<a href="javascript:;" class="jayenScroll">{{ __('Add Review') }}</a></span>
                                </div>
                                <div>
                                    {!! Theme::partial('real-estate.elements.list-fx-features', compact('property')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            <!-- Single Block Wrap - Features -->
                {!! Theme::partial('real-estate.elements.features', ['property' => $property]) !!}

            <!-- Single Block Wrap - Amenities -->
            {!! Theme::partial('real-estate.elements.amenities', ['property' => $property]) !!}

            <!-- Single Block Wrap - Nearby -->
            {!! Theme::partial('real-estate.elements.nearby', ['property' => $property]) !!}

            <!-- Single Block Wrap -->
                <div class="property_block_wrap style-2">

                    <div class="property_block_wrap_header">
                        <a data-bs-toggle="collapse" data-parent="#dsrp" data-bs-target="#clTwo" aria-controls="clTwo"
                           href="javascript:void(0);" aria-expanded="true"><h4
                                class="property_block_title">{{ __('Description') }}</h4></a>
                    </div>
                    <div id="clTwo" class="panel-collapse collapse show">
                        <div class="block-body">
                            {!! clean($property->content) !!}
                        </div>
                    </div>
                </div>

                <!-- Single Block Wrap - Video -->
                {!! Theme::partial('real-estate.elements.video', ['object' => $property]) !!}

                <!-- Single Block Wrap -->
                <div class="property_block_wrap style-2">

                    <div class="property_block_wrap_header">
                        <a data-bs-toggle="collapse" data-parent="#loca" data-bs-target="#clSix" aria-controls="clSix"
                           href="javascript:void(0);" aria-expanded="true" class="collapsed"><h4
                                class="property_block_title">{{ __('Location') }}</h4></a>
                    </div>

                    <div id="clSix" class="panel-collapse collapse show">
                        <div class="block-body">
                            @if ($property->latitude && $property->longitude)
                                {!! Theme::partial('real-estate.elements.traffic-map-modal', ['location' => $property->location . ', ' . $property->city_name]) !!}
                            @else
                                {!! Theme::partial('real-estate.elements.gmap-canvas', ['location' => $property->location]) !!}
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Single Block Wrap - Gallery -->
                {!! Theme::partial('real-estate.elements.gallery', compact('property')) !!}

                @if(is_review_enabled())
                <!-- Single Review -->
                    <div id="reviewWrapper">
                        {!! Theme::partial('real-estate.elements.review', compact('property')) !!}
                    </div>
                @endif
            </div>

            <!-- property Sidebar -->
            <div class="col-lg-4 col-md-12 col-sm-12">

                <!-- Like And Share -->
                <div class="like_share_wrap b-0">
                    <ul class="like_share_list justify-content-center">
                        <li class="social_share_list">
                            <a href="JavaScript:void(0);" class="btn btn-likes" data-toggle="tooltip"
                               data-original-title="Share" title="<?php echo e(__('Share')); ?>"><i class="fas fa-share" title="<?php echo e(__('Share')); ?>"></i>{{ __('Share') }}</a>
                            <div class="social_share_panel">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}&title={{ $property->description }}"
                                   target="_blank" class="cl-facebook"><i class="lni-facebook"></i></a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ $property->description }}"
                                   target="_blank" class="cl-twitter"><i class="lni-twitter"></i></a>
                                <a href="https://linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&summary={{ rawurldecode($property->description) }}&source=Linkedin"
                                   target="_blank" class="cl-linkedin"><i class="lni-linkedin"></i></a>
                            </div>
                        </li>
                        <li>
                            @if(auth('account')->check())
                                <a 
                                    href="JavaScript:Void(0);" 
                                    data-id="{{ $property->id }}" 
                                    class="btn btn-likes add-to-wishlist" 
                                    data-toggle="tooltip" 
                                    data-original-title="Save" title="<?php echo e(__('Add to Wishlist')); ?>"><i class="far fa-heart" title="<?php echo e(__('Add to Wishlist')); ?>"></i>{{ __('Save') }}
                                </a>
                            @else
                                <a href="{{url('/login')}}" class="btn btn-likes" title="<?php echo e(__('Add to Wishlist')); ?>">
                                    <i class="far fa-heart"></i>{{ __('Save') }}
                                </a>
                            @endif
                        </li>
                    </ul>
                </div>

                <div class="details-sidebar">
                @if ($author = $property->author)
                    <!-- Agent Detail -->
                        <div class="sides-widget">
                            <div class="sides-widget-header">
                                @if ($author->username)
                                    <div class="agent-photo">
                                        <img src="{{ RvMedia::getImageUrl($author->avatar->url, 'thumb') }}"
                                             alt="{{ $author->name }}">
                                    </div>
                                    <div class="sides-widget-details">
                                        <h4>
                                            <a href="{{ route('public.agent', $author->username) }}"> {{ $author->name }}</a>
                                        </h4>
                                        <span><i class="lni-phone-handset"></i>{{ $author->phone }}</span>
                                    </div>
                                    <div class="clearfix"></div>
                                @endif
                            </div>

                            <div class="sides-widget-body simple-form">
                                {!! Theme::partial('real-estate.elements.form-contact-consult', ['data' => $property]) !!}
                            </div>
                        </div>
                    @endif
                    {!! dynamic_sidebar('property_sidebar') !!}
                </div>

                <div class="single-widgets widget_thumb_post mt-4">
                    <h4 class="title text-center" style="margin-top: -19px;">{{ __('Related Properties') }}</h4>
                    <!-- <ul> -->
                        @if ($relatedProperty->count())
                        @foreach ($relatedProperty as $reproperty)
                            <div class="mb-3">
                                <!-- <div class="sides_list_property_thumb">
                                    <img src="{{ RvMedia::getImageUrl($reproperty->image, 'thumb', false, RvMedia::getDefaultImage()) }}" class="img-fluid" alt="{{ $reproperty->name }}">
                                </div> -->
                                <div class="sides_list_property_detail custom_sides_list_property_detail">
                                    <div class="mb-2">
                                        <a href="{{ $reproperty->url }}">
                                            <img src="{{ RvMedia::getImageUrl($reproperty->image, 'property_medium', false, RvMedia::getDefaultImage()) }}" class="img-fluid" alt="{{ $reproperty->name }}">
                                        </a>
                                    </div>

                                    <h4><a href="{{ $reproperty->url }}">{{ $reproperty->name }}</a></h4>
                                    <span><i class="ti-location-pin"></i>{{ $reproperty->location }}</span>
                                    <div class="lists_property_price">
                                        <div class="lists_property_types">
                                            <div class="property_types_vlix {{ $reproperty->type->slug }}">{{ $reproperty->type_name }}</div>
                                        </div>
                                        <div class="lists_property_price_value">
                                            <h4 class="prt-price-fix">{{ $property->price_html }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @else
                            <div class="lists_property_price_value">
                                <p>{{ __('No Related Properties.') }}</p>
                            </div>
                        @endif
                    <!-- </ul> -->
                </div>

            </div>
        </div>

        <div class="row">[recently-viewed-properties title="{{ __('Recently Viewed Properties') }}"
            subtitle="{{ __('Your currently viewed properties.') }}"][/recently-viewed-properties]
        </div>
    </div>
</section>

@if ($property->latitude && $property->longitude)
    <div
        data-magnific-popup="#trafficMap"
        data-map-id="trafficMap"
        data-popup-id="#traffic-popup-map-template"
        data-map-icon="{{ $property->map_icon }}"
        data-center="{{ json_encode([$property->latitude, $property->longitude]) }}">
    </div>
@endif

<script id="traffic-popup-map-template" type="text/x-custom-template">
    {!! Theme::partial('real-estate.properties.map', ['property' => $property]) !!}
</script>
<script type="application/javascript"> 
    $(document).ready(function() {
        $(".jayenScroll").click(function() {
            $('html, body').animate({
                scrollTop: $("#reviewWrapper").offset().top
            }, 500);
        });
    });
</script>
<!-- ============================ Property Detail End ================================== -->

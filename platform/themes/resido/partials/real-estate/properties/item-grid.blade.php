@php
    $img_slider = isset($img_slider) ? $img_slider : true;
    $is_lazyload = isset($lazyload) ? $lazyload : true;
@endphp

<div class="property-listing property-2 {{ $class_extend ?? '' }}"
     data-lat="{{ $property->latitude }}"
     data-long="{{ $property->longitude }}">
    <div class="listing-img-wrapper">
        <div class="list-img-slide">
            <div class="click @if(!$img_slider) not-slider @endif">
                @foreach ($property['images'] as $image)
                    <div>
                        <a href="{{ $property->url }}">
                            @if($is_lazyload)
                            <img src="{{ get_image_loading() }}"
                                data-src="{{ RvMedia::getImageUrl($image, 'medium', false, RvMedia::getDefaultImage()) }}"
                                class="img-fluid mx-auto lazy" alt="{{ $property->name }}"/>
                            @else
                            <img src="{{ RvMedia::getImageUrl($image, 'medium', false, RvMedia::getDefaultImage()) }}"
                                class="img-fluid mx-auto" alt="{{ $property->name }}"/>
                            @endif
                        </a>
                    </div>
                    @if(!$img_slider) @break @endif
                @endforeach
            </div>
        </div>
		<div class="icon-actions-wrapper">
                @if(auth('account')->check())
                <a href="JavaScript:Void(0);" data-id="{{ $property->id }}" class="add-to-wishlist" title="<?php echo e(__('Add to Wishlist')); ?>">
                <i class="far fa-heart"></i></a>
                @else
                <a href="{{url('/login')}}" class="btnX" title="<?php echo e(__('Add to Wishlist')); ?>">
                <i class="far fa-heart"></i>
                </a>
                @endif
        </div>
		<div>
            @if($property->is_urgent == true)
            <span class="prt-types urgent">{{ __('Urgent') }}</span>
            @endif
            @if($property->status == 'sold')
            <span class="prt-types sold">{{ __('SOLD') }}</span>
            @endif
        </div>
		<ul class="item-price-wrap hide-on-list">
		<li class="h-type"><span>{{ $property->category_name }}</span></li>
		<li class="item-price">{{ $property->price_html }}</li>
		</ul>
    </div>

    <div class="listing-detail-wrapper">
        <div class="listing-short-detail-wrap">
            <div class="listing-short-detail">
                <div class="list-price d-flex flex-column justify-content-between">
                    <div class="list-badge d-flex justify-content-start">
                        <span class="prt-types {{ $property->type->slug }}">{{ $property->type_name }}</span>
                        
                        @if($property->is_top_property == 1)
                        <span class="prt-types {{ $property->type->slug }}" style="background: #867de7;margin-left: 5px;">{{ __('Top Property') }}</span>
                        @endif

                        @if($property->is_featured == 1)
                        <span class="prt-types {{ $property->type->slug }}" style="background: #f74400b3;margin-left: 5px;">{{ __('Featured') }}</span>
                        @endif
                    </div>
                </div>
                <h4 class="listing-name">
                    <a href="{{ $property->url }}" class="prt-link-detail"
                       title="{{ $property->name }}">{!! clean($property->name) !!}</a>
                </h4>
                @if (is_review_enabled() && $property->reviews_count > 0)
                    {!! Theme::partial('real-estate.elements.property-review', compact('property')) !!}
                @endif
            </div>
        </div>
    </div>

    <div class="price-features-wrapper">
        <div class="list-fx-features">
            <div class="listing-card-info-icon">
                <div class="inc-fleat-icon">
                    <img src="{{ Theme::asset()->url('img/bed.svg') }}" width="13" alt="" title="<?php echo e(__('Bedroom')); ?>" />
                </div>
                {!! clean($property->number_bedroom) !!} {!! __('Beds') !!}
            </div>
            <div class="listing-card-info-icon">
                <div class="inc-fleat-icon">
                    <img src="{{ Theme::asset()->url('img/bathtub.svg') }}" width="13" alt="" title="<?php echo e(__('Bathroom')); ?>" />
                </div>
                {!! clean($property->number_bathroom) !!} {!! __('Bath') !!}
            </div>
            <div class="listing-card-info-icon">
                <div class="inc-fleat-icon">
                    <img src="{{ Theme::asset()->url('img/move.svg') }}" width="13" alt="" title="<?php echo e(__('Bathroom')); ?>" />
                </div>
                {{ $property->square_text }}
            </div>
        </div>
    </div>

    <div class="listing-detail-footer">
        <div class="footer-first">
            <div class="foot-location d-flex">
                <img src="{{ Theme::asset()->url('img/pin.svg') }}" width="18"
                     alt="{!! clean($property->city_name ) !!}"/>
                {!! clean($property->city_name ) !!}
            </div>
        </div>
        <div class="footer-flex">
            <a href="{{ $property->url }}" class="prt-view">{{ __('View') }}</a>
        </div>
    </div>
</div>

@php
    $img_slider = isset($img_slider) ? $img_slider : true;
    $is_lazyload = isset($lazyload) ? $lazyload : true;
    $details = $property->details()
        ->where('status', Botble\Base\Enums\BaseStatusEnum::PUBLISHED)
        ->where('is_featured', 1)
        ->orderBy('order', 'DESC')
        ->get();
@endphp

<div class="property-listing property-1 {{ $class_extend ?? '' }}"
     data-lat="{{ $property->latitude }}"
     data-long="{{ $property->longitude }}">
    <div class="listing-img-wrapper">
	    <div class="list-img-slide">
            <div class="click @if(!$img_slider) not-slider @endif">
                @foreach ($property['images'] as $image)
                    <div>
                        <a href="{{ $property->status == 'sold' ? 'javascript:void(0);' : $property->url }}">
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

    <div class="listing-content">

        <div class="listing-detail-wrapper-box">
            <div class="listing-detail-wrapper">
                <div class="listing-short-detail">
                    <h4 class="listing-name"><a href="{{ $property->status == 'sold' ? 'javascript:void(0);' : $property->url }}" title="{{ $property->name }}">{!! clean($property->name) !!}</a></h4>
                    
                </div>
                <div class="list-price">
                    <div>
                        @if (is_review_enabled() && $property->reviews_count > 0)
                        {!! Theme::partial('real-estate.elements.property-review', compact('property')) !!}
                        @endif
						
                        <span class="prt-types {{ $property->type->slug }}">{{ $property->type_name }}</span>
						@if($property->is_top_property == 1)
                        <span class="prt-types {{ $property->type->slug }}" style="background: #867de7;">{{ __('Top Property') }}</span>
                        @endif

                        @if($property->is_featured == 1)
                        <span class="prt-types {{ $property->type->slug }}" style="background: #f74400b3;">{{ __('Featured') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="price-features-wrapper">
            <div class="list-fx-features">
                @foreach($details as $detail)
                <div class="listing-card-info-icon">
                    <div class="inc-fleat-icon">
                        <i class="{{ $detail->icon }}"></i>
                    </div>
                    {!! clean($detail->pivot->value) !!} 
                    {{ $detail->type == Botble\RealEstate\Enums\DetailTypeEnum::SQUARE ? setting('real_estate_square_unit', 'm²') : $detail->alt }}
                </div>
                @endforeach
            </div>
        </div>

        <div class="listing-footer-wrapper">
            <div class="listing-locate" title="{!! clean($property->city_name) !!}">
                <span class="listing-location"><i class="ti-location-pin"></i>{!! clean($property->city_name) !!}</span>
            </div>
            <div class="listing-detail-btn">
                <a href="{{ $property->status == 'sold' ? 'javascript:void(0);' : $property->url }}" class="more-btn">{{ __('View') }}</a>
            </div>
        </div>

    </div>
</div>

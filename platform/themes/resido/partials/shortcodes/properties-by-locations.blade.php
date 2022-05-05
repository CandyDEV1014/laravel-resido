@php
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Location\Repositories\Interfaces\CityInterface;

$cities = collect([]);
if (is_plugin_active('location')) {
    $cities = app(CityInterface::class)->getFeaturedCities([
        'condition' => [
            'cities.status' => BaseStatusEnum::PUBLISHED,
        ],
        'take' => (int) theme_option('number_of_featured_cities', 3),
        'withCount' => ['properties'],
        'select' => ['cities.id', 'cities.name', 'cities.slug'],
        'with' => ['metadata'],
    ]);
}
@endphp
<section>
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10 text-center">
                <div class="sec-heading center">
                    <h2>{!! clean($title) !!}</h2>
                    <p>{!! clean($description) !!}</p>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach ($cities as $city)
                <div class="col-lg-4 col-md-4">
                    <div class="location-property-wrap">
                        <div class="location-property-thumb">
                            <a href="{{ route('public.properties-by-city', ['slug' => $city['slug']]) }}">
                                <img src="{{ get_image_loading() }}"
                                    data-src="{{ RvMedia::getImageUrl($city->getMetaData('image', true), 'medium', false, RvMedia::getDefaultImage()) }}"
                                    class="w-100 lazy" alt="{{ $city->name }}" />
                            </a>
                        </div>
                        <div class="location-property-content">
                            <div class="lp-content-flex">
                                <h4 class="lp-content-title">{{ $city->name }}</h4>
                                <span>{{ $city->properties_count }} {{ __('Properties') }}</span>
                            </div>
                            <div class="lp-content-right">
                                <a href="{{ $city->url }}" class="lp-property-view"><i
                                        class="ti-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                <a href="{{ route('public.properties') }}"
                    class="btn btn-theme-light-2 rounded">{{ __('Browse More Locations') }}</a>
            </div>
        </div>

    </div>
</section>

<!-- ============================ Latest Property For Sale Start ================================== -->
<section class="pt-7 property-bg-custom">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10 text-center">
                <div class="sec-heading center mb-4">
                    <h2>{!! clean($title) !!}</h2>
                    <p>{!! clean($description) !!}</p>
                </div>
            </div>
        </div>

        <div class="row" style="display: none;">
            <div class="col-lg-12 col-md-12">
                <div class="property-slide">
                    @foreach ($properties as $property)
                        <!-- Single Property -->
                        <div class="single-items">
                            {!! Theme::partial('real-estate.properties.item-grid', ['property' => $property, 'class_extend' => 'shadow-none border']) !!}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row list-layout">
            @foreach($properties as $property)
            <!-- Single Property -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                {!! Theme::partial('real-estate.properties.item-grid', ['property' => $property]) !!}
            </div>
            <!-- End Single Property -->
            @endforeach
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 text-center mt-3">
                <a href="{{ route('public.properties','type=sale') }}" class="btn btn-theme-light-2 rounded">{{ __('Browse More Properties') }}</a>
            </div>
        </div>
    </div>
</section>
<!-- ============================ Latest Property For Sale End ================================== -->

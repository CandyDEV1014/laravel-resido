<div class="image-cover hero-banner"
    style="background:#eff6ff url({{ RvMedia::getImageUrl($bg, null, false, RvMedia::getDefaultImage()) }}) no-repeat;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-md-11 col-sm-12">
                <div class="inner-banner-text text-center">
                    <p class="lead-i">{!! clean($description) !!}</p>
                    <h2>{!! clean($title) !!}</h2>
                </div>
                <div class="full-search-2 eclip-search italian-search hero-search-radius shadow-hard mt-5">
                    <form action="{{ route('public.properties') }}" method="GET" id="frmhomesearch">
                        <div class="hero-search-content">
                            <div class="row">

                                <div class="col-lg-4 col-md-4 col-sm-12 b-r">
                                    <div class="form-group">
                                        <div class="choose-property-type">
                                            {!! Theme::partial('real-estate.filters.type') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-5 col-md-4 col-sm-10 p-0 elio">
                                    <div class="form-group">
                                        <div class="input-with-icon">
                                            {!! Theme::partial('real-estate.filters.input-search') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-1 col-md-1 col-sm-2">
                                    <div class="form-group">
                                        <a class="collapsed ad-search" data-bs-toggle="collapse" data-parent="#search"
                                            data-bs-target="#advance-search" href="javascript:void(0);"
                                            aria-expanded="false" aria-controls="advance-search"><i
                                                class="fa fa-sliders-h"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <button class="btn search-btn" type="submit">{{ __('Search') }}</button>
                                    </div>
                                </div>

                            </div>
                            <!-- Collapse Advance Search Form -->
                            <div class="collapse" id="advance-search" aria-expanded="false" role="banner">

                                <!-- row -->
                                <div class="row">
								
								    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group mb-2">
                                            <div class="input-with-icon">
                                                {!! Theme::partial('real-estate.filters.location_country') !!}
                                                <i class="ti-location-pin"></i>
                                            </div>
                                        </div>
                                    </div>
									
									<div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group mb-2">
                                            <div class="input-with-icon">
                                                {!! Theme::partial('real-estate.filters.location_state') !!}
                                                <i class="ti-location-pin"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group mb-2">
                                            <div class="input-with-icon">
                                                {!! Theme::partial('real-estate.filters.location_city') !!}
                                                <i class="ti-location-pin"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group mb-2">
                                            <div class="input-with-icon">
                                                {!! Theme::partial('real-estate.filters.bedrooms') !!}
                                                <i class="fas fa-bed"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group mb-2">
                                            <div class="input-with-icon">
                                                {!! Theme::partial('real-estate.filters.bathrooms') !!}
                                                <i class="fas fa-bath"></i>
                                            </div>
                                        </div>
                                    </div>
									
									<div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group simple mb-2">
                                            {!! Theme::partial('real-estate.filters.categories') !!}
                                        </div>
                                    </div>

                                </div>
                                <!-- /row -->

                                <!-- row -->
                                <div class="row">
								    
									<div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group simple mb-2">
                                           {!! Theme::partial('real-estate.filters.sub_categories') !!}
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group simple mb-2">
                                            {!! Theme::partial('real-estate.filters.min-price') !!}
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group simple mb-2">
                                            {!! Theme::partial('real-estate.filters.max-price') !!}
                                        </div>
                                    </div>

									<div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group simple mb-2">
                                            {!! Theme::partial('real-estate.filters.agents') !!}
                                        </div>
                                    </div>

                                    <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group mb-2">
                                            {!! Theme::partial('real-estate.filters.min-area') !!}
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group mb-2">
                                            {!! Theme::partial('real-estate.filters.max-area') !!}
                                        </div>
                                    </div>-->

                                </div>
                                <!-- /row -->


                                <!-- row -->
                                <div class="row">

                                    <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                                        <h4 class="text-dark">{{ __('Amenities & Features') }}</h4>
                                        {!! Theme::partial('real-estate.filters.features', ['class' => 'second-row']) !!}
                                    </div>

                                </div>
                                <!-- /row -->

                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- ============================ Hero Banner End ================================== -->

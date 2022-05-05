<div class="image-cover hero-banner"
     style="background:url({{ RvMedia::getImageUrl($bg, null, false, RvMedia::getDefaultImage()) }}) no-repeat;">
    <div class="container">
        <div class="hero-search-wrap">
            <div class="hero-search">
                <h1>{!! clean($title) !!}</h1>
            </div>
            <form action="{{ route('public.properties') }}" method="GET" id="frmhomesearch">
                <div class="hero-search-content side-form">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Min Price') }}</label>
                                {!! Theme::partial('real-estate.filters.min-price') !!}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Max Price') }}</label>
                                {!! Theme::partial('real-estate.filters.max-price') !!}
                            </div>
                        </div>
                    </div>

                    @if(function_exists('is_search_country_sidebar'))
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>{{ __('Category') }}</label>
                                    {!! Theme::partial('real-estate.filters.categories') !!}
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>{{ __('Subcategory') }}</label>
                                    {!! Theme::partial('real-estate.filters.sub_categories') !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>{{ __('Country') }}</label>
                                    {!! Theme::partial('real-estate.filters.location_country') !!}
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>{{ __('State') }}</label>
                                    {!! Theme::partial('real-estate.filters.location_state') !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>{{ __('City') }}</label>
                                    {!! Theme::partial('real-estate.filters.location_city') !!}
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <div class="hero-search-action">
                    <button class="btn search-btn" type="submit">{{ __('Search Result') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

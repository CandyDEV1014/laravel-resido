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
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <div class="input-with-icon">
                                    <input type="text" class="form-control"
                                        placeholder="{{ __('Search for a location') }}">
                                    <img src="{{ Theme::asset()->url('img/pin.svg') }}" width="18" alt="" />
                                </div>
                            </div>
                        </div>
                    </div>

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

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Property Type') }}</label>
                                {!! Theme::partial('real-estate.filters.categories') !!}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>{{ __('Bed Rooms') }}</label>
                                {!! Theme::partial('real-estate.filters.bedrooms') !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Property Location') }}</label>
                                {!! Theme::partial('real-estate.filters.cities') !!}
                            </div>
                        </div>
                    </div>

                </div>
                <div class="hero-search-action">
                    <button class="btn search-btn" type="submit">{{ __('Search Result') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

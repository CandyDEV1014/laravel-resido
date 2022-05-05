<div class="image-bottom hero-banner"
    style="background:#2540a2 url({{ RvMedia::getImageUrl($bg, null, false, RvMedia::getDefaultImage()) }}) no-repeat;"
    data-overlay="{{ $overlay }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-md-11 col-sm-12">
                <div class="inner-banner-text text-center">
                    <p class="lead-i">{!! clean($title) !!}</p>
                    <h2>{!! clean($description) !!}</h2>
                </div>
                <div class="full-search-2 eclip-search italian-search hero-search-radius shadow-hard mt-5">
                    <div class="hero-search-content">
                        <form action="{{ route('public.properties') }}" method="GET" id="frmhomesearch">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-12 b-r">
                                    <div class="form-group">
                                        <div class="choose-property-type">
                                            {!! Theme::partial('real-estate.filters.type') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-5 col-sm-12 p-0 elio">
                                    <div class="form-group">
                                        {!! Theme::partial('real-estate.filters.input-search') !!}
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <button class="btn search-btn" type="submit">{{ __('Search') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

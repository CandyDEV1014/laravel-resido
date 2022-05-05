<!-- ============================ Hero Banner  Start================================== -->
<div class="image-bottom hero-banner"
    style="background:#2540a2 url({{ RvMedia::getImageUrl($bg, null, false, RvMedia::getDefaultImage()) }}) no-repeat;"
    data-overlay="{{ $overlay }}">
    <div class="container">
        <div class="col-lg-8 col-md-11 col-sm-12">
            <p class="lead-i text-light">{!! clean($description) !!}</p>
            <h2>{!! clean($title) !!}</h2>
            <div class="full-search-2 eclip-search italian-search hero-search-radius shadow mt-5">
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

                            <div class="col-lg-6 col-md-6 col-sm-12 p-0">
                                <div class="form-group">
                                    <div class="input-with-icon">
                                        {!! Theme::partial('real-estate.filters.input-search') !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12">
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
<!-- ============================ Hero Banner End ================================== -->

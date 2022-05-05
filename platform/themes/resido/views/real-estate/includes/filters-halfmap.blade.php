<div class="row">

    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="form-group">
            <div class="input-with-icon">
                {!! Theme::partial('real-estate.filters.input-search') !!}
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="form-group">
            <div class="input-with-icon">
                {!! Theme::partial('real-estate.filters.cities') !!}
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="form-group">
            <div class="input-with-icon">
                {!! Theme::partial('real-estate.filters.select-types') !!}
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="range-slider mt-0">
            <div class="input-with-icon">
                {!! Theme::partial('real-estate.filters.min-price') !!}
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group" id="module">
            <a role="button" class="collapsed" data-bs-toggle="collapse" href="#advance-search" aria-expanded="false"
                aria-controls="advance-search"></a>
        </div>
    </div>

    <div class="collapse" id="advance-search" aria-expanded="false" role="banner">

        <div class="col-lg-12 col-md-12 col-sm-12">
            <h4>{{ __('Amenities & Features') }}</h4>
            {!! Theme::partial('real-estate.filters.features') !!}
        </div>

    </div>

</div>

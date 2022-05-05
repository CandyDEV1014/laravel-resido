<div class="image-cover hero-banner"
    style="background:#2540a2 url({{ RvMedia::getImageUrl($bg, null, false, RvMedia::getDefaultImage()) }}) no-repeat;"
    data-overlay="{{ $overlay }}">
    <div class="container">
        <div class="simple-search-wrap">
            <div class="hero-search-2">
                <p class="lead-i text-light">{!! clean($title) !!}</p>
                <h2 class="text-light mb-4">{!! clean($description) !!}</h2>
                <form action="{{ route('public.properties') }}" method="GET" id="frmhomesearch">
                    <div class="pk-input-group">
                        <input type="text" class="form-control" name="k"
                            placeholder="{{ __('Search for a location') }}">
                        <button class="btn btn-black" type="submit">{{ __('Go & Search') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

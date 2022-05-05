<section class="image-cover"
    style="background:url({{ RvMedia::getImageUrl($bg, null, false, RvMedia::getDefaultImage()) }}) no-repeat;"
    data-overlay="5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-8">
                <div class="caption-wrap-content text-center">
                    <h2>{!! clean($title) !!}</h2>
                    <p class="mb-5">{!! clean($description) !!}</p>
                    <a href="{!! clean($btnLink) !!}" class="btn btn-light btn-md rounded">{!! clean($btnText) !!}</a>
                </div>
            </div>
        </div>
    </div>
</section>

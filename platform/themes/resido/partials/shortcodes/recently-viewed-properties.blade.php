<section class="bg-light">
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
            <property-component type="recently-viewed-properties" url="{{ route('public.ajax.properties') }}"></property-component>
        </div>
    </div>
</section>

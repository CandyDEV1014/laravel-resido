<div class="page-title">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h1 class="ipt-title">{{ SeoHelper::getTitle() }}</h2>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <section class="main-homes wishlist-page">
        <div class="row">
            @forelse ($properties as $property)
                <div class="col-lg-4 col-md-4 col-sm-12">
                    {!! Theme::partial('real-estate.properties.item-list', compact('property')) !!}
                </div>
            @empty
                <div class="alert alert-warning w-100" role="alert">
                    {{ __('0 results') }}
                </div>
            @endforelse
        </div>

    </section>
    @if ($properties->count())
        <div class="col-sm-12">
            <nav class="d-flex justify-content-center pt-3" aria-label="Page navigation example">
                {!! $properties->withQueryString()->links() !!}
            </nav>
        </div>
    @endif
</div>

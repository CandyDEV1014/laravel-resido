<div id="loading">
    <div class="half-circle-spinner">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
    </div>
</div>

@if ($properties->count())
    <div class="row">
        @foreach ($properties as $property)
            <!-- Single Property -->
            <div class="col-lg-12 col-md-12">
                {!! Theme::partial('real-estate.properties.item-list', compact('property')) !!}
            </div>
            <!-- End Single Property -->
        @endforeach
    </div>
    <br>
@else
    <div class="alert alert-warning" role="alert">
        {{ __('0 results') }}
    </div>
@endif

<!-- Pagination -->
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <nav class="d-flex justify-content-center pt-3" aria-label="Page navigation">
            {!! $properties->withQueryString()->onEachSide(1)->links() !!}
        </nav>
    </div>
</div>

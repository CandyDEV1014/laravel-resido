<!-- ============================ Page Title Start================================== -->
<div class="page-title">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h1 class="ipt-title">{{ __('All Agents') }}</h1>
                <span class="ipn-subtitle">{{ __('Lists of our all expert agents') }}</span>

            </div>
        </div>
    </div>
</div>
<!-- ============================ Page Title End ================================== -->

<!-- ============================ Agent List Start ================================== -->
<section class="gray-simple">
    <div class="container">
        @if ($accounts->count())
            <div class="row">
                @foreach($accounts as $account)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        {!! Theme::partial('real-estate.agents.item', compact('account')) !!}
                    </div>
                @endforeach
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                    <nav class="d-flex justify-content-center pt-3">
                        {!! $accounts->withQueryString()->links() !!}
                    </nav>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                    <p class="item">{{ __('0 results') }}</p>
                </div>
            </div>
        @endif
    </div>
</section>

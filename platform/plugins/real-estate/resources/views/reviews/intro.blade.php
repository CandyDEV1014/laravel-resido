@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
    <div class="flexbox-grid">
        <div class="flexbox-content">
            <div class="body">
                <div class="box-wrap-emptyTmpl text-center col-12">
                    <h1 class="mt20 mb20 ws-nm font-size-emptyDisplayTmpl">{{ trans('plugins/real-estate::review.intro.title') }}</h1>
                    <p class="text-info-displayTmpl">{{ trans('plugins/real-estate::review.intro.description') }}</p>
                    <div class="empty-displayTmpl-pdtop">
                        <div class="empty-displayTmpl-image">
                            <img src="{{ asset('vendor/core/plugins/real-estate/images/empty-customer.png') }}" alt="image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@extends(Theme::getThemeNamespace() . '::views.real-estate.account.master')
@section('content')
    <div class="settings" id="app-real-estate">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="main-dashboard-form">
                        <div class="mb-5">
                            <!-- Title -->
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="with-actions">{{ trans('plugins/real-estate::dashboard.packages_title') }}
                                    </h4>
                                </div>
                            </div>

                            <!-- Content -->
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <packages-component url="{{ route('public.account.ajax.packages') }}"
                                subscribe_url="{{ route('public.account.ajax.package.subscribe') }}"></packages-component>
                        </div>
                    </div>

                    <div class="main-dashboard-form">
                        <div class="mb-5">
                            <!-- Title -->
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="with-actions">
                                        {{ trans('plugins/real-estate::dashboard.transactions_title') }}</h4>
                                </div>
                            </div>

                            <!-- Content -->
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <payment-history-component url="{{ route('public.account.ajax.transactions') }}"></payment-history-component>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('/vendor/core/plugins/real-estate/js/app.js')}}"></script>
@endpush

<script>
    "use strict";
    window.trans = {
      "package_detail": "{{ trans('package.package_detail') }}",
      "name": "{{ trans('package.name') }}",
      "price": "{{ trans('package.price') }}",
      "promotion_text": "{{ trans('package.promotion_text') }}",
      "your_credits": "{{ trans('package.your_credits') }}",
      "period": "{{ trans('package.period') }}",
      "days": "{{ trans('package.days') }}",
      "unlimited": "{{ trans('package.unlimited') }}",
      "available": "{{ trans('package.available') }}",
      "not_available": "{{ trans('package.not_available') }}",
      "credits": "{{ trans('package.credits') }}",
      "property_submission": "{{ trans('package.property_submission') }}",
      "aminity": "{{ trans('package.aminity') }}",
      "nearest_place": "{{ trans('package.nearest_place') }}",
      "photo": "{{ trans('package.photo') }}",
      "featured_property": "{{ trans('package.featured_property') }}",
      "number_of_featured": "{{ trans('package.number_of_featured') }}",
      "top_property": "{{ trans('package.top_property') }}",
      "number_of_top": "{{ trans('package.number_of_top') }}",
      "urgent_property": "{{ trans('package.urgent_property') }}",
      "number_of_urgent": "{{ trans('package.number_of_urgent') }}",
      "auto_renew": "{{ trans('package.auto_renew') }}",
      "agent": "{{ trans('package.agent') }}",
      "features": "{{ trans('package.features') }}",
      "activate": "{{ trans('package.activate') }}",
      "promo_Days": "{{ trans('package.promo_Days')}}",
      "promo_Hours": "{{ trans('package.promo_Hours')}}",
      "promo_Minutes": "{{ trans('package.promo_Minutes')}}",
      "promo_Seconds": "{{ trans('package.promo_Seconds')}}"
    }
    window.themeUrl = '{{ Theme::asset()->url('') }}';
    window.siteUrl = '{{ url('') }}';
    window.currentLanguage = '{{ App::getLocale() }}';
  </script>

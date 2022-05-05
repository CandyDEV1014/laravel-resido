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

                            <payment-history-component></payment-history-component>
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

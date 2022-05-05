@extends(Theme::getThemeNamespace() . '::views.real-estate.account.master')
@section('content')
    <div class="settings">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    {!! do_shortcode('[payment-form currency="' . strtoupper($package->currency->title) . '" amount="' . $package->price . '" name="' . $package->name . '" return_url="' . route('public.account.packages') . '" callback_url="' . route('public.account.package.subscribe.callback', $package->id) . '"][/payment-form]') !!}
                </div>
                <div class="col-md-7">
                    {!! do_shortcode('[payment-detail package_id="' . $package->id . '"][/payment-detail]') !!}
                </div>
            </div>
        </div>
    </div>
@stop

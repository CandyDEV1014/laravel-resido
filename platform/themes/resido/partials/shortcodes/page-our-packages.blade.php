<section id="pricing-section" style="padding: 0;">
    <div class="container">
        <div class="row">

            @foreach ($packages as $package)
                <div class="col-lg-4 col-md-4">
                    <div class="single-pricing">
                        <span class="pricing-title">{{ $package->name }}</span>
                        <h4 class="pricing-value @if ($package->is_promotion && strtotime($package->promotion_time) > strtotime('now')) line-through @endif">{{ format_price($package->price, $package->currency, false, true) }}</h4>
                        @if ($package->is_promotion && strtotime($package->promotion_time) > strtotime('now'))
                        <h4 class="pricing-promotion-value">{{ format_price($package->promotion_price, $package->currency, false, true) }}</h4>
                        <countdown-component until="{{ $package->promotion_time}}" text="{{ trans('package.promotion_text') }}"></countdown-component>
                        @endif
                        <p class="pricing-duration">{{ trans('package.period') }} <span>{{ $package->number_of_days == -1 ? trans('package.unlimited') : $package->number_of_days }} {{ trans('package.days') }}</span></p>
                        <ul>
                            <li>{{ trans('package.credits') }} {{ $package->credits == -1 ? trans('package.unlimited') : $package->credits }}</li>
                            <li class="">{{ $package->number_of_properties == -1 ? trans('package.unlimited') : $package->number_of_properties }} {{ trans('package.property_submission') }}</li>
                            <li class="">{{ $package->number_of_aminities == -1 ? trans('package.unlimited') : $package->number_of_aminities }} {{ trans('package.aminity')}}</li>
                            <li class="">{{ $package->number_of_nearestplace == -1 ? trans('package.unlimited') : $package->number_of_nearestplace }} {{ trans('package.nearest_place') }}</li>
                            <li class="">{{ $package->number_of_photo == -1 ? trans('package.unlimited') : $package->number_of_photo }} {{ trans('package.photo') }}</li>
                            <li class="@if (!$package->is_allow_featured) add_before @endif">{{ trans('package.featured_property') }}</li>
                            <li class="@if (!$package->is_allow_featured) add_before @endif">{{ !$package->is_allow_featured ? 0 : ($package->is_allow_featured && $package->number_of_featured == -1 ? trans('package.unlimited') : $package->number_of_featured) }} {{ trans('package.featured_property') }}</li>
                            <li class="@if (!$package->is_allow_top) add_before @endif">{{ trans('package.top_property') }}</li>
                            <li class="@if (!$package->is_allow_top) add_before @endif">{{ !$package->is_allow_top ? 0 : ($package->is_allow_top && $package->number_of_top == -1 ? trans('package.unlimited') : $package->number_of_top) }} {{ trans('package.top_property') }}</li>
                            <li class="@if (!$package->is_allow_urgent) add_before @endif">{{ trans('package.urgent_property') }}</li>
                            <li class="@if (!$package->is_allow_urgent) add_before @endif">{{ !$package->is_allow_urgent ? 0 : ($package->is_allow_urgent && $package->number_of_urgent == -1 ? trans('package.unlimited') : $package->number_of_urgent) }} {{ trans('package.urgent_property')}}</li>
                            <li class="@if (!$package->is_auto_renew) add_before @endif">{{ trans('package.auto_renew') }}</li>
                            <li class="@if (!$package->is_agent) add_before @endif">{{ trans('package.agent') }}</li>
                            @if ($package->features)
                                @foreach (json_decode($package->features, true) as $feature)
                                    @if (count($feature) > 0)
                                        @if (Arr::get($feature, '0.value') != '')
                                        <li>{{ Arr::get($feature, '0.value') }}</li>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        </ul>   
                        <a class="pricing-btn" href="{{ route('public.account.package.subscribe', $package->id) }}">{{ trans('package.activate' )}}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

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
                        <p class="pricing-duration">{{ trans('package.period') }}<span>{{ trans('package.period_days', ['value' => $package->number_of_days == -1 ? "Unlimited" : $package->number_of_days]) }}</span></p>
                        <ul>
                            <li>{{ trans('package.credits_count', ['value' => $package->credits == -1 ? "Unlimited" : $package->credits]) }}</li>
                            <li class="">{{ trans('package.property_count', ['value' => $package->number_of_properties == -1 ? "Unlimited" : $package->number_of_properties]) }}</li>
                            <li class="">{{ trans('package.aminity_count', ['value' => $package->number_of_aminities == -1 ? "Unlimited" : $package->number_of_aminities]) }}</li>
                            <li class="">{{ trans('package.nearestplace_count', ['value' => $package->number_of_nearestplace == -1 ? "Unlimited" : $package->number_of_nearestplace]) }}</li>
                            <li class="">{{ trans('package.photo_count', ['value' => $package->number_of_photo == -1 ? "Unlimited" : $package->number_of_photo]) }}</li>
                            <li class="@if (!$package->is_allow_featured) add_before @endif">{{ trans('package.featured_property') }}</li>
                            <li class="@if (!$package->is_allow_featured) add_before @endif">{{ trans('package.featured_property_count', ['value' => !$package->is_allow_featured ? 0 : ($package->is_allow_featured && $package->number_of_featured == -1 ? 'Unlimited' : $package->number_of_featured)]) }}</li>
                            <li class="@if (!$package->is_allow_top) add_before @endif">{{ trans('package.top_property') }}</li>
                            <li class="@if (!$package->is_allow_top) add_before @endif">{{ trans('package.top_property_count', ['value' => !$package->is_allow_top ? 0 : ($package->is_allow_top && $package->number_of_top == -1 ? 'Unlimited' : $package->number_of_top)] )}}</li>
                            <li class="@if (!$package->is_allow_urgent) add_before @endif">{{ trans('package.urgent_property') }}</li>
                            <li class="@if (!$package->is_allow_urgent) add_before @endif">{{ trans('package.urgent_property_count', ['value' => !$package->is_allow_urgent ? 0 : ($package->is_allow_urgent && $package->number_of_urgent == -1 ? 'Unlimited' : $package->number_of_urgent)]) }}</li>
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
                        <a class="pricing-btn" href="{{ route('public.account.package.subscribe', $package->id) }}">{{ trans('package.submit_btn' )}}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

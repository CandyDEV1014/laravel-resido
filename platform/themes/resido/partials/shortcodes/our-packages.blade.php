<section id="pricing-section">
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

            @foreach ($packages as $package)
                <div class="col-lg-4 col-md-4">
                    <div class="pricing-wrap @if ($package->is_default) platinum-pr recommended @else standard-pr @endif">
                        <div class="pricing-header">
                            <h4 class="pr-value">
                                {{ format_price($package->price, $package->currency, false, true) }}
                            </h4>
                            <h4 class="pr-title">{{ $package->name }}</h4>
                        </div>
                        <div class="pricing-body">
                            <ul>
                                @if ($package->features)
                                    @foreach (json_decode($package->features, true) as $feature)
                                        @if (count($feature) > 0)
                                            <li class="available">{{ Arr::get($feature, '0.value') }}</li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="pricing-bottom">
                            <a href="{{ route('public.account.package.subscribe', $package->id) }}"
                                class="btn-pricing">{{ __('Choose Plan') }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

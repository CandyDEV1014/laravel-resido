<div class="detail-wrapper">
    <h4>{{ trans('plugins/payment::subscribe.header_text') }}</h4>
    <div class="row">
        <div class="col">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td width="50%">{{ trans('plugins/payment::subscribe.package_name') }}</td>
                        <td width="50%">{{ $package->name }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('plugins/payment::subscribe.package_price') }}</td>
                        <td width="50%">{{ format_price($package->price, $package->currency, false, true) }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('plugins/payment::subscribe.number_of_days') }}</td>
                        <td width="50%">{{ $package->number_of_days == -1 ? trans('plugins/payment::subscribe.unlimited_text') : $package->number_of_days }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('plugins/payment::subscribe.number_of_properties') }}</td>
                        <td width="50%">{{ $package->number_of_properties == -1 ? trans('plugins/payment::subscribe.unlimited_text') : $package->number_of_properties }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('plugins/payment::subscribe.number_of_aminities') }}</td>
                        <td width="50%">{{ $package->number_of_aminities == -1 ? trans('plugins/payment::subscribe.unlimited_text') : $package->number_of_aminities }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('plugins/payment::subscribe.number_of_nearestplace') }}</td>
                        <td width="50%">{{ $package->number_of_nearestplace == -1 ? trans('plugins/payment::subscribe.unlimited_text') : $package->number_of_nearestplace }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('plugins/payment::subscribe.number_of_photo') }}</td>
                        <td width="50%">{{ $package->number_of_photo == -1 ? trans('plugins/payment::subscribe.unlimited_text') : $package->number_of_photo }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('plugins/payment::subscribe.featured_property') }}</td>
                        <td width="50%">{{ $package->is_allow_featured ? trans('plugins/payment::subscribe.available_text') : trans('plugins/payment::subscribe.not_available_text') }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('plugins/payment::subscribe.number_of_featured') }}</td>
                        <td width="50%">{{ !$package->is_allow_featured ? 0 : ($package->is_allow_featured && $package->number_of_featured == -1 ? trans('plugins/payment::subscribe.unlimited_text') : $package->number_of_featured) }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('plugins/payment::subscribe.top_property') }}</td>
                        <td width="50%">{{ $package->is_allow_top ? trans('plugins/payment::subscribe.available_text') : trans('plugins/payment::subscribe.not_available_text') }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('plugins/payment::subscribe.number_of_top') }}</td>
                        <td width="50%">{{ !$package->is_allow_top ? 0 : ($package->is_allow_top && $package->number_of_top == -1 ? trans('plugins/payment::subscribe.unlimited_text') : $package->number_of_top) }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('plugins/payment::subscribe.urgent_property') }}</td>
                        <td width="50%">{{ $package->is_allow_urgent ? trans('plugins/payment::subscribe.available_text') : trans('plugins/payment::subscribe.not_available_text') }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('plugins/payment::subscribe.number_of_urgent') }}</td>
                        <td width="50%">{{ !$package->is_allow_urgent ? 0 : ($package->is_allow_urgent && $package->number_of_urgent == -1 ? trans('plugins/payment::subscribe.unlimited_text') : $package->number_of_urgent) }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('plugins/payment::subscribe.auto_renew') }}</td>
                        <td width="50%">{{ $package->is_auto_renew ? trans('plugins/payment::subscribe.available_text') : trans('plugins/payment::subscribe.not_available_text') }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('plugins/payment::subscribe.agent') }}</td>
                        <td width="50%">{{ $package->is_agent ? trans('plugins/payment::subscribe.available_text') : trans('plugins/payment::subscribe.not_available_text') }}</td>
                    </tr>
                   
                    @if ($package->features)
                        @php $features = json_decode($package->features, true); @endphp
                        @foreach ($features as $key => $feature)
                            @if (count($feature) > 0)
                                @if (Arr::get($feature, '0.value') != '')
                                    @if ($key == 0) 
                                        <tr>
                                            <td rowspan="{{ count($features) }}">{{ trans('plugins/payment::subscribe.features') }}</td>
                                            <td>{{ Arr::get($feature, '0.value') }}</td>
                                        </tr>
                                    @else
                                        <tr><td>{{ Arr::get($feature, '0.value') }}</td></tr>
                                    @endif
                                @endif
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
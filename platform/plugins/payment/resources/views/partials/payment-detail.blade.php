<div class="detail-wrapper">
    <h4>{{ trans('package.package_detail') }}</h4>
    <div class="row">
        <div class="col">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td width="50%">{{ trans('package.name') }}</td>
                        <td width="50%">{{ $package->name }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('package.price') }}</td>
                        <td width="50%">{{ format_price($package->price, $package->currency, false, true) }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('package.period') }}</td>
                        <td width="50%">{{ $package->number_of_days == -1 ? trans('package.unlimited') : $package->number_of_days }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('package.credits') }}</td>
                        <td width="50%">{{ $package->credits == -1 ? trans('package.unlimited') : $package->credits }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('package.property_submission') }}</td>
                        <td width="50%">{{ $package->number_of_properties == -1 ? trans('package.unlimited') : $package->number_of_properties }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('package.aminity') }}</td>
                        <td width="50%">{{ $package->number_of_aminities == -1 ? trans('package.unlimited') : $package->number_of_aminities }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('package.nearest_place') }}</td>
                        <td width="50%">{{ $package->number_of_nearestplace == -1 ? trans('package.unlimited') : $package->number_of_nearestplace }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('package.photo') }}</td>
                        <td width="50%">{{ $package->number_of_photo == -1 ? trans('package.unlimited') : $package->number_of_photo }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('package.featured_property') }}</td>
                        <td width="50%">{{ $package->is_allow_featured ? trans('package.available') : trans('package.not_available') }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('package.number_of_featured') }}</td>
                        <td width="50%">{{ !$package->is_allow_featured ? 0 : ($package->is_allow_featured && $package->number_of_featured == -1 ? trans('package.unlimited') : $package->number_of_featured) }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('package.top_property') }}</td>
                        <td width="50%">{{ $package->is_allow_top ? trans('package.available') : trans('package.not_available') }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('package.number_of_top') }}</td>
                        <td width="50%">{{ !$package->is_allow_top ? 0 : ($package->is_allow_top && $package->number_of_top == -1 ? trans('package.unlimited') : $package->number_of_top) }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('package.urgent_property') }}</td>
                        <td width="50%">{{ $package->is_allow_urgent ? trans('package.available') : trans('package.not_available') }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('package.number_of_urgent') }}</td>
                        <td width="50%">{{ !$package->is_allow_urgent ? 0 : ($package->is_allow_urgent && $package->number_of_urgent == -1 ? trans('package.unlimited') : $package->number_of_urgent) }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('package.auto_renew') }}</td>
                        <td width="50%">{{ $package->is_auto_renew ? trans('package.available') : trans('package.not_available') }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{ trans('package.agent') }}</td>
                        <td width="50%">{{ $package->is_agent ? trans('package.available') : trans('package.not_available') }}</td>
                    </tr>
                   
                    @if ($package->features)
                        @php $features = json_decode($package->features, true); @endphp
                        @foreach ($features as $key => $feature)
                            @if (count($feature) > 0)
                                @if (Arr::get($feature, '0.value') != '')
                                    @if ($key == 0) 
                                        <tr>
                                            <td rowspan="{{ count($features) }}">{{ trans('package.features') }}</td>
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
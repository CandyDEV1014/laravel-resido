@extends(Theme::getThemeNamespace() . '::views.real-estate.account.master')
@php
$user = auth('account')->user();
$getPro = \Botble\RealEstate\Models\Property::where('author_id',$user->id)->get();
$expiredProperty = 0;

$getClientReview = \Botble\RealEstate\Models\Review::join('re_properties', 're_properties.id', '=', 're_reviews.reviewable_id')->where('author_id', $user->id)->where('expire_date', '>', now()->toDateTimeString())->where('never_expired', false)->count();

$getMyReview = \Botble\RealEstate\Models\Review::select('re_reviews.*','re_properties.expire_date as expireDate')->join('re_properties', 're_properties.id', '=', 're_reviews.reviewable_id')->where('account_id', $user->id)->where('expire_date', '>', now()->toDateTimeString())->where('never_expired', false)->count();

@endphp

@foreach($getPro as $getP)
    @if($getP->expire_date && $getP->expire_date->isPast())
        @php $expiredProperty++; @endphp
    @endif
@endforeach


@section('content')
    {!! apply_filters(ACCOUNT_TOP_STATISTIC_FILTER, null) !!}
    @if (RealEstateHelper::isEnabledCreditsSystem())
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h4>{{ __('Your Current Credits') }}: <span class="pc-title theme-cl">{{ auth('account')->user()->credits }}</span></h4>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="dashboard-stat widget-1">
                <div class="dashboard-stat-content">
                    <h4>{{ $user->properties()->where('moderation_status', \Botble\RealEstate\Enums\ModerationStatusEnum::APPROVED)->count() }}</h4>
                    <span>{{ trans('plugins/real-estate::dashboard.approved_properties') }}</span>
                </div>
                <div class="dash-stst-div"></div>
                <div class="dashboard-stat-icon"><i class="ti-location-pin"></i></div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="dashboard-stat widget-2">
                <div class="dashboard-stat-content">
                    <h4>{{ $user->properties()->where('moderation_status', \Botble\RealEstate\Enums\ModerationStatusEnum::PENDING)->count() }}</h4>
                    <span>{{ trans('plugins/real-estate::dashboard.pending_approve_properties') }}</span>
                </div>
                <div class="dash-stst-div"></div>
                <div class="dashboard-stat-icon"><i class="ti-pie-chart"></i></div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="dashboard-stat widget-3">
                <div class="dashboard-stat-content">
                    <h4>{{ $user->properties()->where('moderation_status', \Botble\RealEstate\Enums\ModerationStatusEnum::REJECTED)->count() }}</h4>
                    <span>{{ trans('plugins/real-estate::dashboard.rejected_properties') }}</span>
                </div>
                <div class="dash-stst-div"></div>
                <div class="dashboard-stat-icon"><i class="ti-user"></i></div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="dashboard-stat widget-5">
                <div class="dashboard-stat-content">
                    <h4 class="wishlist-count">0</h4>
                    <span>{{ trans('plugins/real-estate::dashboard.agent_wishlist') }}</span>
                </div>
                <div class="dash-stst-div"></div>
                <div class="dashboard-stat-icon"><i class="fas fa-heart"></i></div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="dashboard-stat widget-4">
                <div class="dashboard-stat-content">
                    <h4>{{$expiredProperty}}</h4>
                    <span>{{ trans('plugins/real-estate::dashboard.expire_property') }}</span>
                </div>
                <div class="dash-stst-div"></div>
                <div class="dashboard-stat-icon"><i class="fa fa-calendar-minus"></i></div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="dashboard-stat widget-6">
                <div class="dashboard-stat-content">
                    <h4>{{$getMyReview}}</h4>
                    <span>{{ trans('plugins/real-estate::dashboard.my_review') }}</span>
                </div>
                <div class="dash-stst-div"></div>
                <div class="dashboard-stat-icon"><i class="fa fa-calendar-minus"></i></div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="dashboard-stat widget-7">
                <div class="dashboard-stat-content">
                    <h4>{{$getClientReview}}</h4>
                    <span>{{ trans('plugins/real-estate::dashboard.client_review') }}</span>
                </div>
                <div class="dash-stst-div"></div>
                <div class="dashboard-stat-icon"><i class="fa fa-calendar-minus"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div id="app-real-estate">
            <activity-log-component default-active-tab="activity-logs"></activity-log-component>
        </div>
    </div>
@endsection

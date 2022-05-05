<div class="col-lg-7 mb-2">
    @if ($stats->count())
        <div class="chart" id="stats-chart"></div>
    @else
        <div class="h-100 d-flex align-items-center justify-content-center bg-light">
            <div>{{ trans('core/base::tables.no_data') }}</div>
        </div>
    @endif
</div>
<div class="col-lg-5 mb-2">
    <div id="world-map"></div>
</div>
<div class="clearfix"></div>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="info-box">
        <div class="info-box-icon bg-yellow-casablanca font-white">
            <i class="fa fa-eye"></i>
        </div>
        <div class="info-box-content">
            <span class="info-box-text">{{ trans('plugins/analytics::analytics.sessions') }}</span>
            <span class="info-box-number" id="sessions_total">{{ number_format($total['ga:sessions']) }}</span>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="info-box">
        <div class="info-box-icon bg-blue">
            <i class="fa fa-users"></i>
        </div>
        <div class="info-box-content">
            <span class="info-box-text">{{ trans('plugins/analytics::analytics.visitors') }}</span>
            <span class="info-box-number" id="users_total">{{ number_format($total['ga:users']) }}</span>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="info-box border-green-haze">
        <div class="info-box-icon bg-green-haze font-white">
            <i class="icon icon-traffic-cone"></i>
        </div>
        <div class="info-box-content">
            <span class="info-box-text">{{ trans('plugins/analytics::analytics.pageviews') }}</span>
            <span class="info-box-number" id="page_views_total">{{ number_format($total['ga:pageviews']) }}</span>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="info-box">
        <div class="info-box-icon bg-yellow">
            <i class="icon-energy"></i>
        </div>
        <div class="info-box-content">
            <span class="info-box-text">{{ trans('plugins/analytics::analytics.bounce_rate') }}</span>
            <span class="info-box-number" id="bounce_rate_total">{{ round($total['ga:bounceRate'], 2) }}%</span>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="info-box">
        <div class="info-box-icon bg-purple">
            <i class="fas fa-chart-pie"></i>
        </div>
        <div class="info-box-content">
            <span class="info-box-text">{{ trans('plugins/analytics::analytics.percent_new_session') }}</span>
            <span class="info-box-number" id="percent_new_session_total">{{ round($total['ga:percentNewSessions'], 2) }}%</span>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="info-box">
        <div class="info-box-icon bg-yellow-crusta font-white">
            <i class="icon-graph"></i>
        </div>
        <div class="info-box-content">
            <span class="info-box-text">{{ trans('plugins/analytics::analytics.page_session') }}</span>
            <span class="info-box-number" id="page_views_per_visit_total">{{ round($total['ga:pageviewsPerVisit'], 2) }}</span>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="info-box">
        <div class="info-box-icon bg-red">
            <i class="fa fa-clock"></i>
        </div>
        <div class="info-box-content">
            <span class="info-box-text">{{ trans('plugins/analytics::analytics.avg_duration') }}</span>
            <span class="info-box-number" id="session_duration_total">{{ gmdate('H:i:s', $total['ga:avgSessionDuration']) }}</span>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="info-box">
        <div class="info-box-icon bg-yellow-casablanca">
            <i class="fa fa-user-plus"></i>
        </div>
        <div class="info-box-content">
            <span class="info-box-text">{{ trans('plugins/analytics::analytics.new_users') }}</span>
            <span class="info-box-number" id="session_duration_total">{{ $total['ga:newUsers'] }}</span>
        </div>
    </div>
</div>

<div class="clearfix"></div>

<div data-stats='{{ json_encode($stats, JSON_HEX_APOS) }}'></div>
<div data-country-stats='{{ json_encode($country_stats, JSON_HEX_APOS) }}'></div>
<div data-lang-pageviews='{{ trans("plugins/analytics::analytics.pageviews") }}'></div>
<div data-lang-visits='{{ trans("plugins/analytics::analytics.visitors") }}'></div>

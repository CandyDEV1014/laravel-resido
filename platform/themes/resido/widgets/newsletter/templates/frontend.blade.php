@if(is_plugin_active('newsletter'))
<div class="footer-widget newsletter widget_newsletter">
    <div class="header-title-2">
        <h4 class="widget-title">
            <span>{{ $config['name'] }}</span>
        </h4>
        @if (!empty($config['subname']))
            <h3 class="font-heading">{{ $config['subname'] }}</h3>
        @endif
    </div>
    <form class="form-subcriber newsletter-form mt-30" action="{{ route('public.newsletter.subscribe') }}" method="post">
        @csrf
        <div class="form-group d-flex">
            <input type="email" name="email" class="form-control bg-white font-small" placeholder="{{ __('Enter your email') }}">
            <button class="btn bg-dark text-white" type="submit">{{ __('Subscribe') }}</button>
        </div>
        <!--Footer Recaptcha -->
        <!-- @if (setting('enable_captcha') && is_plugin_active('captcha'))
            <div class="form-group">
                {!! Captcha::display() !!}
            </div>
        @endif -->
    </form>
</div>
@endif

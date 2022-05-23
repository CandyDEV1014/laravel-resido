@if(is_plugin_active('newsletter'))
<div class="footer-widget newsletter widget_newsletter">
    <div class="row large_subscribe">
        <div class="col-xl-4 col-lg-5">
            <div class="large_subscribe_text">
                <h4 class="">subscribe our news letter</h4>
            </div>
        </div>
        <div class="col-xl-8 pe-0 col-lg-7">
            <form class="form-subcriber newsletter-form mt-30" action="{{ route('public.newsletter.subscribe') }}" method="post">
                @csrf
                <!-- <div class="form-group d-flex"> -->
                    <input type="email" name="email" class="" placeholder="{{ __('Enter your email') }}">
                    <button class="" type="submit">
                        <i class="fa fa-angle-right"></i>
                    </button>
                <!-- </div> -->
                <!--Footer Recaptcha -->
                <!-- @if (setting('enable_captcha') && is_plugin_active('captcha'))
                    <div class="form-group">
                        {!! Captcha::display() !!}
                    </div>
                @endif -->
            </form>
        </div>
    </div>
    
</div>
@endif

<form class="form-inline" action="{{ route('public.newsletter.subscribe') }}" method="post">
    @csrf
    @if (!in_array('name', $hiddenFields))
        <div class="form-group mb-3">
            <label for="newsletter_name">{{ __('Name') }}</label>
            <input type="text" class="form-control" name="name" id="newsletter_name"
                   placeholder="{{ __('Your name') }}">
        </div>
    @endif
    <div class="form-group mb-3">
        <label for="newsletter_email">{{ __('Email') }}</label>
        <input type="email" name="email" class="form-control" id="newsletter_email"
               placeholder="{{ __('Your email') }}">
    </div>
    @if (setting('enable_captcha') && is_plugin_active('captcha'))
        {!! Captcha::display() !!}
    @endif
    <button type="submit" class="btn btn-secondary">{{ __('Submit') }}</button>
</form>

@if (setting('social_login_facebook_enable', false) || setting('social_login_google_enable', false) || setting('social_login_github_enable', false) || setting('social_login_linkedin_enable', false))
    <div class="modal-divider"><span>{{ __('Or login via') }}</span></div>    
    <div class="login-options social-login">
        <ul>
            @if (setting('social_login_facebook_enable', false))
                <li>
                    <a href="{{ route('auth.social', 'facebook') }}" class="btn connect-fb"><i class="ti-facebook"></i>{{ __('Facebook') }}</a>
                </li>
            @endif
            @if (setting('social_login_google_enable', false))
                <li>
                    <a href="{{ route('auth.social', 'google') }}" class="btn connect-google"><i class="ti-google"></i>{{ __('Google') }}</a>
                </li>
            @endif
            @if (setting('social_login_github_enable', false))
                <li>
                    <a href="{{ route('auth.social', 'github') }}" class="btn btn-dark github connect-github"><i class="ti-github"></i>{{ __('Github') }}</a>
                </li>
            @endif
            @if (setting('social_login_linkedin_enable', false))
                <li>
                    <a href="{{ route('auth.social', 'linkedin') }}" class="btn linkedin connect-linkedin"><i class="ti-linkedin"></i>{{ __('Linkedin') }}</a>
                </li>
            @endif
        </ul>
    </div>
@endif

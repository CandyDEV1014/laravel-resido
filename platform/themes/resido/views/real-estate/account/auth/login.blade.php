<section>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="modal-content" id="registermodal">
                    <div class="modal-body">
                        <h2 class="text-center">{{ trans('plugins/real-estate::dashboard.login-title') }}</h2>
                        <br>
                        @include(Theme::getThemeNamespace() . '::views.real-estate.account.auth.includes.messages')

                        <div class="login-form">
                            <form method="POST" class="simple-form" action="{{ route('public.account.login') }}">
                                @csrf
                                <div class="form-group">
                                    <label>{{ trans('plugins/real-estate::dashboard.email_or_username') }}</label>
                                    <div class="input-with-icon">
                                        <input id="email" type="text"
                                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                               placeholder="{{ trans('plugins/real-estate::dashboard.email_or_username') }}"
                                               name="email" value="{{ old('email') }}" autofocus>
                                        <i class="ti-user"></i>
                                    </div>
                                    @if ($errors->has('email') || $errors->has('username'))
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ !empty($errors->first('email')) ? $errors->first('email') : $errors->first('username') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group ">
                                    <label>{{ trans('plugins/real-estate::dashboard.password') }}</label>
                                    <div class="input-with-icon">
                                        <input id="password" type="password"
                                               class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                               placeholder="{{ trans('plugins/real-estate::dashboard.password') }}"
                                               name="password">
                                        <i class="ti-unlock"></i>
                                    </div>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox"
                                                           name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                    {{ trans('plugins/real-estate::dashboard.remember-me') }}
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 text-md-center">
                                            <a href="{{ route('public.account.password.request') }}" class="link">
                                                {{ trans('plugins/real-estate::dashboard.forgot-password-cta') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
								@if (setting('enable_captcha') && is_plugin_active('captcha'))
                                <div class="form-group">
                                    {!! Captcha::display() !!}
                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="invalid-feedback d-block">
                                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                            </span>
                                    @endif
                                </div>
                                @endif
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md full-width btn-theme-light-2 rounded">
                                        {{ trans('plugins/real-estate::dashboard.login-cta') }}
                                    </button>
                                </div>
                                
                                <div class="form-group text-center">
                                    <p>{{ __("Don't have an account?") }} <a
                                            href="{{ route('public.account.register') }}"
                                            class="link d-block d-sm-inline-block text-sm-left text-center">{{ __('Register a new account') }}</a>
                                    </p>
                                </div>

                                <div class="text-center">
                                    {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Botble\RealEstate\Models\Account::class) !!}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

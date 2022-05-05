<section>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="modal-content">
                    <div class="modal-body">
                        <h2 class="text-center">{{ trans('plugins/real-estate::account.forgot_password') }}</h2>
                        <br>
                        @include(Theme::getThemeNamespace() . '::views.real-estate.account.auth.includes.messages')

                        <form method="POST" class="simple-form" action="{{ route('public.account.password.email') }}">
                            @csrf
                            <div class="form-group">
                                <div class="input-with-icon">
                                    <input id="email" type="email"
                                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="email" value="{{ old('email') }}" required
                                           placeholder="{{ trans('plugins/real-estate::dashboard.email') }}">
                                    <i class="ti-email"></i>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="d-block invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-md full-width btn-theme-light-2 rounded">
                                    {{ trans('plugins/real-estate::dashboard.reset-password-cta') }}
                                </button>
                                <div class="text-center">
                                    <a href="{{ route('public.account.login') }}"
                                       class="btn btn-link">{{ trans('plugins/real-estate::dashboard.back-to-login') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

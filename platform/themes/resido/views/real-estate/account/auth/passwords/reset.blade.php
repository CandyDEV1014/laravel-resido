<section>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="modal-content">
                    <div class="modal-body">
                        <h2 class="text-center">{{ trans('plugins/real-estate::dashboard.reset-password-title') }}</h2>
                        <br>
                        @include(Theme::getThemeNamespace() . '::views.real-estate.account.auth.includes.messages')

                        <form method="POST" class="simple-form" action="{{ route('public.account.password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group">
                                <div class="input-with-icon">
                                    <input id="email" type="email"
                                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="email" value="{{ old('email') }}" required autofocus
                                           placeholder="{{ trans('plugins/real-estate::dashboard.email') }}">
                                    <i class="ti-email"></i>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="d-block invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <div class="input-with-icon">
                                    <input id="password" type="password"
                                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           name="password" required
                                           placeholder="{{ trans('plugins/real-estate::dashboard.password') }}">
                                    <i class="ti-unlock"></i>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="d-block invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <div class="input-with-icon">
                                    <input id="password-confirm" type="password"
                                           class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                           name="password_confirmation" required
                                           placeholder="{{ trans('plugins/real-estate::dashboard.password-confirmation') }}">
                                    <i class="ti-unlock"></i></div>

                                @if ($errors->has('password_confirmation'))
                                    <span class="d-block invalid-feedback">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-md full-width btn-theme-light-2 rounded">
                                    {{ trans('plugins/real-estate::dashboard.reset-password-cta') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

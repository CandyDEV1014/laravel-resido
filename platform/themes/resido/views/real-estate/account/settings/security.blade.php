@extends(Theme::getThemeNamespace() . '::views.real-estate.account.master')

@section('content')
    <div class="dashboard-wraper settings">
        <!-- Basic Information -->
        <div class="form-submit">
            <h4>{{ trans('plugins/real-estate::dashboard.security_title') }}</h4>
            <div class="submit-section">
                <div class="row">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('public.account.post.security') }}"
                          class="settings-reset">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label
                                for="password">{{ trans('plugins/real-estate::dashboard.password_new') }}</label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                        <div class="form-group">
                            <label
                                for="password_confirmation">{{ trans('plugins/real-estate::dashboard.password_new_confirmation') }}</label>
                            <input type="password" class="form-control" name="password_confirmation"
                                   id="password_confirmation">
                        </div>
                        <button type="submit"
                                class="btn btn-theme-light-2">{{ trans('plugins/real-estate::dashboard.password_update_btn') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

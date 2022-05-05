@php
    SeoHelper::setTitle(__('404 - Not found'));
    Theme::fire('beforeRenderTheme', app(\Botble\Theme\Contracts\Theme::class));
@endphp

{!! Theme::partial('header') !!}

<!-- ============================ User Dashboard ================================== -->
<section class="error-wrap">
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-lg-6 col-md-10">
                <div class="text-center">
                    <img src="{{ Theme::asset()->url('img/404.png') }}" class="img-fluid" alt="">
                </div>

                <div>
                    <br><br>
                    <h4>{{ __('This may have occurred because of several reasons') }}:</h4>
                    <ul>
                        <li>{{ __('The page you requested does not exist.') }}</li>
                        <li>{{ __('The link you clicked is no longer.') }}</li>
                        <li>{{ __('The page may have moved to a new location.') }}</li>
                        <li>{{ __('An error may have occurred.') }}</li>
                        <li>{{ __('You are not authorized to view the requested resource.') }}</li>
                    </ul>

                    <strong>{!! clean(__('Please try again in a few minutes, or alternatively return to the homepage by <a href=":link">clicking here</a>.', ['link' => route('public.single')])) !!}</strong>
                    <br>
                </div>

                <div class="text-center">
                    <a class="btn btn-theme" href="{{ route('public.index') }}">{{ __('Back To Home') }}</a>
                </div>
            </div>

        </div>
    </div>
</section>
{!! Theme::partial('footer') !!}



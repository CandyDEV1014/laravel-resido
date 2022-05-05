{!! dynamic_sidebar('footer_sidebar_1') !!}
<!-- ============================ Footer Start ================================== -->
<footer class="dark-footer skin-dark-footer">
    <div class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <div class="footer-widget">
                        @if (theme_option('logo_white'))
                            <img src="{{ RvMedia::getImageUrl(theme_option('logo_white')) }}" class="img-footer"
                                 style="max-height: 38px" alt="{{ theme_option('site_name') }}">
                        @endif
                        <div class="footer-add">
                            @if (theme_option('address'))
                                <p><i class="fas fa-map-marker-alt"></i> {{ theme_option('address') }}</p>
                            @endif
                            @if (theme_option('hotline'))
                                <p><i class="fas fa-phone-square"></i> {{ theme_option('hotline') }}</p>
                            @endif
                            @if (theme_option('email'))
                                <p><i class="fas fa-envelope"></i> {{ theme_option('email') }}</p>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="row">
                        {!! dynamic_sidebar('footer_sidebar_2') !!}
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    {!! dynamic_sidebar('footer_sidebar_3') !!}
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <p class="mb-0">{!! clean(theme_option('copyright')) !!}</p>
                </div>

                <div class="col-lg-6 col-md-6">
                    @if (theme_option('social_links'))
                        <ul class="footer-bottom-social">
                            @foreach(json_decode(theme_option('social_links'), true) as $socialLink)
                                @if (count($socialLink) == 3)
                                    <li><a href="{{ $socialLink[2]['value'] }}" target="_blank"
                                           title="{{ $socialLink[0]['value'] }}"><i
                                                class="{{ $socialLink[1]['value'] }}"></i></a></li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- ============================ Footer End ================================== -->

<a id="back2Top" class="top-scroll" title="Back to top" href="#"><i class="ti-arrow-up"></i></a>
</div>

{!! Theme::footer() !!}
@if (session()->has('success_msg') || session()->has('error_msg') || (isset($errors) && $errors->count() > 0) || isset($error_msg))
    <script type="text/javascript">
        "use strict";
        $(document).ready(function () {
            @if (session()->has('success_msg'))
            window.showAlert('alert-success', '{{ session('success_msg') }}');
            @endif

            @if (session()->has('error_msg'))
            window.showAlert('alert-danger', '{{ session('error_msg') }}');
            @endif

            @if (isset($error_msg))
            window.showAlert('alert-danger', '{{ $error_msg }}');
            @endif

            @if (isset($errors))
            @foreach ($errors->all() as $error)
            window.showAlert('alert-danger', '{!! $error !!}');
            @endforeach
            @endif
        });
    </script>
    @endif
</body>
</html>

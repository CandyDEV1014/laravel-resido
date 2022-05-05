<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5, user-scalable=1" name="viewport"/>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Fonts-->
  <link href="https://fonts.googleapis.com/css2?family={{ urlencode(theme_option('font_heading', 'Jost')) }}:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family={{ urlencode(theme_option('font_body', 'Muli')) }}:300,400,600,700" rel="stylesheet" type="text/css">
  <!-- CSS Library-->

  <style>
    :root {
      --primary-color: {{ theme_option('primary_color', '#2b4db9') }};
      --font-body: {{ theme_option('font_body', 'Muli') }}, sans-serif;
      --font-heading: {{ theme_option('font_heading', 'Jost') }}, sans-serif;
    }
  </style>

  <script>
    "use strict";
    window.trans = {
      "Price": "{{ __('Price') }}",
      "Number of rooms": "{{ __('Number of rooms') }}",
      "Number of rest rooms": "{{ __('Number of rest rooms') }}",
      "Square": "{{ __('Square') }}",
      "No property found": "{{ __('No property found') }}",
      "million": "{{ __('million') }}",
      "billion": "{{ __('billion') }}",
      "in": "{{ __('in') }}",
      "Added to wishlist successfully!": "{{ __('Added to wishlist successfully!') }}",
      "Removed from wishlist successfully!": "{{ __('Removed from wishlist successfully!') }}",
      "I care about this property!!!": "{{ __('I care about this property!!!') }}",
      "See More Reviews": "{{ __('See More Reviews') }}",
      "Reviews": "{{ __('Reviews') }}",
      "See More Comments": "{{ __('See More Comments') }}",
      "Comments": "{{ __('Comments') }}",
      "out of 5.0": "{{ __('out of 5.0') }}",
      "service": "{{ trans('plugins/real-estate::review.service') }}",
      "value": "{{ trans('plugins/real-estate::review.value') }}",
      "location": "{{ trans('plugins/real-estate::review.location') }}",
      "cleanliness": "{{ trans('plugins/real-estate::review.cleanliness') }}",
    }
    window.themeUrl = '{{ Theme::asset()->url('') }}';
    window.siteUrl = '{{ url('') }}';
    window.currentLanguage = '{{ App::getLocale() }}';
  </script>

  {!! Theme::header() !!}
</head>
<body class="{{ theme_option('skin', 'blue') }}" @if (BaseHelper::siteLanguageDirection() == 'rtl') dir="rtl" @endif>
<div id="alert-container"></div>

@if (theme_option('preloader_enabled', 'no') == 'yes')
  <!-- ============================================================== -->
  <!-- Preloader - style you can find in spinners.css -->
  <!-- ============================================================== -->
  <div id="preloader"><div class="preloader"><span></span><span></span></div></div>
@endif

<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper">

  <!-- ============================================================== -->
  <!-- Top header  -->

  <!-- <div class="topbar bg-brand p-2 d-none d-sm-block">
    <div class="container">
      <div class="d-flex align-items-center justify-content-between">
        @if (is_plugin_active('language'))
          <div class="language-wrapper">
            {!! $languages = apply_filters('language_switcher') !!}
          </div>
        @endif

        @if (is_plugin_active('real-estate'))
          <div class="topbar-right d-flex align-items-center">
            <div class="topbar-wishlist">
              <a class="text-white" href="{{ route('public.wishlist') }}"><i class="fas fa-heart"></i> {{ __('Wishlist') }}(<span class="wishlist-count">0</span>)</a>
            </div>
            @php $currencies = get_all_currencies(); @endphp
            @if (count($currencies) > 1)
              <div class="choose-currency ms-3 text-white language-wrapper">
                <div class="dropdown">
                  <button class="btn btn-secondary dropdown-toggle btn-select-language" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    {{ get_application_currency()->title }}
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu language_bar_chooser">
                    @foreach ($currencies as $currency)
                        <li>
                          <a href="{{ route('public.change-currency', $currency->title) }}" @if (get_application_currency_id() == $currency->id) class="active" @endif><span>{{ $currency->title }}</span></a>
                        </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            @endif
          </div>
        @endif
      </div>
    </div>
  </div> -->
  <!-- ============================================================== -->
  <!-- Start Navigation -->
  <div class="header header-light head-shadow">
    <div class="container">
      <nav id="navigation" class="navigation navigation-landscape">
        <div class="nav-header">
          @if (theme_option('logo'))
            <a class="nav-brand" href="{{ route('public.index') }}"><img class="logo" src="{{ RvMedia::getImageUrl(theme_option('logo')) }}" alt="{{ setting('site_title') }}"></a>
          @endif
          <div class="nav-toggle"></div>
        </div>
        <div class="nav-menus-wrapper" style="transition-property: none;">
          {!! Menu::renderMenuLocation('main-menu', [
            'view'    => 'menu',
            'options' => ['class' => 'nav-menu'],
          ]) !!}

          @if (is_plugin_active('real-estate'))
            <ul class="nav-menu nav-menu-social align-to-right">
              <li style="margin-bottom: -4px;">
                @if (is_plugin_active('language'))
                  <div class="language-wrapper">
                    {!! $languages = apply_filters('language_switcher') !!}
                  </div>
                @endif
              </li>                                       
              <li>
                <a href="{{ route('public.account.properties.create') }}" class="text-success"><img src="{{ Theme::asset()->url('') }}/img/submit.svg" width="20" alt="" class="mr-2" /> {{ __('Add Property') }}</a>
              </li>
              @if (auth('account')->check())
                <li>
                @if (is_plugin_active('real-estate'))
                  <div class="topbar-right d-flex align-items-center">
                    <div class="topbar-wishlist">
                      <a class="text-success" href="{{ route('public.wishlist') }}"><i class="fas fa-heart"></i> {{ __('Wishlist') }}(<span class="wishlist-count">0</span>)</a>
                    </div>
                    @php $currencies = get_all_currencies(); @endphp
                    @if (count($currencies) > 1)
                      <div class="choose-currency ms-3 text-success pt-1">
                        <div class="dropdown">
                          <button class="btn btn-secondary text-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            {{ get_application_currency()->title }}
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu">
                            @foreach ($currencies as $currency)
                              <li>
                                <a href="{{ route('public.change-currency', $currency->title) }}" @if (get_application_currency_id() == $currency->id) class="active" @endif><span>{{ $currency->title }}</span></a>
                              </li>
                            @endforeach
                          </ul>
                        </div>
                      </div>
                    @endif
                  </div>
                @endif
              </li>
                <li class="login-item" style="margin-right: -20px;"><a href="{{ route('public.account.dashboard') }}" rel="nofollow"><i class="fas fa-user"></i> <span>{{ auth('account')->user()->name }}</span></a></li>


                <li class="login-item"><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" rel="nofollow"><i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}</a></li>
              @else
                <li class="add-listing">
                  <a href="{{ route('public.account.login') }}"><img src="{{ Theme::asset()->url('') }}/img/user-light.svg" width="12" alt="" class="mr-2" />{{ __('Sign In') }}</a>
                </li>
              @endif
            </ul>

            @if (auth('account')->check())
              <form id="logout-form" action="{{ route('public.account.logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            @endif

            <div class="clearfix"></div>
            <!--<div class="d-sm-none mobile-menu">
              <div class="mobile-menu-item mobile-wishlist">
                <a href="{{ route('public.wishlist') }}"><i class="fas fa-heart"></i> {{ __('Wishlist') }}(<span class="wishlist-count">0</span>)</a>
              </div>
              @if (count($currencies) > 1)
                <div class="mobile-menu-item choose-currency language-wrapper">
                  <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle btn-select-language" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                      {{ get_application_currency()->title }}
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu language_bar_chooser">
                      @foreach ($currencies as $currency)
                        <li>
                          <a href="{{ route('public.change-currency', $currency->title) }}" @if (get_application_currency_id() == $currency->id) class="active" @endif><span>{{ $currency->title }}</span></a>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              @endif
              @if (is_plugin_active('language'))
                <div class="mobile-menu-item language-wrapper">
                  {!! $languages = apply_filters('language_switcher') !!}
                </div>
              @endif
            </div>-->
          @endif
        </div>
      </nav>
    </div>
  </div>
  <!-- End Navigation -->
  <div class="clearfix"></div>

@if (count($languages) > 1)
    <input type="hidden" name="language" value="{{ $currentLanguage->lang_code }}">
    <div id="list-others-language">
        @foreach($languages as $language)
            @if ($language->lang_code != $currentLanguage->lang_code)
                {!! language_flag($language->lang_flag, $language->lang_name) !!}
                <a href="{{ Route::has($route['edit']) ? Request::url() . ($language->lang_code != Language::getDefaultLocaleCode() ? '?ref_lang=' . $language->lang_code : null) : '#' }}" target="_blank">{{ $language->lang_name }} <i class="fas fa-external-link-alt"></i></a>
                <br>
            @endif
        @endforeach
    </div>
@endif

@push('header')
    <meta name="ref_from" content="{{ (!empty($args[0]) && $args[0]->id ? $args[0]->id : 0) }}">
    <meta name="ref_lang" content="{{ $currentLanguage->lang_code }}">
@endpush

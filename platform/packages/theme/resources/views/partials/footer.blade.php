{!! Theme::asset()->container('footer')->styles() !!}
{!! Theme::asset()->container('footer')->scripts() !!}
{!! Theme::asset()->container('after_footer')->scripts() !!}

{!! apply_filters(THEME_FRONT_FOOTER, null) !!}

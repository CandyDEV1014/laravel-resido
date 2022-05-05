<?php

if (!function_exists('render_newsletter_subscribe_form')) {
    /**
     * @param array $hiddenFields
     * @return string
     */
    function render_newsletter_subscribe_form(array $hiddenFields = [])
    {
        return view('plugins/newsletter::partials.form', compact('hiddenFields'))->render();
    }
}

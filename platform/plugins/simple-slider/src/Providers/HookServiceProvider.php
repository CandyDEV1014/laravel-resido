<?php

namespace Botble\SimpleSlider\Providers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Shortcode\Compilers\Shortcode;
use Botble\SimpleSlider\Repositories\Interfaces\SimpleSliderInterface;
use Illuminate\Support\ServiceProvider;
use Theme;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->booted(function () {
            if (setting('simple_slider_using_assets', true) && defined('THEME_OPTIONS_MODULE_SCREEN_NAME')) {
                Theme::asset()
                    ->container('footer')
                    ->usePath(false)
                    ->add('owl.carousel',
                        asset('vendor/core/plugins/simple-slider/libraries/owl-carousel/owl.carousel.css'), [], [],
                        '1.0.0')
                    ->add('simple-slider-css', asset('vendor/core/plugins/simple-slider/css/simple-slider.css'), [],
                        [], '1.0.0')
                    ->add('carousel',
                        asset('vendor/core/plugins/simple-slider/libraries/owl-carousel/owl.carousel.js'),
                        ['jquery'], [], '1.0.0')
                    ->add('simple-slider-js', asset('vendor/core/plugins/simple-slider/js/simple-slider.js'),
                        ['jquery'], [], '1.0.0');
            }

            if (function_exists('shortcode')) {
                add_shortcode('simple-slider',
                    trans('plugins/simple-slider::simple-slider.simple_slider_shortcode_name'),
                    trans('plugins/simple-slider::simple-slider.simple_slider_shortcode_description'),
                    [$this, 'render']);

                shortcode()->setAdminConfig('simple-slider', function () {
                    $sliders = $this->app->make(SimpleSliderInterface::class)->allBy(['status' => BaseStatusEnum::PUBLISHED]);

                    return view('plugins/simple-slider::partials.simple-slider-admin-config', compact('sliders'))->render();
                });
            }

            add_filter(BASE_FILTER_AFTER_SETTING_CONTENT, [$this, 'addSettings'], 301);
        });
    }

    /**
     * @param Shortcode $shortcode
     * @return null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function render($shortcode)
    {
        $slider = $this->app->make(SimpleSliderInterface::class)->getFirstBy([
            'key'    => $shortcode->key,
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        if (empty($slider)) {
            return null;
        }

        return view(apply_filters(SIMPLE_SLIDER_VIEW_TEMPLATE, 'plugins/simple-slider::sliders'), ['sliders' => $slider->sliderItems]);
    }

    /**
     * @param null $data
     * @return string
     * @throws \Throwable
     */
    public function addSettings($data = null)
    {
        return $data . view('plugins/simple-slider::setting')->render();
    }
}

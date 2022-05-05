<?php

namespace Botble\SimpleSlider\Providers;

use Illuminate\Routing\Events\RouteMatched;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\SimpleSlider\Models\SimpleSliderItem;
use Botble\SimpleSlider\Repositories\Caches\SimpleSliderItemCacheDecorator;
use Botble\SimpleSlider\Repositories\Eloquent\SimpleSliderItemRepository;
use Botble\SimpleSlider\Repositories\Interfaces\SimpleSliderItemInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Botble\SimpleSlider\Repositories\Caches\SimpleSliderCacheDecorator;
use Botble\SimpleSlider\Repositories\Eloquent\SimpleSliderRepository;
use Botble\SimpleSlider\Repositories\Interfaces\SimpleSliderInterface;
use Botble\Base\Supports\Helper;
use Language;

class SimpleSliderServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(SimpleSliderInterface::class, function () {
            return new SimpleSliderCacheDecorator(new SimpleSliderRepository(new SimpleSlider));
        });

        $this->app->bind(SimpleSliderItemInterface::class, function () {
            return new SimpleSliderItemCacheDecorator(new SimpleSliderItemRepository(new SimpleSliderItem));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/simple-slider')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-simple-slider',
                'priority'    => 100,
                'parent_id'   => null,
                'name'        => 'plugins/simple-slider::simple-slider.menu',
                'icon'        => 'far fa-image',
                'url'         => route('simple-slider.index'),
                'permissions' => ['simple-slider.index'],
            ]);
        });

        $this->app->booted(function () {
            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                Language::registerModule([SimpleSlider::class]);
            }

            $this->app->register(HookServiceProvider::class);
        });
    }
}

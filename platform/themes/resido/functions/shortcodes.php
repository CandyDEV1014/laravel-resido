<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\RealEstate\Repositories\Interfaces\PackageInterface;
use Botble\Theme\Supports\ThemeSupport;
use Botble\RealEstate\Repositories\Interfaces\CategoryInterface;

app()->booted(function () {

    ThemeSupport::registerGoogleMapsShortcode();
    ThemeSupport::registerYoutubeShortcode();

    if (is_plugin_active('real-estate')) {
        // Top properties
        add_shortcode(
            'properties-slide',
            __('Properties slide'),
            __('Properties slide'),
            function ($shortcode) {
                $number_of_properties_for_top = (int) theme_option('number_of_properties_for_top', 4);
                $properties = get_properties($number_of_properties_for_top, $shortcode->type ?? '');

                return Theme::partial('shortcodes.properties-slide', [
                    'title'       => $shortcode->title,
                    'description' => $shortcode->content,
                    'properties'  => $properties,
                ]);
            }
        );

        shortcode()->setAdminConfig('properties-slide', function ($attributes, $content) {
            return Theme::partial('shortcodes.properties-slide-admin-config', compact('attributes', 'content'));
        });

        // Featured properties
        add_shortcode(
            'featured-properties',
            __('Featured properties'),
            __('Featured properties'),
            function ($shortcode) {
                $number_of_properties_for_featured = (int) theme_option('number_of_properties_for_featured', 4);
                $properties = get_properties_featured($number_of_properties_for_featured, $shortcode->type ?? '');

                return Theme::partial('shortcodes.featured-properties', [
                    'title'       => $shortcode->title,
                    'description' => $shortcode->content,
                    'properties'  => $properties,
                    'style'       => $shortcode->style ?? '1',
                ]);
            }
        );

        shortcode()->setAdminConfig('featured-properties', function ($attributes, $content) {
            return Theme::partial('shortcodes.featured-properties-admin-config', compact('attributes', 'content'));
        });

        // New properties
        add_shortcode(
            'new-properties-slide',
            __('New Properties slide'),
            __('New Properties slide'),
            function ($shortcode) {
                $number_of_properties_for_new = (int) theme_option('number_of_properties_for_new', 4);
                $properties = get_properties_new($number_of_properties_for_new, $shortcode->type ?? '');

                return Theme::partial('shortcodes.new-properties-slide', [
                    'title'       => $shortcode->title,
                    'description' => $shortcode->content,
                    'properties'  => $properties,
                ]);
            }
        );

        shortcode()->setAdminConfig('new-properties-slide', function ($attributes, $content) {
            return Theme::partial('shortcodes.new-properties-slide-admin-config', compact('attributes', 'content'));
        });

        // Sale properties
        add_shortcode(
            'sale-properties-slide',
            __('Sale Properties slide'),
            __('Sale Properties slide'),
            function ($shortcode) {
                $number_of_properties_for_sale = (int) theme_option('number_of_properties_for_sale', 3);
                $properties = get_properties_sale($number_of_properties_for_sale, $shortcode->type ?? '');

                return Theme::partial('shortcodes.sale-properties-slide', [
                    'title'       => $shortcode->title,
                    'description' => $shortcode->content,
                    'properties'  => $properties,
                ]);
            }
        );

        shortcode()->setAdminConfig('sale-properties-slide', function ($attributes, $content) {
            return Theme::partial('shortcodes.sale-properties-slide-admin-config', compact('attributes', 'content'));
        });

        // Rent properties
        add_shortcode(
            'rent-properties-slide',
            __('Rent Properties slide'),
            __('Rent Properties slide'),
            function ($shortcode) {
                $number_of_properties_for_rent = (int) theme_option('number_of_properties_for_rent', 3);
                $properties = get_properties_rent($number_of_properties_for_rent, $shortcode->type ?? '');

                return Theme::partial('shortcodes.rent-properties-slide', [
                    'title'       => $shortcode->title,
                    'description' => $shortcode->content,
                    'properties'  => $properties,
                ]);
            }
        );

        shortcode()->setAdminConfig('rent-properties-slide', function ($attributes, $content) {
            return Theme::partial('shortcodes.rent-properties-slide-admin-config', compact('attributes', 'content'));
        });

        // Find by location
        add_shortcode(
            'properties-by-locations',
            __('Find By Locations'),
            __('Find By Locations'),
            function ($shortcode) {
                return Theme::partial('shortcodes.properties-by-locations', [
                    'title'       => $shortcode->title,
                    'description' => $shortcode->content,
                ]);
            }
        );

        shortcode()->setAdminConfig('properties-by-locations', function ($attributes, $content) {
            return Theme::partial('shortcodes.properties-by-locations-admin-config', compact('attributes', 'content'));
        });

        // Featured agent
        add_shortcode('agent-slider-block', 'Agent Slider Block', 'Custom block', function($shortcode) {

            $myBlock = get_agents();
            
            return Theme::partial('shortcodes.agent-slider', [
                'title'       => $shortcode->title,
                'description' => $shortcode->description,
                'myBlock'     => $myBlock,
            ]);
            
            // return view('shortcodes.agent-slider')->render();
        });
        shortcode()->setAdminConfig('agent-slider-block', function ($attributes, $content) {
            return Theme::partial('shortcodes.agent-slider-admin-config', compact('attributes', 'content'));
        });

        // blog
        if (is_plugin_active('blog')) {
            add_shortcode('latest-news', __('Latest news'), __('Latest news'), function ($shortcode) {
                $number_of_latest_news = (int) theme_option('number_of_latest_news', 3);

                $posts = get_all_posts(true, $number_of_latest_news);
                return Theme::partial('shortcodes.latest-news', [
                    'title'       => $shortcode->title,
                    'description' => $shortcode->content,
                    'posts'       => $posts,
                ]);
            });
            shortcode()->setAdminConfig('latest-news', function ($attributes, $content) {
                return Theme::partial('shortcodes.latest-news-admin-config', compact('attributes', 'content'));
            });
        }
        
        add_shortcode(
            'featured-agents',
            __('Featured Agents'),
            __('Featured Agents'),
            function ($shortcode) {
                return Theme::partial('shortcodes.featured-agents', [
                    'title'       => $shortcode->title,
                    'description' => $shortcode->content,
                ]);
            }
        );

        
        shortcode()->setAdminConfig('featured-agents', function ($attributes, $content) {
            return Theme::partial('shortcodes.featured-agents-admin-config', compact('attributes', 'content'));
        });
        
        add_shortcode('hero-banner', __('Hero banner'), __('Hero banner'), function ($shortcode) {
            $style = $shortcode->style ?? 1;
            $title = theme_option('hero_banner_title', $shortcode->title);
            $bg = theme_option('hero_banner_background', $shortcode->bg);
            return Theme::partial('shortcodes.hero-banner-style-' . $style, [
                'title'       => $title,
                'description' => $shortcode->content ?? '',
                'bg'          => $bg,
                'overlay'     => (int)$shortcode->overlay ?? 0,
            ]);
        });

        shortcode()->setAdminConfig('hero-banner', function ($attributes, $content) {
            return Theme::partial('shortcodes.hero-banner-admin-config', compact('attributes', 'content'));
        });

        // Properties hero slide
        add_shortcode(
            'properties-hero-slide',
            __('Properties hero slide'),
            __('Properties hero slide'),
            function ($shortcode) {
                $properties = get_properties_featured($shortcode->limit ?? 6, $shortcode->type ?? '');
                return Theme::partial('shortcodes.properties-hero-slide', [
                    'properties' => $properties,
                ]);
            }
        );

        shortcode()->setAdminConfig('properties-hero-slide', function ($attributes, $content) {
            return Theme::partial('shortcodes.properties-hero-slide-admin-config', compact('attributes', 'content'));
        });

        // Hero banner map
        add_shortcode('hero-banner-style-map', __('Hero banner map'), __('Hero banner map'), function () {
            return Theme::partial('shortcodes.hero-banner-style-map');
        });

        // Section cover banner
        add_shortcode('cover-banner', __('Cover banner'), __('Cover banner'), function ($shortcode) {
            return Theme::partial('shortcodes.cover-banner', [
                'title'       => $shortcode->title ?? '',
                'description' => $shortcode->content ?? '',
                'bg'          => $shortcode->bg ?? '',
                'btnText'     => $shortcode->btntext ?? '',
                'btnLink'     => $shortcode->btnlink ?? '',
            ]);
        });

        shortcode()->setAdminConfig('cover-banner', function ($attributes, $content) {
            return Theme::partial('shortcodes.cover-banner-admin-config', compact('attributes', 'content'));
        });

        add_shortcode('testimonials', __('Testimonials'), __('Testimonials'), function ($shortcode) {
            return Theme::partial('shortcodes.testimonials', [
                'title'       => $shortcode->title,
                'description' => $shortcode->description,
            ]);
        });

        shortcode()->setAdminConfig('testimonials', function ($attributes, $content) {
            return Theme::partial('shortcodes.testimonials-admin-config', compact('attributes', 'content'));
        });

        add_shortcode('our-packages', __('Our Packages'), __('Our Packages'), function ($shortcode) {
            $packages = app(PackageInterface::class)->allBy(['status' => BaseStatusEnum::PUBLISHED]);

            return Theme::partial('shortcodes.our-packages', [
                'title'       => $shortcode->title,
                'description' => $shortcode->description,
                'packages'    => $packages,
            ]);
        });

        shortcode()->setAdminConfig('our-packages', function ($attributes, $content) {
            return Theme::partial('shortcodes.our-packages-admin-config', compact('attributes', 'content'));
        });

        //custom
        add_shortcode('page-our-packages', __('Our Page Packages'), __('Our Packages'), function ($shortcode) {
            $packages = app(PackageInterface::class)->advancedGet([
                'order_by' => ['order' => 'DESC'], 
                'condition' => ['status' => BaseStatusEnum::PUBLISHED]
            ]);
            
            return Theme::partial('shortcodes.page-our-packages', [
                'packages'    => $packages,
            ]);
        });
        //end custom

        if (is_plugin_active('contact')) {
            add_filter(CONTACT_FORM_TEMPLATE_VIEW, function () {
                return Theme::getThemeNamespace() . '::partials.shortcodes.contact-form';
            }, 120);
        }

        add_shortcode('recently-viewed-properties', __('Recent Viewed Properties'), __('Recently Viewed Properties'),
            function ($shortcode) {

                $cookieName = App::getLocale() . '_recently_viewed_properties';

                $jsonRecentlyViewedProperties = null;
                if (isset($_COOKIE[$cookieName])) {
                    $jsonRecentlyViewedProperties = $_COOKIE[$cookieName];
                }
                $arrValue = collect(json_decode($jsonRecentlyViewedProperties, true))->flatten()->all();

                if (count($arrValue) > 0) {
                    return Theme::partial('shortcodes.recently-viewed-properties', [
                        'title'       => $shortcode->title,
                        'description' => $shortcode->content,
                        'subtitle'    => $shortcode->subtitle,
                    ]);
                }

                return null;
            });

        shortcode()->setAdminConfig('recently-viewed-properties', function ($attributes, $content) {
            return Theme::partial('shortcodes.recently-viewed-properties-admin-config', compact('attributes', 'content'));
        });
    }
});

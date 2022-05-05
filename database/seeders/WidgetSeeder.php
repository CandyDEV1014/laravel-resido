<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Widget\Models\Widget;
use Theme;

class WidgetSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Widget::truncate();
        $theme = Theme::getThemeName();
        $themeVi = $theme . '-vi';

        $data = [
            [
                'widget_id'  => 'ShortcodeWidget',
                'sidebar_id' => 'footer_sidebar_1',
                'theme'      => $theme,
                'position'   => 0,
                'data'       => '{"id":"ShortcodeWidget","content":"[static-block alias=\"sign-up\"][\/static-block]"}',
            ],
            [
                'widget_id'  => 'CustomMenuWidget',
                'sidebar_id' => 'footer_sidebar_2',
                'theme'      => $theme,
                'position'   => 0,
                'data'       => '{"id":"CustomMenuWidget","name":"About","menu_id":"about"}',
            ],
            [
                'widget_id'  => 'CustomMenuWidget',
                'sidebar_id' => 'footer_sidebar_2',
                'theme'      => $theme,
                'position'   => 1,
                'data'       => '{"id":"CustomMenuWidget","name":"MORE INFORMATION","menu_id":"more-information"}',
            ],
            [
                'widget_id'  => 'CustomMenuWidget',
                'sidebar_id' => 'footer_sidebar_2',
                'theme'      => $theme,
                'position'   => 2,
                'data'       => '{"id":"CustomMenuWidget","name":"NEWS","menu_id":"news"}',
            ],
            [
                'widget_id'  => 'ShortcodeWidget',
                'sidebar_id' => 'footer_sidebar_3',
                'theme'      => $theme,
                'position'   => 0,
                'data'       => '{"id":"ShortcodeWidget","content":"[static-block alias=\"download-app-footer\"][\/static-block]"}',
            ],
            [
                'widget_id'  => 'CategoriesWidget',
                'sidebar_id' => 'primary_sidebar',
                'theme'      => $theme,
                'position'   => 0,
                'data'       => '{"id":"CategoriesWidget","name":"Categories"}',
            ],
            [
                'widget_id'  => 'RecentPostsWidget',
                'sidebar_id' => 'primary_sidebar',
                'theme'      => $theme,
                'position'   => 0,
                'data'       => '{"id":"RecentPostsWidget","name":"Recent posts","number_display":"5"}',
            ],
            [
                'widget_id'  => 'FeaturedPropertiesWidget',
                'sidebar_id' => 'primary_sidebar',
                'theme'      => $theme,
                'position'   => 0,
                'data'       => '{"id":"FeaturedPropertiesWidget","name":"Featured properties","number_display":"5"}',
            ],
            [
                'widget_id'  => 'NewsletterWidget',
                'sidebar_id' => 'footer_sidebar_3',
                'position'   => 1,
                'theme'      => $theme,
                'data'       => '{"id":"NewsletterWidget","name":"Subscribe"}'
            ],
            [
                'widget_id'  => 'ShortcodeWidget',
                'sidebar_id' => 'footer_sidebar_1',
                'theme'      => $themeVi,
                'position'   => 0,
                'data'       => '{"id":"ShortcodeWidget","content":"[static-block alias=\"sign-up\"][\/static-block]"}',
            ],
            [
                'widget_id'  => 'CustomMenuWidget',
                'sidebar_id' => 'footer_sidebar_2',
                'theme'      => $themeVi,
                'position'   => 0,
                'data'       => '{"id":"CustomMenuWidget","name":"About","menu_id":"ve-chung-toi"}',
            ],
            [
                'widget_id'  => 'CustomMenuWidget',
                'sidebar_id' => 'footer_sidebar_2',
                'theme'      => $themeVi,
                'position'   => 1,
                'data'       => '{"id":"CustomMenuWidget","name":"MORE INFORMATION","menu_id":"thong-tin-them"}',
            ],
            [
                'widget_id'  => 'CustomMenuWidget',
                'sidebar_id' => 'footer_sidebar_2',
                'theme'      => $themeVi,
                'position'   => 2,
                'data'       => '{"id":"CustomMenuWidget","name":"NEWS","menu_id":"tin-tuc"}',
            ],
            [
                'widget_id'  => 'ShortcodeWidget',
                'sidebar_id' => 'footer_sidebar_3',
                'theme'      => $themeVi,
                'position'   => 0,
                'data'       => '{"id":"ShortcodeWidget","content":"[static-block alias=\"download-app-footer\"][\/static-block]"}',
            ],
            [
                'widget_id'  => 'CategoriesWidget',
                'sidebar_id' => 'primary_sidebar',
                'theme'      => $themeVi,
                'position'   => 0,
                'data'       => '{"id":"CategoriesWidget","name":"Danh mục"}',
            ],
            [
                'widget_id'  => 'RecentPostsWidget',
                'sidebar_id' => 'primary_sidebar',
                'theme'      => $themeVi,
                'position'   => 0,
                'data'       => '{"id":"RecentPostsWidget","name":"Bài viết gần đây","number_display":"5"}',
            ],
            [
                'widget_id'  => 'FeaturedPropertiesWidget',
                'sidebar_id' => 'primary_sidebar',
                'theme'      => $themeVi,
                'position'   => 0,
                'data'       => '{"id":"FeaturedPropertiesWidget","name":"Featured properties","number_display":"5"}',
            ],
            [
                'widget_id'  => 'NewsletterWidget',
                'sidebar_id' => 'footer_sidebar_3',
                'theme'      => $themeVi,
                'position'   => 1,
                'data'       => '{"id":"NewsletterWidget","name":"Subscribe"}'
            ],
        ];

        foreach ($data as $item) {
            Widget::insert($item);
        }
    }
}

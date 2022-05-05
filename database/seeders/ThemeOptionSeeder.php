<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Setting\Models\Setting as SettingModel;
use Theme;

class ThemeOptionSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->uploadFiles('general');

        $theme = Theme::getThemeName();

        SettingModel::whereIn('key', ['show_admin_bar', 'theme', 'admin_logo', 'admin_favicon'])->delete();

        SettingModel::insertOrIgnore([
            [
                'key'   => 'show_admin_bar',
                'value' => '1',
            ],
            [
                'key'   => 'theme',
                'value' => $theme,
            ],
            [
                'key'   => 'admin_logo',
                'value' => 'general/logo-light.png',
            ],
            [
                'key'   => 'admin_favicon',
                'value' => 'general/favicon.png',
            ],
        ]);

        $data = [
            'en_US' => [
                [
                    'key'   => 'skin',
                    'value' => 'blue-skin',
                ],
                [
                    'key'   => 'font_heading',
                    'value' => 'Jost',
                ],
                [
                    'key'   => 'font_body',
                    'value' => 'Muli',
                ],
                [
                    'key'   => 'cookie_consent_message',
                    'value' => 'Your experience on this site will be improved by allowing cookies ',
                ],
                [
                    'key'   => 'cookie_consent_learn_more_url',
                    'value' => url('cookie-policy'),
                ],
                [
                    'key'   => 'cookie_consent_learn_more_text',
                    'value' => 'Cookie Policy',
                ],
                [
                    'key'   => 'copyright',
                    'value' => '©' . now()->format('Y') . ' Resido. All rights reserved by TheSky9.',
                ],
                [
                    'key'   => 'homepage_id',
                    'value' => '1',
                ],
                [
                    'key'   => 'blog_page_id',
                    'value' => '12',
                ],
                [
                    'key'   => 'logo',
                    'value' => 'general/logo.png',
                ],
                [
                    'key'   => 'favicon',
                    'value' => 'general/favicon.png',
                ],
                [
                    'key'   => 'logo_white',
                    'value' => 'general/logo-light.png',
                ],
                [
                    'key'   => 'img_loading',
                    'value' => 'general/img-loading.jpg',
                ],
                [
                    'key'   => 'properties_page_layout',
                    'value' => 'full',
                ],
                [
                    'key'   => 'property_header_layout',
                    'value' => 'layout-1',
                ],
                [
                    'key'   => 'site_title',
                    'value' => 'Resido - Laravel Real Estate Multilingual Syste',
                ],
                [
                    'key'   => 'seo_description',
                    'value' => 'Resido is a premium real estate related websites build on Laravel. With an advanced admin dashboard that will help you create a local or global real-estate directory site.',
                ],
                [
                    'key'   => 'seo_og_image',
                    'value' => 'general/screenshot.png',
                ],
                [
                    'key'   => 'address',
                    'value' => 'Collins Street West, Victoria 8007, Australia.',
                ],
                [
                    'key'   => 'hotline',
                    'value' => '+1 246-345-0695',
                ],
                [
                    'key'   => 'email',
                    'value' => 'info@example.com',
                ],
                [
                    'key'   => 'about-us',
                    'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                ],
            ],

            'vi' => [
                [
                    'key'   => 'skin',
                    'value' => 'blue-skin',
                ],
                [
                    'key'   => 'font_heading',
                    'value' => 'Montserrat',
                ],
                [
                    'key'   => 'font_body',
                    'value' => 'Montserrat',
                ],
                [
                    'key'   => 'copyright',
                    'value' => '©' . now()->format('Y') . ' Resido. Tất cả quyền đã được bảo hộ bởi TheSky9.',
                ],
                [
                    'key'   => 'cookie_consent_message',
                    'value' => 'Trải nghiệm của bạn trên trang web này sẽ được cải thiện bằng cách cho phép cookie ',
                ],
                [
                    'key'   => 'cookie_consent_learn_more_url',
                    'value' => url('cookie-policy'),
                ],
                [
                    'key'   => 'cookie_consent_learn_more_text',
                    'value' => 'Cookie Policy',
                ],
                [
                    'key'   => 'homepage_id',
                    'value' => '1',
                ],
                [
                    'key'   => 'blog_page_id',
                    'value' => '28',
                ],
                [
                    'key'   => 'logo',
                    'value' => 'general/logo.png',
                ],
                [
                    'key'   => 'logo_white',
                    'value' => 'general/logo-light.png',
                ],
                [
                    'key'   => 'img_loading',
                    'value' => 'general/img-loading.jpg',
                ],
                [
                    'key'   => 'properties_page_layout',
                    'value' => 'full',
                ],
                [
                    'key'   => 'property_header_layout',
                    'value' => 'layout-1',
                ],
                [
                    'key'   => 'site_title',
                    'value' => 'Resido - Laravel Real Estate Multilingual Syste',
                ],
                [
                    'key'   => 'seo_description',
                    'value' => 'Resido is a premium real estate related websites build on Laravel. With an advanced admin dashboard that will help you create a local or global real-estate directory site.',
                ],
                [
                    'key'   => 'seo_og_image',
                    'value' => 'general/screenshot.png',
                ],
                [
                    'key'   => 'address',
                    'value' => 'Collins Street West, Victoria 8007, Australia.',
                ],
                [
                    'key'   => 'hotline',
                    'value' => '+1 246-345-0695',
                ],
                [
                    'key'   => 'email',
                    'value' => 'info@example.com',
                ],
                [
                    'key'   => 'about-us',
                    'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                ],
            ],
        ];

        foreach ($data as $locale => $options) {
            foreach ($options as $item) {
                $item['key'] = 'theme-' . $theme . '-' . ($locale != 'en_US' ? $locale . '-' : '') . $item['key'];

                SettingModel::where('key', $item['key'])->delete();

                SettingModel::create($item);
            }
        }

        SettingModel::whereIn('key', [
            'theme-' . $theme . '-social_links',
            'theme-vi-' . $theme . '-social_links',
            'theme-' . $theme . '-min_price',
            'theme-vi-' . $theme . '-min_price',
            'theme-' . $theme . '-max_price',
            'theme-vi-' . $theme . '-max_price',
            'theme-' . $theme . '-bed_room',
            'theme-vi-' . $theme . '-bed_room',
        ])->delete();

        $socialLinks = [
            [
                [
                    'key'   => 'social-name',
                    'value' => 'Facebook',
                ],
                [
                    'key'   => 'social-icon',
                    'value' => 'ti-facebook',
                ],
                [
                    'key'   => 'social-url',
                    'value' => 'https://www.facebook.com/',
                ],
            ],
            [
                [
                    'key'   => 'social-name',
                    'value' => 'Twitter',
                ],
                [
                    'key'   => 'social-icon',
                    'value' => 'ti-twitter',
                ],
                [
                    'key'   => 'social-url',
                    'value' => 'https://www.twitter.com/',
                ],
            ],
            [
                [
                    'key'   => 'social-name',
                    'value' => 'Instagram',
                ],
                [
                    'key'   => 'social-icon',
                    'value' => 'ti-instagram',
                ],
                [
                    'key'   => 'social-url',
                    'value' => 'https://www.instagram.com/',
                ],
            ],
            [
                [
                    'key'   => 'social-name',
                    'value' => 'Linkedin',
                ],
                [
                    'key'   => 'social-icon',
                    'value' => 'ti-linkedin',
                ],
                [
                    'key'   => 'social-url',
                    'value' => 'https://www.linkedin.com/',
                ],
            ],
            [
                [
                    'key'   => 'social-name',
                    'value' => 'Pinterest',
                ],
                [
                    'key'   => 'social-icon',
                    'value' => 'ti-pinterest',
                ],
                [
                    'key'   => 'social-url',
                    'value' => 'https://www.pinterest.com/',
                ],
            ],
        ];

        SettingModel::insertOrIgnore([
            'key'   => 'theme-' . $theme . '-social_links',
            'value' => json_encode($socialLinks),
        ]);

        SettingModel::insertOrIgnore([
            'key'   => 'theme-vi-' . $theme . '-social_links',
            'value' => json_encode($socialLinks),
        ]);

        $minPrices = [
            'en_US' => [
                [
                    [
                        'key'   => 'label',
                        'value' => 500,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 500,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 1000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 1000,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 2000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 2000,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 5000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 5000,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 10000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 10000,
                    ],
                ],
            ],
            'vi'    => [
                [
                    [
                        'key'   => 'label',
                        'value' => 500,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 500,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 1000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 1000,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 2000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 2000,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 5000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 5000,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 10000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 10000,
                    ],
                ],
            ],
        ];

        SettingModel::insertOrIgnore([
            'key'   => 'theme-' . $theme . '-min_price',
            'value' => json_encode($minPrices['en_US']),
        ]);

        SettingModel::insertOrIgnore([
            'key'   => 'theme-vi-' . $theme . '-min_price',
            'value' => json_encode($minPrices['vi']),
        ]);

        $maxPrices = [
            'en_US' => [
                [
                    [
                        'key'   => 'label',
                        'value' => 1000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 1000,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 2000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 2000,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 5000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 5000,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 10000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 10000,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 50000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 50000,
                    ],
                ],
            ],
            'vi'    => [
                [
                    [
                        'key'   => 'label',
                        'value' => 1000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 1000,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 2000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 2000,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 5000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 5000,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 10000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 10000,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 50000,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 50000,
                    ],
                ],
            ],
        ];

        SettingModel::insertOrIgnore([
            'key'   => 'theme-' . $theme . '-max_price',
            'value' => json_encode($maxPrices['en_US']),
        ]);

        SettingModel::insertOrIgnore([
            'key'   => 'theme-vi-' . $theme . '-max_price',
            'value' => json_encode($maxPrices['vi']),
        ]);

        $rooms = [
            'en_US' => [
                [
                    [
                        'key'   => 'label',
                        'value' => 1,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 1,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 2,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 2,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 3,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 3,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 4,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 4,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 5,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 5,
                    ],
                ],
            ],
            'vi'    => [
                [
                    [
                        'key'   => 'label',
                        'value' => 1,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 1,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 2,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 2,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 3,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 3,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 4,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 4,
                    ],
                ],
                [
                    [
                        'key'   => 'label',
                        'value' => 5,
                    ],
                    [
                        'key'   => 'value',
                        'value' => 5,
                    ],
                ],
            ],
        ];

        SettingModel::insertOrIgnore([
            'key'   => 'theme-' . $theme . '-bedroom',
            'value' => json_encode($rooms['en_US']),
        ]);

        SettingModel::insertOrIgnore([
            'key'   => 'theme-vi-' . $theme . '-bedroom',
            'value' => json_encode($rooms['vi']),
        ]);
        
        SettingModel::insertOrIgnore([
            'key'   => 'theme-' . $theme . '-bathroom',
            'value' => json_encode($rooms['en_US']),
        ]);

        SettingModel::insertOrIgnore([
            'key'   => 'theme-vi-' . $theme . '-bathroom',
            'value' => json_encode($rooms['vi']),
        ]);
    }
}

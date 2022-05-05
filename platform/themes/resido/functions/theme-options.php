<?php

app()->booted(function () {
    theme_option()
        ->setField([
            'id'         => 'copyright',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'text',
            'label'      => __('Copyright'),
            'attributes' => [
                'name'    => 'copyright',
                'value'   => '© 2022 Find-Home. All right reserved.',
                'options' => [
                    'class'        => 'form-control',
                    'placeholder'  => __('Change copyright'),
                    'data-counter' => 250,
                ],
            ],
            'helper'     => __('Copyright on footer of site'),
        ])
        ->setField([
            'id'         => 'preloader_enabled',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'select',
            'label'      => __('Enable Preloader?'),
            'attributes' => [
                'name'    => 'preloader_enabled',
                'list'    => [
                    'yes' => trans('core/base::base.yes'),
                    'no'  => trans('core/base::base.no'),
                ],
                'value'   => 'no',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'about-us',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'textarea',
            'label'      => __('About us'),
            'attributes' => [
                'name'    => 'about-us',
                'value'   => null,
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'hotline',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'text',
            'label'      => __('Hotline'),
            'attributes' => [
                'name'    => 'hotline',
                'value'   => null,
                'options' => [
                    'class'        => 'form-control',
                    'placeholder'  => 'Hotline',
                    'data-counter' => 30,
                ],
            ],
        ])
        ->setField([
            'id'         => 'address',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'text',
            'label'      => __('Address'),
            'attributes' => [
                'name'    => 'address',
                'value'   => null,
                'options' => [
                    'class'        => 'form-control',
                    'placeholder'  => 'Address',
                    'data-counter' => 120,
                ],
            ],
        ])
        ->setField([
            'id'         => 'email',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'email',
            'label'      => __('Email'),
            'attributes' => [
                'name'    => 'email',
                'value'   => null,
                'options' => [
                    'class'        => 'form-control',
                    'placeholder'  => 'Email',
                    'data-counter' => 120,
                ],
            ],
        ])
        ->setField([
            'id'         => 'enable_sticky_header',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'select',
            'label'      => __('Enable sticky header?'),
            'attributes' => [
                'name'    => 'enable_sticky_header',
                'list'    => [
                    'yes' => trans('core/base::base.yes'),
                    'no'  => trans('core/base::base.no'),
                ],
                'value'   => 'yes',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'show_map_on_properties_page',
            'section_id' => 'opt-text-subsection-general',
            'type'       => 'select',
            'label'      => __('Shop map on properties page?'),
            'attributes' => [
                'name'    => 'show_map_on_properties_page',
                'list'    => [
                    'yes' => trans('core/base::base.yes'),
                    'no'  => trans('core/base::base.no'),
                ],
                'value'   => 'yes',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setSection([
            'title'      => __('Style'),
            'desc'       => __('Style of page'),
            'id'         => 'opt-text-subsection-style',
            'subsection' => true,
            'icon'       => 'fa fa-bars',
        ])
        ->setField([
            'id'         => 'skin',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'select',
            'label'      => __('Skin'),
            'attributes' => [
                'name'    => 'skin',
                'list'    => [
                    'red-skin'          => __('Red skin'),
                    'green-skin'        => __('Green skin'),
                    'blue-skin'         => __('Blue skin'),
                    'yellow-skin'       => __('Yellow skin'),
                    'darkblue-skin'     => __('Darkblue skin'),
                    'oceangreen-skin'   => __('Oceangreen skin'),
                    'purple-skin'       => __('Purple skin'),
                    'goodred-skin'      => __('Goodred skin'),
                    'goodgreen-skin'    => __('Goodgreen skin'),
                    'blue2-skin'        => __('Blue 2 skin'),
                ],
                'value'   => 'yes',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'font_heading',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'googleFonts',
            'label'      => __('Font heading'),
            'attributes' => [
                'name'  => 'font_heading',
                'value' => 'Jost',
            ],
        ])
        ->setField([
            'id'         => 'font_body',
            'section_id' => 'opt-text-subsection-style',
            'type'       => 'googleFonts',
            'label'      => __('Font body'),
            'attributes' => [
                'name'  => 'font_body',
                'value' => 'Muli',
            ],
        ])
        ->setSection([
            'title'      => __('Social'),
            'desc'       => __('Social links'),
            'id'         => 'opt-text-subsection-social-links',
            'subsection' => true,
            'icon'       => 'fa fa-share-alt',
        ])
        ->setField([
            'id'         => 'social_links',
            'section_id' => 'opt-text-subsection-social-links',
            'type'       => 'repeater',
            'label'      => __('Social links'),
            'attributes' => [
                'name'   => 'social_links',
                'value'  => null,
                'fields' => [
                    [
                        'type'       => 'text',
                        'label'      => __('Name'),
                        'attributes' => [
                            'name'    => 'social-name',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'themeIcon',
                        'label'      => __('Icon'),
                        'attributes' => [
                            'name'    => 'social-icon',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('URL'),
                        'attributes' => [
                            'name'    => 'social-url',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                ],
            ],
        ])
        ->setSection([
            'title'      => __('Content'),
            'desc'       => __('Theme options for content'),
            'id'         => 'opt-text-subsection-homepage',
            'subsection' => true,
            'icon'       => 'fa fa-edit',
            'fields'     => [
                [
                    'id'         => 'number_of_properties_for_top',
                    'type'       => 'number',
                    'label'      => __('Number of properties for top on homepage'),
                    'attributes' => [
                        'name'    => 'number_of_properties_for_top',
                        'value'   => 10,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_properties_for_featured',
                    'type'       => 'number',
                    'label'      => __('Number of properties for featured on homepage'),
                    'attributes' => [
                        'name'    => 'number_of_properties_for_featured',
                        'value'   => 10,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_properties_for_new',
                    'type'       => 'number',
                    'label'      => __('Number of properties for newly added on homepage'),
                    'attributes' => [
                        'name'    => 'number_of_properties_for_new',
                        'value'   => 10,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_properties_for_sale',
                    'type'       => 'number',
                    'label'      => __('Number of properties for sale on homepage'),
                    'attributes' => [
                        'name'    => 'number_of_properties_for_sale',
                        'value'   => 10,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_properties_for_rent',
                    'type'       => 'number',
                    'label'      => __('Number of properties for rent on homepage'),
                    'attributes' => [
                        'name'    => 'number_of_properties_for_rent',
                        'value'   => 10,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_featured_cities',
                    'type'       => 'number',
                    'label'      => __('Number of featured cities on homepage'),
                    'attributes' => [
                        'name'    => 'number_of_featured_cities',
                        'value'   => 10,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_featured_agents',
                    'type'       => 'number',
                    'label'      => __('Number of featured agents on homepage'),
                    'attributes' => [
                        'name'    => 'number_of_featured_agents',
                        'value'   => 10,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_latest_news',
                    'type'       => 'number',
                    'label'      => __('Number of latest news on homepage'),
                    'attributes' => [
                        'name'    => 'number_of_latest_news',
                        'value'   => 10,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_properties_per_page',
                    'type'       => 'number',
                    'label'      => __('Number of properties per page'),
                    'attributes' => [
                        'name'    => 'number_of_properties_per_page',
                        'value'   => 15,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_related_properties',
                    'type'       => 'number',
                    'label'      => __('Number of related properties'),
                    'attributes' => [
                        'name'    => 'number_of_related_properties',
                        'value'   => 8,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'number_of_recently_viewed_properties',
                    'type'       => 'number',
                    'label'      => __('Number of recently viewed properties'),
                    'attributes' => [
                        'name'    => 'number_of_recently_viewed_properties',
                        'value'   => 3,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'hero_banner_title',
                    'type'       => 'text',
                    'label'      => __('The description for banner search block'),
                    'attributes' => [
                        'name'    => 'hero_banner_title',
                        'value'   => null,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ],
                [
                    'id'         => 'hero_banner_background',
                    'type'       => 'mediaImage',
                    'label'      => __('Top banner homepage'),
                    'attributes' => [
                        'name'  => 'hero_banner_background',
                        'value' => null,
                    ],
                ],
                [
                    'id'         => 'properties_description',
                    'type'       => 'textarea',
                    'label'      => __('The description for properties block'),
                    'attributes' => [
                        'name'    => 'properties_description',
                        'value'   => null,
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ],
                ]
            ],
        ])
        ->setField([
            'id'         => 'logo_white',
            'section_id' => 'opt-text-subsection-logo',
            'type'       => 'mediaImage',
            'label'      => 'Logo white',
            'attributes' => [
                'name'    => 'logo_white',
                'value'   => null,
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'img_loading',
            'section_id' => 'opt-text-subsection-logo',
            'type'       => 'mediaImage',
            'label'      => 'Image Loading',
            'attributes' => [
                'name'    => 'img_loading',
                'value'   => null,
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setSection([
            'title'      => __('Real estate'),
            'desc'       => __('Real estate'),
            'id'         => 'es-state',
            'subsection' => false,
            'icon'       => 'fa fa-info-circle',
            'fields'     => [],
        ])
        ->setField([
            'id'         => 'properties_page_layout',
            'section_id' => 'es-state',
            'label'      => __('Properties layouts'),
            'type'       => 'select',
            'attributes' => [
                'name'    => 'properties_page_layout',
                'list'    => ['' => trans('plugins/blog::base.select')] + get_properties_page_layout(),
                'value'   => '',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'property_header_layout',
            'section_id' => 'es-state',
            'label'      => __('Property header layouts'),
            'type'       => 'select',
            'attributes' => [
                'name'    => 'property_header_layout',
                'list'    => ['' => trans('plugins/blog::base.select')] + get_single_header_layout(),
                'value'   => 'layout-1',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ])
        ->setField([
            'id'         => 'min_price',
            'section_id' => 'es-state',
            'type'       => 'repeater',
            'label'      => __('Min price'),
            'attributes' => [
                'name'   => 'min_price',
                'value'  => null,
                'fields' => [
                    [
                        'type'       => 'text',
                        'label'      => __('Label'),
                        'attributes' => [
                            'name'    => 'key',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Value'),
                        'attributes' => [
                            'name'    => 'value',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                ],
            ],
        ])->setField([
            'id'         => 'max_price',
            'section_id' => 'es-state',
            'type'       => 'repeater',
            'label'      => __('Max price'),
            'attributes' => [
                'name'   => 'max_price',
                'value'  => null,
                'fields' => [
                    [
                        'type'       => 'text',
                        'label'      => __('Label'),
                        'attributes' => [
                            'name'    => 'key',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Value'),
                        'attributes' => [
                            'name'    => 'value',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                ],
            ],
        ])
        ->setField([
            'id'         => 'bedroom',
            'section_id' => 'es-state',
            'type'       => 'repeater',
            'label'      => __('Bed Rooms'),
            'attributes' => [
                'name'   => 'bedroom',
                'value'  => null,
                'fields' => [
                    [
                        'type'       => 'text',
                        'label'      => __('Label'),
                        'attributes' => [
                            'name'    => 'key',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Value'),
                        'attributes' => [
                            'name'    => 'value',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                ],
            ],
        ])->setField([
            'id'         => 'bathroom',
            'section_id' => 'es-state',
            'type'       => 'repeater',
            'label'      => __('Bath Rooms'),
            'attributes' => [
                'name'   => 'bathroom',
                'value'  => null,
                'fields' => [
                    [
                        'type'       => 'text',
                        'label'      => __('Label'),
                        'attributes' => [
                            'name'    => 'key',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Value'),
                        'attributes' => [
                            'name'    => 'value',
                            'value'   => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
});

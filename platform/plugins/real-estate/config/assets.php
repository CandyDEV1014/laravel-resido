<?php

return [
    'offline'        => env('ASSETS_OFFLINE', true),
    'enable_version' => env('ASSETS_ENABLE_VERSION', true),
    'version'        => env('ASSETS_VERSION', get_cms_version()),
    'scripts'        => [
        'core',
        'bootstrap',
        'fancybox',
        'toastr',
        'select2',
        'form-validation',
        'cropper',
        'custom-scrollbar',
        'stickytableheaders',
    ],
    'styles'         => [
        'fontawesome',
        'pace',
        'fancybox',
        'select2',
        'toastr',
        'custom-scrollbar',
    ],
    'resources'      => [
        'scripts' => [
            'core'              => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/core/base/js/core.js',
                ],
            ],
            'bootstrap'                => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => [
                        '/vendor/core/core/base/libraries/bootstrap.bundle.min.js',
                    ],
                ],
            ],
            'jquery-validation' => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => [
                        '/vendor/core/core/base/libraries/jquery-validation/jquery.validate.min.js',
                        '/vendor/core/core/base/libraries/jquery-validation/additional-methods.min.js',
                    ],
                ],
            ],
            'jquery-ui'         => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/core/base/libraries/jquery-ui/jquery-ui.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js',
                ],
            ],
            'fancybox'          => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/core/base/libraries/fancybox/jquery.fancybox.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js',
                ],
            ],
            'cropper'          => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => 'vendor/core/plugins/real-estate/libraries/cropper.js',
                ],
            ],
            'are-you-sure'      => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/core/base/libraries/jquery.are-you-sure/jquery.are-you-sure.js',
                ],
            ],
            'toastr'            => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/core/base/libraries/toastr/toastr.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.2/toastr.min.js',
                ],
            ],
            'datatables'        => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => [
                        '/vendor/core/core/base/libraries/datatables/media/js/jquery.dataTables.min.js',
                        '/vendor/core/core/base/libraries/datatables/media/js/dataTables.bootstrap.min.js',
                        '/vendor/core/core/base/libraries/datatables/extensions/Buttons/js/dataTables.buttons.min.js',
                        '/vendor/core/core/base/libraries/datatables/extensions/Buttons/js/buttons.bootstrap.min.js',
                        '/vendor/core/core/base/libraries/datatables/extensions/Responsive/js/dataTables.responsive.min.js',
                    ],
                ],
            ],
            'datepicker'        => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/core/base/libraries/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
                ],
            ],
            'moment'            => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/core/base/libraries/moment-with-locales.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/moment-with-locales.min.js',
                ],
            ],
            'select2'           => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/core/base/libraries/select2/js/select2.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
            ],
            'input-mask'        => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/core/base/libraries/jquery-inputmask/jquery.inputmask.bundle.min.js',
                ],
            ],
            'form-validation'   => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/core/js-validation/js/js-validation.js',
                ],
            ],
            'custom-scrollbar'   => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/core/base/libraries/mcustom-scrollbar/jquery.mCustomScrollbar.js',
                ],
            ],
            'stickytableheaders' => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/core/base/libraries/stickytableheaders/jquery.stickytableheaders.js',
                ],
            ],
            // End JS
        ],
        /* -- STYLESHEET ASSETS -- */
        'styles'  => [
            'fontawesome' => [
                'use_cdn'  => true,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/core/base/libraries/font-awesome/css/fontawesome.min.css',
                    'cdn'   => '//use.fontawesome.com/releases/v5.0.13/css/all.css',
                ],
            ],
            'core'        => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/core/base/css/core.css',
                ],
            ],
            'jquery-ui'   => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/core/base/libraries/jquery-ui/jquery-ui.min.css',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css',
                ],
            ],
            'datatables'  => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => [
                        '/vendor/core/core/base/libraries/datatables/media/css/dataTables.bootstrap.min.css',
                        '/vendor/core/core/base/libraries/datatables/extensions/Buttons/css/buttons.bootstrap.min.css',
                        '/vendor/core/core/base/libraries/datatables/extensions/Responsive/css/responsive.bootstrap.min.css',
                    ],
                ],
            ],
            'datepicker'  => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/core/base/libraries/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                ],
            ],
            'select2'     => [
                'use_cdn'  => true,
                'location' => 'header',
                'src'      => [
                    'local' => [
                        '/vendor/core/core/base/libraries/select2/css/select2.min.css',
                        '/vendor/core/core/base/libraries/select2/css/select2-bootstrap.min.css',
                    ],
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css',
                ],
            ],
            'toastr'      => [
                'use_cdn'  => true,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/core/base/libraries/toastr/toastr.min.css',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.2/toastr.min.css',
                ],
            ],
            'custom-scrollbar'   => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/core/base/libraries/mcustom-scrollbar/jquery.mCustomScrollbar.css',
                ],
            ],
        ],
    ],
];

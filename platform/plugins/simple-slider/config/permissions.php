<?php

return [
    [
        'name' => 'Simple Sliders',
        'flag' => 'simple-slider.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'simple-slider.create',
        'parent_flag' => 'simple-slider.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'simple-slider.edit',
        'parent_flag' => 'simple-slider.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'simple-slider.destroy',
        'parent_flag' => 'simple-slider.index',
    ],

    [
        'name'        => 'Slider Items',
        'flag'        => 'simple-slider-item.index',
        'parent_flag' => 'simple-slider.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'simple-slider-item.create',
        'parent_flag' => 'simple-slider-item.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'simple-slider-item.edit',
        'parent_flag' => 'simple-slider-item.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'simple-slider-item.destroy',
        'parent_flag' => 'simple-slider-item.index',
    ],
];
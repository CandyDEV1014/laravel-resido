<?php

return [
    [
        'name' => 'Testimonial',
        'flag' => 'testimonial.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'testimonial.create',
        'parent_flag' => 'testimonial.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'testimonial.edit',
        'parent_flag' => 'testimonial.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'testimonial.destroy',
        'parent_flag' => 'testimonial.index',
    ],
];
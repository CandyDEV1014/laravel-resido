<?php

return [
    [
        'name' => 'Newsletters',
        'flag' => 'newsletter.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'newsletter.destroy',
        'parent_flag' => 'newsletter.index',
    ],
];
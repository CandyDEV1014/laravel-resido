<?php

return [
    'bulk-import' => [
        'mime_types' => [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/csv',
            'application/csv',
            'text/plain',
        ],
        'mimes'      => [
            'xls',
            'xlsx',
            'csv',
        ],
    ],
    'use_language_v2' => env('LOCATION_USE_LANGUAGE_VERSION_2', false),
];

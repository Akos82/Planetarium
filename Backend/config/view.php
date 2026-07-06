<?php

return [
    'paths' => [
        base_path('../Frontend/views'),
    ],
    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),
];

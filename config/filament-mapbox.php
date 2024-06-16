<?php

return [
    'access_token' => env('MAPBOX_ACCESS_TOKEN'),
    'keys' => [
        'static_map_access_token' => env('MAPBOX_STATIC_MAP_ACCESS_TOKEN', env('MAPBOX_ACCESS_TOKEN')),
        'web_js_map_access_token' => env('MAPBOX_WEB_JS_MAP_ACCESS_TOKEN', env('MAPBOX_ACCESS_TOKEN')),
    ],
    'cache' => [
        'duration' => env('MAPBOX_CACHE_DURATION_SECONDS', 60 * 60 * 24 * 30),
        'store' => env('MAPBOX_MAPS_CACHE_STORE', null),
    ],
];

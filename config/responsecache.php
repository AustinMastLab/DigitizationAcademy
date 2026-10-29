<?php

use App\Http\CacheProfiles\SkipCacheWhenSessionHasFlash;
use Spatie\ResponseCache\Hasher\DefaultHasher;
use Spatie\ResponseCache\Replacers\CsrfTokenReplacer;
use Spatie\ResponseCache\Serializers\JsonSerializer;

$tag = config('app.tag', 'digitizationacademy');

return [
    /*
     * Determine if the response cache middleware should be enabled.
     */
    'enabled' => env('RESPONSE_CACHE_ENABLED', true),

    'cache' => [
        /*
         * Here you may define the cache store that should be used
         * to store requests. This can be the name of any store
         * that is configured in app/config/cache.php
         */
        'store' => env('RESPONSE_CACHE_DRIVER', 'redis'),

        /*
         * The default number of seconds responses will be cached
         * when using the default CacheProfile settings.
         */
        'lifetime_in_seconds' => (int) env('RESPONSE_CACHE_LIFETIME', 86400),

        /*
         * If the cache driver you configured supports tags, you may specify a tag name
         * here. All responses will be tagged. When clearing the responsecache only
         * items with that tag will be flushed.
         *
         * You may use a string or an array here.
         */
        'tag' => env('RESPONSE_CACHE_TAG', ''),
    ],

    'minimum_requests' => [
        /*
         * The number of times a request must be seen before its
         * response is stored. A higher number keeps pages that
         * are requested only once, such as crawler traffic,
         * out of the cache.
         */
        'count' => (int) env('RESPONSE_CACHE_MINIMUM_REQUESTS', 1),

        /*
         * The number of seconds a request counts toward the minimum.
         */
        'lifetime_in_seconds' => (int) env('RESPONSE_CACHE_MINIMUM_REQUESTS_LIFETIME', 86400),

        /*
         * The cache store that holds the request counters. Leave
         * empty to use the response cache store above.
         */
        'store' => env('RESPONSE_CACHE_MINIMUM_REQUESTS_STORE'),
    ],

    /*
     *  Optionally, you can specify a header that will force a cache bypass.
     *  This can be useful to monitor the performance of your application.
     */
    'bypass' => [
        'header_name' => env('CACHE_BYPASS_HEADER_NAME'),
        'header_value' => env('CACHE_BYPASS_HEADER_VALUE'),
    ],

    'debug' => [
        /*
         * Determines if debug headers (cache time, status and age) are
         * added to cached responses. This can be handy when debugging.
         */
        'enabled' => env('APP_DEBUG', false),

        /*
         * The name of the http header containing the time at which
         * the response was cached.
         */
        'cache_time_header_name' => "{$tag}-responsecache",

        /*
         * The name of the http header that indicates whether a response
         * was a HIT or a MISS.
         */
        'cache_status_header_name' => "{$tag}-responsecache-status",

        /*
         * The name of the http header containing the cache age in seconds.
         */
        'cache_age_header_name' => "{$tag}-responsecache-age",

        /*
         * The name of the http header containing the response cache key.
         * This is only added when app.debug is enabled.
         */
        'cache_key_header_name' => "{$tag}-responsecache-key",
    ],

    /*
     * These query parameters will be ignored when generating
     * the cache key. This is useful for ignoring tracking
     * parameters like UTM tags, gclid and also fbclid.
     */
    'ignored_query_parameters' => [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'gclid',
        'fbclid',
    ],

    /*
     * Requests containing any of these headers will bypass cache reads
     * and writes. This prevents full-page responses from being served
     * to Livewire (Filament) requests.
     */
    'excluded_request_headers' => [
        'X-Livewire',
        'X-Livewire-Navigate',
        'X-Inertia',
    ],

    /*
     *  The given class will determinate if a request should be cached. The
     *  default class will cache all successful GET-requests.
     *
     *  You can provide your own class given that it implements the
     *  CacheProfile interface.
     */
    'cache_profile' => SkipCacheWhenSessionHasFlash::class,

    /*
     * This class is responsible for generating a hash for a request. This hash
     * is used to look up a cached response.
     */
    'hasher' => DefaultHasher::class,

    /*
     * This class is responsible for serializing responses.
     */
    'serializer' => JsonSerializer::class,

    /*
     * Here you may define replacers that dynamically replace content from the response.
     * Each replacer must implement the Replacer interface.
     */
    'replacers' => [
        CsrfTokenReplacer::class,
    ],
];

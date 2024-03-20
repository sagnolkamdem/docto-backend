<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'spa_url' => env('SPA_URL', 'http://localhost:8080'),

    /*
    |--------------------------------------------------------------------------
    | Application Api Connection
    |--------------------------------------------------------------------------
    |
    | Here are the type of connection used to authenticate users by api.
    | Only two provider are support.
    |
    | Supported drivers: "sanctum", "passport"
    |
    */

    'api_connection' => env('API_AUTH_PROVIDER', 'sanctum'),

    /*
    |--------------------------------------------------------------------------
    | Application Admin Api Middleware
    |--------------------------------------------------------------------------
    |
    | Middleware to protect routes that can only be accessed by administrators.
    | You can add the roles that will be able to connect or remove the ones you
    | don't want to have access to.
    |
    | To use it in your routes you must add the "admin" name in the middleware
    | Eg.: $route->middleware(['admin'])
    |
    */

    'api_middleware' => [
        'web',
        env('API_AUTH_PROVIDER') === 'sanctum'
            ? 'auth:sanctum'
            : 'auth:api',
        'role:super_admin|manager',
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Default Auth Middleware
    |--------------------------------------------------------------------------
    |
    */
    'auth_middleware' => [
        env('API_AUTH_PROVIDER') === 'sanctum'
            ? 'auth:sanctum'
            : 'auth:api',
    ],

];

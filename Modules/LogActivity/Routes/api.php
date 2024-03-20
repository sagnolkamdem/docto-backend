<?php

use Dingo\Api\Routing\Router;
use Modules\LogActivity\Http\Controllers\LogActivityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', ['middleware' => 'auth:sanctum'], function (Router $api) {
    $api->get('activities/{id}', [LogActivityController::class, 'show']);
});

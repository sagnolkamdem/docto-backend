<?php

use Dingo\Api\Routing\Router;
use Modules\Relative\Http\Controllers\RelativeController;

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
    $api->group(['prefix' => 'relatives'], function (Router $api) {
        $api->get('/', [RelativeController::class, 'getAll']);
        $api->post('/', [RelativeController::class, 'create']);
        $api->get('/user/{id}', [RelativeController::class, 'getByUserId']);
        $api->get('/{id}', [RelativeController::class, 'show']);
        $api->put('/{id}', [RelativeController::class, 'update']);
        $api->delete('/{id}', [RelativeController::class, 'destroy']);
    });
});

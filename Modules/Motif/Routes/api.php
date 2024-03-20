<?php

use Dingo\Api\Routing\Router;
use Modules\Motif\Http\Controllers\Api\MotifController;

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
    $api->group(['prefix' => 'motifs'], function (Router $api) {
        $api->get('/', [MotifController::class, 'getAll']);
        $api->post('/', [MotifController::class, 'create']);
        $api->get('/{id}', [MotifController::class, 'show']);
        $api->put('/{id}', [MotifController::class, 'update']);
        $api->delete('/{id}', [MotifController::class, 'destroy']);
    });
});

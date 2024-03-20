<?php

use Dingo\Api\Routing\Router;
use Modules\Signature\Http\Controllers\Api\SignatureController;

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
    $api->group(['prefix' => 'signatures'], function (Router $api) {
        $api->post('/', [SignatureController::class, 'store']);
        $api->get('/', [SignatureController::class, 'index']);
        $api->get('/{id}', [SignatureController::class, 'show']);
        $api->put('/{id}', [SignatureController::class, 'update']);
        $api->delete('/{id}', [SignatureController::class, 'destroy']);
    });
});

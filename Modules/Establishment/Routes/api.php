<?php

use Dingo\Api\Routing\Router;
use Modules\Establishment\Http\Controllers\EstablishmentController;

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
    $api->group(['prefix' => 'establishments'], function (Router $api) {
        $api->get('/', [EstablishmentController::class, 'index']);
        $api->get('/types', [EstablishmentController::class, 'types']);
        $api->post('/', [EstablishmentController::class, 'store']);
        $api->get('/{id}', [EstablishmentController::class, 'show']);
        $api->put('/{id}', [EstablishmentController::class, 'update']);
        $api->delete('/{id}', [EstablishmentController::class, 'destroy']);
        $api->put('/{id}/status', [EstablishmentController::class, 'changeStatus']);
    });
});

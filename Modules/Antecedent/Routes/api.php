<?php

use Dingo\Api\Routing\Router;
use Modules\Antecedent\Http\Controllers\AntecedentController;
use Modules\Antecedent\Http\Controllers\Api\AntecedentTypeController;

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
    $api->group(['prefix' => 'antecedent-types'], function (Router $api) {
        $api->get('/', [AntecedentTypeController::class, 'getAll']);
        $api->post('/', [AntecedentTypeController::class, 'create']);
        $api->get('/{id}', [AntecedentTypeController::class, 'show']);
        $api->put('/{id}', [AntecedentTypeController::class, 'update']);
        $api->delete('/{id}', [AntecedentTypeController::class, 'destroy']);
    });

    $api->group(['prefix' => 'antecedents'], function (Router $api) {
        $api->post('/', [AntecedentController::class, 'store']);
        $api->get('/', [AntecedentController::class, 'getAll']);
        $api->get('/user/{id}', [AntecedentController::class, 'getByUserId']);
        $api->get('/{id}', [AntecedentController::class, 'show']);
        $api->put('/{id}', [AntecedentController::class, 'update']);
        $api->delete('/{id}', [AntecedentController::class, 'destroy']);
    });
});

<?php

use Dingo\Api\Routing\Router;
use Modules\Employee\Http\Controllers\Api\EmployeeController;

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
    $api->group(['prefix' => 'employees'], function (Router $api) {
        $api->post('/', [EmployeeController::class, 'store']);
        $api->get('/', [EmployeeController::class, 'index']);
        $api->get('/{id}', [EmployeeController::class, 'show']);
        $api->put('/{id}', [EmployeeController::class, 'update']);
        $api->delete('/{id}', [EmployeeController::class, 'destroy']);
    });
});

<?php

use Dingo\Api\Routing\Router;
use Modules\Speciality\Http\Controllers\SpecialityController;

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

$api->version('v1', function (Router $api) {
    $api->get('specialities', [SpecialityController::class, 'index']);
    $api->get('specialities/practicians/{id}', [SpecialityController::class, 'available']);
    $api->group(['prefix' => 'specialities', 'middleware' => 'auth:sanctum'], function (Router $api) {
        $api->post('/', [SpecialityController::class, 'store']);
        $api->get('/{id}', [SpecialityController::class, 'show']);
        $api->put('/{id}', [SpecialityController::class, 'update']);
        $api->delete('/{id}', [SpecialityController::class, 'destroy']);
        $api->put('/{id}/status', [SpecialityController::class, 'changeStatus']);
    });
});

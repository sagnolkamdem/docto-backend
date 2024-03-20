<?php

use Dingo\Api\Routing\Router;
use Modules\TimeSlot\Http\Controllers\Api\TimeSlotController;

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
    $api->group(['prefix' => 'timeslots'], function (Router $api) {
        $api->get('/', [TimeSlotController::class, 'getAll']);
        $api->post('/practician', [TimeSlotController::class, 'createForPractician']);
        $api->get('/{id}', [TimeSlotController::class, 'show']);
        $api->put('/{id}', [TimeSlotController::class, 'update']);
        $api->delete('/{id}', [TimeSlotController::class, 'destroy']);
    });
});

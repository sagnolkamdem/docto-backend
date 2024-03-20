<?php

use Dingo\Api\Routing\Router;
use Modules\Appointment\Http\Controllers\Api\Api\StatsController;
use Modules\Appointment\Http\Controllers\Api\AppointmentController;

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
    $api->get('appointments/search', [AppointmentController::class, 'search']);
    $api->get('practicians', [AppointmentController::class, 'getAllPracticians']);
    $api->get('availability', [AppointmentController::class, 'availableSlots']);
    $api->get('occupied', [AppointmentController::class, 'unavailableSlots']);
    $api->get('agenda/{id}', [AppointmentController::class, 'unavailableSlotsByEstablishment']);
    $api->get('practicians/{id}', [AppointmentController::class, 'showPractician']);
    $api->get('next-slot', [AppointmentController::class, 'nextSlots']);

    $api->group(['prefix' => 'appointments', 'middleware' => 'auth:sanctum'], function (Router $api) {
        $api->post('/', [AppointmentController::class, 'store']);
        $api->get('/', [AppointmentController::class, 'index']);
        $api->get('/patient/{id}', [AppointmentController::class, 'getByUser']);
        $api->get('/best/practicians', [AppointmentController::class, 'bestPracticians']);
        $api->get('/transfered/{id}', [AppointmentController::class, 'transfered']);
        $api->get('/documents/{id}', [AppointmentController::class, 'getDocsByAppointment']);
        $api->get('/remind/{id}', [AppointmentController::class, 'remind']);
        $api->get('/{id}', [AppointmentController::class, 'show']);
        $api->put('/status/{id}', [AppointmentController::class, 'updateStatus']);
        $api->put('/postpone/{id}', [AppointmentController::class, 'postpone']);
        $api->put('/transfer/{id}', [AppointmentController::class, 'transfer']);
        $api->put('/{id}', [AppointmentController::class, 'update']);
        $api->delete('/{id}', [AppointmentController::class, 'destroy']);
    });

    $api->group(['prefix' => 'stats', 'middleware' => 'auth:sanctum'], function (Router $api) {
        $api->get('/activity/{id}', [StatsController::class, 'getActivity']);
        $api->get('/', [StatsController::class, 'dashboard']);
    });
});

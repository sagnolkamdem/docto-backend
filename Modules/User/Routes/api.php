<?php

use Dingo\Api\Routing\Router;
use Modules\User\Http\Controllers\Api\PatientController;
use Modules\User\Http\Controllers\Api\RoleController;
use Modules\User\Http\Controllers\Api\UserController;

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
    $api->get('/user/me', [UserController::class, 'me']);
    $api->post('/user/profile/{id}', [UserController::class, 'update']);
    $api->put('/user/preferences/{id}', [UserController::class, 'updatePreferences']);
    $api->put('/user/password', [UserController::class, 'updatePassword']);
    $api->delete('/user/delete/{id}', [UserController::class, 'destroy']);
    $api->delete('/practician/delete/{id}', [UserController::class, 'destroyPractician']);
    $api->get('waitlist/establishment/{id}', [PatientController::class, 'getWaitListByEstablishment']);
    $api->get('waitlist/practician/{id}', [PatientController::class, 'getWaitListByPractician']);
    $api->get('consultations/establishment/{id}', [PatientController::class, 'getConsultationsByEstablishment']);
    $api->get('consultations/practician/{id}', [PatientController::class, 'getConsultationsByPractician']);

    $api->group(['prefix' => 'patients'], function (Router $api) {
        $api->get('/', [PatientController::class, 'index']);
        $api->post('/', [PatientController::class, 'store']);
        $api->get('/{id}', [PatientController::class, 'show']);
        $api->get('/establishment/{id}', [PatientController::class, 'getByEstablishment']);
        $api->get('/practician/{id}', [PatientController::class, 'getByPractician']);
        $api->put('/activate/{id}', [PatientController::class, 'activate']);
    });

    $api->group(['prefix' => 'roles'], function (Router $api) {
        $api->get('/', [RoleController::class, 'index']);
        $api->get('/{id}', [RoleController::class, 'show']);
        $api->put('/{id}', [RoleController::class, 'update']);
    });
});

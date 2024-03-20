<?php

use Dingo\Api\Routing\Router;
use Modules\Practician\Http\Controllers\Api\AdminController;
use Modules\Practician\Http\Controllers\Api\AuthController;
use Modules\Practician\Http\Controllers\Api\PracticianController;

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

    $api->group(['prefix' => 'practician'], function (Router $api) {
        $api->post('/login', [AuthController::class, 'login']);
        $api->post('/register', [AuthController::class, 'register']);

        $api->post('/forgot-password', [AuthController::class, 'forgot']);
        $api->post('/reset-password', [AuthController::class, 'reset']);

        /* Authenticated Routes */
        $api->group(['middleware' => 'auth:sanctum'], function (Router $api) {
            $api->post('/logout', [AuthController::class, 'logout']);
            $api->get('/me', [PracticianController::class, 'me']);
            $api->post('/profile/{id}', [PracticianController::class, 'update']);
            $api->put('/password', [PracticianController::class, 'updatePassword']);
        });

        $api->group(['middleware' => 'auth:sanctum'], function (Router $api) {
            $api->get('/', [AdminController::class, 'getAllPracticians']);
            $api->get('/inactif', [AdminController::class, 'getInactifAdminPracticians']);
            $api->get('/admin', [AdminController::class, 'getAdminPracticians']);
            $api->put('/validate/{id}', [AdminController::class, 'validatePractician']);
            $api->put('/activate/{id}', [AdminController::class, 'activatePractician']);
            $api->get('/establishment/doctors/{id}', [PracticianController::class, 'getDocsByEstablishment']);
            $api->get('/establishment/{id}', [PracticianController::class, 'getByEstablishment']);
            $api->post('/', [PracticianController::class, 'create']);
            $api->delete('/{id}', [PracticianController::class, 'destroy']);
            $api->get('/{id}', [AdminController::class, 'show']);
        });
    });
});

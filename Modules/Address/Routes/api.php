<?php

use Dingo\Api\Routing\Router;
use Modules\Address\Http\Controllers\Api\AddressController;

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
    $api->group(['prefix' => 'addresses'], function (Router $api) {
        $api->get('/', [AddressController::class, 'getAll']);
        $api->post('/practician', [AddressController::class, 'createForPractician']);
        $api->post('/establishment', [AddressController::class, 'createForEstablishment']);
        $api->get('/establishment/{id}', [AddressController::class, 'getByEstablishment']);
        $api->get('/practician/{id}', [AddressController::class, 'getByPractician']);
        $api->get('/{id}', [AddressController::class, 'show']);
        $api->put('/{id}', [AddressController::class, 'update']);
        $api->delete('/{id}', [AddressController::class, 'destroy']);
    });
});

<?php

use Dingo\Api\Routing\Router;
use Modules\Contact\Http\Controllers\Api\ContactController;

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
    $api->post('contacts/', [ContactController::class, 'create']);
    $api->group(['prefix' => 'contacts', 'middleware' => 'auth:sanctum'], function (Router $api) {
        $api->get('/', [ContactController::class, 'getAll']);
        $api->get('/{id}', [ContactController::class, 'show']);
        $api->put('/{id}', [ContactController::class, 'update']);
        $api->delete('/{id}', [ContactController::class, 'destroy']);
    });
});

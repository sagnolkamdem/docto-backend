<?php

use Dingo\Api\Routing\Router;
use Modules\Document\Http\Controllers\DocumentController;

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
    $api->group(['prefix' => 'documents'], function (Router $api) {
        $api->post('/', [DocumentController::class, 'store']);
        $api->get('/', [DocumentController::class, 'getAll']);
        $api->get('/types', [DocumentController::class, 'types']);
        $api->get('/patient/{id}', [DocumentController::class, 'getByUserId']);
        $api->get('/{id}', [DocumentController::class, 'show']);
        $api->put('/{id}', [DocumentController::class, 'update']);
        $api->delete('/{id}', [DocumentController::class, 'destroy']);
    });
});

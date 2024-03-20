<?php

use Dingo\Api\Routing\Router;
use Modules\Chat\Http\Controllers\Api\ChatController;
use Modules\Chat\Http\Controllers\Api\MessageController;

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
    $api->group(['prefix' => 'chats'], function (Router $api) {
        $api->get('/', [ChatController::class, 'getAllChats']);
        $api->post('/', [ChatController::class, 'store']);
        $api->get('/{id}', [ChatController::class, 'show']);
        $api->put('/read/{id}', [ChatController::class, 'readMsg']);
        $api->put('/receive/{id}', [ChatController::class, 'receiveMsg']);
        $api->put('/remove/{id}', [ChatController::class, 'removeUsers']);
        $api->put('/add/{id}', [ChatController::class, 'addUsers']);
        $api->put('/{id}', [ChatController::class, 'update']);
        $api->delete('/{id}', [ChatController::class, 'destroy']);
    });

    $api->group(['prefix' => 'messages'], function (Router $api) {
        $api->get('/chat/{id}', [MessageController::class, 'getByChat']);
        $api->post('/', [MessageController::class, 'store']);
        $api->get('/{id}', [MessageController::class, 'show']);
        $api->put('/{id}', [MessageController::class, 'update']);
        $api->delete('/{id}', [MessageController::class, 'destroy']);
    });
});

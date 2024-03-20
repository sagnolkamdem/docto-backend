<?php

use Dingo\Api\Routing\Router;
use Modules\Notes\Http\Controllers\NotesController;

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
    $api->group(['prefix' => 'notes'], function (Router $api) {
        $api->post('/', [NotesController::class, 'store']);
        $api->get('/', [NotesController::class, 'getAll']);
        $api->get('/patient/{id}', [NotesController::class, 'getByUserId']);
        $api->get('/establishment/{id}', [NotesController::class, 'getByEstablishmentId']);
        $api->get('/{id}', [NotesController::class, 'show']);
        $api->put('/{id}', [NotesController::class, 'update']);
        $api->delete('/{id}', [NotesController::class, 'delete']);
    });
});

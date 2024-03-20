<?php

use Dingo\Api\Routing\Router;
use Modules\Core\Http\Controllers\Api\CitiesController;
use Modules\Core\Http\Controllers\Api\CountryController;
use Modules\Core\Http\Controllers\Api\CurrencyController;
use Modules\Core\Http\Controllers\Api\SessionController;

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
    $api->get('/cities', [CitiesController::class, 'index']);
    $api->get('/locations', [CitiesController::class, 'location']);
    $api->get('/active/cities', [CitiesController::class, 'getAllActiveCurrencies']);
    $api->get('/cities/{id}', [CitiesController::class, 'show']);

    $api->get('/currencies', [CurrencyController::class, 'index']);
    $api->get('/active/currencies', [CurrencyController::class, 'getAllActiveCurrencies']);
    $api->get('/currencies/{id}', [CurrencyController::class, 'show']);

    $api->group(['prefix' => '', 'middleware' => 'auth:sanctum'], function (Router $api) {
        $api->get('/countries', [CountryController::class, 'getAllCountries']);
        $api->get('/enabled/countries', [CountryController::class, 'getAllEnabledCountries']);
        $api->get('/active/countries', [CountryController::class, 'getAllActiveCountries']);
        $api->get('/countries/{country_id}', [CountryController::class, 'getCountry']);
    });

    $api->group(['prefix' => 'admin', 'middleware' => 'auth:sanctum'], function (Router $api) {
        $api->put('/countries/{id}/status', [CountryController::class, 'changeStatus']);
        $api->post('/countries', [CountryController::class, 'store']);

        $api->put('/currencies/{id}/status', [CurrencyController::class, 'changeStatus']);
        $api->post('/currencies', [CurrencyController::class, 'store']);

        $api->put('/cities/{id}/status', [CitiesController::class, 'changeStatus']);
        $api->put('/cities/{id}', [CitiesController::class, 'update']);
        $api->delete('/cities/{id}', [CitiesController::class, 'destroy']);
        $api->post('/cities', [CitiesController::class, 'store']);
    });

    $api->group(['prefix' => 'sessions', 'middleware' => 'auth:sanctum'], function (Router $api) {
        $api->get('/', [SessionController::class, 'index']);
        $api->get('/{id}', [SessionController::class, 'show']);
        $api->delete('/{id}', [SessionController::class, 'delete']);
    });
});

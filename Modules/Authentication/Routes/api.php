<?php

use Dingo\Api\Routing\Router;
use Modules\Authentication\Http\Controllers\Api\AuthenticateController;
use Modules\Authentication\Http\Controllers\Api\ForgotPasswordController;
use Modules\Authentication\Http\Controllers\Api\RegisterController;
use Modules\Authentication\Http\Controllers\Api\ResetPasswordController;
use Modules\Authentication\Http\Controllers\Api\VerifyEmailController;
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

$api->version('v1', function (Router $api) {
    $api->post('/login', [AuthenticateController::class, 'login']);
    $api->post('/send-otp', [AuthenticateController::class, 'otpSend']);
    $api->post('/verify-otp', [AuthenticateController::class, 'otpVerify']);
    $api->post('/register', [RegisterController::class, 'register']);

    $api->post('/user/exists', [UserController::class, 'exists']);
    $api->post('/forgot-password', [ForgotPasswordController::class, 'forgot']);
    $api->post('/reset-password', [ResetPasswordController::class, 'reset']);
    $api->post('/resend-email/{id}', [VerifyEmailController::class, 'resendEmail']);

    /* Authenticated Routes */
    $api->group(['middleware' => 'auth:sanctum'], function (Router $api) {
        $api->post('logout', [AuthenticateController::class, 'logout']);
    });
});

<?php


use Illuminate\Support\Facades\Route;
use Modules\Authentication\Http\Controllers\Api\VerifyEmailController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('practician/reset', [VerifyEmailController::class, 'redirect_practician'])
    ->middleware(['throttle:6,1'])
    ->name('password.reset-practician');

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OrgController;
use App\Http\Controllers\Api\V1\UserController;

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

Route::prefix('v1')->group(function () {
    Route::post('regis', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::get('/user-check', [AuthController::class, 'currentUser']);

    Route::middleware(['auth:admin-api'])->group(function () {
        Route::prefix('org')->group(function () {
            Route::get('get', [OrgController::class, 'getOrgs']);
            Route::get('status/{org_id}', [OrgController::class, 'updateStatus']);
        });

        Route::prefix('user')->group(function () {
            Route::get('get', [UserController::class, 'getUsers']);
            Route::get('status/{user_id}', [UserController::class, 'updateStatus']);
        });
    });
});
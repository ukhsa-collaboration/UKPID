<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TestController;

Route::group(['middleware' => ['auth:api']], function () {
    Route::apiResource('user', UserController::class);
    Route::apiResource('role', RoleController::class)->only(['index']);
});

Route::prefix('test')->group(function() {
    Route::get('/', [TestController::class, 'index']);
    Route::post('/', [TestController::class, 'store']);
    Route::get('/{id}', [TestController::class, 'show']);
    Route::put('/{id}', [TestController::class, 'update']);
    Route::delete('/{id}', [TestController::class, 'destroy']);
});

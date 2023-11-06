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
use App\Http\Controllers\EnquiryController;

Route::group(['middleware' => ['auth:api']], function () {
    Route::apiResource('user', UserController::class);
    Route::apiResource('role', RoleController::class)->only(['index']);
});

// API resource routes for Enquiry
Route::apiResource('enquiries', EnquiryController::class)
    ->parameters(['enquiries' => 'enquiry:key']);
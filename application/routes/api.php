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

use App\FormDefinition\FileImport;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\CodeTableController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\FormDefinitionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

Route::get('/status', function () {
    return response('Success');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/user/me', [UserController::class, 'me']);
    Route::apiResourceWithAudits('user', UserController::class);

    Route::apiResource('role', RoleController::class)->only(['index']);
    Route::apiResourceWithAudits('code', CodeController::class)->except(['destroy']);
    Route::apiResourceWithAudits('code-table', CodeTableController::class)->except(['destroy']);
    Route::apiResource('form-definition', FormDefinitionController::class)->except(['destroy']);
    Route::post('/form-definition/validate', [FormDefinitionController::class, 'validateForm']);
});

// API resource routes for Enquiry
Route::apiResource('enquiries', EnquiryController::class)
    ->parameters(['enquiries' => 'enquiry:key']);

Route::get('/form-data', function () {
    return response()->json(FileImport::getFormData());
});

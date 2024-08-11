<?php

use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\PlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

Route::prefix('v1')->group(function() {

    // Not logged routes
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'create']);

    Route::middleware('auth:sanctum')->group(function() {

        // Logged users routes
        Route::prefix('users')->controller(UserController::class)->group(function() {
            Route::get('logout', 'logout');
            Route::put('update', 'update');
        });

        // Plans routes
        Route::prefix('plans')->controller(PlanController::class)->group(function() {
            Route::get('index', 'index');
            Route::get('show/{id}', 'show');
            Route::get('pdf/generate/{plan}', 'generatePlanPdf');
            Route::post('create', 'create');
            Route::post('participants/add/{plan}', 'planParticipantAdd');
            Route::put('update/{plan}', 'update');
            Route::delete('delete/{plan}', 'delete');
            Route::delete('participants/remove/{plan}', 'planParticipantRemove');
        });

        // Participant routes
        Route::prefix('participants')->controller(ParticipantController::class)->group(function() {
            Route::get('index', 'index');
            Route::get('show/{id}', 'show');
            Route::post('create', 'create');
            Route::put('update/{participant}', 'update');
            Route::delete('delete/{participant}', 'delete');
        });
    });
});

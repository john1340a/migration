<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\MapController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserBackgroundController;
use App\Http\Controllers\Admin\MapBasemapController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('v1/admin')->middleware(AdminMiddleware::class)->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);

        // Background routes
        Route::post('/users/{user}/background', [UserBackgroundController::class, 'upload']);

        // Map routes
        Route::get('/maps', [MapController::class, 'adminIndex']);
        Route::post('/users/{user}/maps', [MapController::class, 'adminStore']);
        Route::put('/users/{user}/maps/{map}', [MapController::class, 'adminUpdate']);
        Route::delete('/users/{user}/maps/{map}', [MapController::class, 'adminDestroy']);

        // MapBasemap routes
        Route::get('/maps/{map}/basemaps', [MapBasemapController::class, 'index']);
        Route::post('/maps/{map}/basemaps', [MapBasemapController::class, 'store']);
        Route::put('/maps/{map}/basemaps/{mapBasemap}', [MapBasemapController::class, 'update']);
        Route::delete('/maps/{map}/basemaps/{mapBasemap}', [MapBasemapController::class, 'destroy']);
        
        });

});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/{user}/maps', [MapController::class, 'index']);
    Route::get('/users/{user}/maps/{map}', [MapController::class, 'show']);
});

Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
Route::post('/register', [RegisteredUserController::class, 'store']);
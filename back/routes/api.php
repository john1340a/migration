<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1/admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    // Routes utilisateurs
    Route::apiResource('users', UserController::class);
    // Route spécifique pour les cartes d'un utilisateur
    Route::post('users/{user}/maps', [UserController::class, 'addMap']);
});

Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('captcha', [AuthController::class, 'captcha']);

// User profile
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
    Route::get('user/profile', [UserProfileController::class, 'show']);
    Route::put('user/profile', [UserProfileController::class, 'update']);
});

// Admin routes
Route::middleware(['auth:sanctum', 'can:manage-users'])->prefix('admin')->group(function () {
    Route::apiResource('users', AdminUserController::class);
    Route::get('users/search', [AdminUserController::class, 'search']);
});


<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{UserController, AuthController, EmailVerificationController};


Route::group(['namespace' => 'Api'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/resend_code', [UserController::class, 'resend_code']);
    Route::post('/verify', [EmailVerificationController::class, 'verify']);
    Route::post('/reset-password', [UserController::class, 'resetPass']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});


Route::group([/*'middleware' => 'EmailVerificationMiddleware',*/'namespace' => 'Api'], function () {
    //Route::get('api-test/{id?}', [UserController::class, 'get_user_by_id']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/loginweb', [AuthController::class, 'login'])->name('weblogin');
    Route::post('/createcard', [UserController::class, 'create_card']); #add gate
});

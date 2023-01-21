<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{UserController, AuthController, CardController, EmailVerificationController};
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ContactController;

Route::group(['namespace' => 'Api'], function () {
    Route::post('/login', [AuthController::class, 'login']);#dex
    Route::post('/register', [AuthController::class, 'register']);#dh
    Route::post('/resend_code', [UserController::class, 'resend_code']);#dex
    Route::post('/reset-password', [UserController::class, 'resetPass']);#dex
    Route::post('/verify', [EmailVerificationController::class, 'verify']);#dex
    Route::post('/change-password', [UserController::class, 'changePassword']);#hamza
    // Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/update_card', [CardController::class, 'update']); ##test #hamza
    Route::get('/add_contact/{user_id?}', [ContactController::class, 'add_contact']);#hamza
    Route::get('/contacts', [ContactController::class, 'contacts_list']);#dh
    Route::get('/user/{id?}', [UserController::class, 'get_user_by_id']); #getUser #hamza
    Route::get('/card/{id}', [CardController::class, 'getCard']);#hamza
    Route::post('/check_code',[UserController::class, 'check_code']);#new #dex
    Route::delete('/contact/{contact_id}', [ContactController::class, 'destroy']);#hamza
});


Route::group([/*'middleware' => 'EmailVerificationMiddleware',*/'namespace' => 'Api'], function () {
    //Route::get('api-test/{id?}', [UserController::class, 'get_user_by_id']);
    Route::post('/logout', [AuthController::class, 'logout']);#dex

    Route::post('/createcard', [UserController::class, 'create_card']); #add gate #dh
});



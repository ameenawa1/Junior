<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{UserController, AuthController, CardController, EmailVerificationController};
use App\Http\Controllers\ContactController;

Route::group(['namespace' => 'Api'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/resend_code', [UserController::class, 'resend_code']);
    Route::post('/verify', [EmailVerificationController::class, 'verify']);
    Route::post('/reset-password', [UserController::class, 'resetPass']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
    // Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/update_card', [CardController::class, 'update']);

    Route::get('/contact', [ContactController::class, 'contacts_list']);
    Route::post('/contact/{contact_id}', [ContactController::class, 'add_contact']);
    Route::delete('/contact/{contact_id}', [ContactController::class, 'destroy']);
});


Route::group([/*'middleware' => 'EmailVerificationMiddleware',*/'namespace' => 'Api'], function () {
    //Route::get('api-test/{id?}', [UserController::class, 'get_user_by_id']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/loginweb', [AuthController::class, 'login'])->name('weblogin');
    Route::post('/createcard', [UserController::class, 'create_card']); #add gate
});

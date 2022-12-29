<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{AdminController};
use Illuminate\Support\Facades\Auth;

Route::get('/', [AdminController::class, 'login']);
Route::group(['middleware' => 'auth',],function(){
    
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

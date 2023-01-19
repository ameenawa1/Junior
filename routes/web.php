<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{AdminController};
use Illuminate\Support\Facades\Auth;




Route::group(['middleware' => 'guest'],

    function(){
        Route::get('/', function(){ return view('auth.login'); });
        Route::get('/login', function(){ return view('auth.login'); })->name('login');
        Route::post('/login', [AdminController::class, 'login']);
    });

Route::group(['middleware' => 'auth'], function(){

    Route::get('/dashboard', function(){return view('home'); })->name('home');
    Route::get('/logout', [AdminController::class, 'logout']);
});
/*Route::get('/login',[AdminController::class, 'login'])->middleware('guest');
Route::post('/login',[AdminController::class, 'login'])->name('weblog');

Route::post('logout',[AdminController::class, 'logout'])->middleware('auth');

    Route::group(['middleware' => 'auth','namespace' => 'admin'],function(){


        Route::get('suck',function(){return response('test', 200); });
    });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');*/



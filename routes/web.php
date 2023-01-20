<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{AdminController, DashboardController};
use Illuminate\Support\Facades\Auth;



Route::get('/', function(){return response('Hello world', 200, );});


Route::group(['middleware' => 'wbg'],

    function(){
        Route::get('/', function(){ return view('auth.login'); });
        Route::get('/login', function(){ return view('auth.login'); })->name('login');
        Route::post('/login', [AdminController::class, 'login']);
    });

Route::group(['middleware' => 'wbl'], function(){

    Route::get('/dashboard', [DashboardController::class, 'index'] )->name('home');
    Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
    Route::patch('/updateuser/{id}',[DashboardController::class, 'update']);
    Route::delete('/deleteuser/{id}',[DashboardController::class, 'delete']);
    #updateuser patch
    #deleteuser/{id} delete
});






/*Route::get('/login',[AdminController::class, 'login'])->middleware('guest');
Route::post('/login',[AdminController::class, 'login'])->name('weblog');

Route::post('logout',[AdminController::class, 'logout'])->middleware('auth');

    Route::group(['middleware' => 'auth','namespace' => 'admin'],function(){


        Route::get('suck',function(){return response('test', 200); });
    });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');*/



<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{AdminController};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AdminController::class, 'login']);
//Route::get('login', function(){return view('auth.login');});
//Route::get('login.php', function(){return view('auth.login');});

Route::group(
    [
        'middleware' => 'auth',
    ],
function()
{


});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

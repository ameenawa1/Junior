<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{UserController, AuthController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([/*'middleware' => 'auth:sanctum',*/ 'namespace' => 'Api'],function()
{

    //Route::get('api-test/{id?}', [UserController::class, 'get_user_by_id']);

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/refresh', [AuthController::class, 'refresh']);

    Route::post('/loginweb', [AuthController::class, 'login'])->name('weblogin');

    Route::post('/createcard', [UserController::class, 'create_card']);
}
);

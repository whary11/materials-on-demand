<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'user'], function(){
    Route::post('login', [UserController::class, 'login']);
});


Route::group(['middleware' => ['auth_custom'],'prefix' => 'product'], function(){
    Route::post('get-all', [ProductController::class, 'getAll']);
});


Route::group(['middleware' => [],'prefix' => 'user'], function(){
    Route::post('/get_users_manage', [UserController::class, 'getUsersManage']);
});

Route::group(['middleware' => [],'prefix' => 'user'], function(){
    Route::post('get_headquarters_not_user', [UserController::class, 'getHeadquartersNotUser']);
    Route::post('add_headquarter_to_user', [UserController::class, 'addHeadquarters']);
});


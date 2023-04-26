<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->controller(\App\Http\Controllers\AuthController::class)->group(function (){
    Route::post('login','login');
    Route::post('register','register');
});

Route::prefix('blog')->controller(\App\Http\Controllers\BlogController::class)->group(function (){
    Route::get('','getAll');
});

Route::middleware('auth:sanctum')->group(function (){
    Route::prefix('auth')->controller(\App\Http\Controllers\AuthController::class)->group(function (){
        Route::post('logout','logout');
    });

    Route::prefix('users')->controller(\App\Http\Controllers\UserController::class)->group(function (){
        Route::get('','getAll');
        Route::patch('forbidden/{user}','forbidden');
    });

    Route::prefix('blog')->controller(\App\Http\Controllers\BlogController::class)->group(function (){
        Route::post('create','create');
        Route::delete('delete','delete');
        Route::post('like/{blog}','like');
        Route::post('comment/{blog}','comment');
    });
});

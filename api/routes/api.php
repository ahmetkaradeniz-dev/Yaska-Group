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

    Route::prefix('account')->controller(\App\Http\Controllers\AccountController::class)->group(function (){
        Route::get('blog','blog');
    });

    Route::prefix('users')->controller(\App\Http\Controllers\UserController::class)->group(function (){
        Route::get('','getAll');
        Route::patch('forbidden/{user}','forbidden');
        Route::patch('recover/{user}','recover');
    });

    Route::prefix('blog')->controller(\App\Http\Controllers\BlogController::class)->group(function (){
        Route::get('getAllByAdmin','getAllByAdmin');
        Route::get('getByAdmin/{blog}','getByAdmin');
        Route::post('','create');
        Route::get('{blog}','get');
        Route::post('update/{blog}','update');
        Route::delete('{blog}','delete');
        Route::post('like/{blog}','like');
        Route::post('unlike/{blog}','unLike');
        Route::post('comment/{blog}','comment');
        Route::get('comments/{blog}','comments');
        Route::get('comment/getAllByAdmin','getAllCommentByAdmin');
        Route::get('comment/getByAdmin/{blog_comment}','getCommentByAdmin');
        Route::put('comment/updateByAdmin/{blog_comment}','updateCommentByAdmin');
        Route::delete('comment/deleteByAdmin/{blog_comment}','deleteCommentByAdmin');
    });
});

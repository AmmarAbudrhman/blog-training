<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Auth\LoginController;
use App\Http\Auth\SignupController;
use App\Http\Auth\LogoutController;
use App\Http\Auth\RefreshController;
use App\Http\ProfileController;

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('/login', LoginController::class);
    Route::post('/signup', SignupController::class);
    Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');
    Route::post('/refresh', RefreshController::class)->middleware('auth:sanctum');

    Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth:sanctum');
    Route::put('/profile', [ProfileController::class, 'update'])->middleware('auth:sanctum');
});




Route::prefix('catgories')->group(function () {
    Route::get('/', \App\Http\Catgories\IndexController::class);
    Route::get('/{id}', \App\Http\Catgories\ShowController::class);
    Route::post('/', \App\Http\Catgories\StoreController::class);
    Route::put('/{id}', \App\Http\Catgories\UpdateController::class);
    Route::delete('/{id}', \App\Http\Catgories\DestroyController::class);
});

Route::prefix('posts')->group(function () {
    Route::get('/', \App\Http\Posts\IndexController::class);
    Route::get('/{id}', \App\Http\Posts\ShowController::class);
    Route::post('/', \App\Http\Posts\StoreController::class)->middleware('auth:sanctum');
    Route::put('/{id}', \App\Http\Posts\UpdateController::class)->middleware('auth:sanctum');
    Route::delete('/{id}', \App\Http\Posts\DestroyController::class)->middleware('auth:sanctum');
});



Route::prefix('comments')->group(function () {
    Route::get('/post/{postId}', \App\Http\Comments\IndexController::class);
    Route::post('/', \App\Http\Comments\StoreController::class)->middleware('auth:sanctum');
    Route::put('/{id}', \App\Http\Comments\UpdateController::class)->middleware('auth:sanctum');
    Route::delete('/{id}', \App\Http\Comments\DestroyController::class)->middleware('auth:sanctum');
});


Route::prefix('likes')->group(function () {
    Route::post('/', \App\Http\Likes\StoreController::class)->middleware('auth:sanctum');
    Route::delete('/{postId}', \App\Http\Likes\DestroyController::class)->middleware('auth:sanctum');
});

Route::prefix('tags')->group(function () {
    Route::get('/', \App\Http\Tags\IndexController::class);
    Route::post('/', \App\Http\Tags\StoreController::class)->middleware('auth:sanctum');
    Route::put('/{id}', \App\Http\Tags\UpdateController::class)->middleware('auth:sanctum');
    Route::delete('/{id}', \App\Http\Tags\DestroyController::class)->middleware('auth:sanctum');
});

Route::prefix('otp')->group(function () {
    Route::post('/send', \App\Http\Otp\StoreController::class);
    Route::post('/verify', \App\Http\Otp\VerifyController::class);
});




<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\AuthController;
use App\Http\Catgories;
use App\Http\Controllers\Comments;

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/login', [AuthController::class, 'login']);

});



Route::prefix('catgories')->group(function () {
    Route::get('/', Catgories\IndexController::class);
    Route::get('/{id}', Catgories\ShowController::class);
    Route::post('/', Catgories\StoreController::class);
    Route::put('/{id}', Catgories\UpdateController::class);
    Route::delete('/{id}', Catgories\DestroyController::class);
});

Route::prefix('comments')->group(function () {
    Route::get('/', Comments\IndexController::class);
    Route::post('/', Comments\StoreController::class);
    Route::get('/{comment}', Comments\ShowController::class);
    Route::put('/{id}', Comments\UpdateController::class);
    Route::delete('/{id}', Comments\DestroyController::class);
});
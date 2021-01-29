<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use CloudCreativity\LaravelJsonApi\Facades\JsonApi;
use Illuminate\Support\Facades\Route;


JsonApi::register('v1')->routes(function ($api) {
    $api->resource('articles')->relationships(function ($api) {
        $api->hasOne('authors');
        $api->hasOne('categories');
    });
    $api->resource('authors')->only('index', 'read')->relationships(function ($api) {
        $api->hasMany('articles')->except('replace', 'add', 'remove');
    });
    $api->resource('categories')->relationships(function ($api) {
        $api->hasMany('articles')->except('replace', 'add', 'remove');
    });

    Route::post('login', [LoginController::class, 'login'])
        ->name('login')
        ->middleware('guest:sanctum');

    Route::post('logout', [LoginController::class, 'logout'])
        ->middleware('auth:sanctum')
        ->name('logout');

    Route::post('register', [RegisterController::class, 'register'])
       // ->middleware('auth:sanctum')
        ->name('register');

});


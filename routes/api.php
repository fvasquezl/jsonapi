<?php

use App\Http\Controllers\Api\LoginController;
use CloudCreativity\LaravelJsonApi\Facades\JsonApi;
use Illuminate\Support\Facades\Route;


JsonApi::register('v1')->routes(function ($api) {
    $api->resource('articles')->relationships(function ($api){
        $api->hasOne('authors');
        $api->hasOne('categories');
    });
    $api->resource('authors')->only('index','read')->relationships(function ($api){
        $api->hasMany('articles')->except('replace','add','remove');
    });
    $api->resource('categories')->relationships(function ($api){
        $api->hasMany('articles')->except('replace','add','remove');
    });

    Route::post('login',[LoginController::class, 'login'])->name('login');
});


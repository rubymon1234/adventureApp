<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware([])->group(function () {
    Route::post('register', "Api\ApiController@register");
    Route::post('login', "Api\ApiController@Login");
});

Route::middleware(["auth:api"])->group(function () {
    Route::post('join-event', "Api\ApiController@joinEvent");
    Route::post('event-detail', "Api\ApiController@eventDetail");
});
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/shops', 'App\Http\Controllers\ShopController@index')->middleware('guest');
Route::post('/shops', 'App\Http\Controllers\ShopController@store')->middleware('guest');
Route::get('/shops/{shopId}', 'App\Http\Controllers\ShopController@show')->middleware('guest');
Route::put('/shops/{shopId}', 'App\Http\Controllers\ShopController@update')->middleware('guest');
Route::delete('/shops/{shopId}', 'App\Http\Controllers\ShopController@destroy')->middleware('guest');

Route::put('/products/buy/{productId}', 'App\Http\Controllers\ProductController@buy')->middleware('guest');

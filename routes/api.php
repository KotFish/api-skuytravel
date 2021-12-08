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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');
Route::get('wisata', 'Api\WisataController@index');
Route::get('wisata/{id}', 'Api\WisataController@show');
Route::post('wisata', 'Api\WisataController@store');
Route::put('wisata/{id}', 'Api\WisataController@update');
Route::delete('wisata/{id}', 'Api\WisataController@destroy');

Route::get('rental', 'Api\RentalController@index');
Route::get('rental/{id}', 'Api\RentalController@show');
Route::post('rental', 'Api\RentalController@store');
Route::put('rental/{id}', 'Api\RentalController@update');
Route::delete('rental/{id}', 'Api\RentalController@destroy');

// Route::group(['middleware' => 'auth:api'], function(){
    
// });
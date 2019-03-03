<?php

use Illuminate\Http\Request;

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

// Route::get('/', function () {
//     return view('login');
// });
// Route::get('login', function () {
//     return view('login');
// });

// Route::get('dashboard', function () {
//     return view('dashboard');
// });

// Route::get('logout', 'API\AuthController@logout');

// Route::post('/', 'API\AuthController@login');

// Route::post('login', 'API\AuthController@login');
// Route::post('register', 'API\AuthController@register');
// Route::middleware('auth:api')->group(function(){
// Route::post('details', 'API\AuthController@getDetails');
// });
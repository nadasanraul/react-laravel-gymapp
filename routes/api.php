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

Route::group(['middleware' => 'jwt.auth'], function() {
    //Exercises routes
    Route::apiResource('exercises', 'ExerciseController');

    //Categories routes
    Route::apiResource('categories', 'CategoryController');

    //Sets routes
    Route::apiResource('sets', 'SetController');
    Route::get('/sets/{exercise}/{day}', 'SetController@show');
    Route::post('/sets/{exercise}/{day}', 'SetController@store');

    //Workout routes
    Route::get('/workouts/{day}', 'WorkoutController@show');
    Route::get('/workouts', 'WorkoutController@index');

    Route::post('logout', 'AuthController@logout');

    //Refresh token
    Route::get('/refreshtoken', 'AuthController@refresh');
});


//Auth routes
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

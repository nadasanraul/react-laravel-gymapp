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

    //Auth routes
    Route::post('/logout', 'AuthController@logout');
    Route::get('/refreshtoken', 'AuthController@refresh');
    Route::get('/user', 'AuthController@getUser');
});


//Auth routes
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
